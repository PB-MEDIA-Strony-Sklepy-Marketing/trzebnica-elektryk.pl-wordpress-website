<?php
/**
 * Portfolio Schema Implementation
 *
 * Generates structured data for portfolio items (realizacje)
 * Implements CreativeWork schema with project-specific details
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

/**
 * Generate CreativeWork schema for portfolio item
 *
 * @param int|WP_Post $post Post object or ID
 * @return array|null Portfolio schema or null
 */
function voltmont_get_portfolio_schema($post = null) {
    if (!$post) {
        $post = get_post();
    }

    if (!$post || get_post_type($post) !== 'portfolio') {
        return null;
    }

    // Base schema structure
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => get_the_title($post),
        'description' => get_the_excerpt($post) ?: wp_trim_words(strip_tags(get_the_content(null, false, $post)), 30),
        'url' => get_permalink($post),
        'datePublished' => get_the_date('c', $post),
        'dateModified' => get_the_modified_date('c', $post),
        'author' => array(
            '@type' => 'Organization',
            'name' => 'Voltmont - Instalacje Elektryczne',
            'url' => home_url(),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_stylesheet_directory_uri() . '/site-login-logo.png'
            )
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => 'Voltmont - Instalacje Elektryczne',
            'url' => home_url(),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_stylesheet_directory_uri() . '/site-login-logo.png'
            )
        )
    );

    // Add featured image
    if (has_post_thumbnail($post)) {
        $image_url = get_the_post_thumbnail_url($post, 'full');
        $image_id = get_post_thumbnail_id($post);
        $image_meta = wp_get_attachment_metadata($image_id);

        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $image_url,
            'width' => isset($image_meta['width']) ? $image_meta['width'] : 1200,
            'height' => isset($image_meta['height']) ? $image_meta['height'] : 630
        );
    }

    // Add custom meta data if exists
    $client = get_post_meta($post->ID, '_voltmont_client', true);
    $project_date = get_post_meta($post->ID, '_voltmont_project_date', true);
    $location = get_post_meta($post->ID, '_voltmont_location', true);

    if ($client) {
        $schema['client'] = array(
            '@type' => 'Organization',
            'name' => $client
        );
    }

    if ($project_date) {
        $schema['temporal'] = $project_date;
    }

    if ($location) {
        $schema['locationCreated'] = array(
            '@type' => 'Place',
            'name' => $location
        );
    }

    // Add project category/type from taxonomy
    $portfolio_types = get_the_terms($post->ID, 'portfolio-types');
    if ($portfolio_types && !is_wp_error($portfolio_types)) {
        $schema['genre'] = array_map(function($term) {
            return $term->name;
        }, $portfolio_types);
    }

    // Add keywords from tags
    $portfolio_tags = get_the_terms($post->ID, 'portfolio-tags');
    if ($portfolio_tags && !is_wp_error($portfolio_tags)) {
        $schema['keywords'] = implode(', ', array_map(function($term) {
            return $term->name;
        }, $portfolio_tags));
    }

    return apply_filters('voltmont_portfolio_schema', $schema, $post);
}

/**
 * Output portfolio schema in head for single portfolio items
 */
function voltmont_output_portfolio_schema() {
    if (!is_singular('portfolio')) {
        return;
    }

    $schema = voltmont_get_portfolio_schema();

    if ($schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}
add_action('wp_head', 'voltmont_output_portfolio_schema', 9);

/**
 * Generate ItemList schema for portfolio archive
 *
 * @return array|null ItemList schema or null
 */
function voltmont_get_portfolio_archive_schema() {
    if (!is_post_type_archive('portfolio') && !is_tax(array('portfolio-types', 'portfolio-tags'))) {
        return null;
    }

    global $wp_query;

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => is_tax() ? single_term_title('', false) : 'Portfolio - Realizacje',
        'description' => 'Nasze zrealizowane projekty instalacji elektrycznych',
        'url' => get_pagenum_link(),
        'numberOfItems' => $wp_query->found_posts,
        'itemListElement' => array()
    );

    // Add portfolio items to list
    if ($wp_query->have_posts()) {
        $position = 1;

        while ($wp_query->have_posts()) {
            $wp_query->the_post();

            $item = array(
                '@type' => 'ListItem',
                'position' => $position,
                'url' => get_permalink(),
                'name' => get_the_title()
            );

            // Add image if available
            if (has_post_thumbnail()) {
                $item['image'] = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            }

            $schema['itemListElement'][] = $item;
            $position++;
        }

        wp_reset_postdata();
    }

    return !empty($schema['itemListElement']) ? $schema : null;
}

/**
 * Output portfolio archive schema
 */
function voltmont_output_portfolio_archive_schema() {
    $schema = voltmont_get_portfolio_archive_schema();

    if ($schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
    }
}
add_action('wp_head', 'voltmont_output_portfolio_archive_schema', 10);

/**
 * Add portfolio meta box for schema data
 */
