<?php
/**
 * Performance Optimization Utilities
 *
 * Functions to improve page load speed, optimize assets, and reduce overhead
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

/**
 * Defer non-critical CSS
 */
function voltmont_defer_non_critical_css() {
    ?>
    <script>
    // Load non-critical CSS asynchronously
    function loadDeferredStyles() {
        var addStylesNode = document.getElementById("deferred-styles");
        if (addStylesNode) {
            var replacement = document.createElement("div");
            replacement.innerHTML = addStylesNode.textContent;
            document.body.appendChild(replacement);
            addStylesNode.parentElement.removeChild(addStylesNode);
        }
    }
    var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
        window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
    if (raf) raf(function() { window.setTimeout(loadDeferredStyles, 0); });
    else window.addEventListener('load', loadDeferredStyles);
    </script>
    <?php
}
add_action('wp_footer', 'voltmont_defer_non_critical_css');

/**
 * Preconnect to external domains for faster DNS resolution
 */
function voltmont_add_preconnect() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="dns-prefetch" href="//maps.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
}
add_action('wp_head', 'voltmont_add_preconnect', 1);

/**
 * Remove query strings from static resources
 *
 * @param string $src Resource URL
 * @return string Modified URL
 */
function voltmont_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('script_loader_src', 'voltmont_remove_query_strings', 15);
add_filter('style_loader_src', 'voltmont_remove_query_strings', 15);

/**
 * Disable embeds (YouTube, Twitter, etc.) if not needed
 * Comment out if you use embeds
 */
function voltmont_disable_embeds() {
    // Remove oEmbed scripts
    wp_deregister_script('wp-embed');

    // Remove oEmbed discovery links
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed REST API
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Remove oEmbed filters
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
}
// Uncomment to enable:
// add_action('init', 'voltmont_disable_embeds', 9999);

/**
 * Limit post revisions
 */
if (!defined('WP_POST_REVISIONS')) {
    define('WP_POST_REVISIONS', 5);
}

/**
 * Set autosave interval to 2 minutes
 */
if (!defined('AUTOSAVE_INTERVAL')) {
    define('AUTOSAVE_INTERVAL', 120);
}

/**
 * Optimize WP_Query for better performance
 *
 * @param WP_Query $query Query object
 */
function voltmont_optimize_queries($query) {
    // Don't modify admin queries or main query
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    // Disable update checks on post queries
    if ($query->is_singular()) {
        $query->set('no_found_rows', true);
        $query->set('update_post_term_cache', false);
        $query->set('update_post_meta_cache', false);
    }
}
add_action('pre_get_posts', 'voltmont_optimize_queries');

/**
 * Disable WordPress emoji scripts
 */
function voltmont_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'voltmont_disable_emojis');

/**
 * Remove unnecessary meta tags and links
 */
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);

/**
 * Add expires headers for static assets
 */
function voltmont_add_expires_headers() {
    if (!is_admin()) {
        header('Cache-Control: public, max-age=31536000');
    }
}
// Uncomment if server doesn't handle this:
// add_action('send_headers', 'voltmont_add_expires_headers');

/**
 * Optimize database on theme switch
 */
function voltmont_optimize_database() {
    global $wpdb;

    // Optimize all tables
    $tables = $wpdb->get_results('SHOW TABLES', ARRAY_N);
    foreach ($tables as $table) {
        $wpdb->query("OPTIMIZE TABLE {$table[0]}");
    }

    // Clean transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
}
// Run on theme activation (comment out after first run)
// add_action('after_switch_theme', 'voltmont_optimize_database');

/**
 * Lazy load images (native WordPress 5.5+)
 * Add fallback for older browsers
 */
function voltmont_add_lazy_load_attributes($attr, $attachment) {
    $attr['loading'] = 'lazy';
    $attr['decoding'] = 'async';
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'voltmont_add_lazy_load_attributes', 10, 2);

/**
 * Minify HTML output
 *
 * @param string $buffer HTML content
 * @return string Minified HTML
 */
function voltmont_minify_html($buffer) {
    // Don't minify if user is logged in
    if (is_user_logged_in()) {
        return $buffer;
    }

    // Remove HTML comments (except IE conditionals)
    $buffer = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer);

    // Remove whitespace between tags
    $buffer = preg_replace('/>\s+</', '><', $buffer);

    // Remove extra whitespace
    $buffer = preg_replace('/\s+/', ' ', $buffer);

    return $buffer;
}

/**
 * Start output buffering for HTML minification
 */
