<?php
/**
 * Schema.org LocalBusiness + Service JSON-LD Implementation
 *
 * Generates structured data for Voltmont - Instalacje Elektryczne
 * Implements LocalBusiness with Service offerings for better local SEO
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

/**
 * Generate LocalBusiness schema with services
 *
 * @return array Schema.org structured data
 */
function voltmont_get_localbusiness_schema() {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        '@id' => home_url('/#organization'),
        'name' => 'Voltmont - Instalacje Elektryczne',
        'alternateName' => 'Voltmont',
        'description' => 'Profesjonalne instalacje elektryczne w Trzebnicy i na Dolnym Śląsku. Kompleksowa obsługa inwestycji, modernizacje, instalacje odgromowe, smart home.',
        'url' => home_url(),
        'logo' => array(
            '@type' => 'ImageObject',
            'url' => get_stylesheet_directory_uri() . '/site-login-logo.png',
            'width' => 320,
            'height' => 160
        ),
        'image' => get_stylesheet_directory_uri() . '/site-login-logo.png',
        'telephone' => '+48 691 594 820',
        'email' => 'biuro@trzebnica-elektryk.pl',
        'priceRange' => '$$',
        'address' => array(
            '@type' => 'PostalAddress',
            'addressLocality' => 'Trzebnica',
            'addressRegion' => 'Dolnośląskie',
            'addressCountry' => 'PL'
        ),
        'geo' => array(
            '@type' => 'GeoCoordinates',
            'latitude' => '51.3094',
            'longitude' => '17.0628'
        ),
        'areaServed' => array(
            array(
                '@type' => 'City',
                'name' => 'Trzebnica'
            ),
            array(
                '@type' => 'State',
                'name' => 'Dolnośląskie'
            ),
            array(
                '@type' => 'City',
                'name' => 'Wrocław'
            )
        ),
        'openingHoursSpecification' => array(
            array(
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'),
                'opens' => '08:00',
                'closes' => '17:00'
            )
        ),
        'sameAs' => array(
            'https://www.facebook.com/profile.php?id=100063601389747',
            'https://maps.app.goo.gl/uWX3H3oYdpkx4wAY6'
        ),
        'hasOfferCatalog' => array(
            '@type' => 'OfferCatalog',
            'name' => 'Usługi elektryczne',
            'itemListElement' => array(
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Kompleksowa obsługa inwestycji od strony elektrycznej',
                        'description' => 'Pełna realizacja projektów elektrycznych od projektu po wykonanie w domach, budynkach wielorodzinnych i obiektach komercyjnych.'
                    )
                ),
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Nadzór elektryczny obiektów produkcyjno-usługowych',
                        'description' => 'Bieżący nadzór techniczny nad infrastrukturą elektryczną w firmach. Zgodność z przepisami i bezpieczeństwo.'
                    )
                ),
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Instalacje podstawowe i specjalistyczne',
                        'description' => 'Instalacje elektryczne, RTV, internetowe, domofonowe, alarmowe oraz inteligentne systemy SMART.'
                    )
                ),
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Instalacje odgromowe',
                        'description' => 'Montaż i serwis instalacji odgromowych chroniących obiekty przed skutkami wyładowań atmosferycznych.'
                    )
                ),
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Kompleksowa wymiana instalacji WLZ',
                        'description' => 'Wymiana wewnętrznych linii zasilających oraz modernizacja układów w budynkach wielorodzinnych.'
                    )
                ),
                array(
                    '@type' => 'Offer',
                    'itemOffered' => array(
                        '@type' => 'Service',
                        'name' => 'Modernizacja instalacji w blokach mieszkalnych',
                        'description' => 'Wymiana głównych pionów elektrycznych, rozdzielnic, tablic licznikowych oraz przewodów zasilających.'
                    )
                )
            )
        )
    );

    return $schema;
}

/**
 * Generate Service schema for specific service page
 *
 * @param string $service_name Service name
 * @param string $service_description Service description
 * @param string $service_url Service page URL
 * @return array Service schema
 */
function voltmont_get_service_schema($service_name, $service_description, $service_url) {
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $service_name,
        'description' => $service_description,
        'url' => $service_url,
        'provider' => array(
            '@type' => 'LocalBusiness',
            '@id' => home_url('/#organization'),
            'name' => 'Voltmont - Instalacje Elektryczne',
            'telephone' => '+48 691 594 820',
            'email' => 'biuro@trzebnica-elektryk.pl'
        ),
        'areaServed' => array(
            '@type' => 'State',
            'name' => 'Dolnośląskie'
        ),
        'serviceType' => 'Instalacje elektryczne',
        'availableChannel' => array(
            '@type' => 'ServiceChannel',
            'availableLanguage' => 'pl',
            'servicePhone' => array(
                '@type' => 'ContactPoint',
                'telephone' => '+48 691 594 820',
                'contactType' => 'customer service'
            ),
            'serviceUrl' => $service_url
        )
    );

    return $schema;
}

/**
 * Output LocalBusiness schema in head
 */
function voltmont_output_localbusiness_schema() {
    // Only output on front page and main pages
    if (!is_front_page() && !is_page()) {
        return;
    }

    $schema = voltmont_get_localbusiness_schema();

    // If on service page, add Service schema
    if (is_page()) {
        global $post;

        // Check if page has service-related category
        $page_categories = wp_get_post_terms($post->ID, 'page_category');

        if (!empty($page_categories)) {
            $service_schema = voltmont_get_service_schema(
                get_the_title(),
                get_the_excerpt() ?: wp_trim_words(get_the_content(), 30),
                get_permalink()
            );

            // Combine schemas using @graph
            $combined_schema = array(
                '@context' => 'https://schema.org',
                '@graph' => array($schema, $service_schema)
            );

            echo '<script type="application/ld+json">' . wp_json_encode($combined_schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
            return;
        }
    }

    // Output just LocalBusiness schema
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
}
add_action('wp_head', 'voltmont_output_localbusiness_schema', 5);

/**
 * Generate BreadcrumbList schema
 *
 * @return array|null Breadcrumb schema or null if not applicable
 */
function voltmont_get_breadcrumb_schema() {
    if (is_front_page()) {
        return null;
    }

    $breadcrumbs = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array()
    );

    // Home
    $breadcrumbs['itemListElement'][] = array(
        '@type' => 'ListItem',
        'position' => 1,
        'name' => 'Strona główna',
        'item' => home_url()
    );

    $position = 2;

    // Categories/taxonomies
    if (is_category() || is_tax()) {
        $term = get_queried_object();
        $breadcrumbs['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => $term->name,
            'item' => get_term_link($term)
        );
    }

    // Single page/post
    if (is_singular()) {
        global $post;

        // Add parent pages if exist
        if ($post->post_parent) {
            $parent_ids = get_post_ancestors($post->ID);
            $parent_ids = array_reverse($parent_ids);

            foreach ($parent_ids as $parent_id) {
                $breadcrumbs['itemListElement'][] = array(
                    '@type' => 'ListItem',
                    'position' => $position++,
                    'name' => get_the_title($parent_id),
                    'item' => get_permalink($parent_id)
                );
            }
        }

        // Current page
        $breadcrumbs['itemListElement'][] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink()
        );
    }

    return $breadcrumbs;
}

/**
 * Output breadcrumb schema
 */
function voltmont_output_breadcrumb_schema() {
    $schema = voltmont_get_breadcrumb_schema();

    if ($schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
    }
}
add_action('wp_head', 'voltmont_output_breadcrumb_schema', 6);
