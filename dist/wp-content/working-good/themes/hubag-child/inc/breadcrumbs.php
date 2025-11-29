<?php
/**
 * Breadcrumbs with Schema.org Markup
 *
 * Displays breadcrumb navigation with proper schema markup
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

/**
 * Display breadcrumbs
 *
 * @param array $args Configuration arguments
 * @return string Breadcrumb HTML
 */
function voltmont_breadcrumbs($args = array()) {
    // Don't display on front page
    if (is_front_page()) {
        return '';
    }

    // Default arguments
    $defaults = array(
        'separator' => '<span class="breadcrumb-separator">/</span>',
        'home_title' => 'Strona główna',
        'show_current' => true,
        'show_schema' => true,
        'container_class' => 'voltmont-breadcrumbs',
        'list_class' => 'breadcrumb-list'
    );

    $args = wp_parse_args($args, $defaults);

    global $post;
    $breadcrumbs = array();
    $position = 1;

    // Home
    $breadcrumbs[] = array(
        'title' => $args['home_title'],
        'url' => home_url(),
        'position' => $position++
    );

    // Category/Taxonomy archives
    if (is_category() || is_tax() || is_tag()) {
        $term = get_queried_object();

        // Parent categories
        if ($term->parent && is_category()) {
            $parent_cats = get_category_parents($term->parent, false, '||', false);
            $parent_cats = explode('||', $parent_cats);

            foreach ($parent_cats as $parent) {
                if (!empty($parent)) {
                    $parent_term = get_term_by('name', $parent, 'category');
                    if ($parent_term) {
                        $breadcrumbs[] = array(
                            'title' => $parent_term->name,
                            'url' => get_term_link($parent_term),
                            'position' => $position++
                        );
                    }
                }
            }
        }

        // Current term
        $breadcrumbs[] = array(
            'title' => $term->name,
            'url' => get_term_link($term),
            'position' => $position++,
            'current' => true
        );
    }

    // Single post/page
    if (is_singular()) {
        // Get post type
        $post_type = get_post_type();
        $post_type_object = get_post_type_object($post_type);

        // Portfolio archive
        if ($post_type === 'portfolio') {
            $breadcrumbs[] = array(
                'title' => 'Galeria realizacji',
                'url' => get_post_type_archive_link('portfolio'),
                'position' => $position++
            );

            // Portfolio categories
            $terms = get_the_terms($post->ID, 'portfolio-types');
            if ($terms && !is_wp_error($terms)) {
                $main_term = $terms[0];
                $breadcrumbs[] = array(
                    'title' => $main_term->name,
                    'url' => get_term_link($main_term),
                    'position' => $position++
                );
            }
        }

        // Page categories (custom taxonomy)
        if ($post_type === 'page') {
            $terms = get_the_terms($post->ID, 'page_category');
            if ($terms && !is_wp_error($terms)) {
                $main_term = $terms[0];
                $breadcrumbs[] = array(
                    'title' => $main_term->name,
                    'url' => get_term_link($main_term),
                    'position' => $position++
                );
            }
        }

        // Parent pages
        if ($post->post_parent) {
            $parent_ids = get_post_ancestors($post->ID);
            $parent_ids = array_reverse($parent_ids);

            foreach ($parent_ids as $parent_id) {
                $breadcrumbs[] = array(
                    'title' => get_the_title($parent_id),
                    'url' => get_permalink($parent_id),
                    'position' => $position++
                );
            }
        }

        // Current page
        if ($args['show_current']) {
            $breadcrumbs[] = array(
                'title' => get_the_title(),
                'url' => get_permalink(),
                'position' => $position++,
                'current' => true
            );
        }
    }

    // Archive pages
    if (is_archive() && !is_category() && !is_tax() && !is_tag()) {
        if (is_post_type_archive()) {
            $post_type = get_query_var('post_type');
            if (is_array($post_type)) {
                $post_type = reset($post_type);
            }
            $post_type_object = get_post_type_object($post_type);

            $breadcrumbs[] = array(
                'title' => $post_type_object->labels->name,
                'url' => get_post_type_archive_link($post_type),
                'position' => $position++,
                'current' => true
            );
        } elseif (is_author()) {
            $author = get_queried_object();
            $breadcrumbs[] = array(
                'title' => 'Autor: ' . $author->display_name,
                'url' => get_author_posts_url($author->ID),
                'position' => $position++,
                'current' => true
            );
        } elseif (is_date()) {
            $year = get_query_var('year');
            $month = get_query_var('monthnum');
            $day = get_query_var('day');

            if ($year) {
                $breadcrumbs[] = array(
                    'title' => $year,
                    'url' => get_year_link($year),
                    'position' => $position++,
                    'current' => !$month
                );
            }

            if ($month) {
                $breadcrumbs[] = array(
                    'title' => date('F', mktime(0, 0, 0, $month, 1)),
                    'url' => get_month_link($year, $month),
                    'position' => $position++,
                    'current' => !$day
                );
            }

            if ($day) {
                $breadcrumbs[] = array(
                    'title' => $day,
                    'url' => get_day_link($year, $month, $day),
                    'position' => $position++,
                    'current' => true
                );
            }
        }
    }

    // Search results
    if (is_search()) {
        $breadcrumbs[] = array(
            'title' => 'Wyniki wyszukiwania: ' . get_search_query(),
            'url' => get_search_link(),
            'position' => $position++,
            'current' => true
        );
    }

    // 404
    if (is_404()) {
        $breadcrumbs[] = array(
            'title' => 'Strona nie znaleziona',
            'url' => '',
            'position' => $position++,
            'current' => true
        );
    }

    // Build HTML
    ob_start();
    ?>
    <nav class="<?php echo esc_attr($args['container_class']); ?>" aria-label="Breadcrumb">
        <ol class="<?php echo esc_attr($args['list_class']); ?>" <?php echo $args['show_schema'] ? 'itemscope itemtype="https://schema.org/BreadcrumbList"' : ''; ?>>
            <?php foreach ($breadcrumbs as $crumb) : ?>
                <li class="breadcrumb-item<?php echo !empty($crumb['current']) ? ' current' : ''; ?>"
                    <?php echo $args['show_schema'] ? 'itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"' : ''; ?>>
                    <?php if (empty($crumb['current']) || empty($args['show_current'])) : ?>
                        <a href="<?php echo esc_url($crumb['url']); ?>"
                           <?php echo $args['show_schema'] ? 'itemprop="item"' : ''; ?>>
                            <span <?php echo $args['show_schema'] ? 'itemprop="name"' : ''; ?>>
                                <?php echo esc_html($crumb['title']); ?>
                            </span>
                        </a>
                        <?php if ($args['show_schema']) : ?>
                            <meta itemprop="position" content="<?php echo esc_attr($crumb['position']); ?>">
                        <?php endif; ?>
                    <?php else : ?>
                        <span <?php echo $args['show_schema'] ? 'itemprop="name"' : ''; ?>>
                            <?php echo esc_html($crumb['title']); ?>
                        </span>
                        <?php if ($args['show_schema']) : ?>
                            <meta itemprop="position" content="<?php echo esc_attr($crumb['position']); ?>">
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (end($breadcrumbs) !== $crumb) : ?>
                        <?php echo $args['separator']; ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php
    return ob_get_clean();
}

/**
 * Display breadcrumbs hook
 */
function voltmont_display_breadcrumbs() {
    echo voltmont_breadcrumbs();
}
add_action('mfn_hook_content_before', 'voltmont_display_breadcrumbs', 5);

/**
 * Breadcrumbs shortcode
 *
 * Usage: [voltmont_breadcrumbs separator=" > " show_current="yes"]
 */
function voltmont_breadcrumbs_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'separator' => '/',
            'show_current' => 'yes',
            'home_title' => 'Strona główna'
        ),
        $atts,
        'voltmont_breadcrumbs'
    );

    $args = array(
        'separator' => '<span class="breadcrumb-separator">' . esc_html($atts['separator']) . '</span>',
        'show_current' => $atts['show_current'] === 'yes',
        'home_title' => $atts['home_title']
    );

    return voltmont_breadcrumbs($args);
}
add_shortcode('voltmont_breadcrumbs', 'voltmont_breadcrumbs_shortcode');