function voltmont_start_html_minification() {
    // Only minify on front-end
    if (!is_admin() && !is_customize_preview()) {
        ob_start('voltmont_minify_html');
    }
}
// Uncomment to enable HTML minification:
// add_action('template_redirect', 'voltmont_start_html_minification', 0);

/**
 * Defer JavaScript loading
 *
 * @param string $tag Script tag
 * @param string $handle Script handle
 * @return string Modified script tag
 */
function voltmont_defer_scripts($tag, $handle) {
    // Don't defer jQuery or admin scripts
    $exclude_handles = array('jquery', 'jquery-core', 'jquery-migrate');

    if (in_array($handle, $exclude_handles)) {
        return $tag;
    }

    // Add defer attribute
    return str_replace(' src', ' defer src', $tag);
}
// Uncomment to enable script deferring:
// add_filter('script_loader_tag', 'voltmont_defer_scripts', 10, 2);

/**
 * Preload critical assets
 */
function voltmont_preload_critical_assets() {
    // Preload main CSS
    echo '<link rel="preload" href="' . get_stylesheet_uri() . '" as="style">' . "\n";

    // Preload logo
    $logo_url = get_stylesheet_directory_uri() . '/site-login-logo.png';
    echo '<link rel="preload" href="' . esc_url($logo_url) . '" as="image">' . "\n";

    // Preload fonts (if using custom fonts)
    // echo '<link rel="preload" href="' . get_stylesheet_directory_uri() . '/assets/fonts/font.woff2" as="font" type="font/woff2" crossorigin>' . "\n";
}
add_action('wp_head', 'voltmont_preload_critical_assets', 1);

/**
 * Database query caching utility
 *
 * @param string $key Cache key
 * @param callable $callback Function to generate data
 * @param int $expiration Cache expiration in seconds
 * @return mixed Cached or fresh data
 */
function voltmont_cache_query($key, $callback, $expiration = 3600) {
    $cache_key = 'voltmont_' . md5($key);
    $cached = get_transient($cache_key);

    if ($cached !== false) {
        return $cached;
    }

    $data = $callback();
    set_transient($cache_key, $data, $expiration);

    return $data;
}

/**
 * Clear all Voltmont caches
 */
function voltmont_clear_all_caches() {
    global $wpdb;

    // Delete all Voltmont transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_voltmont_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_voltmont_%'");

    // Flush object cache
    wp_cache_flush();

    // Clear BeTheme caches if available
    if (function_exists('mfn_clear_cache')) {
        mfn_clear_cache();
    }
}

/**
 * Add cache-busting for updated assets
 */
function voltmont_cache_bust_assets($src) {
    if (strpos($src, get_stylesheet_directory_uri()) !== false) {
        $file_path = str_replace(get_stylesheet_directory_uri(), get_stylesheet_directory(), $src);
        if (file_exists($file_path)) {
            $mtime = filemtime($file_path);
            $src = add_query_arg('v', $mtime, $src);
        }
    }
    return $src;
}
// Uncomment to enable cache busting:
// add_filter('style_loader_src', 'voltmont_cache_bust_assets');
// add_filter('script_loader_src', 'voltmont_cache_bust_assets');

/**
 * ========================================================================
 * TRANSIENTS FOR EXPENSIVE QUERIES
 * ========================================================================
 */

/**
 * Get portfolio items with caching
 *
 * @param array $args WP_Query arguments
 * @param int $cache_time Cache duration in seconds (default: 12 hours)
 * @return WP_Query Portfolio query results
 */