function voltmont_add_portfolio_schema_meta_box() {
    add_meta_box(
        'voltmont_portfolio_schema',
        'Dane projektu (Schema.org)',
        'voltmont_render_portfolio_schema_meta_box',
        'portfolio',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'voltmont_add_portfolio_schema_meta_box');

/**
 * Render portfolio schema meta box
 *
 * @param WP_Post $post Current post object
 */
function voltmont_render_portfolio_schema_meta_box($post) {
    wp_nonce_field('voltmont_save_portfolio_schema', 'voltmont_portfolio_schema_nonce');

    $client = get_post_meta($post->ID, '_voltmont_client', true);
    $project_date = get_post_meta($post->ID, '_voltmont_project_date', true);
    $location = get_post_meta($post->ID, '_voltmont_location', true);

    ?>
    <p class="description">
        Dodatkowe dane projektu dla structured data (Google Rich Results).
    </p>

    <p>
        <label for="voltmont_client"><strong>Klient:</strong></label>
        <input type="text"
               id="voltmont_client"
               name="voltmont_client"
               value="<?php echo esc_attr($client); ?>"
               placeholder="Nazwa klienta"
               class="widefat">
    </p>

    <p>
        <label for="voltmont_project_date"><strong>Data realizacji:</strong></label>
        <input type="date"
               id="voltmont_project_date"
               name="voltmont_project_date"
               value="<?php echo esc_attr($project_date); ?>"
               class="widefat">
    </p>

    <p>
        <label for="voltmont_location"><strong>Lokalizacja:</strong></label>
        <input type="text"
               id="voltmont_location"
               name="voltmont_location"
               value="<?php echo esc_attr($location); ?>"
               placeholder="np. Trzebnica, Wrocław"
               class="widefat">
    </p>

    <p class="description">
        <small>Te dane będą wykorzystane w schema.org markup dla lepszej widoczności w Google.</small>
    </p>
    <?php
}

/**
 * Save portfolio schema meta
 *
 * @param int $post_id Post ID
 */
function voltmont_save_portfolio_schema_meta($post_id) {
    // Security checks
    if (!isset($_POST['voltmont_portfolio_schema_nonce']) ||
        !wp_verify_nonce($_POST['voltmont_portfolio_schema_nonce'], 'voltmont_save_portfolio_schema')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save client
    if (isset($_POST['voltmont_client'])) {
        update_post_meta($post_id, '_voltmont_client', sanitize_text_field($_POST['voltmont_client']));
    }

    // Save project date
    if (isset($_POST['voltmont_project_date'])) {
        update_post_meta($post_id, '_voltmont_project_date', sanitize_text_field($_POST['voltmont_project_date']));
    }

    // Save location
    if (isset($_POST['voltmont_location'])) {
        update_post_meta($post_id, '_voltmont_location', sanitize_text_field($_POST['voltmont_location']));
    }
}
add_action('save_post_portfolio', 'voltmont_save_portfolio_schema_meta');

/**
 * Add schema data columns to portfolio admin list
 *
 * @param array $columns Existing columns
 * @return array Modified columns
 */
function voltmont_add_portfolio_admin_columns($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Add after title
        if ($key === 'title') {
            $new_columns['client'] = 'Klient';
            $new_columns['location'] = 'Lokalizacja';
            $new_columns['project_date'] = 'Data realizacji';
        }
    }

    return $new_columns;
}
add_filter('manage_portfolio_posts_columns', 'voltmont_add_portfolio_admin_columns');

/**
 * Populate custom columns in portfolio admin list
 *
 * @param string $column Column name
 * @param int $post_id Post ID
 */
function voltmont_populate_portfolio_admin_columns($column, $post_id) {
    switch ($column) {
        case 'client':
            $client = get_post_meta($post_id, '_voltmont_client', true);
            echo $client ? esc_html($client) : '—';
            break;

        case 'location':
            $location = get_post_meta($post_id, '_voltmont_location', true);
            echo $location ? esc_html($location) : '—';
            break;

        case 'project_date':
            $date = get_post_meta($post_id, '_voltmont_project_date', true);
            if ($date) {
                echo esc_html(date_i18n('j F Y', strtotime($date)));
            } else {
                echo '—';
            }
            break;
    }
}
add_action('manage_portfolio_posts_custom_column', 'voltmont_populate_portfolio_admin_columns', 10, 2);

/**
 * Make schema data columns sortable
 *
 * @param array $columns Sortable columns
 * @return array Modified columns
 */
function voltmont_make_portfolio_columns_sortable($columns) {
    $columns['client'] = 'client';
    $columns['location'] = 'location';
    $columns['project_date'] = 'project_date';

    return $columns;
}
add_filter('manage_edit-portfolio_sortable_columns', 'voltmont_make_portfolio_columns_sortable');

/**
 * Handle sorting of custom columns
 *
 * @param WP_Query $query The WordPress query
 */
function voltmont_portfolio_columns_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    switch ($orderby) {
        case 'client':
            $query->set('meta_key', '_voltmont_client');
            $query->set('orderby', 'meta_value');
            break;

        case 'location':
            $query->set('meta_key', '_voltmont_location');
            $query->set('orderby', 'meta_value');
            break;

        case 'project_date':
            $query->set('meta_key', '_voltmont_project_date');
            $query->set('orderby', 'meta_value');
            break;
    }
}
add_action('pre_get_posts', 'voltmont_portfolio_columns_orderby');
