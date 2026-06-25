<?php
declare(strict_types=1);

// ─── Configuration ────────────────────────────────────────────────────────────

/**
 * Absolute path to the data directory.
 * Change this to an out-of-webroot path for maximum security.
 * Example: '/var/www/tayba-data/'
 */
define('DATA_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR);

/** Target JSON file that the landing page reads. */
define('MENU_FILE', DATA_DIR . 'restaurant.json');

/** Temporary file for atomic write. Same directory — rename() must be atomic. */
define('MENU_FILE_TMP', MENU_FILE . '.tmp');

/**
 * The HMAC-SHA256 secret shared with Firebase.
 * Reads from environment variable. Fall back to a constants file if needed.
 * NEVER hard-code the real secret here.
 */
$envSecret = getenv('WEBHOOK_SECRET');
define('WEBHOOK_SECRET', $envSecret !== false ? $envSecret : '');

/** Maximum accepted payload size (bytes). Prevents memory exhaustion. */
define('MAX_PAYLOAD_BYTES', 1_048_576); // 1 MiB

// ─── Helpers ──────────────────────────────────────────────────────────────────

/** Send a JSON response and exit. */
function jsonResponse(int $status, string $statusText, string $message): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    // Prevent response caching
    header('Cache-Control: no-store');
    echo json_encode(['status' => $statusText, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

/** Read the X-Signature-256 header safely (handles both Apache and CGI modes). */
function getSignatureHeader(): string
{
    // Standard PHP way
    if (isset($_SERVER['HTTP_X_SIGNATURE_256'])) {
        return $_SERVER['HTTP_X_SIGNATURE_256'];
    }
    // Fallback for CGI/FastCGI where Apache may not auto-populate HTTP_* vars
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        foreach ($headers as $name => $value) {
            if (strtolower($name) === 'x-signature-256') {
                return $value;
            }
        }
    }
    return '';
}

// ─── Step 1: Method guard ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    jsonResponse(405, 'error', 'Method Not Allowed. Only POST requests are accepted.');
}

// ─── Step 2: Secret availability guard ───────────────────────────────────────
if (WEBHOOK_SECRET === '') {
    // Log server-side; never expose configuration details to the caller
    error_log('[WebhookMenu] WEBHOOK_SECRET environment variable is not set.');
    jsonResponse(500, 'error', 'Server configuration error.');
}

// ─── Step 3: Extract and validate signature header ───────────────────────────
$incomingSignature = trim(getSignatureHeader());

if ($incomingSignature === '') {
    // Missing signature header — reject immediately, before reading body
    jsonResponse(401, 'error', 'Missing X-Signature-256 header.');
}

// Signature must have the "sha256=" prefix
if (strncmp($incomingSignature, 'sha256=', 7) !== 0) {
    jsonResponse(401, 'error', 'Invalid signature format. Expected sha256=<hex>.');
}

// ─── Step 4: Read raw payload ─────────────────────────────────────────────────
$rawBody = file_get_contents('php://input', false, null, 0, MAX_PAYLOAD_BYTES + 1);

if ($rawBody === false) {
    jsonResponse(400, 'error', 'Failed to read request body.');
}

if (strlen($rawBody) > MAX_PAYLOAD_BYTES) {
    jsonResponse(413, 'error', 'Payload Too Large.');
}

if ($rawBody === '') {
    jsonResponse(400, 'error', 'Empty request body.');
}

// ─── Step 5: HMAC verification (timing-attack-safe) ──────────────────────────
$expectedSignature = 'sha256=' . hash_hmac('sha256', $rawBody, WEBHOOK_SECRET);

if (!hash_equals($expectedSignature, $incomingSignature)) {
    // Invalid signature — reject without revealing details
    error_log('[WebhookMenu] Signature mismatch. Possible unauthorized request.');
    jsonResponse(401, 'error', 'Invalid signature.');
}

// ─── Step 6: Parse and validate JSON ─────────────────────────────────────────
$payload = json_decode($rawBody, true, 32, JSON_BIGINT_AS_STRING);

if ($payload === null || json_last_error() !== JSON_ERROR_NONE) {
    error_log('[WebhookMenu] JSON parse error: ' . json_last_error_msg());
    jsonResponse(400, 'error', 'Malformed JSON payload.');
}

// Required top-level fields
$requiredFields = ['vendor_id', 'restaurant_id', 'updated_at', 'menu', '_webhook_meta'];
foreach ($requiredFields as $field) {
    if (!array_key_exists($field, $payload)) {
        jsonResponse(400, 'error', "Missing required field: {$field}");
    }
}

// Validate menu structure
if (!is_array($payload['menu']) || !isset($payload['menu']['categories'])) {
    jsonResponse(400, 'error', 'Invalid menu structure: missing categories array.');
}

// Validate updated_at is a parseable ISO 8601 timestamp
$incomingUpdatedAt = $payload['_webhook_meta']['updated_at'] ?? $payload['updated_at'];
$incomingTs = strtotime((string) $incomingUpdatedAt);

if ($incomingTs === false || $incomingTs <= 0) {
    jsonResponse(400, 'error', 'Invalid or unparseable updated_at timestamp.');
}

// Sanitize scalar fields to prevent XSS / injection in the stored JSON
$payload['vendor_id']      = preg_replace('/[^\w\-]/', '', (string) $payload['vendor_id']);
$payload['restaurant_id']  = preg_replace('/[^\w\-]/', '', (string) $payload['restaurant_id']);
$payload['restaurant_name'] = substr(strip_tags((string) ($payload['restaurant_name'] ?? '')), 0, 255);

// ─── Step 7: Idempotency check ────────────────────────────────────────────────
//
// Read the existing file's stored timestamp. If the incoming payload is OLDER
// (or equal), discard it — this prevents out-of-order race conditions when
// multiple triggers fire in rapid succession.
//
if (file_exists(MENU_FILE)) {
    $existingRaw = @file_get_contents(MENU_FILE);
    if ($existingRaw !== false) {
        $existing = json_decode($existingRaw, true, 32);
        $existingTs = isset($existing['_webhook_meta']['updated_at'])
            ? strtotime((string) $existing['_webhook_meta']['updated_at'])
            : 0;

        if ($existingTs > 0 && $incomingTs <= $existingTs) {
            // Payload is stale — acknowledge it so Firebase doesn't retry,
            // but don't overwrite the current file.
            error_log(
                '[WebhookMenu] Stale payload discarded. ' .
                "Incoming: {$incomingUpdatedAt}, Existing: {$existing['_webhook_meta']['updated_at']}"
            );
            jsonResponse(200, 'ok', 'Stale payload discarded (no update needed).');
        }
    }
}

// ─── Step 8: Atomic write ─────────────────────────────────────────────────────
//
// Write to a .tmp file first, then rename().
// On POSIX systems (Linux), rename() is atomic at the filesystem level —
// readers of restaurant.json will never see a half-written file.
// On Windows (WAMP), rename() is NOT atomic if the destination exists;
// we use unlink + rename as the closest approximation.
//
$jsonOutput = json_encode(
    $payload,
    JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);

if ($jsonOutput === false) {
    error_log('[WebhookMenu] Failed to re-encode payload as JSON.');
    jsonResponse(500, 'error', 'Internal encoding error.');
}

// Ensure data directory exists and is writable
if (!is_dir(DATA_DIR)) {
    if (!mkdir(DATA_DIR, 0750, true) && !is_dir(DATA_DIR)) {
        error_log('[WebhookMenu] Could not create data directory: ' . DATA_DIR);
        jsonResponse(500, 'error', 'Storage directory unavailable.');
    }
}

$written = file_put_contents(MENU_FILE_TMP, $jsonOutput, LOCK_EX);

if ($written === false || $written !== strlen($jsonOutput)) {
    @unlink(MENU_FILE_TMP);
    error_log('[WebhookMenu] Failed to write temporary file: ' . MENU_FILE_TMP);
    jsonResponse(500, 'error', 'Failed to write data file.');
}

// Atomic swap
if (file_exists(MENU_FILE)) {
    // Windows-compatible: unlink destination before rename
    @unlink(MENU_FILE);
}

if (!rename(MENU_FILE_TMP, MENU_FILE)) {
    @unlink(MENU_FILE_TMP);
    error_log('[WebhookMenu] rename() failed: ' . MENU_FILE_TMP . ' → ' . MENU_FILE);
    jsonResponse(500, 'error', 'Failed to finalize data file.');
}

// ─── Step 9: Success response ─────────────────────────────────────────────────
error_log(
    '[WebhookMenu] Menu updated. ' .
    "vendor={$payload['vendor_id']} restaurant={$payload['restaurant_id']} " .
    "categories=" . count($payload['menu']['categories']) . " " .
    "updated_at={$incomingUpdatedAt}"
);

jsonResponse(200, 'ok', 'Menu updated successfully.');