function voltmont_get_cached_portfolio($args = array(), $cache_time = 43200) {
    $cache_key = 'voltmont_portfolio_' . md5(serialize($args));
    $cached_query = get_transient($cache_key);

    if ($cached_query !== false) {
        return $cached_query;
    }

    $defaults = array(
        'post_type' => 'portfolio',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $args = wp_parse_args($args, $defaults);
    $query = new WP_Query($args);

    // Cache for 12 hours
    set_transient($cache_key, $query, $cache_time);

    return $query;
}

/**
 * Get menu items with caching
 *
 * @param string $location Menu location
 * @param int $cache_time Cache duration in seconds (default: 24 hours)
 * @return array|false Menu items or false
 */
function voltmont_get_cached_menu($location, $cache_time = 86400) {
    $cache_key = 'voltmont_menu_' . $location;
    $cached_menu = get_transient($cache_key);

    if ($cached_menu !== false) {
        return $cached_menu;
    }

    $locations = get_nav_menu_locations();
    
    if (!isset($locations[$location])) {
        return false;
    }

    $menu = wp_get_nav_menu_items($locations[$location]);

    if ($menu) {
        set_transient($cache_key, $menu, $cache_time);
    }

    return $menu;
}

/**
 * Get terms with caching
 *
 * @param string $taxonomy Taxonomy name
 * @param array $args get_terms arguments
 * @param int $cache_time Cache duration in seconds (default: 24 hours)
 * @return array|WP_Error Terms array or error
 */
function voltmont_get_cached_terms($taxonomy, $args = array(), $cache_time = 86400) {
    $cache_key = 'voltmont_terms_' . $taxonomy . '_' . md5(serialize($args));
    $cached_terms = get_transient($cache_key);

    if ($cached_terms !== false) {
        return $cached_terms;
    }

    $terms = get_terms($taxonomy, $args);

    if (!is_wp_error($terms)) {
        set_transient($cache_key, $terms, $cache_time);
    }

    return $terms;
}

/**
 * Clear portfolio cache on post save
 *
 * @param int $post_id Post ID
 */
function voltmont_clear_portfolio_cache($post_id) {
    if (get_post_type($post_id) !== 'portfolio') {
        return;
    }

    global $wpdb;
    
    // Delete all portfolio transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_voltmont_portfolio_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_voltmont_portfolio_%'");
}
add_action('save_post_portfolio', 'voltmont_clear_portfolio_cache');
add_action('delete_post', 'voltmont_clear_portfolio_cache');

/**
 * Clear menu cache when menus are updated
 */
function voltmont_clear_menu_cache() {
    global $wpdb;
    
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_voltmont_menu_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_voltmont_menu_%'");
}
add_action('wp_update_nav_menu', 'voltmont_clear_menu_cache');

/**
 * Clear term cache when terms are updated
 *
 * @param int $term_id Term ID
 * @param int $tt_id Term taxonomy ID
 * @param string $taxonomy Taxonomy slug
 */
function voltmont_clear_term_cache($term_id, $tt_id, $taxonomy) {
    global $wpdb;
    
    $pattern = '_transient_voltmont_terms_' . $taxonomy . '_%';
    $wpdb->query($wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
        $pattern
    ));
    
    $timeout_pattern = '_transient_timeout_voltmont_terms_' . $taxonomy . '_%';
    $wpdb->query($wpdb->prepare(
        "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
        $timeout_pattern
    ));
}
add_action('edited_term', 'voltmont_clear_term_cache', 10, 3);
add_action('create_term', 'voltmont_clear_term_cache', 10, 3);
add_action('delete_term', 'voltmont_clear_term_cache', 10, 3);

/**
 * ========================================================================
 * WEBP IMAGE CONVERSION
 * ========================================================================
 */

/**
 * Convert uploaded image to WebP format
 *
 * @param array $metadata Image metadata
 * @param int $attachment_id Attachment ID
 * @return array Modified metadata
 */
function voltmont_convert_to_webp($metadata, $attachment_id) {
    if (!function_exists('imagewebp')) {
        return $metadata;
    }

    $file = get_attached_file($attachment_id);
    
    if (!$file || !file_exists($file)) {
        return $metadata;
    }

    $mime_type = get_post_mime_type($attachment_id);
    $allowed_types = array('image/jpeg', 'image/jpg', 'image/png');

    if (!in_array($mime_type, $allowed_types)) {
        return $metadata;
    }

    // Convert main image
    voltmont_create_webp_image($file, $mime_type);

    // Convert all sizes
    if (isset($metadata['sizes']) && is_array($metadata['sizes'])) {
        $upload_dir = wp_upload_dir();
        $base_dir = dirname($file);

        foreach ($metadata['sizes'] as $size => $size_data) {
            $size_file = $base_dir . '/' . $size_data['file'];
            
            if (file_exists($size_file)) {
                voltmont_create_webp_image($size_file, $mime_type);
            }
        }
    }

    return $metadata;
}
add_filter('wp_generate_attachment_metadata', 'voltmont_convert_to_webp', 10, 2);

/**
 * Create WebP version of image
 *
 * @param string $file Image file path
 * @param string $mime_type Original MIME type
 * @return bool Success status
 */
function voltmont_create_webp_image($file, $mime_type) {
    $webp_file = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);

    // Skip if WebP already exists
    if (file_exists($webp_file)) {
        return true;
    }

    // Create image resource
    switch ($mime_type) {
        case 'image/jpeg':
        case 'image/jpg':
            $image = imagecreatefromjpeg($file);
            break;
        case 'image/png':
            $image = imagecreatefrompng($file);
            // Preserve transparency
            imagealphablending($image, false);
            imagesavealpha($image, true);
            break;
        default:
            return false;
    }

    if (!$image) {
        return false;
    }

    // Convert to WebP with 85% quality
    $result = imagewebp($image, $webp_file, 85);
    imagedestroy($image);

    return $result;
}

