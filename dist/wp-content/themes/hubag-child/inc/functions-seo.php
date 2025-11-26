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

/**
 * ========================================================================
 * ADVANCED SEO FUNCTIONS
 * ========================================================================
 */

/**
 * Generate meta keywords (for older SEO tools)
 *
 * @return string Comma-separated keywords
 */
function voltmont_get_meta_keywords() {
    $keywords = array();

    if (is_front_page()) {
        $keywords = array(
            'elektryk Trzebnica',
            'instalacje elektryczne Dolny Śląsk',
            'instalacje elektryczne Wrocław',
            'smart home Trzebnica',
            'instalacje odgromowe',
            'modernizacja instalacji elektrycznych'
        );
    } elseif (is_singular()) {
        // Get tags
        $tags = get_the_tags();
        if ($tags) {
            foreach ($tags as $tag) {
                $keywords[] = $tag->name;
            }
        }

        // Get categories
        $categories = get_the_category();
        if ($categories) {
            foreach ($categories as $category) {
                $keywords[] = $category->name;
            }
        }

        // Add location keywords
        $keywords[] = 'Trzebnica';
        $keywords[] = 'Dolny Śląsk';
    }

    // Remove duplicates and limit to 10
    $keywords = array_unique($keywords);
    $keywords = array_slice($keywords, 0, 10);

    return implode(', ', $keywords);
}

/**
 * Get estimated reading time for content
 *
 * @param string $content Post content
 * @return int Reading time in minutes
 */
function voltmont_get_reading_time($content = '') {
    if (empty($content)) {
        global $post;
        $content = $post->post_content;
    }

    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute

    return $reading_time;
}

/**
 * Output article schema for blog posts
 */
function voltmont_output_article_schema() {
    if (!is_single() || get_post_type() !== 'post') {
        return;
    }

    global $post;

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'description' => voltmont_get_meta_description(),
        'datePublished' => get_the_date('c'),
        'dateModified' => get_the_modified_date('c'),
        'author' => array(
            '@type' => 'Person',
            'name' => get_the_author()
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => 'Voltmont - Instalacje Elektryczne',
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_stylesheet_directory_uri() . '/site-login-logo.png'
            )
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink()
        )
    );

    // Add image
    if (has_post_thumbnail()) {
        $image_id = get_post_thumbnail_id();
        $image_data = wp_get_attachment_image_src($image_id, 'full');
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_data[0],
            'width' => $image_data[1],
            'height' => $image_data[2]
        );
    }

    // Add word count
    $word_count = str_word_count(strip_tags($post->post_content));
    $schema['wordCount'] = $word_count;

    // Add reading time
    $schema['timeRequired'] = 'PT' . voltmont_get_reading_time() . 'M';

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'voltmont_output_article_schema', 11);

/**
 * Add meta keywords tag (optional, for older SEO tools)
 */
function voltmont_output_meta_keywords() {
    $keywords = voltmont_get_meta_keywords();
    
    if (!empty($keywords)) {
        echo '<meta name="keywords" content="' . esc_attr($keywords) . '">' . "\n";
    }
}
// Uncomment to enable meta keywords:
// add_action('wp_head', 'voltmont_output_meta_keywords', 1);

/**
 * Add author meta tag
 */
function voltmont_output_author_meta() {
    if (is_singular()) {
        echo '<meta name="author" content="' . esc_attr(get_the_author()) . '">' . "\n";
    }
}
add_action('wp_head', 'voltmont_output_author_meta', 1);

/**
 * Add copyright meta tag
 */
function voltmont_output_copyright_meta() {
    $year = date('Y');
    echo '<meta name="copyright" content="&copy; ' . $year . ' Voltmont - Instalacje Elektryczne. Wszelkie prawa zastrzeżone.">' . "\n";
}
add_action('wp_head', 'voltmont_output_copyright_meta', 1);

/**
 * Generate organization JSON-LD for sitewide context
 */
