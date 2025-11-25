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
 *
 * @param string $src Asset URL
 * @return string Modified URL with version
 */
function voltmont_add_cache_busting($src) {
    // Get theme version
    $theme = wp_get_theme();
    $version = $theme->get('Version');

    // Add version as query parameter
    if (strpos($src, get_stylesheet_directory_uri()) !== false) {
        $src = add_query_arg('v', $version, $src);
    }

    return $src;
}
add_filter('style_loader_src', 'voltmont_add_cache_busting');
add_filter('script_loader_src', 'voltmont_add_cache_busting');

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
