<?php
/**
 * Template Name: Sekcja Facebook Feed (Voltmont Premium - 100% Width Fix)
 * Description: Hybrydowa integracja z Facebook SDK z wymuszeniem szerokości.
 */

$fb_page_url = 'https://www.facebook.com/profile.php?id=100063601389747';
// Fallback image
$profile_img = 'https://graph.facebook.com/100063601389747/picture?type=large';
?>

<div id="fb-root"></div>
<script>
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/pl_PL/sdk.js#xfbml=1&version=v18.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<section class="vm-fb-section">
    <div class="container">
        
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="vm-fb-heading">Bądź na bieżąco z <span class="vm-fb-highlight">VOLTMONT</span></h2>
                <div class="vm-fb-divider"></div>
                <p class="vm-fb-intro">
                    Obserwuj nasze najnowsze realizacje, porady i aktualności bezpośrednio z placu budowy.
                </p>
            </div>
        </div>

        <div class="vm-fb-card">
            <div class="row g-0">
                
                <!-- LEWA KOLUMNA: Zmieniona szerokość na col-lg-5 dla lepszego balansu -->
                <div class="col-lg-5 col-md-5 vm-fb-sidebar">
                    <div class="vm-fb-profile">
                        <div class="vm-fb-avatar-wrapper">
                            <img src="<?php echo esc_url($profile_img); ?>" alt="Voltmont Facebook" class="vm-fb-avatar">
                            <span class="vm-fb-status"></span>
                        </div>
                        <h3 class="vm-fb-name">Voltmont - Instalacje Elektryczne</h3>
                        <p class="vm-fb-handle">@trzebnica-elektryk.pl</p>
                        
                        <div class="vm-fb-actions">
                            <div class="fb-like" 
                                 data-href="<?php echo esc_url($fb_page_url); ?>" 
                                 data-width="" 
                                 data-layout="button_count" 
                                 data-action="like" 
                                 data-size="large" 
                                 data-share="true">
                            </div>
                        </div>
                    </div>

                    <div class="vm-fb-menu">
                        <div class="vm-fb-menu-label">Materiały</div>
                        
                        <a href="<?php echo esc_url($fb_page_url); ?>&sk=photos" target="_blank" class="vm-fb-link">
                            <div class="vm-fb-icon"><i class="fas fa-images"></i></div>
                            <div class="vm-fb-text">
                                <strong>Zdjęcia</strong>
                                <span>Zobacz galerię realizacji</span>
                            </div>
                            <i class="fas fa-external-link-alt vm-fb-arrow"></i>
                        </a>

                        <a href="<?php echo esc_url($fb_page_url); ?>&sk=photos_albums" target="_blank" class="vm-fb-link">
                            <div class="vm-fb-icon"><i class="fas fa-layer-group"></i></div>
                            <div class="vm-fb-text">
                                <strong>Albumy</strong>
                                <span>Przeglądaj tematycznie</span>
                            </div>
                            <i class="fas fa-external-link-alt vm-fb-arrow"></i>
                        </a>

                        <a href="<?php echo esc_url($fb_page_url); ?>&sk=videos" target="_blank" class="vm-fb-link">
                            <div class="vm-fb-icon"><i class="fas fa-play-circle"></i></div>
                            <div class="vm-fb-text">
                                <strong>Wideo</strong>
                                <span>Relacje z inwestycji</span>
                            </div>
                            <i class="fas fa-external-link-alt vm-fb-arrow"></i>
                        </a>
                    </div>
                    
                    <div class="vm-fb-footer">
                        <a href="<?php echo esc_url($fb_page_url); ?>" target="_blank" class="vm-btn-fb-full">
                            <i class="fab fa-facebook-f"></i> Odwiedź nasz Profil
                        </a>
                    </div>
                </div>

                <!-- PRAWA KOLUMNA: Zmieniona na col-lg-7, aby 500px wyglądało na 100% -->
                <div class="col-lg-7 col-md-7 vm-fb-content">
                    <div class="vm-fb-feed-header">
                        <span class="vm-feed-title"><i class="fas fa-rss me-2"></i> Ostatnie posty</span>
                        <div class="vm-live-indicator">
                            <span class="dot"></span> Aktualizowane na żywo
                        </div>
                    </div>
                    
                    <div class="vm-fb-feed-container">
                        <!-- 
                            FIX 100% WIDTH:
                            1. data-width="500" -> Max dozwolony przez FB
                            2. data-adapt-container-width="true" -> Wymusza dopasowanie
                            3. Style CSS (poniżej) rozciągają iframe
                        -->
                        <div class="fb-page" 
                             data-href="<?php echo esc_url($fb_page_url); ?>" 
                             data-tabs="timeline" 
                             data-width="500" 
                             data-height="700" 
                             data-small-header="false" 
                             data-adapt-container-width="true" 
                             data-hide-cover="false" 
                             data-show-facepile="false">
                            <blockquote cite="<?php echo esc_url($fb_page_url); ?>" class="fb-xfbml-parse-ignore">
                                <a href="<?php echo esc_url($fb_page_url); ?>">Voltmont - Instalacje elektryczne</a>
                            </blockquote>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</section>