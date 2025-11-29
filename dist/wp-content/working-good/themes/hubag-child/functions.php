<?php

/**
 * Child Theme constants
 * You can change below constants
 */

// white label

define('WHITE_LABEL', false);

// Ładowanie własnych plików CSS w motywie potomnym
function child_theme_enqueue_custom_css_bootstrap_w3() {

    // Ścieżka do motywu potomnego
    $theme_dir = get_stylesheet_directory_uri();

    // Bootstrap Grid
    wp_enqueue_style(
        'child-bootstrap-grid', // Unikalny identyfikator
        $theme_dir . '/frame/bootstrap/bootstrap-grid.css',
        array(), // Brak zależności
        filemtime( get_stylesheet_directory() . '/frame/bootstrap/bootstrap-grid.min.css' ) // wersja = czas modyfikacji pliku (cache busting)
    );

    // W3.CSS
    wp_enqueue_style(
        'child-w3', // Unikalny identyfikator
        $theme_dir . '/frame/w3/w3.css',
        array(),
        filemtime( get_stylesheet_directory() . '/frame/w3/w3.min.css' )
    );
}
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_custom_css_bootstrap_w3' );

// Funkcja ładująca pliki JS w stopce z async i defer
function child_theme_enqueue_custom_js_bootstrap_w3() {

    $theme_dir = get_stylesheet_directory_uri();

    // Bootstrap Bundle
    wp_enqueue_script(
        'child-bootstrap-bundle-js',
        $theme_dir . '/frame/bootstrap/bootstrap.bundle.min.js',
        array(), // brak zależności
        filemtime( get_stylesheet_directory() . '/frame/bootstrap/bootstrap.bundle.min.js' ),
        true // ładowanie w stopce
    );

    // Bootstrap
    wp_enqueue_script(
        'child-bootstrap-js',
        $theme_dir . '/frame/bootstrap/bootstrap.min.js',
        array(),
        filemtime( get_stylesheet_directory() . '/frame/bootstrap/bootstrap.min.js' ),
        true
    );

    // W3
    wp_enqueue_script(
        'child-w3-js',
        $theme_dir . '/frame/w3/w3.min.js',
        array(),
        filemtime( get_stylesheet_directory() . '/frame/w3/w3.min.js' ),
        true
    );
}
add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_custom_js_bootstrap_w3' );

// Dodanie async i defer do wczytywanych skryptów
function child_theme_add_async_defer_bootstrap_w3_js( $tag, $handle, $src ) {
    $async_defer_scripts = array(
        'child-bootstrap-bundle-js',
        'child-bootstrap-js',
        'child-w3-js'
    );

    if ( in_array( $handle, $async_defer_scripts ) ) {
        return '<script src="' . esc_url( $src ) . '" async defer></script>';
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'child_theme_add_async_defer_bootstrap_w3_js', 10, 3 );

/**
 * Enqueue Styles
 */

function mfnch_enqueue_styles()
{
	// enqueue the parent stylesheet
	// however we do not need this if it is empty
	// wp_enqueue_style('parent-style', get_template_directory_uri() .'/style.css');

	// enqueue the parent RTL stylesheet

	if (is_rtl()) {
		wp_enqueue_style('mfn-rtl', get_template_directory_uri() . '/rtl.css');
	}

	// enqueue the child stylesheet

	wp_dequeue_style('style');
	wp_enqueue_style('style', get_stylesheet_directory_uri() .'/style.css');
}
add_action('wp_enqueue_scripts', 'mfnch_enqueue_styles', 101);

/**
 * Load Textdomain
 */

function mfnch_textdomain()
{
	load_child_theme_textdomain('betheme', get_stylesheet_directory() . '/languages');
	load_child_theme_textdomain('mfn-opts', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'mfnch_textdomain');

/**
 * Adds webp filetype to allowed mimes
 * 
 * @see https://codex.wordpress.org/Plugin_API/Filter_Reference/upload_mimes
 * 
 * @param array $mimes Mime types keyed by the file extension regex corresponding to
 *                     those types. 'swf' and 'exe' removed from full list. 'htm|html' also
 *                     removed depending on '$user' capabilities.
 *
 * @return array
 */
add_filter( 'upload_mimes', 'wpse_mime_types_webp' );
function wpse_mime_types_webp( $mimes ) {
    $mimes['webp'] = 'image/webp';

  return $mimes;
}

// Add to existing function.php file
// Disable support for comments and trackbacks in post types
function df_disable_comments_post_types_support() {
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}
add_action('admin_init', 'df_disable_comments_post_types_support');
// Close comments on the front-end
function df_disable_comments_status() {
	return false;
}
add_filter('comments_open', 'df_disable_comments_status', 20, 2);
add_filter('pings_open', 'df_disable_comments_status', 20, 2);
// Hide existing comments
function df_disable_comments_hide_existing_comments($comments) {
	$comments = array();
	return $comments;
}
add_filter('comments_array', 'df_disable_comments_hide_existing_comments', 10, 2);
// Remove comments page in menu
function df_disable_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'df_disable_comments_admin_menu');
// Redirect any user trying to access comments page
function df_disable_comments_admin_menu_redirect() {
	global $pagenow;
	if ($pagenow === 'edit-comments.php') {
		wp_redirect(admin_url()); exit;
	}
}
add_action('admin_init', 'df_disable_comments_admin_menu_redirect');
// Remove comments metabox from dashboard
function df_disable_comments_dashboard() {
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'df_disable_comments_dashboard');
// Remove comments links from admin bar
function df_disable_comments_admin_bar() {
	if (is_admin_bar_showing()) {
		remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
	}
}
add_action('init', 'df_disable_comments_admin_bar');

/* Automatically set the image Title, Alt-Text, Caption & Description upon upload
--------------------------------------------------------------------------------------*/
add_action( 'add_attachment', 'my_set_image_meta_upon_image_upload' );
function my_set_image_meta_upon_image_upload( $post_ID ) {

	// Check if uploaded file is an image, else do nothing

	if ( wp_attachment_is_image( $post_ID ) ) {

		$my_image_title = get_post( $post_ID )->post_title;

		// Sanitize the title:  remove hyphens, underscores & extra spaces:
		$my_image_title = preg_replace( '%\s*[-_\s]+\s*%', ' ',  $my_image_title );

		// Sanitize the title:  capitalize first letter of every word (other letters lower case):
		$my_image_title = ucwords( strtolower( $my_image_title ) );

		// Create an array with the image meta (Title, Caption, Description) to be updated
		// Note:  comment out the Excerpt/Caption or Content/Description lines if not needed
		$my_image_meta = array(
			'ID'		=> $post_ID,			// Specify the image (ID) to be updated
			'post_title'	=> $my_image_title,		// Set image Title to sanitized title
			'post_excerpt'	=> $my_image_title,		// Set image Caption (Excerpt) to sanitized title
			'post_content'	=> $my_image_title,		// Set image Description (Content) to sanitized title
		);

		// Set the image Alt-Text
		update_post_meta( $post_ID, '_wp_attachment_image_alt', $my_image_title );

		// Set the image meta (e.g. Title, Excerpt, Content)
		wp_update_post( $my_image_meta );

	} 
}

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 11 );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'child-style', get_stylesheet_uri() );
}

