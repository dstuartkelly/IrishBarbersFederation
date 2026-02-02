// Animated particles background
function initParticles() {
    const particlesContainer = document.getElementById('particles');
    
    // Create 30 animated particles
    for (let i = 0; i < 30; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 10 + 's';
        particle.style.animationDuration = (Math.random() * 10 + 5) + 's';
        particlesContainer.appendChild(particle);
    }
}

// Mobile menu toggle
function initMobileMenu() {
    const menuBtn = document.getElementById('menuBtn');
    const nav = document.getElementById('nav');
    
    if (menuBtn && nav) {
        menuBtn.addEventListener('click', () => {
            nav.classList.toggle('active');
        });

        // Close mobile menu when clicking a link
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', () => {
                nav.classList.remove('active');
            });
        });
    }
}

// Theme toggle (light / dark)
function initThemeToggle() {
    const themeToggle = document.getElementById("themeToggle");
    const themeStylesheet = document.getElementById("themeStylesheet");

    if (!themeToggle || !themeStylesheet) return;

    const LIGHT_THEME = "light";
    const DARK_THEME = "dark";

    // Load saved theme or system preference
    const savedTheme = localStorage.getItem("theme");
    const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

    const currentTheme =
        savedTheme || (prefersDark ? DARK_THEME : LIGHT_THEME);

    applyTheme(currentTheme);

    themeToggle.addEventListener("click", () => {
        const isLight = themeStylesheet.href.includes("light-style.css");
        applyTheme(isLight ? DARK_THEME : LIGHT_THEME);
    });

    function applyTheme(theme) {
        if (theme === DARK_THEME) {
            themeStylesheet.href = "styles/dark-style.css";
            themeToggle.textContent = "☀️";
        } else {
            themeStylesheet.href = "styles/light-style.css";
            themeToggle.textContent = "🌙";
        }

        localStorage.setItem("theme", theme);
    }
}


// Initialize all functions when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initParticles();
    initMobileMenu();
    initThemeToggle();
});