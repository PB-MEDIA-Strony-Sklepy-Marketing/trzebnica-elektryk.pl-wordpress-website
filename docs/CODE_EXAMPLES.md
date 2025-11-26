# Code Examples & Best Practices - trzebnica-elektryk.pl

**Practical code examples for common tasks in the Voltmont project**

---

## 🎯 Purpose

This document provides copy-paste-ready code examples following our coding standards. Use these as templates for your own implementations.

---

## 📋 Table of Contents

1. [WordPress Custom Functions](#wordpress-custom-functions)
2. [AJAX Handlers](#ajax-handlers)
3. [Schema.org Markup](#schemaorg-markup)
4. [Security Patterns](#security-patterns)
5. [CSS Components](#css-components)
6. [JavaScript Modules](#javascript-modules)
7. [Custom Post Types](#custom-post-types)
8. [Meta Boxes](#meta-boxes)
9. [SEO & Structured Data](#seo--structured-data)

---

## 1. WordPress Custom Functions

### Basic Custom Function

```php
<?php
/**
 * Get company contact information
 *
 * Returns an array of contact details for Voltmont.
 *
 * @since 2.0.0
 * @return array Contact information
 */
function voltmont_get_contact_info() {
    return array(
        'phone' => '+48 691 594 820',
        'email' => 'biuro@trzebnica-elektryk.pl',
        'address' => 'Trzebnica, Dolnośląskie',
        'website' => 'https://trzebnica-elektryk.pl',
    );
}
```

### Hook Into WordPress Actions

```php
<?php
/**
 * Add custom scripts to footer
 *
 * @since 2.0.0
 */
function voltmont_enqueue_custom_scripts() {
    wp_enqueue_script(
        'voltmont-custom',
        get_stylesheet_directory_uri() . '/assets/js/custom.js',
        array( 'jquery' ),
        '1.0.0',
        true
    );
    
    // Localize script for AJAX
    wp_localize_script( 'voltmont-custom', 'voltmontAjax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'voltmont-ajax-nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'voltmont_enqueue_custom_scripts' );
```

---

## 2. AJAX Handlers

### Complete AJAX Handler (Form Submission)

```php
<?php
/**
 * Handle contact form submission via AJAX
 *
 * @since 2.0.0
 */
function voltmont_handle_contact_form() {
    // 1. Verify nonce (SECURITY!)
    check_ajax_referer( 'voltmont-ajax-nonce', 'nonce' );
    
    // 2. Sanitize all inputs (SECURITY!)
    $name    = sanitize_text_field( $_POST['name'] ?? '' );
    $email   = sanitize_email( $_POST['email'] ?? '' );
    $phone   = sanitize_text_field( $_POST['phone'] ?? '' );
    $message = sanitize_textarea_field( $_POST['message'] ?? '' );
    
    // 3. Validate
    $errors = array();
    
    if ( empty( $name ) ) {
        $errors[] = 'Imię jest wymagane.';
    }
    
    if ( empty( $email ) || ! is_email( $email ) ) {
        $errors[] = 'Poprawny email jest wymagany.';
    }
    
    if ( empty( $message ) ) {
        $errors[] = 'Wiadomość jest wymagana.';
    }
    
    if ( ! empty( $errors ) ) {
        wp_send_json_error( array(
            'message' => implode( ' ', $errors ),
        ) );
    }
    
    // 4. Process (send email, save to DB, etc.)
    $to      = 'biuro@trzebnica-elektryk.pl';
    $subject = 'Nowa wiadomość z formularza kontaktowego';
    $body    = sprintf(
        "Imię: %s\nEmail: %s\nTelefon: %s\n\nWiadomość:\n%s",
        $name,
        $email,
        $phone,
        $message
    );
    $headers = array( 'Content-Type: text/plain; charset=UTF-8' );
    
    $sent = wp_mail( $to, $subject, $body, $headers );
    
    // 5. Return response
    if ( $sent ) {
        wp_send_json_success( array(
            'message' => 'Dziękujemy! Wiadomość została wysłana.',
        ) );
    } else {
        wp_send_json_error( array(
            'message' => 'Wystąpił błąd. Spróbuj ponownie.',
        ) );
    }
}
add_action( 'wp_ajax_voltmont_contact_form', 'voltmont_handle_contact_form' );
add_action( 'wp_ajax_nopriv_voltmont_contact_form', 'voltmont_handle_contact_form' );
```

### JavaScript (Frontend)

```javascript
// assets/js/contact-form.js
jQuery(document).ready(function($) {
    $('#contact-form').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');
        const $message = $('#form-message');
        
        // Disable submit button
        $submitBtn.prop('disabled', true).text('Wysyłanie...');
        
        $.ajax({
            url: voltmontAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'voltmont_contact_form',
                nonce: voltmontAjax.nonce,
                name: $('#name').val(),
                email: $('#email').val(),
                phone: $('#phone').val(),
                message: $('#message').val()
            },
            success: function(response) {
                if (response.success) {
                    $message.html('<p class="success">' + response.data.message + '</p>');
                    $form[0].reset();
                } else {
                    $message.html('<p class="error">' + response.data.message + '</p>');
                }
            },
            error: function() {
                $message.html('<p class="error">Wystąpił błąd. Spróbuj ponownie.</p>');
            },
            complete: function() {
                $submitBtn.prop('disabled', false).text('Wyślij');
            }
        });
    });
});
```

---

## 3. Schema.org Markup

### LocalBusiness Schema

```php
<?php
/**
 * Output LocalBusiness schema.org markup
 *
 * @since 2.0.0
 */
function voltmont_output_localbusiness_schema() {
    if ( ! is_front_page() ) {
        return;
    }
    
    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'LocalBusiness',
        'name'        => 'Voltmont - Instalacje Elektryczne',
        'description' => 'Profesjonalne usługi elektryczne w Trzebnicy i Dolnym Śląsku',
        'url'         => home_url(),
        'telephone'   => '+48 691 594 820',
        'email'       => 'biuro@trzebnica-elektryk.pl',
        'address'     => array(
            '@type'           => 'PostalAddress',
            'addressLocality' => 'Trzebnica',
            'addressRegion'   => 'Dolnośląskie',
            'addressCountry'  => 'PL',
        ),
        'geo'         => array(
            '@type'     => 'GeoCoordinates',
            'latitude'  => '51.3096',
            'longitude' => '17.0628',
        ),
        'areaServed'  => array(
            '@type' => 'State',
            'name'  => 'Dolnośląskie',
        ),
        'priceRange'  => '$$',
        'openingHours' => 'Mo-Fr 08:00-18:00, Sa 09:00-14:00',
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'voltmont_output_localbusiness_schema' );
```

### Service Schema

```php
<?php
/**
 * Output Service schema for specific service page
 *
 * @since 2.0.0
 */
function voltmont_output_service_schema() {
    if ( ! is_singular( 'service' ) ) {
        return;
    }
    
    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'           => 'Service',
        'name'            => get_the_title(),
        'description'     => get_the_excerpt(),
        'provider'        => array(
            '@type' => 'LocalBusiness',
            'name'  => 'Voltmont - Instalacje Elektryczne',
            'url'   => home_url(),
        ),
        'areaServed'      => array(
            '@type' => 'State',
            'name'  => 'Dolnośląskie',
        ),
        'serviceType'     => get_the_title(),
        'offers'          => array(
            '@type'        => 'Offer',
            'availability' => 'https://schema.org/InStock',
        ),
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'voltmont_output_service_schema' );
```

---

## 4. Security Patterns

### Input Sanitization Examples

```php
<?php
// Text field
$name = sanitize_text_field( $_POST['name'] );

// Email
$email = sanitize_email( $_POST['email'] );

// URL
$website = esc_url_raw( $_POST['website'] );

// Textarea
$message = sanitize_textarea_field( $_POST['message'] );

// Integer
$post_id = absint( $_POST['post_id'] );

// Array of IDs
$ids = array_map( 'absint', $_POST['ids'] );

// HTML (strip tags except allowed)
$html = wp_kses_post( $_POST['html'] );
```

### Output Escaping Examples

```php
<?php
// HTML content
echo esc_html( $user_input );

// Attributes
echo '<div class="' . esc_attr( $class ) . '">';

// URL
echo '<a href="' . esc_url( $link ) . '">Link</a>';

// JavaScript string
echo '<script>var name = "' . esc_js( $name ) . '";</script>';

// Textarea content
echo '<textarea>' . esc_textarea( $content ) . '</textarea>';
```

### Nonce Verification

```php
<?php
// Create nonce in form
<form method="post">
    <?php wp_nonce_field( 'voltmont_form_action', 'voltmont_nonce' ); ?>
    <input type="text" name="field">
    <button type="submit">Submit</button>
</form>

// Verify nonce in handler
if ( ! isset( $_POST['voltmont_nonce'] ) || ! wp_verify_nonce( $_POST['voltmont_nonce'], 'voltmont_form_action' ) ) {
    wp_die( 'Security check failed' );
}
```

### Capability Checks

```php
<?php
// Check if user can edit posts
if ( ! current_user_can( 'edit_posts' ) ) {
    wp_die( 'Unauthorized access' );
}

// Check if user can manage options (admin)
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'You do not have sufficient permissions' );
}
```

---

## 5. CSS Components

### Button Component (BEM)

```css
/* assets/css/components/button.css */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: var(--space-3) var(--space-5);
    border: none;
    border-radius: var(--radius-md);
    font-size: var(--font-size-base);
    font-weight: var(--font-weight-semibold);
    text-decoration: none;
    transition: all var(--transition-base);
    cursor: pointer;
    min-height: 44px; /* WCAG touch target */
}

.btn--primary {
    background-color: var(--color-primary);
    color: var(--color-white);
}

.btn--primary:hover,
.btn--primary:focus {
    background-color: var(--color-hover);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(77, 129, 233, 0.3);
}

.btn--primary:focus {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
}

.btn--secondary {
    background-color: var(--color-secondary);
    color: var(--color-white);
}

.btn--ghost {
    background-color: transparent;
    border: 2px solid var(--color-white);
    color: var(--color-white);
}

.btn--large {
    padding: var(--space-4) var(--space-6);
    font-size: var(--font-size-lg);
}

.btn--small {
    padding: var(--space-2) var(--space-4);
    font-size: var(--font-size-sm);
    min-height: 36px;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
```

### Card Component (BEM)

```css
/* assets/css/components/card.css */
.card {
    background-color: var(--color-white);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    transition: transform var(--transition-base), box-shadow var(--transition-base);
}

.card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.card__image {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.card__body {
    padding: var(--space-4);
}

.card__title {
    margin: 0 0 var(--space-2) 0;
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    color: var(--color-text-primary);
}

.card__description {
    margin: 0;
    color: var(--color-text-secondary);
    line-height: var(--line-height-relaxed);
}

.card__footer {
    padding: var(--space-3) var(--space-4);
    background-color: var(--color-gray-50);
    border-top: 1px solid var(--color-gray-200);
}

/* Responsive */
@media (max-width: 768px) {
    .card__image {
        height: 150px;
    }
    
    .card__body {
        padding: var(--space-3);
    }
}
```

---

## 6. JavaScript Modules

### Lazy Loading Images

```javascript
// assets/js/modules/lazy-load.js
export function initLazyLoad() {
    // Use Intersection Observer for performance
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    img.classList.add('lazy-loaded');
                    imageObserver.unobserve(img);
                }
            });
        });

        const lazyImages = document.querySelectorAll('img.lazy');
        lazyImages.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for older browsers
        const lazyImages = document.querySelectorAll('img.lazy');
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            img.classList.remove('lazy');
        });
    }
}
```

### Smooth Scroll to Anchor

```javascript
// assets/js/modules/smooth-scroll.js
export function initSmoothScroll() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Skip if just "#"
            if (href === '#') return;
            
            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Update focus for accessibility
                target.setAttribute('tabindex', '-1');
                target.focus();
            }
        });
    });
}
```

---

## 7. Custom Post Types

### Register Custom Post Type

```php
<?php
/**
 * Register Portfolio custom post type
 *
 * @since 2.0.0
 */
function voltmont_register_portfolio_cpt() {
    $labels = array(
        'name'                  => __( 'Portfolio', 'voltmont' ),
        'singular_name'         => __( 'Portfolio Item', 'voltmont' ),
        'menu_name'             => __( 'Portfolio', 'voltmont' ),
        'add_new'               => __( 'Add New', 'voltmont' ),
        'add_new_item'          => __( 'Add New Portfolio Item', 'voltmont' ),
        'edit_item'             => __( 'Edit Portfolio Item', 'voltmont' ),
        'new_item'              => __( 'New Portfolio Item', 'voltmont' ),
        'view_item'             => __( 'View Portfolio Item', 'voltmont' ),
        'search_items'          => __( 'Search Portfolio', 'voltmont' ),
        'not_found'             => __( 'No portfolio items found', 'voltmont' ),
        'not_found_in_trash'    => __( 'No portfolio items found in Trash', 'voltmont' ),
        'all_items'             => __( 'All Portfolio Items', 'voltmont' ),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'realizacje' ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-portfolio',
        'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'        => true, // Enable Gutenberg
    );

    register_post_type( 'portfolio', $args );
}
add_action( 'init', 'voltmont_register_portfolio_cpt' );
```

### Register Custom Taxonomy

```php
<?php
/**
 * Register Portfolio Category taxonomy
 *
 * @since 2.0.0
 */
function voltmont_register_portfolio_taxonomy() {
    $labels = array(
        'name'              => __( 'Portfolio Categories', 'voltmont' ),
        'singular_name'     => __( 'Portfolio Category', 'voltmont' ),
        'search_items'      => __( 'Search Categories', 'voltmont' ),
        'all_items'         => __( 'All Categories', 'voltmont' ),
        'parent_item'       => __( 'Parent Category', 'voltmont' ),
        'parent_item_colon' => __( 'Parent Category:', 'voltmont' ),
        'edit_item'         => __( 'Edit Category', 'voltmont' ),
        'update_item'       => __( 'Update Category', 'voltmont' ),
        'add_new_item'      => __( 'Add New Category', 'voltmont' ),
        'new_item_name'     => __( 'New Category Name', 'voltmont' ),
        'menu_name'         => __( 'Categories', 'voltmont' ),
    );

    $args = array(
        'labels'            => $labels,
        'hierarchical'      => true, // Like categories
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'portfolio-category' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'portfolio_category', array( 'portfolio' ), $args );
}
add_action( 'init', 'voltmont_register_portfolio_taxonomy' );
```

---

## 8. Meta Boxes

### Add Custom Meta Box

```php
<?php
/**
 * Add custom meta box for portfolio items
 *
 * @since 2.0.0
 */
function voltmont_add_portfolio_meta_box() {
    add_meta_box(
        'voltmont_portfolio_details',
        __( 'Portfolio Details', 'voltmont' ),
        'voltmont_render_portfolio_meta_box',
        'portfolio',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'voltmont_add_portfolio_meta_box' );

/**
 * Render meta box content
 *
 * @param WP_Post $post Current post object.
 */
function voltmont_render_portfolio_meta_box( $post ) {
    // Add nonce for security
    wp_nonce_field( 'voltmont_portfolio_meta_box', 'voltmont_portfolio_meta_box_nonce' );
    
    // Get existing values
    $client = get_post_meta( $post->ID, '_voltmont_client', true );
    $date = get_post_meta( $post->ID, '_voltmont_project_date', true );
    $location = get_post_meta( $post->ID, '_voltmont_location', true );
    
    ?>
    <p>
        <label for="voltmont_client"><?php _e( 'Client Name:', 'voltmont' ); ?></label>
        <input type="text" id="voltmont_client" name="voltmont_client" value="<?php echo esc_attr( $client ); ?>" style="width: 100%;">
    </p>
    <p>
        <label for="voltmont_project_date"><?php _e( 'Project Date:', 'voltmont' ); ?></label>
        <input type="date" id="voltmont_project_date" name="voltmont_project_date" value="<?php echo esc_attr( $date ); ?>">
    </p>
    <p>
        <label for="voltmont_location"><?php _e( 'Location:', 'voltmont' ); ?></label>
        <input type="text" id="voltmont_location" name="voltmont_location" value="<?php echo esc_attr( $location ); ?>" style="width: 100%;">
    </p>
    <?php
}

/**
 * Save meta box data
 *
 * @param int $post_id Post ID.
 */
function voltmont_save_portfolio_meta_box( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['voltmont_portfolio_meta_box_nonce'] ) ||
         ! wp_verify_nonce( $_POST['voltmont_portfolio_meta_box_nonce'], 'voltmont_portfolio_meta_box' ) ) {
        return;
    }
    
    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Save data
    if ( isset( $_POST['voltmont_client'] ) ) {
        update_post_meta( $post_id, '_voltmont_client', sanitize_text_field( $_POST['voltmont_client'] ) );
    }
    
    if ( isset( $_POST['voltmont_project_date'] ) ) {
        update_post_meta( $post_id, '_voltmont_project_date', sanitize_text_field( $_POST['voltmont_project_date'] ) );
    }
    
    if ( isset( $_POST['voltmont_location'] ) ) {
        update_post_meta( $post_id, '_voltmont_location', sanitize_text_field( $_POST['voltmont_location'] ) );
    }
}
add_action( 'save_post_portfolio', 'voltmont_save_portfolio_meta_box' );
```

---

## 🔍 Additional Resources

### Internal Documentation
- [CLAUDE.md](../CLAUDE.md) - Complete development guidelines
- [SECURITY.md](../SECURITY.md) - Security best practices
- [TESTING.md](../TESTING.md) - Testing requirements
- [PERFORMANCE_GUIDE.md](PERFORMANCE_GUIDE.md) - Performance optimization

### WordPress Resources
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Plugin Handbook](https://developer.wordpress.org/plugins/)
- [Theme Handbook](https://developer.wordpress.org/themes/)

---

## 10. Performance Optimization

### Cached Portfolio Query

```php
<?php
/**
 * Get portfolio items with transient caching
 *
 * @return WP_Query Cached portfolio query
 */
function get_portfolio_with_cache() {
    $portfolio = voltmont_get_cached_portfolio(array(
        'posts_per_page' => 12,
        'orderby' => 'date',
        'order' => 'DESC'
    ), 43200); // Cache for 12 hours

    return $portfolio;
}

// Usage
$portfolio = get_portfolio_with_cache();

if ($portfolio->have_posts()) {
    while ($portfolio->have_posts()) {
        $portfolio->the_post();
        // Display portfolio item
    }
    wp_reset_postdata();
}
```

### Cached Menu

```php
<?php
/**
 * Get navigation menu with caching
 *
 * @return array|false Menu items
 */
function get_cached_navigation() {
    $menu = voltmont_get_cached_menu('primary', 86400); // 24 hours
    
    if (!$menu) {
        return false;
    }

    return $menu;
}

// Usage
$nav_items = get_cached_navigation();

if ($nav_items) {
    foreach ($nav_items as $item) {
        echo '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
    }
}
```

### WebP Background Image (Lazy Loading)

```html
<!-- Use data-bg for lazy loaded backgrounds -->
<div class="hero-section" 
     data-bg="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/hero.webp"
     style="min-height: 500px;">
    <h1>Hero Title</h1>
</div>

<!-- JavaScript will automatically convert data-bg to background-image when in viewport -->
```

### Optimized Image Output

```php
<?php
/**
 * Output optimized image with WebP support and lazy loading
 *
 * @param int $attachment_id Image attachment ID
 * @param string $size Image size
 * @return void
 */
function voltmont_optimized_image($attachment_id, $size = 'large') {
    // Get image data
    $image_src = wp_get_attachment_image_src($attachment_id, $size);
    $image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
    
    if (!$image_src) {
        return;
    }

    // Check if WebP exists
    $file_path = get_attached_file($attachment_id);
    $webp_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $file_path);
    $webp_url = preg_replace('/\.(jpe?g|png)$/i', '.webp', $image_src[0]);
    
    ?>
    <picture>
        <?php if (file_exists($webp_path)) : ?>
            <source srcset="<?php echo esc_url($webp_url); ?>" type="image/webp">
        <?php endif; ?>
        <img src="<?php echo esc_url($image_src[0]); ?>"
             width="<?php echo esc_attr($image_src[1]); ?>"
             height="<?php echo esc_attr($image_src[2]); ?>"
             alt="<?php echo esc_attr($image_alt); ?>"
             loading="lazy"
             decoding="async">
    </picture>
    <?php
}

// Usage
voltmont_optimized_image(get_post_thumbnail_id(), 'large');
```

### Clear Specific Cache

```php
<?php
/**
 * Clear portfolio cache programmatically
 */
function clear_portfolio_cache_example() {
    global $wpdb;
    
    // Clear all portfolio transients
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_voltmont_portfolio_%'");
    $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_voltmont_portfolio_%'");
    
    // Or use the helper function
    voltmont_clear_portfolio_cache(0); // Pass any post ID
}

/**
 * Clear all caches
 */
function clear_all_caches_example() {
    voltmont_clear_all_caches();
}
```

### Minify Custom CSS

```php
<?php
/**
 * Output minified custom CSS
 */
function output_custom_css() {
    $css = "
    .my-custom-class {
        color: red;
        font-size: 16px;
        /* This is a comment */
        padding: 10px;
    }
    ";
    
    // Minify before output
    $minified = voltmont_minify_css($css);
    
    echo '<style>' . $minified . '</style>';
}
add_action('wp_head', 'output_custom_css');
```

---

**Need more examples?** Check the codebase in `dist/wp-content/themes/hubag-child/` or create an issue!

---

## 9. SEO & Structured Data

### Dynamic Meta Description

```php
<?php
/**
 * Generate optimized meta description (150-160 characters)
 *
 * @return string Meta description
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

        // Add location keywords if missing
        if (!stripos($description, 'Trzebnica') && !stripos($description, 'Dolny Śląsk')) {
            $description .= ' Trzebnica, Dolny Śląsk.';
        }
    }

    // Truncate to 160 characters
    if (strlen($description) > 160) {
        $description = substr($description, 0, 157) . '...';
    }

    return $description;
}

// Output in head
function voltmont_output_meta_description() {
    echo '<meta name="description" content="' . esc_attr(voltmont_get_meta_description()) . '">' . "\n";
}
add_action('wp_head', 'voltmont_output_meta_description', 1);
```

### FAQ Schema Generator

```php
<?php
/**
 * Generate FAQ schema from array
 *
 * @param array $faqs Array of ['question' => '...', 'answer' => '...']
 * @return array|null FAQPage schema
 */
function voltmont_get_faq_schema($faqs) {
    if (empty($faqs)) {
        return null;
    }

    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array()
    );

    foreach ($faqs as $faq) {
        if (empty($faq['question']) || empty($faq['answer'])) {
            continue;
        }

        $schema['mainEntity'][] = array(
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => array(
                '@type' => 'Answer',
                'text' => $faq['answer']
            )
        );
    }

    return !empty($schema['mainEntity']) ? $schema : null;
}

// Example usage
$faqs = array(
    array(
        'question' => 'Jakie są koszty instalacji elektrycznej?',
        'answer' => 'Koszty zależą od zakresu prac, powierzchni i rodzaju instalacji...'
    ),
    array(
        'question' => 'Czy oferujecie modernizację instalacji w blokach?',
        'answer' => 'Tak, specjalizujemy się w modernizacji WLZ w budynkach wielorodzinnych...'
    )
);

$schema = voltmont_get_faq_schema($faqs);

// Output
echo '<script type="application/ld+json">' . 
     wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . 
     '</script>';
```

### Portfolio Schema with Meta Data

```php
<?php
/**
 * Generate portfolio CreativeWork schema
 *
 * @param int|WP_Post $post Post object or ID
 * @return array|null Portfolio schema
 */
function voltmont_get_portfolio_schema($post = null) {
    if (!$post) {
        $post = get_post();
    }

    if (!$post || get_post_type($post) !== 'portfolio') {
        return null;
    }

    // Base schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'CreativeWork',
        'name' => get_the_title($post),
        'description' => get_the_excerpt($post),
        'url' => get_permalink($post),
        'datePublished' => get_the_date('c', $post),
        'author' => array(
            '@type' => 'Organization',
            'name' => 'Voltmont - Instalacje Elektryczne'
        )
    );

    // Add featured image
    if (has_post_thumbnail($post)) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => get_the_post_thumbnail_url($post, 'full')
        );
    }

    // Add custom meta if exists
    $client = get_post_meta($post->ID, '_voltmont_client', true);
    if ($client) {
        $schema['client'] = array(
            '@type' => 'Organization',
            'name' => $client
        );
    }

    $location = get_post_meta($post->ID, '_voltmont_location', true);
    if ($location) {
        $schema['locationCreated'] = array(
            '@type' => 'Place',
            'name' => $location
        );
    }

    return $schema;
}
```

### OpenGraph Tags

```php
<?php
/**
 * Output OpenGraph meta tags
 */
function voltmont_output_opengraph_tags() {
    $og_title = get_the_title();
    $og_description = voltmont_get_meta_description();
    $og_url = get_permalink();
    $og_image = has_post_thumbnail() ? 
        get_the_post_thumbnail_url(get_the_ID(), 'full') : 
        get_stylesheet_directory_uri() . '/site-login-logo.png';

    echo '<!-- OpenGraph Meta Tags -->' . "\n";
    echo '<meta property="og:locale" content="pl_PL">' . "\n";
    echo '<meta property="og:type" content="article">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($og_description) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($og_image) . '">' . "\n";
    echo '<meta property="og:image:width" content="1200">' . "\n";
    echo '<meta property="og:image:height" content="630">' . "\n";
}
add_action('wp_head', 'voltmont_output_opengraph_tags', 2);
```

---

## 🔍 Additional Resources