function voltmont_output_organization_schema() {
    // Only output on homepage
    if (!is_front_page()) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        '@id' => home_url() . '/#organization',
        'name' => 'Voltmont - Instalacje Elektryczne',
        'url' => home_url(),
        'logo' => get_stylesheet_directory_uri() . '/site-login-logo.png',
        'description' => 'Profesjonalne instalacje elektryczne w Trzebnicy i na Dolnym Śląsku',
        'email' => 'biuro@trzebnica-elektryk.pl',
        'telephone' => '+48691594820',
        'address' => array(
            '@type' => 'PostalAddress',
            'addressLocality' => 'Trzebnica',
            'addressRegion' => 'Dolnośląskie',
            'postalCode' => '55-100',
            'addressCountry' => 'PL'
        ),
        'sameAs' => array(
            'https://www.facebook.com/profile.php?id=100063601389747'
        ),
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'telephone' => '+48691594820',
            'contactType' => 'customer service',
            'email' => 'biuro@trzebnica-elektryk.pl',
            'availableLanguage' => 'Polish'
        )
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'voltmont_output_organization_schema', 12);

/**
 * Add website navigation schema
 */
function voltmont_output_website_navigation_schema() {
    if (!is_front_page()) {
        return;
    }

    // Get main menu items
    $menu_items = wp_get_nav_menu_items('primary');
    
    if (!$menu_items) {
        return;
    }

    $potential_actions = array();

    foreach ($menu_items as $item) {
        if ($item->menu_item_parent == 0) { // Only top-level items
            $potential_actions[] = array(
                '@type' => 'SearchAction',
                'target' => $item->url,
                'name' => $item->title
            );
        }
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        '@id' => home_url() . '/#website',
        'url' => home_url(),
        'name' => 'Voltmont - Instalacje Elektryczne',
        'description' => 'Profesjonalne instalacje elektryczne w Trzebnicy i na Dolnym Śląsku',
        'publisher' => array(
            '@id' => home_url() . '/#organization'
        ),
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url() . '/?s={search_term_string}'
            ),
            'query-input' => 'required name=search_term_string'
        )
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'voltmont_output_website_navigation_schema', 13);

/**
 * Add prev/next rel links for pagination
 */
function voltmont_output_pagination_links() {
    global $paged;

    if (get_previous_posts_link()) {
        $prev_page = $paged > 2 ? $paged - 1 : 1;
        $prev_url = get_pagenum_link($prev_page);
        echo '<link rel="prev" href="' . esc_url($prev_url) . '">' . "\n";
    }

    if (get_next_posts_link()) {
        $next_page = $paged + 1;
        $next_url = get_pagenum_link($next_page);
        echo '<link rel="next" href="' . esc_url($next_url) . '">' . "\n";
    }
}
add_action('wp_head', 'voltmont_output_pagination_links', 5);

/**
 * Modify excerpt length for SEO
 *
 * @param int $length Excerpt length
 * @return int Modified length
 */
function voltmont_custom_excerpt_length($length) {
    // Optimal for meta descriptions: 25-30 words
    return 25;
}
add_filter('excerpt_length', 'voltmont_custom_excerpt_length', 999);

/**
 * Remove [...] from excerpt
 *
 * @param string $more Excerpt more string
 * @return string Modified more string
 */
function voltmont_custom_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'voltmont_custom_excerpt_more');

/**
 * Add site verification meta tags
 */
function voltmont_output_site_verification() {
    // Google Search Console
    // echo '<meta name="google-site-verification" content="YOUR_VERIFICATION_CODE">' . "\n";
    
    // Bing Webmaster Tools
    // echo '<meta name="msvalidate.01" content="YOUR_VERIFICATION_CODE">' . "\n";
    
    // Yandex Webmaster
    // echo '<meta name="yandex-verification" content="YOUR_VERIFICATION_CODE">' . "\n";
}
add_action('wp_head', 'voltmont_output_site_verification', 1);

/**
 * Add mobile-specific meta tags
 */
