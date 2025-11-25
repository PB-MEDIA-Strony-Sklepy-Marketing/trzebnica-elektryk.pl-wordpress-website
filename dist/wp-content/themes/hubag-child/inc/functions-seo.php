<?php
/**
 * SEO Functions: Meta Tags, OpenGraph, Twitter Cards
 *
 * Comprehensive SEO implementation for Voltmont website
 * Generates dynamic meta tags, OpenGraph, and Twitter Card markup
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

/**
 * Generate dynamic meta title
 *
 * @return string Optimized meta title (50-60 characters)
 */
function voltmont_get_meta_title() {
    $title = '';
    $separator = '|';
    $site_name = 'Voltmont - Instalacje Elektryczne';

    if (is_front_page()) {
        $title = 'Elektryk Trzebnica - Instalacje Elektryczne Dolny Śląsk | Voltmont';
    } elseif (is_singular()) {
        $title = get_the_title() . ' ' . $separator . ' ' . $site_name;
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $title = $term->name . ' ' . $separator . ' ' . $site_name;
    } elseif (is_archive()) {
        $title = get_the_archive_title() . ' ' . $separator . ' ' . $site_name;
    } elseif (is_search()) {
        $title = 'Wyniki wyszukiwania: ' . get_search_query() . ' ' . $separator . ' ' . $site_name;
    } else {
        $title = wp_get_document_title();
    }

    // Truncate to 60 characters if too long
    if (strlen($title) > 60) {
        $title = substr($title, 0, 57) . '...';
    }

    return apply_filters('voltmont_meta_title', $title);
}

/**
 * Generate dynamic meta description
 *
 * @return string Optimized meta description (150-160 characters)
 */
function voltmont_get_meta_description() {
    $description = '';

    if (is_front_page()) {
        $description = 'Profesjonalne instalacje elektryczne w Trzebnicy i na Dolnym Śląsku. Kompleksowa obsługa inwestycji, modernizacje, instalacje odgromowe, smart home. Tel: +48 691 594 820';
    } elseif (is_singular()) {
        global $post;

        // Try excerpt first
        if (has_excerpt()) {
            $description = wp_strip_all_tags(get_the_excerpt());
        } else {
            // Use content excerpt
            $description = wp_trim_words(wp_strip_all_tags(get_the_content()), 25, '...');
        }

        // Add location keywords
        if (!stripos($description, 'Trzebnica') && !stripos($description, 'Dolny Śląsk')) {
            $description .= ' Trzebnica, Dolny Śląsk.';
        }
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $term_desc = term_description($term->term_id);

        if (!empty($term_desc)) {
            $description = wp_strip_all_tags($term_desc);
        } else {
            $description = 'Wszystkie wpisy w kategorii: ' . $term->name . ' - Voltmont Instalacje Elektryczne, Trzebnica.';
        }
    } elseif (is_archive()) {
        $description = 'Archiwum wpisów - Voltmont Instalacje Elektryczne. Elektryk Trzebnica, Dolny Śląsk.';
    }

    // Truncate to 160 characters
    if (strlen($description) > 160) {
        $description = substr($description, 0, 157) . '...';
    }

    return apply_filters('voltmont_meta_description', $description);
}

/**
 * Get optimal featured image for OpenGraph
 *
 * @param int|null $post_id Post ID
 * @return string Image URL
 */
function voltmont_get_og_image($post_id = null) {
    $default_image = get_stylesheet_directory_uri() . '/site-login-logo.png';

    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (has_post_thumbnail($post_id)) {
        $image = get_the_post_thumbnail_url($post_id, 'full');
        return $image ?: $default_image;
    }

    return $default_image;
}

/**
 * Output SEO meta tags in head
 */
function voltmont_output_seo_meta_tags() {
    // Basic meta tags
    echo '<meta name="description" content="' . esc_attr(voltmont_get_meta_description()) . '">' . "\n";

    // Canonical URL
    if (is_singular()) {
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">' . "\n";
    } elseif (is_front_page()) {
        echo '<link rel="canonical" href="' . esc_url(home_url()) . '">' . "\n";
    }

    // Robots meta
    if (is_404() || is_search()) {
        echo '<meta name="robots" content="noindex, follow">' . "\n";
    } else {
        echo '<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">' . "\n";
    }

    // Geo tags for local SEO
    echo '<meta name="geo.region" content="PL-DS">' . "\n";
    echo '<meta name="geo.placename" content="Trzebnica">' . "\n";
    echo '<meta name="geo.position" content="51.3094;17.0628">' . "\n";
    echo '<meta name="ICBM" content="51.3094, 17.0628">' . "\n";
}
add_action('wp_head', 'voltmont_output_seo_meta_tags', 1);

/**
 * Output OpenGraph meta tags
 */