add_action('mfn_hook_bottom', 'kodydobody');
function kodydobody(){
  ?>
  
<!-- START PASEK WYSUWANY VOLTMONT -->
<div id="voltmont-emergency-panel" class="voltmont-panel">
    <div class="voltmont-panel-trigger">
        <div class="voltmont-panel-icon">
            <i class="fas fa-bolt"></i>
        </div>
        <span class="voltmont-panel-text">24h</span>
        <span class="voltmont-panel-title">POGOTOWIE<br>ELEKTRYCZNE</span>
    </div>
    <div class="voltmont-panel-content">
        <div class="voltmont-panel-header">
            <div class="voltmont-pulse-icon">
                <i class="fas fa-bolt"></i>
            </div>
            <h3>Pogotowie Elektryczne 24h</h3>
            <p class="voltmont-panel-subtitle">Wrocław • Trzebnica • Dolny Śląsk</p>
        </div>
        <div class="voltmont-panel-body">
            <div class="voltmont-contact-item">
                <div class="voltmont-contact-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div class="voltmont-contact-info">
                    <span class="voltmont-contact-label">Zadzwoń teraz:</span>
                    <a href="tel:+48691594820" class="voltmont-contact-link voltmont-phone">
                        +48 691 594 820
                    </a>
                </div>
            </div>
            <div class="voltmont-contact-item">
                <div class="voltmont-contact-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="voltmont-contact-info">
                    <span class="voltmont-contact-label">Napisz do nas:</span>
                    <a href="mailto:biuro@trzebnica-elektryk.pl" class="voltmont-contact-link">
                        biuro@trzebnica-elektryk.pl
                    </a>
                </div>
            </div>
            <div class="voltmont-contact-item">
                <div class="voltmont-contact-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="voltmont-contact-info">
                    <span class="voltmont-contact-label">Dostępność:</span>
                    <span class="voltmont-availability">
                        <span class="voltmont-status-dot"></span>
                        Dostępni 24/7
                    </span>
                </div>
            </div>
        </div>
        <div class="voltmont-panel-footer">
            <a href="#modal-opened" class="voltmont-cta-button">
                <i class="fas fa-comment-dots"></i>
                Zamów rozmowę zwrotną
            </a>
        </div>
    </div>
</div>
<!-- END PASEK WYSUWANY VOLTMONT -->
  
<!-- START PASKI BOCZNE PRAWE SLIDEOUT WYSUWANY VOLTMONT -->
<div class="voltmont-social-widgets">
    
    <!-- Facebook Widget -->
    <div class="voltmont-slideout voltmont-slideout-facebook" data-position="1">
        <div class="voltmont-slideout-handler">
            <div class="voltmont-icon-wrapper">
                <i class="fab fa-facebook-f"></i>
            </div>
            <span class="voltmont-handler-text">Facebook</span>
        </div>
        <div class="voltmont-slideout-content">
            <div class="voltmont-content-inner">
                <div class="voltmont-content-icon">
                    <i class="fab fa-facebook-f"></i>
                </div>
                <div class="voltmont-content-text">
                    <h4>Śledź nas na Facebook</h4>
                    <p>Aktualne realizacje i porady elektryczne</p>
                    <a href="https://www. facebook.com/profile.php? id=100063601389747" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="voltmont-widget-link"
                       aria-label="Odwiedź naszą stronę Facebook">
                        <span>Przejdź do profilu</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Widget -->
    <div class="voltmont-slideout voltmont-slideout-email" data-position="2">
        <div class="voltmont-slideout-handler">
            <div class="voltmont-icon-wrapper">
                <i class="fas fa-envelope"></i>
            </div>
            <span class="voltmont-handler-text">E-mail</span>
        </div>
        <div class="voltmont-slideout-content">
            <div class="voltmont-content-inner">
                <div class="voltmont-content-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="voltmont-content-text">
                    <h4>Napisz do nas</h4>
                    <p>Odpowiadamy w ciągu 24h</p>
                    <a href="mailto:biuro@trzebnica-elektryk.pl" 
                       class="voltmont-widget-link"
                       aria-label="Wyślij email do Voltmont">
                        <span>biuro@trzebnica-elektryk.pl</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Phone Widget -->
    <div class="voltmont-slideout voltmont-slideout-phone" data-position="3">
        <div class="voltmont-slideout-handler">
            <div class="voltmont-icon-wrapper">
                <i class="fas fa-phone-alt"></i>
            </div>
            <span class="voltmont-handler-text">Telefon</span>
        </div>
        <div class="voltmont-slideout-content">
            <div class="voltmont-content-inner">
                <div class="voltmont-content-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div class="voltmont-content-text">
                    <h4>Zadzwoń teraz</h4>
                    <p>Dostępni 24/7</p>
                    <a href="tel:+48691594820" 
                       class="voltmont-widget-link voltmont-phone-link"
                       aria-label="Zadzwoń do Voltmont">
                        <span>+48 691 594 820</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Maps Widget -->
    <div class="voltmont-slideout voltmont-slideout-location" data-position="4">
        <div class="voltmont-slideout-handler">
            <div class="voltmont-icon-wrapper">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <span class="voltmont-handler-text">Lokalizacja</span>
        </div>
        <div class="voltmont-slideout-content voltmont-slideout-wide">
            <div class="voltmont-content-inner">
                <div class="voltmont-content-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="voltmont-content-text">
                    <h4>Nasza lokalizacja</h4>
                    <p>Trzebnica • Wrocław • Dolny Śląsk</p>
                    <a href="https://maps.app.goo.gl/uWX3H3oYdpkx4wAY6" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="voltmont-widget-link"
                       aria-label="Zobacz lokalizację na mapie Google">
                        <span>Zobacz na mapie Google</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- END PASKI BOCZNE PRAWE SLIDEOUT WYSUWANY VOLTMONT -->

<!-- Okienko zamów rozmowę -->
<div class="modal-containerhubag" id="modal-opened">
  <div class="modalhubag">
    <div class="modal__detailshubag">
      <h1 class="modal__titlehubag">Zamów rozmowę</h1>
      <p class="modal__descriptionhubag">Zostaw dane kontaktowe w formularzu poniżej</p>
    </div>
    <p class="modal__texthubag"><?php echo do_shortcode('[contact-form-7 id="df648c3" title="Zamów rozmowę - formularz kontaktowy"]'); ?></p>
    <center style="margin-top: 4rem;"><a href="#modal-closed" style="color: #fff;"><button class="modal__btnhubag">Zamknij okno</button></a></center>
    <a href="#modal-closed" class="link-2hubag">x</a>
  </div>
</div>

<!-- Okienko aplikuj o prace -->
<div class="modal-containerhubag" id="aplikuj-opened">
  <div class="modalhubag">
    <div class="modal__detailshubag">
		<p class="modal__descriptionhubag">Aplikuj do pracy w HUBAG</p>
    </div>
    <p class="modal__texthubag"><?php echo do_shortcode('[contact-form-7 id="0416136" title="Aplikacja o prace - formularz kontaktowy"]'); ?></p>
    <center style="margin-top: 4rem;"><a href="#aplikuj-closed" style="color: #fff;"><button class="modal__btnhubag">Zamknij okno</button></a></center>
    <a href="#aplikuj-closed" class="link-2hubag">x</a>
  </div>
</div>
  <?php
}