function voltmont_output_mobile_meta_tags() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">' . "\n";
    echo '<meta name="format-detection" content="telephone=yes">' . "\n";
    echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
    echo '<meta name="apple-mobile-web-app-title" content="Voltmont">' . "\n";
    echo '<meta name="theme-color" content="#4d81e9">' . "\n";
}
add_action('wp_head', 'voltmont_output_mobile_meta_tags', 1);

/**
 * Add RSS feed links
 */
function voltmont_add_rss_feed_links() {
    echo '<link rel="alternate" type="application/rss+xml" title="' . esc_attr(get_bloginfo('name')) . ' RSS Feed" href="' . esc_url(get_feed_link()) . '">' . "\n";
    echo '<link rel="alternate" type="application/atom+xml" title="' . esc_attr(get_bloginfo('name')) . ' Atom Feed" href="' . esc_url(get_feed_link('atom')) . '">' . "\n";
}
add_action('wp_head', 'voltmont_add_rss_feed_links', 2);

/**
 * Generate video schema for pages with embedded videos
 */
function voltmont_output_video_schema() {
    if (!is_singular()) {
        return;
    }

    global $post;
    
    // Check if post content has video embed (YouTube, Vimeo, etc.)
    if (!preg_match('/<iframe[^>]+src=["\']([^"\']*youtube[^"\']*)["\']/', $post->post_content, $matches) &&
        !preg_match('/<iframe[^>]+src=["\']([^"\']*vimeo[^"\']*)["\']/', $post->post_content, $matches)) {
        return;
    }

    $video_url = isset($matches[1]) ? $matches[1] : '';

    if (empty($video_url)) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'VideoObject',
        'name' => get_the_title(),
        'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
        'uploadDate' => get_the_date('c'),
        'thumbnailUrl' => voltmont_get_og_image(),
        'embedUrl' => $video_url
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'voltmont_output_video_schema', 14);

/**
 * Add image alt attributes automatically
 *
 * @param array $attr Image attributes
 * @param WP_Post $attachment Attachment object
 * @return array Modified attributes
 */
function voltmont_auto_image_alt($attr, $attachment) {
    if (empty($attr['alt'])) {
        $post_parent = get_post($attachment->post_parent);
        
        if ($post_parent) {
            $attr['alt'] = $post_parent->post_title . ' - Voltmont Instalacje Elektryczne';
        } else {
            $attr['alt'] = $attachment->post_title;
        }
    }

    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'voltmont_auto_image_alt', 10, 2);

/**
 * Improve internal linking with related posts
 *
 * @return array Related posts
 */
function voltmont_get_related_posts() {
    global $post;

    if (!$post) {
        return array();
    }

    // Get related by category
    $categories = get_the_category($post->ID);
    
    if (empty($categories)) {
        return array();
    }

    $category_ids = array();
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }

    $args = array(
        'category__in' => $category_ids,
        'post__not_in' => array($post->ID),
        'posts_per_page' => 3,
        'orderby' => 'rand'
    );

    $related = new WP_Query($args);

    return $related->posts;
}

/**
 * Add nofollow to external links
 *
 * @param string $content Post content
 * @return string Modified content
 */
function voltmont_add_nofollow_to_external_links($content) {
    if (empty($content)) {
        return $content;
    }

    $home_url = home_url();
    
    // Pattern to match external links
    $pattern = '/<a\s+([^>]*href=["\'](?!' . preg_quote($home_url, '/') . ')([^"\']+)["\'][^>]*)>/i';
    
    $content = preg_replace_callback($pattern, function($matches) {
        $link = $matches[0];
        
        // Add rel="nofollow noopener" if not present
        if (strpos($link, 'rel=') === false) {
            $link = str_replace('<a ', '<a rel="nofollow noopener" ', $link);
        } elseif (strpos($link, 'nofollow') === false) {
            $link = preg_replace('/rel=(["\'])([^"\']*)\1/', 'rel="$2 nofollow noopener"', $link);
        }

        return $link;
    }, $content);

    return $content;
}
// Uncomment to enable nofollow on external links:
// add_filter('the_content', 'voltmont_add_nofollow_to_external_links');

