const menuToggle = document.getElementById("menuToggle");
const navLinks = document.getElementById("navLinks");

menuToggle.addEventListener("click", () => {
  navLinks.classList.toggle("active");
});

document.querySelectorAll(".nav-links a").forEach((link) => {
  link.addEventListener("click", () => {
    navLinks.classList.remove("active");
  });
});

const navbar = document.getElementById("navbar");
window.addEventListener("scroll", () => {
  if (window.scrollY > 100) {
    navbar.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
  }
});

document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({
        behavior: "smooth",
        block: "start",
      });
    }
  });
});

const observerOptions = {
  threshold: 0.1,
  rootMargin: "0px 0px -100px 0px",
};

const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = "1";
      entry.target.style.transform = "translateY(0)";
    }
  });
}, observerOptions);

document.querySelectorAll(".menu-item").forEach((item, index) => {
  item.style.opacity = "0";
  item.style.transform = "translateY(50px)";
  item.style.transition = `all 0.6s ease ${index * 0.1}s`;
  observer.observe(item);
});

// Observe gallery items
document.querySelectorAll(".gallery-item").forEach((item, index) => {
  item.style.opacity = "0";
  item.style.transform = "scale(0.8)";
  item.style.transition = `all 0.6s ease ${index * 0.1}s`;
  observer.observe(item);
});

// Hero Parallax Scroll Effect
window.addEventListener('scroll', function() {
  const hero = document.querySelector('.hero');
  const heroContent = document.querySelector('.hero-content');
  const tomato = document.querySelector('.tomato');
  const onion = document.querySelector('.onion');
  const scrollPosition = window.scrollY;
  
  if (heroContent) {
    const translateY = -scrollPosition * 0.5;
    heroContent.style.transform = `translateY(${translateY}px)`;
    heroContent.style.opacity = 1 - (scrollPosition / 500);
  }
  
  if (hero) {
    const scale = 1 + (scrollPosition * 0.001);
    hero.style.backgroundSize = `${100 * scale}% auto`;
    const opacity = 0.7 + (scrollPosition * 0.001);
    hero.style.setProperty('--overlay-opacity', Math.min(opacity, 0.9));
  }
  
  if (tomato) {
    const tomatoMove = scrollPosition * 0.2;
    tomato.style.transform = `translateY(${tomatoMove}px)`;
  }
  
  if (onion) {
    const onionMove = scrollPosition * 0.1; 
    onion.style.transform = `translateY(${onionMove}px)`;
  }
});
