# Voltmont Custom Functionality - Implementation Guide

This directory contains modular PHP files that extend WordPress and BeTheme functionality for the Voltmont electrical services website.

## 📁 Files Overview

### 1. `schema-localbusiness.php`
**Schema.org Structured Data for Local SEO**

Generates JSON-LD markup for:
- LocalBusiness schema with complete company information
- Service catalog for all electrical services
- BreadcrumbList for navigation
- Page-specific Service schema

**Automatic Integration**: Hooks into `wp_head` action (priority 5)

**No configuration needed** - works automatically on all pages.

**Customization**:
```php
// Filter business schema before output
add_filter('voltmont_localbusiness_schema', function($schema) {
    // Modify schema array
    $schema['priceRange'] = '$$';
    return $schema;
});
```

---

### 2. `functions-seo.php`
**SEO Meta Tags, OpenGraph & Twitter Cards**

Dynamically generates:
- Page titles (50-60 characters)
- Meta descriptions (150-160 characters)
- OpenGraph tags for social sharing
- Twitter Card markup
- Canonical URLs
- Robots meta tags

**Automatic Integration**: Hooks into `wp_head` action (priority 1)

**Customization**:
```php
// Override meta description for specific page
add_filter('voltmont_meta_description', function($description) {
    if (is_page('oferta')) {
        return 'Custom description for offer page';
    }
    return $description;
});

// Change default OpenGraph image
add_filter('voltmont_og_image', function($image_url) {
    return get_stylesheet_directory_uri() . '/assets/images/custom-og.jpg';
});
```

---

### 3. `custom-shortcodes.php`
**Reusable Content Components**

Provides 9 shortcodes for building pages without code:

#### Available Shortcodes:

**Emergency Contact Bar**
```
[voltmont_emergency_contact text="Pogotowie elektryczne 24h" phone="+48 691 594 820"]
```

**Service Box**
```
[voltmont_service_box
    icon="fas fa-bolt"
    title="Instalacje elektryczne"
    description="Kompleksowe instalacje"
    link="/oferta/instalacje"
    link_text="Zobacz więcej"]
```

**Pricing Table**
```
[voltmont_pricing
    title="Pakiet Standard"
    price="1500"
    period="zł"
    features="Feature 1,Feature 2,Feature 3"
    featured="yes"]
```

**Team Member**
```
[voltmont_team_member
    name="Jan Kowalski"
    position="Elektryk"
    photo="url-to-photo.jpg"
    phone="+48 123 456 789"
    email="jan@example.com"]
```

**Statistics Counter**
```
[voltmont_counter
    number="500"
    suffix="+"
    label="Zadowolonych klientów"
    icon="fas fa-check-circle"]
```

**Business Hours**
```
[voltmont_business_hours]
```

**Google Maps**
```
[voltmont_map height="400" zoom="13"]
```

**Testimonial**
```
[voltmont_testimonial author="Jan Kowalski" rating="5" date="2024-01-15"]
Doskonała obsługa, polecam!
[/voltmont_testimonial]
```

**CTA Button**
```
[voltmont_cta_button
    text="Skontaktuj się"
    link="#modal-opened"
    icon="fas fa-phone"
    style="primary"
    size="large"]
```

**CSS Required**: Add styles in your theme's CSS or use provided `sections-offer.scss`

---

### 4. `performance-optimization.php`
**Page Speed & Performance Utilities**

**Features (some optional, commented out by default)**:

#### Active by Default:
- ✅ Preconnect to external domains (Google Fonts, Analytics)
- ✅ Remove query strings from static resources
- ✅ Disable WordPress emoji scripts
- ✅ Remove unnecessary meta tags
- ✅ Lazy load images with native `loading="lazy"`
- ✅ Add cache-busting with theme version
- ✅ Optimize Heartbeat API

#### Optional (Uncomment to Enable):
- ⚠️ Disable WordPress embeds (line 82)
- ⚠️ HTML minification (line 214)
- ⚠️ Defer JavaScript loading (line 235)

**Cache Utility Function**:
```php
// Cache expensive database queries
$popular_posts = voltmont_cache_query('popular_posts', function() {
    // Your expensive query here
    return get_posts(['orderby' => 'comment_count']);
}, 3600); // Cache for 1 hour
```

**Clear All Caches**:
```php
voltmont_clear_all_caches();
```

**⚠️ Warning**: Test optional features on staging before production!

---

### 5. `faq-schema.php`
**FAQ Schema with Admin Interface**

Generates FAQPage schema.org markup for Google rich results.

**Automatic FAQ Detection**:
- BeTheme accordion shortcodes
- HTML heading + paragraph patterns (h3/h4 followed by p)

**Manual FAQ Entry**:
1. Edit any page in WordPress admin
2. Find "FAQ dla Schema.org" meta box
3. Click "Dodaj pytanie" to add question/answer pairs
4. Save page

