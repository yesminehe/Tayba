<?php
$jsonData = file_get_contents('data/restaurant.json');
$data = json_decode($jsonData, true);
$restaurant = $data['restaurant'] ?? [
  'name' => $data['restaurant_name'] ?? 'TAYBA FOOD',
  'logo' => 'assets/images/logotayba.png',
  'type' => 'Fast Food',
  'slogan' => 'L\'Art du Fast Food',
  'description' => 'Découvrez nos délicieux smash burgers, nos savoureux tacos français, nos bowls gourmands et nos snacks tex-mex croustillants.',
  'menu' => $data['menu'] ?? ['categories' => []]
];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($restaurant['name']); ?></title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="shortcut icon" href="assets/images/icon.png" type="image/png">
  <link rel="icon" href="assets/images/icon.png" type="image/png">
</head>

<body>
  <!-- Navigation -->
  <nav id="navbar">
    <div class="nav-container">
      <div class="logo">
        <img src="assets/images/logotayba.png" alt="Logo TAYBA FOOD">
      </div>
      <ul class="nav-links" id="navLinks">
        <li><a href="#about">Notre Histoire</a></li>
        <li><a href="#menu">La Carte</a></li>
        <li><a href="#gallery">Galerie</a></li>
        <li><a href="#contact">Nous Contacter</a></li>
      </ul>
      <div class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
      </div>
    </div>
  </nav>

  <section class="hero" id="home">
    <div class="hero-parallax">
      <div class="hero-content">
        <p class="hero-subtitle"><?php echo htmlspecialchars($restaurant['type']); ?></p>
        <h1><?php echo htmlspecialchars($restaurant['slogan']); ?></h1>
        <p><?php echo htmlspecialchars($restaurant['description']); ?></p>
        <a href="#menu" class="btn-outline"><span>DÉCOUVRIR LE MENU</span></a>
      </div>
    </div>
    <div class="hero-scroll">
      <div class="hero-scroll-line"></div>
      <span>SCROLL</span>
    </div>
  </section>

  <!-- About Section -->
  <section class="about" id="about">
    <img src="assets/images/about-feast.jpg" alt="Notre sélection fast food Tayba Food">
    <div class="about-content">
      <p>FAST FOOD</p>
      <h2 class="section-title">Découvrez nos délicieux smash burgers, nos savoureux tacos français, nos bowls gourmands
        et nos snacks tex-mex croustillants.
      </h2>
      <a href="#contact" class="btn-outline"><span>NOUS RENDRE VISITE</span></a>
    </div>
  </section>

  <!-- Fastriom Section -->
  <section id="fastriom" class="menu-section">
    <div class="container">
      <div class="menu-content">
        <img src="assets/images/1x/fastriom-logo-8.png" alt="Fastriom">
        <h2>Commandez en quelques secondes !</h2>
        <p>Scannez le QR code, choisissez vos plats et recevez votre commande directement à table — rapide, simple et
          sans friction.</p>
        <div class="qr-code">
          <img src="assets/images/qr.png" alt="QR Code Fastriom">
          <div class="qr-code-text">
            <span>SCANNER ICI</span>
            <span class="qr-code-sub">Commander via Fastriom</span>
          </div>
        </div>
        <a href="https://fastriom.app/qr/ZWQpfIIF82XLGOx0umVk" target="_blank" rel="noopener noreferrer"
          class="btn-outline">
          <i class="fas fa-utensils"></i>
          <span>COMMANDER EN LIGNE</span>
        </a>
      </div>
      <div class="menu-image">
        <img src="assets/images/burger-valaisan-app.png" alt="Smash Burger Valaisan Tayba Food">
      </div>
    </div>
  </section>

  <!-- Menu Section -->
  <section id="menu" class="food-menu">
    <div class="food-menu-header">
      <p class="section-p">FAIT MAISON, SERVI AVEC PASSION</p>
      <h2 class="section-title">Notre Carte</h2>
    </div>
    <div class="menu-container">
      <?php
      // Diviser les catégories en deux colonnes
      $categories = $restaurant['menu']['categories'];
      $categoriesChunks = array_chunk($categories, ceil(count($categories) / 2));

      foreach ($categoriesChunks as $columnCategories):
        ?>
        <div class="menu-column">
          <?php foreach ($columnCategories as $category): ?>
            <h2 class="column-title"><?php echo htmlspecialchars($category['name']); ?></h2>
            <?php foreach ($category['items'] as $item): ?>
              <div class="menu-item">
                <div class="item-header">
                  <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                  <span class="item-dots"></span>
                  <span class="price"><?php echo htmlspecialchars($item['price']); ?></span>
                </div>
                <p><?php echo $item['description']; ?></p>
              </div>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Bannière Spéciale -->
  <section class="special-offer">
    <img src="assets/images/burger-float.png" alt="Smash Burger Tayba Food" class="special-decoration tomato">
    <div class="special-offer-overlay"></div>
    <div class="special-offer-content">
      <h2>Le Goût qui <span>Marque</span></h2>
      <p>Des recettes généreuses, des ingrédients frais — chaque plat est une expérience à part entière.</p>
      <a href="#menu" class="btn-outline"><span>Explorer la Carte</span></a>
    </div>
  </section>

  <!-- Section Galerie -->
  <section class="gallery-section" id="gallery">
    <div class="gallery-header">
      <p class="gallery-subtitle">NOS PLATS EN IMAGES</p>
      <h2 class="gallery-title"><?php echo htmlspecialchars($restaurant['name']); ?></h2>
    </div>

    <div class="gallery-container">

      <div class="gallery-row">
        <div class="gallery-item wide">
          <img src="assets/images/gallery-mozza.jpg" alt="Mozza Sticks Tayba Food">
        </div>
        <div class="gallery-item tall">
          <img src="assets/images/gallery-valaisan.jpg" alt="Valaisan Smash Burger">
        </div>
        <div class="gallery-item">
          <img src="assets/images/gallery-tacos.jpg" alt="Tacos Français Tayba Food">
        </div>
      </div>

      <div class="gallery-row">
        <div class="gallery-item">
          <img src="assets/images/gallery-smash.jpg" alt="Double Smash Classic">
        </div>
        <div class="gallery-item">
          <img src="assets/images/gallery-tandoori.jpg" alt="Bowl Tandoori Tayba Food">
        </div>
        <div class="gallery-item wide">
          <img src="assets/images/gallery-wings.jpg" alt="Chicken Wings Tayba Food">
        </div>
      </div>
    </div>
  </section>

  <!-- Section Contact -->
  <section class="contact" id="contact">
    <div class="contact-header">
      <p class="section-label">On est là pour vous</p>
      <h2>Venez Nous Rendre Visite</h2>
    </div>
    <div class="contact-grid">
      <div class="contact-info">
        <div class="contact-item">
          <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <h3>Adresse</h3>
            <p>Rue de Collège 6<br>1920 Martigny - Suisse</p>
          </div>
        </div>
        <div class="contact-item">
          <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
          <div>
            <h3>Téléphone</h3>
            <p>+41 79 881 41 73</p>
          </div>
        </div>
        <div class="contact-item">
          <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <h3>Email</h3>
            <p>hasnimelik@hotmail.com</p>
          </div>
        </div>
        <div class="contact-item">
          <div class="contact-item-icon"><i class="fas fa-clock"></i></div>
          <div>
            <h3>Horaires</h3>
            <p>Lun - Sam: 09h00 - 23h00<br>Dim: 09h00 - 22h00</p>
          </div>
        </div>
      </div>
      <form class="contact-form" id="contactForm">
        <div class="form-group">
          <input type="text" id="name" name="name" placeholder="Votre prénom &amp; nom" required>
        </div>
        <div class="form-group">
          <input type="email" id="email" name="email" placeholder="Votre adresse email" required>
        </div>
        <div class="form-group">
          <input type="tel" id="phone" name="phone" placeholder="Votre numéro de téléphone">
        </div>
        <div class="form-group">
          <textarea id="message" name="message" placeholder="Votre message, demande de réservation ou question..."
            required></textarea>
        </div>
        <button type="submit">ENVOYER MA DEMANDE</button>
        <div id="formSuccess" class="form-success-message"></div>
      </form>
    </div>
  </section>

  <!-- Pied de page -->
  <footer class="site-footer">
    <div class="footer-content">
      <div class="footer-logo">
        <img src="assets/images/logotayba.png" alt="Logo TAYBA FOOD">
      </div>
      <div class="footer-links">
        <ul>
          <li><a href="#about">Notre Histoire</a></li>
          <li><a href="#menu">La Carte</a></li>
          <li><a href="#fastriom">Commander</a></li>
          <li><a href="#gallery">Galerie</a></li>
          <li><a href="#contact">Contact</a></li>
        </ul>
      </div>

    </div>
    <div class="footer-bottom">
      <p>&copy; 2026 TAYBA FOOD. Tous droits réservés.</p>
    </div>
  </footer>

  <script src="assets/js/script.js"></script>
  <script src="assets/js/contact-form.js"></script>
</body>

</html>