/**
 * Add WebP as allowed upload type
 *
 * @param array $mimes Allowed MIME types
 * @return array Modified MIME types
 */
function voltmont_add_webp_mime($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'voltmont_add_webp_mime');

/**
 * Serve WebP images if available and supported
 *
 * @param string $image Image HTML
 * @param int $attachment_id Attachment ID
 * @return string Modified image HTML
 */
function voltmont_maybe_use_webp($image, $attachment_id) {
    // Check if browser supports WebP
    if (!isset($_SERVER['HTTP_ACCEPT']) || strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') === false) {
        return $image;
    }

    // Get original image path
    $file = get_attached_file($attachment_id);
    $webp_file = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file);

    if (!file_exists($webp_file)) {
        return $image;
    }

    // Replace image src with WebP version
    $webp_url = preg_replace('/\.(jpe?g|png)$/i', '.webp', wp_get_attachment_url($attachment_id));
    $image = str_replace(wp_get_attachment_url($attachment_id), $webp_url, $image);

    return $image;
}
add_filter('wp_get_attachment_image', 'voltmont_maybe_use_webp', 10, 2);

/**
 * ========================================================================
 * LAZY LOADING FOR IMAGES
 * ========================================================================
 */

/**
 * Add lazy loading to images
 *
 * @param string $content Post content
 * @return string Modified content
 */
function voltmont_add_lazy_loading($content) {
    if (is_admin() || is_feed() || wp_doing_ajax()) {
        return $content;
    }

    // Add loading="lazy" to all images
    $content = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
        $img_tag = $matches[0];
        
        // Skip if already has loading attribute
        if (strpos($img_tag, 'loading=') !== false) {
            return $img_tag;
        }

        // Add loading="lazy"
        $img_tag = str_replace('<img', '<img loading="lazy"', $img_tag);

        // Add decoding="async" for better performance
        if (strpos($img_tag, 'decoding=') === false) {
            $img_tag = str_replace('<img', '<img decoding="async"', $img_tag);
        }

        return $img_tag;
    }, $content);

    return $content;
}
add_filter('the_content', 'voltmont_add_lazy_loading', 20);
add_filter('post_thumbnail_html', 'voltmont_add_lazy_loading', 20);
add_filter('get_avatar', 'voltmont_add_lazy_loading', 20);

/**
 * Add lazy loading to background images via JavaScript
 */
function voltmont_lazy_load_backgrounds() {
    if (is_admin()) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lazy load background images
        const lazyBackgrounds = document.querySelectorAll('[data-bg]');
        
        if ('IntersectionObserver' in window) {
            const bgObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.style.backgroundImage = 'url(' + entry.target.dataset.bg + ')';
                        entry.target.removeAttribute('data-bg');
                        bgObserver.unobserve(entry.target);
                    }
                });
            });
            
            lazyBackgrounds.forEach(function(bg) {
                bgObserver.observe(bg);
            });
        } else {
            // Fallback for browsers without Intersection Observer
            lazyBackgrounds.forEach(function(bg) {
                bg.style.backgroundImage = 'url(' + bg.dataset.bg + ')';
            });
        }
    });
    </script>
    <?php
}
add_action('wp_footer', 'voltmont_lazy_load_backgrounds', 100);

/**
 * Enable native lazy loading for WordPress
 */
add_filter('wp_lazy_loading_enabled', '__return_true');

/**
 * ========================================================================
 * CSS/JS MINIFICATION
 * ========================================================================
 */

/**
 * Minify inline CSS
 *
 * @param string $css CSS code
 * @return string Minified CSS
 */
function voltmont_minify_css($css) {
    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    
    // Remove whitespace
    $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
    
    // Remove spaces around operators
    $css = preg_replace('/\s*([{}|:;,])\s+/', '$1', $css);
    $css = preg_replace('/\s+/', ' ', $css);
    $css = preg_replace('/;}/', '}', $css);
    
    return trim($css);
}

/**
 * Minify inline JavaScript
 *
 * @param string $js JavaScript code
 * @return string Minified JavaScript
 */
function voltmont_minify_js($js) {
    // Remove comments (simple regex, not perfect but safe)
    $js = preg_replace('/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\)\/\/.*))/', '', $js);
    
    // Remove whitespace
    $js = preg_replace('/\s+/', ' ', $js);
    
    // Remove spaces around operators (be careful with this)
    $js = preg_replace('/\s*([{}();,=<>:?&|!+\-*\/])\s*/', '$1', $js);
    
    return trim($js);
}

