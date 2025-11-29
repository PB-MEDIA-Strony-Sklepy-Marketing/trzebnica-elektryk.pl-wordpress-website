<?php
/**
 * Template Name: Voltmont Banner Bottom Slider (Final Fix)
 * Description: Premium banner with JS-relocated Lightbox to fix Z-Index issues
 */

// Definicja ścieżki do obrazka
$image_url = get_stylesheet_directory_uri() . '/assets/images/banner-bottom-slider.jpg';
// Unikalne ID
$banner_id = 'voltmont-banner-' . uniqid();
?>

<section class="voltmont-premium-banner-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                
                <!-- Wrapper Bannera (To co widać na stronie) -->
                <div class="voltmont-banner-wrapper">
                    
                    <!-- Dekoracyjny Glow -->
                    <div class="voltmont-banner-glow"></div>
                    
                    <!-- Link otwierający Lightbox -->
                    <a href="#" 
                       class="voltmont-lightbox-trigger" 
                       data-target="<?php echo esc_attr($banner_id); ?>"
                       aria-label="Powiększ baner Voltmont">
                        
                        <!-- Obraz główny -->
                        <img src="<?php echo esc_url($image_url); ?>" 
                             alt="Voltmont Instalacje Elektryczne - Paweł Papst - Kontakt" 
                             class="img-fluid voltmont-banner-img"
                             width="1200" 
                             height="400"
                             loading="lazy">
                        
                        <!-- Warstwa Hover z Ikoną -->
                        <div class="voltmont-hover-overlay">
                            <div class="voltmont-icon-circle">
                                <i class="fas fa-search-plus"></i>
                            </div>
                            <span class="voltmont-hover-text">Powiększ grafikę</span>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom Lightbox Modal (Ukryty kontener) -->
<!-- WAŻNE: Ten element zostanie przeniesiony przez JS na koniec BODY -->
<div id="<?php echo esc_attr($banner_id); ?>" class="voltmont-custom-lightbox" aria-hidden="true">
    <div class="voltmont-lightbox-backdrop"></div>
    <div class="voltmont-lightbox-wrapper">
        <div class="voltmont-lightbox-content">
            <button type="button" class="voltmont-lightbox-close" aria-label="Zamknij">
                <i class="fas fa-times"></i>
            </button>
            <img src="<?php echo esc_url($image_url); ?>" alt="Voltmont Banner Fullscreen">
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pobieramy elementy
    const trigger = document.querySelector('.voltmont-lightbox-trigger[data-target="<?php echo esc_attr($banner_id); ?>"]');
    const modal = document.getElementById('<?php echo esc_attr($banner_id); ?>');
    
    if(trigger && modal) {
        // ---------------------------------------------------------
        // KLUCZOWA POPRAWKA: Przenosimy modal na sam koniec <body>
        // To rozwiązuje problem z warstwami (z-index) Slidera Revolution
        // ---------------------------------------------------------
        document.body.appendChild(modal);

        const closeBtn = modal.querySelector('.voltmont-lightbox-close');
        const backdrop = modal.querySelector('.voltmont-lightbox-backdrop');

        // Funkcja otwierania
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.add('is-active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden'; // Blokada scrollowania strony
        });

        // Funkcja zamykania
        const closeModal = () => {
            modal.classList.remove('is-active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = ''; // Przywrócenie scrollowania
        };

        // Eventy zamykania
        closeBtn.addEventListener('click', closeModal);
        backdrop.addEventListener('click', closeModal);
        
        // Zamknięcie klawiszem ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('is-active')) {
                closeModal();
            }
        });
    }
});
</script>