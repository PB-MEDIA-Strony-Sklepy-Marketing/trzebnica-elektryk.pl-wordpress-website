<?php
/**
 * Plugin Name: PB MEDIA Coming Soon
 * Plugin URI: https://pbmediaonline.pl
 * Description: Wyświetla stronę Coming Soon dla niezalogowanych użytkowników podczas prac nad witryną.
 * Version: 1.2.0
 * Author: PB MEDIA - Strony, Sklepy, Marketing
 * Author URI: https://pbmediaonline.pl
 * Text Domain: pb-media-coming-soon
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * ADMIN: Rejestracja strony ustawień
 */
function safepilot_coming_soon_menu() {
    add_options_page(
        'Ustawienia Coming Soon',
        'Coming Soon',
        'manage_options',
        'safepilot-coming-soon',
        'safepilot_coming_soon_settings_page'
    );
}
add_action('admin_menu', 'safepilot_coming_soon_menu');

/**
 * ADMIN: Rejestracja opcji
 */
function safepilot_coming_soon_register_settings() {
    // Istniejące opcje
    register_setting('safepilot_coming_soon_options', 'safepilot_coming_soon_enabled');
    register_setting('safepilot_coming_soon_options', 'safepilot_coming_soon_background');
    register_setting('safepilot_coming_soon_options', 'safepilot_coming_soon_send_503');
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_coming_soon_logo',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default'           => plugin_dir_url(__FILE__) . 'assets/logo.jpg',
        )
    );
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_coming_soon_location',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'SafePilot, ul. Kordiana 50B/65, 30-653 Kraków',
        )
    );
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_coming_soon_phone',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '+48 726 739 238',
        )
    );
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_coming_soon_email',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_email',
            'default'           => 'biuro@safepilot.pl',
        )
    );
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_coming_soon_text',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'www.safepilot.pl',
        )
    );
    
    // NOWE OPCJE - Social Media
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_facebook_url',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default'           => 'https://www.facebook.com/pbmediaonline',
        )
    );
    
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_instagram_url',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default'           => 'https://www.instagram.com/pbmediastudio',
        )
    );
    
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_whatsapp_number',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '48695816068',
        )
    );
    
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_google_url',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default'           => 'https://share.google/rbjiE5pLLRBMr1gOj',
        )
    );
    
    // NOWE OPCJE - SEO
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_description_seo',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_textarea_field',
            'default'           => 'TWORZYMY PROFESJONALNE STRONY I SKLEPY INTERNETOWE. Zapoznaj się z naszą OFERTĄ IT, sprawdź także co posiadamy w ofercie MARKETING INTERNETOWY wszystko to dla małych i dużych firm.',
        )
    );
    
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_keywords_seo',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'PB MEDIA, Strony internetowe, Sklepy internetowe, Marketing, Trzebnica, Wrocław',
        )
    );
    
    register_setting(
        'safepilot_coming_soon_options',
        'safepilot_author_seo',
        array(
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => 'PB MEDIA - Strony, Sklepy, Marketing',
        )
    );
}
add_action('admin_init', 'safepilot_coming_soon_register_settings');

/**
 * ADMIN: Widok ustawień
 */