function voltmont_output_opengraph_tags() {
    $og_title = voltmont_get_meta_title();
    $og_description = voltmont_get_meta_description();
    $og_url = is_singular() ? get_permalink() : home_url();
    $og_image = voltmont_get_og_image();
    $og_type = is_front_page() ? 'website' : 'article';
    $og_site_name = 'Voltmont - Instalacje Elektryczne';

    echo '<!-- OpenGraph Meta Tags -->' . "\n";
    echo '<meta property="og:locale" content="pl_PL">' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($og_description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr($og_site_name) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
    echo '<meta property="og:image:width" content="1200">' . "\n";
    echo '<meta property="og:image:height" content="630">' . "\n";
    echo '<meta property="og:image:alt" content="' . esc_attr($og_title) . '">' . "\n";

    // Article specific tags
    if (is_single()) {
        global $post;
        echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
        echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
        echo '<meta property="article:author" content="' . esc_attr(get_the_author()) . '">' . "\n";

        // Article section (category)
        $categories = get_the_category();
        if (!empty($categories)) {
            echo '<meta property="article:section" content="' . esc_attr($categories[0]->name) . '">' . "\n";
        }

        // Tags
        $tags = get_the_tags();
        if ($tags) {
            foreach ($tags as $tag) {
                echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
            }
        }
    }

    // Business info
    echo '<meta property="business:contact_data:street_address" content="Trzebnica">' . "\n";
    echo '<meta property="business:contact_data:locality" content="Trzebnica">' . "\n";
    echo '<meta property="business:contact_data:region" content="Dolnośląskie">' . "\n";
    echo '<meta property="business:contact_data:postal_code" content="55-100">' . "\n";
    echo '<meta property="business:contact_data:country_name" content="Polska">' . "\n";
    echo '<meta property="business:contact_data:email" content="biuro@trzebnica-elektryk.pl">' . "\n";
    echo '<meta property="business:contact_data:phone_number" content="+48691594820">' . "\n";
    echo '<meta property="business:contact_data:website" content="' . esc_url(home_url()) . '">' . "\n";
}
add_action('wp_head', 'voltmont_output_opengraph_tags', 2);

/**
 * Output Twitter Card meta tags
 */
function voltmont_output_twitter_card_tags() {
    $twitter_title = voltmont_get_meta_title();
    $twitter_description = voltmont_get_meta_description();
    $twitter_image = voltmont_get_og_image();

    echo '<!-- Twitter Card Meta Tags -->' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($twitter_title) . '">' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($twitter_description) . '">' . "\n";
    echo '<meta name="twitter:image" content="' . esc_url($twitter_image) . '">' . "\n";
    echo '<meta name="twitter:image:alt" content="' . esc_attr($twitter_title) . '">' . "\n";
}
add_action('wp_head', 'voltmont_output_twitter_card_tags', 3);

/**
 * Add JSON-LD for specific post types/pages
 * Integrates with Muffin Builder custom fields if available
 */
function voltmont_output_page_specific_schema() {
    if (!is_singular()) {
        return;
    }

    global $post;

    // Check for Muffin Builder meta
    $muffin_data = get_post_meta($post->ID, 'mfn-post-meta', true);

    // Service page detection
    $page_categories = wp_get_post_terms($post->ID, 'page_category', array('fields' => 'slugs'));

    if (!empty($page_categories) && in_array('uslugi-elektryczne', $page_categories)) {
        // This is handled by schema-localbusiness.php Service schema
        return;
    }

    // Portfolio specific schema
    if (get_post_type() === 'portfolio') {
        $portfolio_schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => get_the_title(),
            'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
            'image' => voltmont_get_og_image(),
            'author' => array(
                '@type' => 'Organization',
                'name' => 'Voltmont - Instalacje Elektryczne'
            ),
            'datePublished' => get_the_date('c'),
            'dateModified' => get_the_modified_date('c')
        );

        echo '<script type="application/ld+json">' . wp_json_encode($portfolio_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
add_action('wp_head', 'voltmont_output_page_specific_schema', 7);

/**
 * Modify document title
 *
 * @param array $title Title parts
 * @return array Modified title parts
 */
function voltmont_modify_document_title($title) {
    if (is_front_page()) {
        $title['title'] = 'Elektryk Trzebnica - Instalacje Elektryczne Dolny Śląsk';
        $title['tagline'] = 'Voltmont';
    }

    return $title;
}
add_filter('document_title_parts', 'voltmont_modify_document_title');

/**
 * Add hreflang tags if multilingual (future-proof)
 */
function voltmont_output_hreflang_tags() {
    // Currently only Polish, but structure ready for expansion
    if (is_singular() || is_front_page()) {
        $current_url = is_front_page() ? home_url() : get_permalink();

        echo '<link rel="alternate" hreflang="pl" href="' . esc_url($current_url) . '">' . "\n";
        echo '<link rel="alternate" hreflang="x-default" href="' . esc_url($current_url) . '">' . "\n";
    }
}
add_action('wp_head', 'voltmont_output_hreflang_tags', 4);
