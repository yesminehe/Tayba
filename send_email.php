<?php
ini_set('display_errors', 0);
error_reporting(0);

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée', 405);
    }

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Le nom est requis";
    }
    
    if (empty($email)) {
        $errors[] = "L'email est requis";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Veuillez entrer un email valide";
    }
    
    if (empty($message)) {
        $errors[] = "Le message est requis";
    }

    if (!empty($errors)) {
        http_response_code(400);
        throw new Exception(implode("\n", $errors));
    }

    $to = "contact@fastriom.com";
    $subject = "Nouveau message de $name - Formulaire de contact";
    $headers = "From: $name <$email>\r\n" .
               "Reply-To: $email\r\n" .
               'X-Mailer: PHP/' . phpversion() . "\r\n" .
               'Content-Type: text/plain; charset=UTF-8';

    $email_body = "Vous avez reçu un nouveau message depuis le formulaire de contact de votre site web.\n\n" .
                 "Nom: $name\n" .
                 "Email: $email\n" .
                 "Téléphone: " . (empty($phone) ? 'Non fourni' : $phone) . "\n\n" .
                 "Message:\n$message";

    if (mail($to, $subject, $email_body, $headers)) {
        echo json_encode([
            'success' => true, 
            'message' => 'Votre message a été envoyé avec succès !'
        ]);
    } else {
        throw new Exception('Une erreur est survenue lors de l\'envoi du message. Veuillez réessayer.', 500);
    }

} catch (Exception $e) {
    $code = $e->getCode() ?: 500;
    http_response_code($code);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}