remove_action('wp_head', 'wp_generator');

function my_secure_generator( $generator, $type ) {
	return '';
}
add_filter( 'the_generator', 'my_secure_generator', 10, 2 );

function my_remove_src_version( $src ) {
	global $wp_version;

	$version_str = '?ver='.$wp_version;
	$offset = strlen( $src ) - strlen( $version_str );

	if ( $offset >= 0 && strpos($src, $version_str, $offset) !== FALSE )
		return substr( $src, 0, $offset );

	return $src;
}
add_filter( 'script_loader_src', 'my_remove_src_version' );
add_filter( 'style_loader_src', 'my_remove_src_version' );

add_filter('xmlrpc_enabled', '__return_false');

function my_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/site-login-logo.png);
        height:160px;
		width:320px;
		background-size: 320px 160px;
		background-repeat: no-repeat;
        	padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

function my_login_logo_url() {
    return home_url();
}
add_filter( 'login_headerurl', 'my_login_logo_url' );

function my_login_logo_url_title() {
    return 'Kokpit www.hubag.pl';
}
add_filter( 'login_headertitle', 'my_login_logo_url_title' );

// Simple Shortcode
# Dodanie Listwy i Czyszczenie shortcode dla strony głównej.
function podofertafunkcja() {
    ob_start();
    get_template_part('podoferta');
    return ob_get_clean();   
} 
add_shortcode( 'podoferta_shortcode', 'podofertafunkcja' );

/*
 * This code duplicates a WordPress page. The duplicate page will appear as a draft and the user will be redirected to the edit screen.
 */
function rd_duplicate_post_as_draft(){
    global $wpdb;
    if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'rd_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
        wp_die('No post to duplicate has been supplied!');
    }
    if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
        return;
    $post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
    $post = get_post( $post_id );
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;
    if (isset( $post ) && $post != null) {
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );
        $new_post_id = wp_insert_post( $args );
        $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }
        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos)!=0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                if( $meta_key == '_wp_old_slug' ) continue;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query.= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }
        wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
        exit;
    } else {
        wp_die('Tworzenie posta nie powiodło się, nie można znaleźć oryginalnego posta:' . $post_id);
    }
}
add_action( 'admin_action_rd_duplicate_post_as_draft', 'rd_duplicate_post_as_draft' );
function rd_duplicate_post_link( $actions, $post ) {
    if (current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=rd_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplikuj to" rel="permalink">Duplikuj</a>';
    }
    return $actions;
}
add_filter( 'post_row_actions', 'rd_duplicate_post_link', 10, 2 );
add_filter( 'page_row_actions', 'rd_duplicate_post_link', 10, 2 );

