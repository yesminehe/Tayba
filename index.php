<?php
$jsonData = file_get_contents('data/restaurant.json');
$restaurant = json_decode($jsonData, true)['restaurant'];
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
        <img src="<?php echo htmlspecialchars($restaurant['logo']); ?>"
          alt="Logo <?php echo htmlspecialchars($restaurant['name']); ?>">
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
    <img src="assets/images/pexels-eros-jhave-ormeno-centeno-46789059-7508167 (1).jpg" alt="Notre cuisine syrienne">
    <div class="about-content">
      <p>L'ART DE LA CUISINE SYRIENNE</p>
      <h2 class="section-title">Découvrez l'authenticité de nos shawarmas traditionnels, nos tushkas grillés et nos savoureux poulets rôtis.
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
          <img src="assets/images/qr-code.png" alt="QR Code Fastriom">
          <div class="qr-code-text">
            <span>SCANNER ICI</span>
            <span class="qr-code-sub">Commander via Fastriom</span>
          </div>
        </div>
      </div>
      <div class="menu-image">
        <img src="assets/images/1x/Fichier 1-8.png" alt="Aperçu du menu">
      </div>
    </div>
  </section>

  <!-- Menu Section -->
  <section id="menu" class="food-menu">
    <img src="assets/images/shawarma.png" alt="Shawarma" class="menu-taco-image">
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
    <img src="assets/images/shawarma.png" alt="Shawarma maison" class="special-decoration tomato">
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
          <img src="assets/images/pexels-yasin-onus-520099596-37290085.jpg" alt="Cuisine Syrienne Tayba Food">

        </div>
        <div class="gallery-item tall">
          <img src="assets/images/pexels-omar-b-shokerat-3263898-18177325.jpg" alt="Spécialités Traditionnelles">

        </div>
        <div class="gallery-item">
          <img src="assets/images/pexels-musato-8364505.jpg" alt="Falafels Maison">

        </div>
      </div>

      <div class="gallery-row">
        <div class="gallery-item">
          <img src="assets/images/pexels-furkanfdemir-10821330 (1).jpg" alt="Plats Savoureux">

        </div>
        <div class="gallery-item wide">
          <img src="assets/images/pexels-omar-b-shokerat-3263898-18177532 (1).jpg" alt="Spécialités Syriennes">

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
            <p><?php echo nl2br(htmlspecialchars($restaurant['contact']['address'])); ?></p>
          </div>
        </div>
        <?php if (!empty($restaurant['contact']['phone']) && $restaurant['contact']['phone'] !== '####'): ?>
        <div class="contact-item">
          <div class="contact-item-icon"><i class="fas fa-phone"></i></div>
          <div>
            <h3>Téléphone</h3>
            <p><?php echo htmlspecialchars($restaurant['contact']['phone']); ?></p>
          </div>
        </div>
        <?php endif; ?>
        <div class="contact-item">
          <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <h3>Email</h3>
            <p><?php echo htmlspecialchars($restaurant['contact']['email']); ?></p>
          </div>
        </div>
        <div class="contact-item">
          <div class="contact-item-icon"><i class="fas fa-clock"></i></div>
          <div>
            <h3>Horaires</h3>
            <p><?php echo htmlspecialchars($restaurant['contact']['openingHours']['weekdays']); ?></p>
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
        <img src="assets/images/logo.png" alt="Logo TAYBA FOOD">
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
      <p>&copy; 2025 TAYBA FOOD. Tous droits réservés.</p>
    </div>
  </footer>

  <script src="assets/js/script.js"></script>
  <script src="assets/js/contact-form.js"></script>
</body>

</html>