/**
 * Minify inline styles in content
 *
 * @param string $content Post content
 * @return string Content with minified inline styles
 */
function voltmont_minify_inline_styles($content) {
    if (is_admin()) {
        return $content;
    }

    $content = preg_replace_callback('/<style[^>]*>(.*?)<\/style>/is', function($matches) {
        $minified = voltmont_minify_css($matches[1]);
        return '<style>' . $minified . '</style>';
    }, $content);

    return $content;
}
add_filter('the_content', 'voltmont_minify_inline_styles', 30);

/**
 * Minify inline scripts in content
 *
 * @param string $content Post content
 * @return string Content with minified inline scripts
 */
function voltmont_minify_inline_scripts($content) {
    if (is_admin()) {
        return $content;
    }

    $content = preg_replace_callback('/<script[^>]*>(.*?)<\/script>/is', function($matches) {
        // Skip if it's a JSON-LD script (schema.org)
        if (strpos($matches[0], 'application/ld+json') !== false) {
            return $matches[0];
        }
        
        $minified = voltmont_minify_js($matches[1]);
        return str_replace($matches[1], $minified, $matches[0]);
    }, $content);

    return $content;
}
add_filter('the_content', 'voltmont_minify_inline_scripts', 30);

/**
 * Minify wp_head inline styles
 */
function voltmont_minify_wp_head_styles() {
    ob_start(function($buffer) {
        // Minify inline <style> tags in head
        $buffer = preg_replace_callback('/<style[^>]*>(.*?)<\/style>/is', function($matches) {
            // Skip if it's an external stylesheet
            if (strpos($matches[0], '@import') !== false) {
                return $matches[0];
            }
            
            $minified = voltmont_minify_css($matches[1]);
            return '<style>' . $minified . '</style>';
        }, $buffer);

        return $buffer;
    });
}
add_action('wp_head', 'voltmont_minify_wp_head_styles', 1);

/**
 * Flush minification buffer
 */
function voltmont_flush_minification_buffer() {
    if (ob_get_level() > 0) {
        ob_end_flush();
    }
}
add_action('wp_head', 'voltmont_flush_minification_buffer', 999);

/**
 * Add admin notice for cache clearing
 */
function voltmont_cache_clear_admin_notice() {
    if (isset($_GET['voltmont_cache_cleared']) && $_GET['voltmont_cache_cleared'] === '1') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>Voltmont Cache:</strong> All caches have been cleared successfully.</p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'voltmont_cache_clear_admin_notice');

/**
 * Add cache clear button to admin bar
 *
 * @param WP_Admin_Bar $wp_admin_bar Admin bar instance
 */
function voltmont_add_cache_clear_button($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        return;
    }

    $wp_admin_bar->add_node(array(
        'id' => 'voltmont-clear-cache',
        'title' => '<span class="ab-icon dashicons-before dashicons-update"></span> Clear Voltmont Cache',
        'href' => wp_nonce_url(admin_url('admin-post.php?action=voltmont_clear_cache'), 'voltmont_clear_cache'),
        'meta' => array(
            'title' => 'Clear all Voltmont transients and caches',
        ),
    ));
}
add_action('admin_bar_menu', 'voltmont_add_cache_clear_button', 100);

/**
 * Handle cache clear action
 */
function voltmont_handle_cache_clear() {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }

    check_admin_referer('voltmont_clear_cache');

    voltmont_clear_all_caches();

    wp_redirect(add_query_arg('voltmont_cache_cleared', '1', wp_get_referer()));
    exit;
}
add_action('admin_post_voltmont_clear_cache', 'voltmont_handle_cache_clear');

/**
 * Disable Heartbeat API where not needed
 *
 * @param array $settings Heartbeat settings
 * @return array Modified settings
 */
function voltmont_optimize_heartbeat($settings) {
    // Disable on front-end
    if (!is_admin()) {
        $settings['interval'] = 300; // 5 minutes
    }

    return $settings;
}
add_filter('heartbeat_settings', 'voltmont_optimize_heartbeat');

/**
 * Limit Heartbeat API to necessary pages only
 */
function voltmont_limit_heartbeat() {
    global $pagenow;

    // Only run on post edit pages
    if ($pagenow !== 'post.php' && $pagenow !== 'post-new.php') {
        wp_deregister_script('heartbeat');
    }
}
add_action('admin_enqueue_scripts', 'voltmont_limit_heartbeat', 99);