/**
 * Generate XML sitemap meta tag (if not using Yoast/RankMath)
 */
function voltmont_output_sitemap_link() {
    // Only if sitemap exists
    $sitemap_url = home_url('/sitemap.xml');
    
    echo '<link rel="sitemap" type="application/xml" title="Sitemap" href="' . esc_url($sitemap_url) . '">' . "\n";
}
add_action('wp_head', 'voltmont_output_sitemap_link', 2);

/**
 * Output person/author schema for about page
 */
function voltmont_output_person_schema() {
    // Check if this is "o nas" or "about" page
    if (!is_page(array('o-nas', 'about', 'o-firmie'))) {
        return;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Person',
        'name' => 'Voltmont',
        'jobTitle' => 'Electrical Contractor',
        'worksFor' => array(
            '@id' => home_url() . '/#organization'
        ),
        'url' => get_permalink(),
        'sameAs' => array(
            'https://www.facebook.com/profile.php?id=100063601389747'
        )
    );

    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'voltmont_output_person_schema', 15);

/**
 * SEO redirect for changed URLs (301 permanent)
 */
function voltmont_seo_redirects() {
    // Example redirects
    $redirects = array(
        // 'old-url' => 'new-url',
    );

    $current_url = $_SERVER['REQUEST_URI'];

    foreach ($redirects as $old => $new) {
        if (strpos($current_url, $old) !== false) {
            wp_redirect(home_url($new), 301);
            exit;
        }
    }
}
add_action('template_redirect', 'voltmont_seo_redirects', 1);

/**
 * Remove unnecessary WordPress meta tags
 */
function voltmont_remove_unnecessary_meta_tags() {
    // Remove WP version
    remove_action('wp_head', 'wp_generator');
    
    // Remove Windows Live Writer
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove REST API link (if not using)
    // remove_action('wp_head', 'rest_output_link_wp_head');
    
    // Remove emoji detection script
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
}
add_action('init', 'voltmont_remove_unnecessary_meta_tags');

/**
 * Add last modified date to posts
 *
 * @param string $content Post content
 * @return string Modified content
 */
function voltmont_add_last_modified($content) {
    if (!is_single() || get_post_type() !== 'post') {
        return $content;
    }

    global $post;

    $published = get_the_date('j F Y');
    $modified = get_the_modified_date('j F Y');

    if ($published !== $modified) {
        $last_modified = '<div class="last-modified" style="font-size: 0.9em; color: #666; margin: 20px 0;">';
        $last_modified .= '<strong>Ostatnia aktualizacja:</strong> ' . $modified;
        $last_modified .= '</div>';

        $content = $last_modified . $content;
    }

    return $content;
}
// Uncomment to show last modified date:
// add_filter('the_content', 'voltmont_add_last_modified', 1);

/**
 * Generate dynamic title separators based on context
 *
 * @return string Title separator
 */
function voltmont_get_title_separator() {
    if (is_front_page()) {
        return '-';
    } elseif (is_category() || is_tag()) {
        return '»';
    } else {
        return '|';
    }
}

/**
 * Output all SEO meta tags summary comment (for debugging)
 */
function voltmont_output_seo_debug_comment() {
    if (!current_user_can('manage_options') || !isset($_GET['seo_debug'])) {
        return;
    }

    echo "\n<!-- Voltmont SEO Debug -->\n";
    echo "<!-- Title: " . esc_html(voltmont_get_meta_title()) . " -->\n";
    echo "<!-- Description: " . esc_html(voltmont_get_meta_description()) . " -->\n";
    echo "<!-- Keywords: " . esc_html(voltmont_get_meta_keywords()) . " -->\n";
    echo "<!-- Reading Time: " . voltmont_get_reading_time() . " minutes -->\n";
    echo "<!-- Page Type: " . (is_front_page() ? 'Homepage' : (is_single() ? 'Single' : (is_archive() ? 'Archive' : 'Other'))) . " -->\n";
    echo "<!-- End SEO Debug -->\n\n";
}
add_action('wp_head', 'voltmont_output_seo_debug_comment', 999);
