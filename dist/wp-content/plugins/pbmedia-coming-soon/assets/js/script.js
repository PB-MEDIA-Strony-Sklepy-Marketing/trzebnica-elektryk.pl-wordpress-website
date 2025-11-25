// Initialize AOS (Animate On Scroll)
AOS.init({
    once: false,
    mirror: true,
    easing: 'ease-out-cubic',
    duration: 800
});

// Add parallax effect to background
document.addEventListener('mousemove', function(e) {
    const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
    const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
    
    document.body.style.backgroundPosition = `calc(50% + ${moveX}px) calc(50% + ${moveY}px)`;
});

// Preloader
window.addEventListener('load', function() {
    // Add a subtle fade-in effect when the page loads
    document.body.classList.add('loaded');
});

// Dynamic glow effect for primary elements
document.addEventListener('DOMContentLoaded', function() {
    // Apply a subtle animation to the coming soon badge
    const badge = document.querySelector('.coming-soon-badge span');
    
    if (badge) {
        setInterval(() => {
            badge.classList.add('glow');
            setTimeout(() => {
                badge.classList.remove('glow');
            }, 1000);
        }, 3000);
    }
});

// Smooth scroll for any anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Touch device detection for enhanced mobile experience
function isTouchDevice() {
    return (('ontouchstart' in window) ||
        (navigator.maxTouchPoints > 0) ||
        (navigator.msMaxTouchPoints > 0));
}

// Add specific class for touch devices
if (isTouchDevice()) {
    document.body.classList.add('touch-device');
}