**Programmatic Usage**:
```php
// Add FAQs via code
$faqs = array(
    array(
        'question' => 'Ile kosztuje instalacja elektryczna?',
        'answer' => 'Koszt zależy od rozmiaru projektu...'
    ),
    array(
        'question' => 'Jak długo trwa instalacja?',
        'answer' => 'Zazwyczaj 2-5 dni roboczych...'
    )
);
update_post_meta($post_id, '_voltmont_faqs', $faqs);
```

**Testing**:
- Use Google Rich Results Test: https://search.google.com/test/rich-results
- Check source code for `<script type="application/ld+json">` with FAQPage

---

### 6. `breadcrumbs.php`
**SEO-Friendly Breadcrumb Navigation**

Displays breadcrumb trail with Schema.org BreadcrumbList markup.

**Automatic Display**: Hooks into BeTheme's `mfn_hook_content_before` (before page content)

**Manual Display**:
```php
// In template file
echo voltmont_breadcrumbs();

// Or use shortcode
[voltmont_breadcrumbs separator=" > " show_current="yes"]
```

**Customization**:
```php
// Custom breadcrumb arguments
$breadcrumbs = voltmont_breadcrumbs(array(
    'separator' => '<span>/</span>',
    'home_title' => 'Start',
    'show_current' => true,
    'show_schema' => true,
    'container_class' => 'custom-breadcrumbs',
    'list_class' => 'custom-list'
));
```

**CSS Styling**:
```css
.voltmont-breadcrumbs {
    padding: 15px 0;
    font-size: 14px;
}
.breadcrumb-separator {
    margin: 0 8px;
    color: #999;
}
.breadcrumb-item.current {
    color: #4d81e9;
}
```

---

### 7. `cf7-validation.php`
**Contact Form 7 Enhanced Validation**

Custom validation rules for Polish market:

#### Validation Rules:
- ✅ **Polish phone numbers**: 9 digits, optional +48 prefix
- ✅ **Postal codes**: XX-XXX format (e.g., 55-100)
- ✅ **NIP tax ID**: 10 digits with checksum validation
- ✅ **Email**: Block disposable email domains
- ✅ **Message length**: 20-2000 characters
- ✅ **Honeypot**: Anti-spam hidden field
- ✅ **Time-based spam**: Minimum 3 seconds to submit

#### Contact Form 7 Setup:

**Phone Field**:
```
[tel* tel-123]
```

**Postal Code Field** (must contain "postal" or "kod" in name):
```
[text* kod-pocztowy]
```

**NIP Field** (must contain "nip" in name):
```
[text nip-firmy]
```

**Message Field** (must contain "message" or "wiadomosc" in name):
```
[textarea* your-message]
```

**Honeypot Field** (hidden with CSS):
```
[text voltmont_honeypot class:hidden]
```

**Error Messages**: Automatically translated to Polish

**UTM Tracking**: Automatically captures UTM parameters from URL and includes in email

**Testing**:
```bash
# Test invalid NIP
1234567890 → Error: "Nieprawidłowy numer NIP"

# Test invalid postal code
12345 → Error: "Nieprawidłowy kod pocztowy. Użyj formatu: XX-XXX"

# Test short message
"Test" → Error: "Wiadomość jest zbyt krótka. Napisz przynajmniej 20 znaków."
```

---

## 🚀 Installation

### Step 1: Include Files in functions.php

Add to `dist/wp-content/themes/hubag-child/functions.php`:

```php
/**
 * Load custom functionality modules
 */
$inc_files = array(
    'schema-localbusiness.php',  // Schema.org structured data
    'functions-seo.php',          // SEO meta tags & OpenGraph
    'custom-shortcodes.php',      // Reusable shortcodes
    'performance-optimization.php', // Performance utilities
    'faq-schema.php',             // FAQ schema with admin UI
    'breadcrumbs.php',            // Breadcrumbs with schema
    'cf7-validation.php',         // Contact Form 7 validation
);

foreach ($inc_files as $file) {
    $filepath = get_stylesheet_directory() . '/inc/' . $file;
    if (file_exists($filepath)) {
        require_once $filepath;
    }
}
```

### Step 2: Compile SCSS (if using sections-offer.scss)

```bash
# Navigate to theme directory
cd dist/wp-content/themes/hubag-child

# Compile SCSS to CSS
npm run build:css
# or manually:
sass assets/css/sections-offer.scss assets/css/sections-offer.css
```

### Step 3: Enqueue Compiled CSS

Add to `functions.php`:

```php
function voltmont_enqueue_custom_styles() {
    wp_enqueue_style(
        'voltmont-sections-offer',
        get_stylesheet_directory_uri() . '/assets/css/sections-offer.css',
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'voltmont_enqueue_custom_styles');
```

### Step 4: Clear Caches

```bash
# Clear WordPress caches
wp cache flush

# Or use the provided function
voltmont_clear_all_caches();
```

---

## 🧪 Testing Checklist

