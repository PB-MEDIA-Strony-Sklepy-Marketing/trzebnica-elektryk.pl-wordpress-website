# Copilot Instructions – trzebnica-elektryk.pl

**Guidelines for GitHub Copilot when working on this WordPress project**

---

## Project Context

This is a production WordPress website for **Voltmont - Instalacje Elektryczne**, an electrical services company in Trzebnica, Poland. The site is built on BeTheme with a custom child theme.

---

## Core Principles

1. **Work in `dist/wp-content/themes/hubag-child/`** – This is the primary development directory
2. **Security First** – Always sanitize input and escape output
3. **WordPress Standards** – Follow WordPress Coding Standards
4. **Performance Matters** – Optimize queries, use transients, lazy load assets
5. **Accessibility** – WCAG 2.2 AA compliance required
6. **Mobile-First** – Responsive design from 320px up

---

## Coding Standards

### PHP (PSR-12 + WordPress)

**Function Naming:**
```php
// Prefix all functions with 'voltmont_'
function voltmont_get_meta_description() { }
```

**Security:**
```php
// ALWAYS escape output
echo esc_html( $user_data );
echo '<a href="' . esc_url( $link ) . '">';
echo '<div data-value="' . esc_attr( $value ) . '">';

// ALWAYS sanitize input
$clean = sanitize_text_field( $_POST['field'] );
$email = sanitize_email( $_POST['email'] );

// ALWAYS use prepared statements
$results = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE ID = %d", $post_id );

// ALWAYS verify nonces
if ( ! wp_verify_nonce( $_POST['nonce'], 'action_name' ) ) {
    wp_die( 'Security check failed' );
}
```

### CSS (BEM Methodology)

```css
/* Block */
.service-card { }

/* Element */
.service-card__title { }
.service-card__icon { }

/* Modifier */
.service-card--featured { }
```

**Use CSS Variables (from brand-system.css):**
```css
.element {
    color: var(--color-primary);
    background: var(--color-bg-dark);
    padding: var(--space-4);
}
```

### JavaScript (ES6+)

```javascript
// Use modern syntax
const fetchData = async (url) => {
    try {
        const response = await fetch(url);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
};

// Prefer const/let over var
const API_URL = 'https://api.example.com';
let counter = 0;
```

---

## Common Tasks

### Adding a New Function

```php
/**
 * Brief description
 *
 * Detailed description of what this function does.
 *
 * @since 2.0.0
 * @param string $param1 Description of parameter
 * @return string Description of return value
 */
function voltmont_new_feature( $param1 ) {
    // Validate input
    $param1 = sanitize_text_field( $param1 );
    
    // Check capabilities if needed
    if ( ! current_user_can( 'edit_posts' ) ) {
        return false;
    }
    
    // Your logic here
    $result = do_something( $param1 );
    
    // Return escaped output
    return esc_html( $result );
}
```

### Adding Schema.org Markup

```php
function voltmont_output_service_schema() {
    if ( ! is_singular( 'page' ) ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'serviceType' => get_the_title(),
        'provider' => array(
            '@type' => 'LocalBusiness',
            'name' => 'Voltmont - Instalacje Elektryczne',
            'telephone' => '+48 691 594 820',
            'url' => home_url(),
        ),
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>' . "\n";
}
add_action( 'wp_head', 'voltmont_output_service_schema' );
```

### Enqueuing Assets

```php
function voltmont_enqueue_custom_script() {
    wp_enqueue_script(
        'voltmont-feature',                                    // Handle
        get_stylesheet_directory_uri() . '/assets/js/feature.js', // URL
        array( 'jquery' ),                                     // Dependencies
        '1.0.0',                                               // Version
        true                                                   // In footer
    );
    
    // Localize script (pass data to JS)
    wp_localize_script( 'voltmont-feature', 'voltmontData', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'voltmont-ajax-nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'voltmont_enqueue_custom_script' );
```

### Creating AJAX Handler

```php
// AJAX handler
function voltmont_ajax_handler() {
    // Verify nonce
    check_ajax_referer( 'voltmont-ajax-nonce', 'nonce' );
    
    // Get and sanitize data
    $data = sanitize_text_field( $_POST['data'] );
    
    // Process data
    $result = do_something( $data );
    
    // Return JSON
    wp_send_json_success( array(
        'message' => 'Success',
        'data' => $result,
    ) );
}
add_action( 'wp_ajax_voltmont_action', 'voltmont_ajax_handler' );
add_action( 'wp_ajax_nopriv_voltmont_action', 'voltmont_ajax_handler' );
```

---

## Brand System

### Colors

```css
--color-primary: #4d81e9;      /* Vibrant blue */
--color-secondary: #041028;    /* Deep navy */
--color-text: #edf0fd;         /* Light text */
--color-bg: #163162;           /* Dark blue bg */
--color-hover: #2a54a1;        /* Hover state */
```

### Typography

