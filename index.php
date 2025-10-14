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
</head>
<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <div class="logo">
               <img src="<?php echo htmlspecialchars($restaurant['logo']); ?>" alt="Logo <?php echo htmlspecialchars($restaurant['name']); ?>">
            </div>
            <ul class="nav-links" id="navLinks">
                <li><a href="#about">À propos</a></li>
                <li><a href="#menu">Nos Menus</a></li>
                <li><a href="#contact">Réservation</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <div class="social-links">
             <?php foreach ($restaurant['socialMedia'] as $social): ?>
                    <a href="<?php echo htmlspecialchars($social['url']); ?>" aria-label="<?php echo htmlspecialchars($social['platform']); ?>">
                        <i class="fab fa-<?php echo strtolower($social['platform']); ?>"></i>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <section class="hero" id="home">
        <img src="assets/images/tomate.png" alt="Tomate" class="hero-decoration tomato">
        <div class="hero-parallax">
            <div class="hero-content">
                <p class="hero-subtitle"><?php echo htmlspecialchars($restaurant['type']); ?></p>
                <h1><?php echo htmlspecialchars($restaurant['slogan']); ?></h1>
                <p><?php echo htmlspecialchars($restaurant['description']); ?></p>
                <a href="#menu" class="btn-outline">DÉCOUVRIR LE MENU</a>
            </div>
        </div>
        <img src="assets/images/onion.png" alt="Oignon" class="hero-decoration onion">
    </section>
    <script>
        window.addEventListener('scroll', function() {
            const hero = document.querySelector('.hero');
            const heroContent = document.querySelector('.hero-content');
            const tomato = document.querySelector('.tomato');
            const onion = document.querySelector('.onion');
            const scrollPosition = window.scrollY;
            
            const translateY = -scrollPosition * 0.5;
            heroContent.style.transform = `translateY(${translateY}px)`;
            heroContent.style.opacity = 1 - (scrollPosition / 500);
            
            const scale = 1 + (scrollPosition * 0.001);
            hero.style.backgroundSize = `${100 * scale}% auto`;
            
            const opacity = 0.7 + (scrollPosition * 0.001);
            hero.style.setProperty('--overlay-opacity', Math.min(opacity, 0.9));
            
            if (tomato) {
                const tomatoMove = scrollPosition * 0.2;
                tomato.style.transform = `translateY(${tomatoMove}px)`;
            }
            
            if (onion) {
                const onionMove = scrollPosition * 0.1; 
                onion.style.transform = `translateY(${onionMove}px)`;
            }
        });
    </script>
   
    <!-- About Section -->
    <section class="about" id="about">
        <img src="assets/images/images4.png" alt="">
        <div class="about-content">
             <p>SAVOUREZ LE GOÛT AUTHENTIQUE DE NOS SHAWARMA ET SANDWICHS FAITS MAISON</p>
            <h2 class="section-title">Découvrez notre spécialité libanaise, Smash Burger, et French Tacos préparés avec passion pour offrir des saveurs uniques !</h2>
            <a href="#contact" class="btn-outline">RESERVE A TABLE</a>
        </div>
    </section>

    <!-- New Menu Section -->
    <section id="fastriom" class="menu-section">
        <div class="container">
            <div class="menu-content">
                <img src="assets/images/1x/fastriom-logo-8.png" alt="logo">
                <h2>Vos repas favoris, en un seul clic !</h2>
                <p>La nouvelle façon de commander vos plats <br>préférés, sans attente.</p>
                <div class="qr-code">
                    <img src="assets/images/1x/Fichier 2-8.png" alt="QR Code">
                    <span>SCAN ME</span>
                </div>
            </div>
            <div class="menu-image">
                <img src="assets/images/1x/Fichier 1-8.png" alt="Menu">
            </div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="food-menu">
        <img src="assets/images/tacoss.png" alt="Tacos" class="menu-taco-image">
                     <p class="section-p">DÉCOUVREZ NOS SAVEURS AUTHENTIQUES</p>

        <h2 class="section-title">Notre Menu</h2>
        <div class="menu-container">
            <?php foreach ($restaurant['menu']['categories'] as $category): ?>
                <div class="menu-column">
                    <h2 class="column-title"><?php echo htmlspecialchars($category['name']); ?></h2>
                    <?php foreach ($category['items'] as $item): ?>
                        <div class="menu-item">
                            <div class="item-header">
                                <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                                <span class="price"><?php echo htmlspecialchars($item['price']); ?></span>
                            </div>
                            <p><?php echo htmlspecialchars($item['description']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Découvrez Nos Spécialités -->
    <section class="special-offer">
        <img src="assets/images/shawarma.png" alt="Spécialités maison" class="special-decoration tomato">
        <div class="special-offer-overlay"></div>
        <div class="special-offer-content">
            <h2>Découvrez Nos Spécialités</h2>
            <p>Des saveurs uniques préparées avec soin pour vous</p>
            <a href="#menu" class="btn-outline">Voir le Menu</a>
        </div>
    </section>

    <!-- Section Galerie -->
    <section class="gallery-section" id="gallery">
        <div class="gallery-header">
            <p class="gallery-subtitle">NOTRE UNIVERS EN IMAGES</p>
            <h2 class="gallery-title">@<?php echo htmlspecialchars($restaurant['name']); ?></h2>
        </div>
        
        <div class="gallery-container">
          
            <div class="gallery-row">
                <div class="gallery-item wide">
                    <img src="assets/images/hero.jpg" alt="Intérieur du restaurant">
                   
                </div>
                <div class="gallery-item tall">
                    <img src="assets/images/images6.png" alt="Cuisine raffinée">
                   
                </div>
                <div class="gallery-item">
                    <img src="assets/images/images1.png" alt="Cuisinier au travail">
                  
                </div>
            </div>
            
            <div class="gallery-row">
                <div class="gallery-item">
                    <img src="assets/images/images3.png" alt="Sélection de vins">
                    
                </div>
                <div class="gallery-item wide">
                    <img src="assets/images/images2.png" alt="Bar du restaurant">
                   
                </div>
            </div>
        </div>
    </section>

    <!-- Section Contact -->
    <section class="contact" id="contact">
        <h2>Nous Trouver</h2>
        <div class="contact-grid">
            <div class="contact-info">
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h3>Adresse</h3>
                        <p><?php echo nl2br(htmlspecialchars($restaurant['contact']['address'])); ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h3>Téléphone</h3>
                        <p><?php echo htmlspecialchars($restaurant['contact']['phone']); ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h3>Email</h3>
                        <p><?php echo htmlspecialchars($restaurant['contact']['email']); ?></p>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h3>Horaires</h3>
                        <p><?php echo htmlspecialchars($restaurant['contact']['openingHours']['weekdays']); ?></p>
                    </div>
                </div>
            </div>
         <form class="contact-form" id="contactForm">
    <div class="form-group">
        <input type="text" id="name" name="name" placeholder="Votre Nom" required>
    </div>
    <div class="form-group">
        <input type="email" id="email" name="email" placeholder="Votre Email" required>
    </div>
    <div class="form-group">
        <input type="tel" id="phone" name="phone" placeholder="Numéro de téléphone">
    </div>
    <div class="form-group">
        <textarea id="message" name="message" placeholder="Votre message ou demande spéciale" required></textarea>
    </div>
    <button type="submit">ENVOYER LE MESSAGE</button>
    <div id="formSuccess" class="form-success-message"></div>
</form>
        </div>
    </section>

    <!-- Pied de page -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="assets/images/logo.png" alt="Logo Shamwich">
            </div>
            
            <div class="footer-links">
                <ul>
                    <li><a href="#menu">Menu</a></li>
                    <li><a href="#fastriom">Fastriom</a></li>
                    <li><a href="#gallery">Galerie</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            
            <div class="footer-right">
                <div class="footer-social">
                    <?php foreach ($restaurant['socialMedia'] as $social): ?>
                        <a href="<?php echo htmlspecialchars($social['url']); ?>" class="social-icon" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-<?php echo strtolower($social['platform']); ?>"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="footer-bottom">
                    <p>&copy; 2025 FASTRIOM. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>

   <script src="assets/js/script.js"></script>
   <script src="assets/js/contact-form.js"></script>
</body>
</html>