// Dodanie support zajawka excerpt do custom post type (Strona, Klienci, Oferta, Referencje)
add_post_type_support( 'page', 'excerpt' );
add_post_type_support( 'client', 'excerpt' );
add_post_type_support( 'offer', 'excerpt' );
add_post_type_support( 'testimonial', 'excerpt' );

// Krok 1: Dodanie strony do menu administracyjnego
add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {

    // Dodanie strony do menu
    add_menu_page(
        'Pomoc techniczna',          // Tytuł strony
        'Pomoc techniczna',          // Tytuł w menu
        'edit_posts',                // Wymagana rola
        'pomoc-techniczna',          // Slug strony
        'my_plugin_options',         // Callback funkcji wyświetlającej
        'dashicons-info',                   // Ikona
        100                          // Pozycja w menu
    );
}

// Krok 2: Funkcja callback (możesz dostosować treść strony)
function my_plugin_options() {
    ?>
    <div class="wrap">
        <h1>Pomoc techniczna</h1>
        <p>Skontaktuj się z nami kiedy potrzebujesz fachowej i profesjonalnej pomocy ze swoją witryną internetową.</p>
    </div>
       <style>
div.container4 {
  margin-top: 20%;
  margin-left: 40%;
  align-items: center;
  justify-content: center
  text-align: center; }
  div.container5 {
  margin-top: 20px;
  margin-left: 45%;
  align-items: center;
  justify-content: center
  text-align: center; }
</style>
<div class="container4">
<h1 style="font-size: 50px;">Pomoc techniczna</h1></div>
<div class="container5"><h4><a href="mailto:biuro@pbmediaonline.pl" target="_blank" style="font-size: 20px;">biuro@pbmediaonline.pl</a></h4></div>
    <?php
}

// Disable comments from media
function filter_media_comment_status( $open, $post_id ) {
    $post = get_post( $post_id );
    if( $post->post_type == 'attachment' ) {
        return false;
    }
    return $open;
}
add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );

add_theme_support( 'post-thumbnails' );
add_action( 'after_setup_theme', 'wpdocs_theme_setup' );
function wpdocs_theme_setup() {
    add_image_size( 'category-thumb', 300 ); // 300 pixels wide (and unlimited height)
    add_image_size( 'homepage-thumb', 220, 180, true ); // (cropped)
    add_image_size( 'sidebar-thumb', 120, 120, true ); // Hard Crop Mode
    add_image_size( 'singlepost-thumb', 590, 999 ); // Unlimited Height Mode
}

// Lazy loading obrazów (WordPress 5.5+ ma tę funkcję wbudowaną, ale można ją wzmocnić)
add_filter('wp_get_attachment_image_attributes', function($attr) {
    $attr['loading'] = 'lazy';
    return $attr;
});