```css
--font-primary: 'Inter', sans-serif;
--font-size-h1: 3rem;          /* 48px */
--font-size-base: 1rem;        /* 16px */
--line-height-normal: 1.5;
```

### Spacing

```css
--space-1: 0.25rem;  /* 4px */
--space-2: 0.5rem;   /* 8px */
--space-4: 1rem;     /* 16px */
--space-6: 2rem;     /* 32px */
--space-8: 3rem;     /* 48px */
```

---

## BeTheme Integration

### Available Hooks

```php
// Before </head>
add_action( 'mfn_hook_top', 'your_function' );

// Before main content
add_action( 'mfn_hook_content_before', 'your_function' );

// After main content
add_action( 'mfn_hook_content_after', 'your_function' );

// Before </body>
add_action( 'mfn_hook_bottom', 'your_function' );
```

### Custom Post Types

```php
// Portfolio
$portfolio = new WP_Query( array(
    'post_type' => 'portfolio',
    'posts_per_page' => 6,
    'tax_query' => array(
        array(
            'taxonomy' => 'portfolio-types',
            'field' => 'slug',
            'terms' => 'modernizacja',
        ),
    ),
) );
```

---

## Performance

### Use Transients for Expensive Queries

```php
function voltmont_get_data() {
    $cache_key = 'voltmont_data';
    $cached = get_transient( $cache_key );
    
    if ( false !== $cached ) {
        return $cached;
    }
    
    // Expensive operation
    $data = expensive_query();
    
    // Cache for 1 hour
    set_transient( $cache_key, $data, HOUR_IN_SECONDS );
    
    return $data;
}
```

### Lazy Load Images

```html
<!-- WordPress 5.5+ native lazy loading -->
<img src="image.jpg" alt="Description" loading="lazy">
```

---

## SEO

### Meta Tags

```php
function voltmont_output_meta_tags() {
    if ( ! is_singular() ) {
        return;
    }
    
    $title = get_the_title() . ' | Voltmont';
    $description = get_the_excerpt() ?: 'Default description';
    
    echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
    echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
    echo '<meta property="og:url" content="' . esc_url( get_permalink() ) . '">' . "\n";
}
add_action( 'wp_head', 'voltmont_output_meta_tags' );
```

---

## Testing

### Before Committing

```bash
# Lint everything
npm run lint

# Run tests
npm test

# Build production
npm run build:production
```

### Manual Checks

- [ ] Test in Chrome, Firefox, Safari, Edge
- [ ] Test on mobile (iOS, Android)
- [ ] Verify keyboard navigation
- [ ] Check console for errors
- [ ] Validate HTML (W3C)
- [ ] Run Lighthouse audit

---

## Common Pitfalls to Avoid

❌ **Don't:**
- Use `$_POST` directly without sanitization
- Echo user data without escaping
- Write direct SQL queries without `$wpdb->prepare()`
- Ignore nonce verification for forms
- Use `eval()` or `unserialize()` with user data
- Forget to check user capabilities
- Use inline styles/scripts (XSS risk)
- Load assets on every page (check conditionals)

✅ **Do:**
- Always sanitize input
- Always escape output
- Use WordPress functions when available
- Check user permissions
- Use transients for expensive operations
- Optimize database queries
- Follow BEM for CSS
- Write descriptive commit messages

---

## Useful WordPress Functions

### Data Retrieval

```php
get_post_meta( $post_id, 'meta_key', true );
get_term_meta( $term_id, 'meta_key', true );
get_option( 'option_name' );
get_the_title( $post_id );
get_the_excerpt( $post_id );
get_permalink( $post_id );
```

### Sanitization

```php
sanitize_text_field( $string );
sanitize_email( $email );
sanitize_url( $url );
sanitize_textarea_field( $text );
absint( $number );
```

### Escaping

```php
esc_html( $text );
esc_attr( $attribute );
esc_url( $url );
esc_js( $javascript );
wp_kses_post( $html );
```

### User Capabilities

```php
is_user_logged_in();
current_user_can( 'edit_posts' );
current_user_can( 'manage_options' );
```

---

## Resources

- **WordPress Codex:** https://codex.wordpress.org/
- **WordPress Coding Standards:** https://developer.wordpress.org/coding-standards/
- **BeTheme Documentation:** `/docs/documentation/`
- **Brand System:** `dist/wp-content/themes/hubag-child/assets/css/brand-system.css`

---

## Quick Reference

**Primary Working Directory:**
```
dist/wp-content/themes/hubag-child/
```

**Key Files:**
- `functions.php` – Main theme functions
- `style.css` – Theme styles
- `assets/css/brand-system.css` – Design tokens
- `inc/` – Additional PHP files
- `template-parts/` – Template partials

**Contact:**
- Email: biuro@pbmediaonline.pl
- Phone: +48 691 594 820

---

**Last Updated:** 2024-01-15  
**Version:** 1.0