function safepilot_coming_soon_settings_page() {
    ?>
    <div class="wrap">
        <h1>PB MEDIA Coming Soon - Ustawienia</h1>
        <form method="post" action="options.php">
            <?php settings_fields('safepilot_coming_soon_options'); ?>
            <?php do_settings_sections('safepilot_coming_soon_options'); ?>
            
            <h2>Ustawienia podstawowe</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Włącz tryb Coming Soon</th>
                    <td>
                        <label>
                            <input type="checkbox" name="safepilot_coming_soon_enabled" value="1" <?php checked(get_option('safepilot_coming_soon_enabled'), 1); ?> />
                            Aktywuj stronę Coming Soon dla niezalogowanych użytkowników.
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Wyślij nagłówek 503</th>
                    <td>
                        <label>
                            <input type="checkbox" name="safepilot_coming_soon_send_503" value="1" <?php checked(get_option('safepilot_coming_soon_send_503'), 1); ?> />
                            Zaznacz, aby zwracać 503 Service Unavailable (zalecane tylko podczas krótkich prac).
                        </label>
                        <p class="description">Domyślnie wyłączone (zwracane jest 200 OK), aby uniknąć błędów w konsoli.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">URL obrazka tła</th>
                    <td>
                        <input type="text" name="safepilot_coming_soon_background"
                               value="<?php echo esc_attr(get_option('safepilot_coming_soon_background', plugin_dir_url(__FILE__) . 'assets/background.jpg')); ?>"
                               class="regular-text" />
                        <p class="description">Podaj pełny URL obrazka tła. Domyślnie: assets/background.jpg z tej wtyczki.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">URL logo na stronie</th>
                    <td>
                        <input type="text" name="safepilot_coming_soon_logo"
                               value="<?php echo esc_attr(get_option('safepilot_coming_soon_logo', plugin_dir_url(__FILE__) . 'assets/logo.jpg')); ?>"
                               class="regular-text" />
                        <p class="description">Podaj pełny URL logo wyświetlanego na stronie Coming Soon.</p>
                    </td>
                </tr>
            </table>

            <h2>Dane kontaktowe</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Lokalizacja firmy</th>
                    <td>
                        <input type="text" name="safepilot_coming_soon_location"
                               value="<?php echo esc_attr(get_option('safepilot_coming_soon_location', 'SafePilot, ul. Kordiana 50B/65, 30-653 Kraków')); ?>"
                               class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Numer telefonu</th>
                    <td>
                        <input type="text" name="safepilot_coming_soon_phone"
                               value="<?php echo esc_attr(get_option('safepilot_coming_soon_phone', '+48 726 739 238')); ?>"
                               class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Adres e-mail</th>
                    <td>
                        <input type="email" name="safepilot_coming_soon_email"
                               value="<?php echo esc_attr(get_option('safepilot_coming_soon_email', 'biuro@safepilot.pl')); ?>"
                               class="regular-text" />
                    </td>
                </tr>
                <tr>
                    <th scope="row">Tekst coming soon</th>
                    <td>
                        <input type="text" name="safepilot_coming_soon_text"
                               value="<?php echo esc_attr(get_option('safepilot_coming_soon_text', 'www.safepilot.pl')); ?>"
                               class="regular-text" />
                    </td>
                </tr>
            </table>

            <h2>Social Media</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Facebook page url</th>
                    <td>
                        <input type="url" name="safepilot_facebook_url"
                               value="<?php echo esc_attr(get_option('safepilot_facebook_url', 'https://www.facebook.com/pbmediaonline')); ?>"
                               class="regular-text" 
                               placeholder="https://www.facebook.com/twojastrona" />
                        <p class="description">Pełny URL strony Facebook. Pozostaw puste, aby ukryć ikonę Facebook.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Instagram page url</th>
                    <td>
                        <input type="url" name="safepilot_instagram_url"
                               value="<?php echo esc_attr(get_option('safepilot_instagram_url', 'https://www.instagram.com/pbmediastudio')); ?>"
                               class="regular-text" 
                               placeholder="https://www.instagram.com/twojprofil" />
                        <p class="description">Pełny URL profilu Instagram. Pozostaw puste, aby ukryć ikonę Instagram.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">WhatsApp number</th>
                    <td>
                        <input type="text" name="safepilot_whatsapp_number"
                               value="<?php echo esc_attr(get_option('safepilot_whatsapp_number', '48695816068')); ?>"
                               class="regular-text" 
                               placeholder="48123456789" />
                        <p class="description">Numer WhatsApp w formacie międzynarodowym bez + (np. 48695816068). Pozostaw puste, aby ukryć ikonę WhatsApp.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Google url</th>
                    <td>
                        <input type="url" name="safepilot_google_url"
                               value="<?php echo esc_attr(get_option('safepilot_google_url', 'https://share.google/rbjiE5pLLRBMr1gOj')); ?>"
                               class="regular-text" 
                               placeholder="https://share.google/xxxxx" />
                        <p class="description">Pełny URL profilu Google Moja Firma. Pozostaw puste, aby ukryć ikonę Google.</p>
                    </td>
                </tr>
            </table>

            <h2>Ustawienia SEO</h2>
            <table class="form-table" role="presentation">
                <tr>
                    <th scope="row">Meta Description SEO</th>
                    <td>
                        <textarea name="safepilot_description_seo" 
                                  rows="3" 
                                  class="large-text"
                                  placeholder="Opis strony dla wyszukiwarek (max 160 znaków)"><?php echo esc_textarea(get_option('safepilot_description_seo', 'TWORZYMY PROFESJONALNE STRONY I SKLEPY INTERNETOWE. Zapoznaj się z naszą OFERTĄ IT, sprawdź także co posiadamy w ofercie MARKETING INTERNETOWY wszystko to dla małych i dużych firm.')); ?></textarea>
                        <p class="description">Opis meta wyświetlany w wynikach wyszukiwania. Zalecana długość: 150-160 znaków. Pozostaw puste, aby ukryć meta description.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Meta Keywords SEO</th>
                    <td>
                        <input type="text" name="safepilot_keywords_seo"
                               value="<?php echo esc_attr(get_option('safepilot_keywords_seo', 'PB MEDIA, Strony internetowe, Sklepy internetowe, Marketing, Trzebnica, Wrocław')); ?>"
                               class="large-text" 
                               placeholder="słowo1, słowo2, słowo3" />
                        <p class="description">Słowa kluczowe oddzielone przecinkami. Pozostaw puste, aby ukryć meta keywords.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Meta Author SEO</th>
                    <td>
                        <input type="text" name="safepilot_author_seo"
                               value="<?php echo esc_attr(get_option('safepilot_author_seo', 'PB MEDIA - Strony, Sklepy, Marketing')); ?>"
                               class="regular-text" 
                               placeholder="Nazwa autora lub firmy" />
                        <p class="description">Autor strony. Pozostaw puste, aby ukryć meta author.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * Logika: czy powinniśmy pokazać Coming Soon dla tego żądania
 */
function safepilot_should_show_coming_soon() {
    if (!get_option('safepilot_coming_soon_enabled')) {
        return false;
    }

    // Zalogowani widzą normalnie
    if (is_user_logged_in()) {
        return false;
    }

    // Panel admina, cron, ajax, REST API — pomijamy
    if (is_admin()) return false;
    if (defined('DOING_CRON') && DOING_CRON) return false;
    if (defined('DOING_AJAX') && DOING_AJAX) return false;
    if (defined('REST_REQUEST') && REST_REQUEST) return false;

    // Whitelist dla technicznych endpointów i plików
    $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $script = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : '';

    // logowanie/admin bezpiecznie pominąć
    if (strpos($uri, '/wp-login.php') !== false || strpos($script, 'wp-login.php') !== false) return false;
    if (strpos($uri, '/wp-admin') === 0) return false;

    // robots, favicon, sitemap, feed, cron
    $whitelist_substrings = array('robots.txt', 'favicon.ico', 'sitemap', '/feed/', 'wp-cron.php', 'index.php?rest_route=');
    foreach ($whitelist_substrings as $needle) {
        if (strpos($uri, $needle) !== false) return false;
    }

    // W przeciwnym razie — pokazujemy Coming Soon
    return true;
}

/**
 * Render strony Coming Soon
 */
function safepilot_render_coming_soon() {
    // Nagłówki
    if (get_option('safepilot_coming_soon_send_503')) {
        status_header(503);
        header('Retry-After: 3600'); // 1 godzina
        nocache_headers();
    } else {
        status_header(200);
        nocache_headers();
    }
    header('Content-Type: text/html; charset=' . get_bloginfo('charset'));

    // Pobieranie wartości z bazy danych
    $background_url = get_option('safepilot_coming_soon_background', plugin_dir_url(__FILE__) . 'img/background.jpg');
    $logo_url = get_option('safepilot_coming_soon_logo', plugin_dir_url(__FILE__) . 'img/logo.jpg');
    $location_text = get_option('safepilot_coming_soon_location', 'SafePilot, ul. Kordiana 50B/65, 30-653 Kraków');
    $phone_text = get_option('safepilot_coming_soon_phone', '+48 726 739 238');
    $email_text = get_option('safepilot_coming_soon_email', 'biuro@safepilot.pl');
    $coming_soon_text = get_option('safepilot_coming_soon_text', 'www.safepilot.pl');
    
    // Social Media URLs
    $facebook_url = get_option('safepilot_facebook_url', '');
    $instagram_url = get_option('safepilot_instagram_url', '');
    $whatsapp_number = get_option('safepilot_whatsapp_number', '');
    $google_url = get_option('safepilot_google_url', '');
    
    // SEO Meta
    $description_seo = get_option('safepilot_description_seo', '');
    $keywords_seo = get_option('safepilot_keywords_seo', '');
    $author_seo = get_option('safepilot_author_seo', '');

    $phone_href = preg_replace('/[^0-9+]/', '', $phone_text);
    if (empty($phone_href)) {
        $phone_href = preg_replace('/\s+/', '', $phone_text);
    }

    ?>
    <!DOCTYPE html>
    <html lang="pl">
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php bloginfo('name'); ?></title>
        
        <?php if (!empty($description_seo)): ?>
        <meta name="description" content="<?php echo esc_attr($description_seo); ?>">
        <?php endif; ?>
        
        <?php if (!empty($keywords_seo)): ?>
        <meta name="keywords" content="<?php echo esc_attr($keywords_seo); ?>">
        <?php endif; ?>
        
        <?php if (!empty($author_seo)): ?>
        <meta name="author" content="<?php echo esc_attr($author_seo); ?>">
        <?php endif; ?>
    
        <link rel="canonical" href="<?php echo esc_url(get_site_url()); ?>">
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex, nofollow">

        <!-- Bootstrap CSS -->
        <link href="<?php echo plugin_dir_url(__FILE__) . 'assets/bootstrap/bootstrap.min.css'; ?>" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'awesome/css/all.min.css'; ?>">
        <!-- AOS Animation Library -->
        <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'assets/css/aos.css'; ?>">
        <!-- Custom Styles -->
        <link href="<?php echo plugin_dir_url(__FILE__) . 'assets/css/style.css'; ?>" rel="stylesheet">
        
        <style>
            body {
                background: url('<?php echo esc_url($background_url); ?>') no-repeat center center;
                background-size: cover;
                background-attachment: fixed;
            }
        </style>
    </head>
    <body>
        <div class="bg-overlay"></div>

        <div class="container min-vh-100 d-flex align-items-center justify-content-center">
            <div class="col-lg-8 col-md-10 text-center content-wrapper">
                <div class="logo-container" data-aos="fade-down" data-aos-duration="1000">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" class="img-fluid logo-img">
                </div>

                <div class="company-info" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                    <?php if (!empty($location_text)): ?>
                    <p class="address">
                        <i class="fas fa-map-marker-alt info-icon"></i>
                        <?php echo esc_html($location_text); ?>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($phone_text)): ?>
                    <p class="phone">
                        <i class="fas fa-phone info-icon"></i>
                        <a href="tel:<?php echo esc_attr($phone_href); ?>"><?php echo esc_html($phone_text); ?></a>
                    </p>
                    <?php endif; ?>
                    
                    <?php if (!empty($email_text)): ?>
                    <p class="email">
                        <i class="fas fa-envelope info-icon"></i>
                        <a href="mailto:<?php echo esc_attr($email_text); ?>"><?php echo esc_html($email_text); ?></a>
                    </p>
                    <?php endif; ?>

                    <?php if (!empty($coming_soon_text)): ?>
                    <div class="company-id">
                        <p class="website"><?php echo esc_html($coming_soon_text); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Social media -->
                    <?php if (!empty($facebook_url) || !empty($instagram_url) || !empty($whatsapp_number) || !empty($google_url)): ?>
                    <div class="social-media-row">
                        <?php if (!empty($facebook_url)): ?>
                        <a href="<?php echo esc_url($facebook_url); ?>" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook"></i>&nbsp;Facebook</a>
                        <?php endif; ?>
                        
                        <?php if (!empty($facebook_url) && !empty($instagram_url)): ?><?php endif; ?>
                        
                        <?php if (!empty($instagram_url)): ?>
                        <a href="<?php echo esc_url($instagram_url); ?>" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i>&nbsp;Instagram</a>
                        <?php endif; ?>
                        
                        <?php if (!empty($instagram_url) && !empty($whatsapp_number)): ?><?php endif; ?>
                        
                        <?php if (!empty($whatsapp_number)): ?>
                        <a href="https://wa.me/<?php echo esc_attr($whatsapp_number); ?>" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-whatsapp"></i>&nbsp;WhatsApp</a>
                        <?php endif; ?>
                        
                        <?php if (!empty($whatsapp_number) && !empty($google_url)): ?><?php endif; ?>
                        
                        <?php if (!empty($google_url)): ?>
                        <a href="<?php echo esc_url($google_url); ?>" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-google"></i>&nbsp;Google Moja Firma</a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Copyright -->
                    <p class="copyrights-text">
                        <?php echo date('Y'); ?> © <?php echo esc_url(get_site_url()); ?>&nbsp;/&nbsp;
                        <a target="_blank" rel="dofollow" href="https://pbmediaonline.pl">
                            Powered by: PB MEDIA - Strony, Sklepy, Marketing.
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Bootstrap JS Bundle -->
        <script src="<?php echo plugin_dir_url(__FILE__) . 'assets/js/bootstrap.bundle.min.js'; ?>"></script>
        <!-- AOS Animation -->
        <script src="<?php echo plugin_dir_url(__FILE__) . 'assets/js/aos.js'; ?>"></script>
        <!-- Custom script -->
        <script src="<?php echo plugin_dir_url(__FILE__) . 'assets/js/script.js'; ?>"></script>
        <script>
            // Start animacji po załadowaniu
            window.addEventListener('load', function() {
                document.body.classList.add('loaded');
                AOS.init({
                    once: false,
                    mirror: true,
                    easing: 'ease-out-cubic',
                    duration: 800
                });
            });

            // Subtelny parallax
            document.addEventListener('mousemove', function(e) {
                const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
                const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
                document.body.style.backgroundPosition = `calc(50% + ${moveX}px) calc(50% + ${moveY}px)`;
            });

            // Dynamiczny glow dla badge
            document.addEventListener('DOMContentLoaded', function() {
                const badge = document.querySelector('.coming-soon-badge span');
                if (badge) {
                    setInterval(() => {
                        badge.classList.add('glow');
                        setTimeout(() => { badge.classList.remove('glow'); }, 1000);
                    }, 3000);
                }
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}

/**
 * Hook do wyświetlenia Coming Soon
 */
function safepilot_coming_soon_template() {
    if (safepilot_should_show_coming_soon()) {
        safepilot_render_coming_soon();
    }
}
add_action('template_redirect', 'safepilot_coming_soon_template', 0);

/**
 * Link do ustawień na liście wtyczek
 */
function safepilot_coming_soon_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=safepilot-coming-soon">Ustawienia</a>';
    array_unshift($links, $settings_link);
    return $links;
}
$plugin_basename = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_basename", 'safepilot_coming_soon_settings_link');