// Wyłączenie WordPress Admin Bar dla wszystkich użytkowników poza administratorami i edytorami
function disable_admin_bar_for_non_admins() {
    if (!current_user_can('administrator') && !current_user_can('editor')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'disable_admin_bar_for_non_admins');

add_filter(
	'admin_footer_text',
	function ( $footer_text ) {
		// Edit the line below to customize the footer text.
		$footer_text = 'Powered by <a href="https://www.pbmediaonline.pl" target="_blank" rel="noopener">PB MEDIA Studio - Strony & Sklepy internetowe</a> | trzebnica-elektryk.pl: <a href="https://trzebnica-elektryk.pl" target="_blank" rel="noopener">www.trzebnica-elektryk.pl</a>';
		
		return $footer_text;
	}
);

add_filter( 'enable_post_by_email_configuration', '__return_false' );

add_action('login_footer', function () {
    echo '<div style="text-align: center; margin-top: 20px;">© ' . date('Y') . ' 2025 Voltmont. All Rights Reserved. PB MEDIA Studio Strony & Sklepy Internetowe</div>';
});

add_filter('login_headertext', function () {
    return 'Witamy w panelu logowania - Voltmont';
});

// Please edit the address and name below.
// Change the From address.
add_filter( 'wp_mail_from', function ( $original_email_address ) {
    return 'biuro@trzebnica-elektryk.pl';
} );

// Change the From name.
add_filter( 'wp_mail_from_name', function ( $original_email_from ) {
    return 'Voltmont - www.trzebnica-elektryk.pl';
} );

add_action( 'wp_body_open', 'wpl_gtm_body_code' );

// Wyłączenie aktualizacji Wordpress i odmowa dostępu
// === Ustawienia globalne ===
// Sprawdzenie, czy aktualny użytkownik nie ma uprawnień (czyli nie jest 'admin')
function bbloomer_should_hide_updates(): bool {
    return ( is_user_logged_in() && wp_get_current_user()->user_login !== 'admin' );
}

// === Ukrywanie aktualizacji ===

add_filter( 'pre_site_transient_update_core', 'bbloomer_maybe_disable_update_core' );
add_filter( 'pre_site_transient_update_plugins', 'bbloomer_maybe_disable_update_plugins' );
add_filter( 'pre_site_transient_update_themes', 'bbloomer_maybe_disable_update_themes' );

function bbloomer_maybe_disable_update_core( $value ) {
    if ( bbloomer_should_hide_updates() ) {
        global $wp_version;
        return (object) array(
            'last_checked'    => time(),
            'version_checked' => $wp_version,
        );
    }
    return $value;
}

function bbloomer_maybe_disable_update_plugins( $value ) {
    if ( bbloomer_should_hide_updates() ) {
        return (object) array();
    }
    return $value;
}

function bbloomer_maybe_disable_update_themes( $value ) {
    if ( bbloomer_should_hide_updates() ) {
        return (object) array();
    }
    return $value;
}

// === Zmiana etykiet menu ===

add_action( 'admin_menu', 'bbloomer_modify_admin_menu_labels', 999 );
function bbloomer_modify_admin_menu_labels() {
    if ( ! bbloomer_should_hide_updates() ) {
        return;
    }

    global $menu, $submenu;

    if ( isset( $menu[65][0] ) ) {
        $menu[65][0] = 'Wtyczki (ukryte)';
    }

    if ( isset( $submenu['index.php'][10][0] ) ) {
        $submenu['index.php'][10][0] = 'Aktualizacje (ukryte)';
    }
}

// === BLOKOWANIE DOSTĘPU DO ZAWARTOŚCI STRON WP-ADMIN ===

add_action( 'admin_init', 'bbloomer_block_plugins_and_updates_pages' );
function bbloomer_block_plugins_and_updates_pages() {
    if ( ! bbloomer_should_hide_updates() ) {
        return;
    }

    $current_screen = $_SERVER['REQUEST_URI'];

    // Blokuj stronę wtyczek
    if ( strpos( $current_screen, '/plugins.php' ) !== false ) {
        bbloomer_render_blocked_message();
    }

    // Blokuj stronę aktualizacji
    if ( strpos( $current_screen, '/update-core.php' ) !== false ) {
        bbloomer_render_blocked_message();
    }
}

// Funkcja wyświetlająca komunikat o braku dostępu
function bbloomer_render_blocked_message() {
    wp_die(
        '<h1>Brak uprawnień</h1><p>Brak uprawnień aby mieć dostęp do tej podstrony.</p>',
        'Brak dostępu',
        array( 'response' => 403 )
    );
}

function wpcode_snippet_oembed_defaults( $sizes ) {
	return array(
		'width'  => 400,
		'height' => 280,
	);
}
add_filter( 'embed_defaults', 'wpcode_snippet_oembed_defaults' );

/**
 * Run shortcodes on HTML field content
 *
 * @link   https://wpforms.com/developers/how-to-display-shortcodes-inside-the-html-field/
 * 
 * For support, please visit: https://www.facebook.com/groups/wpformsvip
 */

function wpf_dev_html_field_shortcodes( $field, $field_atts, $form_data ) {
 
    if ( ! empty( $field[ 'code' ] ) ) {
        $field[ 'code' ] = do_shortcode( $field[ 'code' ] );
    }
 
    return $field;
}
add_filter( 'wpforms_html_field_display', 'wpf_dev_html_field_shortcodes', 10, 3 );

/**
 * Prevent publishing posts under a minimum number of words.
 *
 * @param int     $post_id The id of the post.
 * @param WP_Post $post The post object.
 *
 * @return void
 */
function wpcode_snippet_publish_min_words( $post_id, $post ) {
	// Edit the line below to set the desired minimum number of words.
	$word_count = 100;

	if ( str_word_count( $post->post_content ) < $word_count ) {
		wp_die(
			sprintf(
				// Translators: placeholder adds the minimum number of words.
				esc_html__( 'Treść wpisu jest poniżej minimalnej liczby słów. Aby wpis został opublikowany, musi mieć co najmniej %d słów.' ),
				absint( $word_count )
			)
		);
	}
}

add_action( 'publish_post', 'wpcode_snippet_publish_min_words', 9, 2 );

/* 
Increase WordPress upload size, post size, and max execution time 

Original doc link: https://wpforms.com/how-to-change-max-file-upload-size-in-wordpress/

For support, please visit: https://www.facebook.com/groups/wpformsvip
*/

@ini_set( 'upload_max_size' , '512M' );
@ini_set( 'post_max_size', '512M');
@ini_set( 'max_execution_time', '300' );

add_filter( 'manage_posts_columns', function ( $columns ) {
	$columns['last_modified'] = __( 'Ostatnia modyfikacja' );
	return $columns;
} );

add_action( 'manage_posts_custom_column', function ( $column, $post_id ) {
	if ( 'last_modified' === $column ) {
		$modified_time = get_the_modified_time( 'Y/m/d g:i:s a', $post_id );
		echo esc_html( $modified_time );
	}
}, 10, 2 );

$u_time          = get_the_time( 'U' );
$u_modified_time = get_the_modified_time( 'U' );
// Only display modified date if 24hrs have passed since the post was published.
if ( $u_modified_time >= $u_time + 86400 ) {

	$updated_date = get_the_modified_time( 'F jS, Y' );
	$updated_time = get_the_modified_time( 'h:i a' );

	$updated = '<p class="last-updated">';

	$updated .= sprintf(
	// Translators: Placeholders get replaced with the date and time when the post was modified.
		esc_html__( 'Ostatnia aktualizacja była %1$s między %2$s' ),
		$updated_date,
		$updated_time
	);
	$updated .= '</p>';

	echo wp_kses_post( $updated );
}

/**
 * Wrap the thumbnail in a link to the post.
 * Only use this if your theme doesn't already wrap thumbnails in a link.
 *
 * @param string $html The thumbnail HTML to wrap in an anchor.
 * @param int    $post_id The post ID.
 * @param int    $post_image_id The image id.
 *
 * @return string
 */
function wpcode_snippet_autolink_featured_images( $html, $post_id, $post_image_id ) {
	$html = '<a href="' . get_permalink( $post_id ) . '" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . $html . '</a>';

	return $html;
}

add_filter( 'post_thumbnail_html', 'wpcode_snippet_autolink_featured_images', 20, 3 );

add_action( 'wp_before_admin_bar_render', function () {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu( 'wp-logo' );
}, 0 );

add_filter( 'manage_posts_columns', function ( $columns ) {
	// You can change this to any other position by changing 'title' to the name of the column you want to put it after.
	$move_after     = 'title';
	$move_after_key = array_search( $move_after, array_keys( $columns ), true );

	$first_columns = array_slice( $columns, 0, $move_after_key + 1 );
	$last_columns  = array_slice( $columns, $move_after_key + 1 );

	return array_merge(
		$first_columns,
		array(
			'featured_image' => __( 'Obrazek wyróżniający' ),
		),
		$last_columns
	);
} );

add_action( 'manage_posts_custom_column', function ( $column ) {
	if ( 'featured_image' === $column ) {
		the_post_thumbnail( array( 300, 80 ) );
	}
} );

function wpcode_snippets_change_read_more( $read_more, $read_more_text ) {

	// Edit the line below to add your own "Read More" text.
	$custom_text = 'Czytaj więcej';

	$read_more = str_replace( $read_more_text, $custom_text, $read_more );

	return $read_more;
}

add_filter( 'the_content_more_link', 'wpcode_snippets_change_read_more', 15, 2 );

add_filter( 'sanitize_file_name', 'mb_strtolower' );

add_filter( 'admin_title', function ( $admin_title, $title ) {
	return str_replace( " — WordPress", '', $admin_title );
}, 10, 2 );

add_filter( 'manage_upload_columns', function ( $columns ) {
	$columns ['file_size'] = esc_html__( 'Rozmiar pliku' );

	return $columns;
} );

add_action( 'manage_media_custom_column', function ( $column_name, $media_item ) {
	if ( 'file_size' !== $column_name || ! wp_attachment_is_image( $media_item ) ) {
		return;
	}
	$filesize = size_format( filesize( get_attached_file( $media_item ) ), 2 );
	echo esc_html( $filesize );

}, 10, 2 );

add_action( 'admin_init', function () {
// Get all public post types
	$post_types = get_post_types( array(), 'names' );

	function wpcode_add_post_id_column( $columns ) {
		$columns['wpcode_post_id'] = 'ID numer'; // 'ID' is the column title

		return $columns;
	}

	function wpcode_show_post_id_column_data( $column, $post_id ) {
		if ( 'wpcode_post_id' === $column ) {
			echo '<code>' . absint( $post_id ) . '</code>';
		}
	}

	foreach ( $post_types as $post_type ) {
		// Add new column to the posts list
		add_filter( "manage_{$post_type}_posts_columns", 'wpcode_add_post_id_column' );

		// Fill the new column with the post ID
		add_action( "manage_{$post_type}_posts_custom_column", 'wpcode_show_post_id_column_data', 10, 2 );
	}
} );

remove_filter( 'comment_text', 'make_clickable', 9 );

add_action(
	'enqueue_block_editor_assets',
	function () {
		$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
		wp_add_inline_script( 'wp-blocks', $script );
	}
);

global $post;

if ( ! empty( $post ) ) {
	$categories = get_the_category( $post->ID );

	if ( $categories ) {
		$category_ids = array();
		foreach ( $categories as $category ) {
			$category_ids[] = $category->term_id;
		}

		$query_args = array(
			'category__in'   => $category_ids,
			'post__not_in'   => array( $post->ID ),
			'posts_per_page' => 5
		);

		$related_posts = new WP_Query( $query_args );

		if ( $related_posts->have_posts() ) {
			echo '<div class="related-posts">';
			echo '<h3>Powiązane artykuły</h3><ul>';
			while ( $related_posts->have_posts() ) : $related_posts->the_post();
				echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
			endwhile;
			echo '</ul>';
			echo '</div>';

			wp_reset_postdata();
		}
	}
}
add_filter('wp_is_application_passwords_available', '__return_false');

/**
 * Wczytaj pliki CSS i JS z katalogu /custom/ w katalogu głównym WordPressa
 */
function enqueue_custom_assets_from_custom_folder() {
    $custom_base_url = get_site_url() . '/custom';

    // CSS
    wp_enqueue_style(
        'cookieconsent',
        $custom_base_url . '/cookieconsent.min.css',
        array(),
        null
    );

    wp_enqueue_style(
        'iframemanager-css',
        $custom_base_url . '/iframemanager.min.css',
        array(),
        null
    );

    // JS

    wp_enqueue_script(
        'iframemanager-js',
        $custom_base_url . '/iframemanager.js',
        array(),
        null,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_assets_from_custom_folder' );

/**
 * Wczytaj cookieconsent-config.js jako moduł (type="module")
 */
function enqueue_cookieconsent_module_script() {
    $custom_base_url = get_site_url() . '/custom';

    wp_enqueue_script(
        'cookieconsent-config',
        $custom_base_url . '/cookieconsent-config.js',
        array(),
        null,
        true // wczytaj w footerze
    );
}
add_action( 'wp_enqueue_scripts', 'enqueue_cookieconsent_module_script' );

/**
 * Dodaj type="module" do cookieconsent-config.js
 */
function add_type_module_to_cookieconsent( $tag, $handle, $src ) {
    if ( 'cookieconsent-config' === $handle ) {
        return '<script type="module" src="' . esc_url( $src ) . '"></script>';
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'add_type_module_to_cookieconsent', 10, 3 );

// Ukrywanie wybranych pozycji menu dla wszystkich użytkowników z wyjątkiem użytkownika o ID 1
function wpdocs_customize_admin_menu_for_users() {
    // Sprawdź, czy użytkownik jest zalogowany
    if ( ! is_user_logged_in() ) {
        return;
    }

    // Pobierz ID aktualnie zalogowanego użytkownika
    $current_user_id = get_current_user_id();

    // Jeśli to NIE użytkownik o ID 1 — ukryj określone pozycje menu
    if ( $current_user_id !== 1 ) {
    remove_menu_page('betheme'); //WP CODE
    }
}
add_action( 'admin_init', 'wpdocs_customize_admin_menu_for_users', 999 );

// Rejestracja osobnych kategorii i tagów dla stron
add_action( 'init', function() {

    // Kategorie stron
    register_taxonomy(
        'page_category',
        'page',
        array(
            'labels' => array(
                'name'              => __( 'Kategorie stron oferta', 'betheme' ),
                'singular_name'     => __( 'Kategoria strony oferta', 'betheme' ),
                'search_items'      => __( 'Szukaj kategorii stron oferta', 'betheme' ),
                'all_items'         => __( 'Wszystkie kategorie stron oferta', 'betheme' ),
                'parent_item'       => __( 'Nadrzędna kategoria strony oferta', 'betheme' ),
                'parent_item_colon' => __( 'Nadrzędna kategoria strony oferta:', 'betheme' ),
                'edit_item'         => __( 'Edytuj kategorię strony oferta', 'betheme' ),
                'update_item'       => __( 'Zaktualizuj kategorię strony oferta', 'betheme' ),
                'add_new_item'      => __( 'Dodaj nową kategorię strony oferta', 'betheme' ),
                'new_item_name'     => __( 'Nazwa nowej kategorii strony oferta', 'betheme' ),
                'menu_name'         => __( 'Kategorie stron oferta', 'betheme' ),
            ),
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'kategorie-oferty' ),
            'show_in_rest'      => true // dla edytora blokowego
        )
    );

    // Tagi stron
    register_taxonomy(
        'page_tag',
        'page',
        array(
            'labels' => array(
                'name'                       => __( 'Tagi stron oferta', 'betheme' ),
                'singular_name'              => __( 'Tag strony oferta', 'betheme' ),
                'search_items'               => __( 'Szukaj tagów stron oferta', 'betheme' ),
                'popular_items'              => __( 'Popularne tagi stron oferta', 'betheme' ),
                'all_items'                  => __( 'Wszystkie tagi stron oferta', 'betheme' ),
                'edit_item'                  => __( 'Edytuj tag strony oferta', 'betheme' ),
                'update_item'                 => __( 'Zaktualizuj tag strony oferta', 'betheme' ),
                'add_new_item'               => __( 'Dodaj nowy tag strony oferta', 'betheme' ),
                'new_item_name'               => __( 'Nazwa nowego tagu strony oferta', 'betheme' ),
                'separate_items_with_commas'  => __( 'Oddziel tagi przecinkami', 'betheme' ),
                'add_or_remove_items'         => __( 'Dodaj lub usuń tagi oferta', 'betheme' ),
                'choose_from_most_used'       => __( 'Wybierz spośród najczęściej używanych', 'betheme' ),
                'menu_name'                   => __( 'Tagi stron oferta', 'betheme' ),
            ),
            'hierarchical'      => false,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'tagi-oferty' ),
            'show_in_rest'      => true
        )
    );
});

/**
 * Filter the except length to 20 words.
 *
 * @param int $length Excerpt length.
 * @return int (Maybe) modified excerpt length.
 */
function wpdocs_custom_excerpt_length( $length ) {
	return 10;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

// Globalny excerpt lub opis taksonomii w BeTheme z ikoną Font Awesome (bez tagów HTML w term_description)
function child_theme_global_excerpt_or_term_description() {
    global $post;
    
    // Wykluczenie konkretnej strony po ID
    $excluded_page_id = 50; // ← tutaj wpisz ID strony do wykluczenia
    
    // Jeśli to strona główna – nic nie wyświetlamy
    if (is_front_page()) {
        return;
    }
    
    // Specjalne traktowanie dla strony z wpisami (blog)
    if (is_home()) {
        // Pobierz ID strony ustawionej jako strona z wpisami
        $posts_page_id = get_option('page_for_posts');
        
        // Jeśli istnieje strona z wpisami i ma excerpt
        if ($posts_page_id && has_excerpt($posts_page_id)) {
            echo '<div class="container text-center"><div class="row align-items-start"><div class="col">';
            echo '<div class="betheme-excerpt-container" id="zajawkaposty">';
            echo '<span class="betheme-excerpt-icon"><i class="fas fa-info-circle" aria-hidden="true"></i></span>';
            echo '<p class="betheme-excerpt-text"><b class="themecolor">Krótki wstęp:</b> ' . wp_kses_post(get_the_excerpt($posts_page_id)) . '</p>';
            echo '</div></div></div></div>';
        }
        return;
    }

    // 1. Jeżeli jesteśmy na stronie taksonomii (kategorii, tagu, własnej taksonomii)
    if (is_tax() || is_category() || is_tag()) {
        $term_description_raw = term_description();
        $term_description_clean = wp_strip_all_tags($term_description_raw); // usunięcie wszystkich tagów HTML

        if (!empty($term_description_clean)) {
            echo '<div class="container text-center"><div class="row align-items-start"><div class="col">';
            echo '<div class="betheme-excerpt-container" id="opiskategorieitagi">';
            echo '<span class="betheme-excerpt-icon"><i class="fas fa-info-circle" aria-hidden="true"></i></span>';
            echo '<p class="betheme-excerpt-text"><b class="themecolor">Opis kategorii/tagu:</b> ' . wp_kses_post($term_description_clean) . '</p>';
            echo '</div></div></div></div>';
        }
        return;
    }

    // 2. W pozostałych widokach – excerpt z posta (jeśli istnieje)
    if (isset($post) && has_excerpt($post->ID) && $post->ID != $excluded_page_id) {
        echo '<div class="container text-center"><div class="row align-items-start"><div class="col">';
        echo '<div class="betheme-excerpt-container" id="zajawkaposty">';
        echo '<span class="betheme-excerpt-icon"><i class="fas fa-info-circle" aria-hidden="true"></i></span>';
        echo '<p class="betheme-excerpt-text"><b class="themecolor">Krótki wstęp:</b> ' . wp_kses_post(get_the_excerpt($post->ID)) . '</p>';
        echo '</div></div></div></div>';
    }
}
add_action( 'mfn_hook_content_before', 'child_theme_global_excerpt_or_term_description' );

// Rejestracja nowej taksonomii "Tagi Portfolio" dla portfolio
function voltmont_register_portfolio_tags_taxonomy() {
    register_taxonomy(
        'portfolio-tags',                  // Nazwa taksonomii (systemowa)
        'portfolio',                      // Do jakiego post_type (portfolio)
        array(
            'label' => 'Tagi Portfolio',  // Nazwa wyświetlana w panelu
            'hierarchical' => false,      // Typ tagów (false = tagi, true = kategorie)
            'rewrite' => array(
                'slug' => 'galeria-realizacji-tagi', // Slug w URL
                'with_front' => false,
            ),
            'show_ui' => true,
            'show_in_rest' => true,       // Obsługa Gutenberg/REST API
        )
    );
}
add_action('init', 'voltmont_register_portfolio_tags_taxonomy');

// Naprawa permalinków dla portfolio (post_type=portfolio) i taxonomy=portfolio-types
function voltmont_portfolio_rewrite() {
    // Zarejestruj CPT portfolio z własnym slugiem
    register_post_type('portfolio', array(
        'label' => 'Portfolio',
        'public' => true,
        'has_archive' => true,
        'rewrite' => array(
            'slug' => 'galeria-realizacji', // ZMIANA! Inny slug dla CPT niż dla taksonomii
            'with_front' => false,
        ),
        'supports' => array('title','editor','thumbnail','excerpt','author','custom-fields'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'voltmont_portfolio_rewrite', 11);

// Nadpisanie taxonomy portfolio-types z własnym slugiem
function voltmont_override_portfolio_types_taxonomy() {
    // Rejestracja taxonomy z własnym slugiem (wysoki priorytet, aby nadpisać motyw BeTheme)
    register_taxonomy('portfolio-types', 'portfolio', array(
        'label' => esc_html__('Kategorie portfolio', 'betheme'),
        'hierarchical' => true,
        'query_var' => true,
        'show_ui' => true,
        'show_in_rest' => true,
        'rewrite' => array(
            'slug' => 'galeria-realizacji-kategoria', 
            'with_front' => false,
            'hierarchical' => true, // dla /galeria-realizacji-kategoria/kategoria-podkategoria/
        ),
    ));
}
add_action('init', 'voltmont_override_portfolio_types_taxonomy', 999);

// Dodaj funkcję do wywoływania ręcznego flush rewrite rules
function voltmont_flush_rewrites() {
    voltmont_portfolio_rewrite();
    voltmont_override_portfolio_types_taxonomy();
    flush_rewrite_rules();
}

// Wykonaj flush tylko przy aktywacji motywu lub zapisie permalinków
add_action('after_switch_theme', 'voltmont_flush_rewrites');
add_action('admin_init', function() {
    if (isset($_GET['settings-updated'])) {
        voltmont_flush_rewrites();
    }
});

// Indywidualne ustawienie każdego z pojedyńczych excerptów na limit słów
function excerpt($limit) {
      $excerpt = explode(' ', get_the_excerpt(), $limit);

      if (count($excerpt) >= $limit) {
          array_pop($excerpt);
          $excerpt = implode(" ", $excerpt) . '...';
      } else {
          $excerpt = implode(" ", $excerpt);
      }

      $excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);

      return $excerpt;
}

function content($limit) {
    $content = explode(' ', get_the_content(), $limit);

    if (count($content) >= $limit) {
        array_pop($content);
        $content = implode(" ", $content) . '...';
    } else {
        $content = implode(" ", $content);
    }

    $content = preg_replace('/\[.+\]/','', $content);
    $content = apply_filters('the_content', $content); 
    $content = str_replace(']]>', ']]&gt;', $content);

    return $content;
}

/**
 * Przekierowanie ze strony błędu 404 na stronę główną
 * 
 * @since 1.0.0
 */
function voltmont_redirect_404_to_home() {
    // Sprawdź czy to strona 404
    if (is_404()) {
        // Zapisz URL, który spowodował błąd 404 (opcjonalnie dla logowania)
        $current_url = home_url($_SERVER['REQUEST_URI']);
        
        // Opcjonalnie: zapisz adres IP użytkownika i URL powodujący 404 do logu
        // error_log("Przekierowanie 404: " . $_SERVER['REMOTE_ADDR'] . " próbował uzyskać dostęp do " . $current_url);
        
        // Przekieruj na stronę główną z kodem 301 (trwałe przekierowanie)
        wp_redirect(home_url(), 301);
        exit;
    }
}
add_action('template_redirect', 'voltmont_redirect_404_to_home');

/**
 * Enqueue custom JavaScript for Voltmont
 * 
 * @since 1.0.0
 */
function voltmont_enqueue_custom_js() {
    wp_enqueue_script(
        'voltmont-custom-js',
        get_stylesheet_directory_uri() . '/assets/js/custom-javascripts.js',
        array('jquery'),
        filemtime(get_stylesheet_directory() . '/assets/js/custom-javascripts.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'voltmont_enqueue_custom_js', 999);