### Schema.org Validation
- [ ] Test with [Google Rich Results Test](https://search.google.com/test/rich-results)
- [ ] Verify LocalBusiness schema on homepage
- [ ] Check FAQPage schema on pages with FAQs
- [ ] Validate BreadcrumbList schema

### SEO Meta Tags
- [ ] Inspect page source for `<meta>` tags
- [ ] Test social sharing on Facebook (OpenGraph)
- [ ] Test social sharing on Twitter (Twitter Cards)
- [ ] Verify canonical URLs

### Shortcodes
- [ ] Test each shortcode in page editor
- [ ] Verify responsive display on mobile
- [ ] Check accessibility (WCAG 2.2 AA)

### Performance
- [ ] Run Google PageSpeed Insights
- [ ] Test with GTmetrix or WebPageTest
- [ ] Verify lazy loading works
- [ ] Check browser console for errors

### Contact Form 7 Validation
- [ ] Test invalid phone number → Should show error
- [ ] Test invalid postal code → Should show error
- [ ] Test invalid NIP → Should show error
- [ ] Test honeypot (fill hidden field) → Should block
- [ ] Test quick submission (<3 seconds) → Should block

### Breadcrumbs
- [ ] Verify breadcrumbs appear on all pages (except homepage)
- [ ] Check hierarchical navigation works
- [ ] Test schema markup in source code

---

## 🔧 Configuration

### Disable Specific Features

**Disable Breadcrumbs Auto-Display**:
```php
remove_action('mfn_hook_content_before', 'voltmont_display_breadcrumbs', 5);
```

**Disable SEO Meta Tags** (if using Yoast/Rank Math):
```php
remove_action('wp_head', 'voltmont_output_seo_tags', 1);
remove_action('wp_head', 'voltmont_output_opengraph_tags', 2);
```

**Disable Schema.org Output**:
```php
remove_action('wp_head', 'voltmont_output_localbusiness_schema', 5);
```

**Disable CF7 Validation for Specific Field**:
```php
remove_filter('wpcf7_validate_tel', 'voltmont_validate_phone', 10);
```

---

## 📦 Dependencies

- **WordPress**: 6.4+
- **PHP**: 8.0+
- **BeTheme**: Latest version
- **Contact Form 7**: 5.8+ (for cf7-validation.php)
- **Font Awesome**: 6.x (for shortcode icons)

**Optional**:
- **SCSS Compiler**: For sections-offer.scss compilation
- **Redis/Memcached**: For enhanced caching performance

---

## 🐛 Troubleshooting

### Schema.org Not Appearing
```php
// Check if function exists
if (function_exists('voltmont_output_localbusiness_schema')) {
    echo 'Schema function loaded';
}

// Manually trigger output
do_action('wp_head');
```

### Breadcrumbs Not Showing
- Verify BeTheme hook exists: Check if `mfn_hook_content_before` is called in theme
- Check CSS: Breadcrumbs might be hidden with `display: none`
- Test manual display with shortcode

### Performance Features Not Working
- Check server configuration (mod_headers, mod_expires)
- Verify cache plugins aren't conflicting
- Uncomment optional features in performance-optimization.php

### Contact Form 7 Validation Issues
- Ensure field names match patterns (e.g., "nip" in field name for NIP validation)
- Check Contact Form 7 version (5.8+)
- Verify JavaScript is not disabled

---

## 📝 Development Notes

### Code Standards
- **PHP**: PSR-12 with WordPress Coding Standards
- **CSS**: BEM methodology
- **Accessibility**: WCAG 2.2 AA compliant
- **Security**: All inputs sanitized, outputs escaped

### Hooks & Filters
All functions use WordPress hooks and can be modified via:
- `add_filter()` to change output
- `remove_action()` to disable features
- `do_action()` to trigger manually

### Performance Considerations
- All schema functions cache results with transients
- Database queries are optimized with `no_found_rows`
- Images use native lazy loading
- Scripts can be deferred for faster page load

---

## 🔐 Security

- ✅ All user inputs sanitized with `sanitize_text_field()`, `sanitize_textarea_field()`, `sanitize_email()`
- ✅ All outputs escaped with `esc_html()`, `esc_attr()`, `esc_url()`
- ✅ Nonce verification for admin forms
- ✅ Capability checks with `current_user_can()`
- ✅ SQL injection prevention with `$wpdb->prepare()`
- ✅ XSS protection with `wp_kses_post()`

---

## 📚 Additional Resources

- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- [Schema.org Documentation](https://schema.org/)
- [Contact Form 7 Developer Guide](https://contactform7.com/docs/)
- [BeTheme Documentation](https://themes.muffingroup.com/betheme/documentation/)
- [Google Rich Results Guidelines](https://developers.google.com/search/docs/appearance/structured-data)

---

## ✅ Quick Start Summary

1. **Include all files** in `functions.php` (see Step 1)
2. **Compile SCSS** to CSS (if using)
3. **Clear caches** with `wp cache flush`
4. **Test schema** with Google Rich Results Test
5. **Configure Contact Form 7** with proper field names
6. **Add shortcodes** to pages as needed
7. **Monitor performance** with PageSpeed Insights

---

**Version**: 2.0.0
**Last Updated**: 2024-01-25
**Maintainer**: Voltmont Development Team

For questions or issues, refer to `CONTRIBUTING.md` in the project root.
