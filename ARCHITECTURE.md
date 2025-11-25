# Architecture Documentation – trzebnica-elektryk.pl

**Voltmont - Instalacje Elektryczne**  
System architecture and technical design

---

## Table of Contents

1. [System Overview](#system-overview)
2. [Technology Stack](#technology-stack)
3. [Directory Structure](#directory-structure)
4. [WordPress Architecture](#wordpress-architecture)
5. [BeTheme Integration](#betheme-integration)
6. [Asset Pipeline](#asset-pipeline)
7. [Database Schema](#database-schema)
8. [Security Architecture](#security-architecture)
9. [Performance Architecture](#performance-architecture)
10. [Third-Party Integrations](#third-party-integrations)

---

## System Overview

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                         Frontend (Client)                    │
│  ┌─────────────┐  ┌──────────────┐  ┌──────────────┐       │
│  │  HTML/CSS   │  │  JavaScript  │  │   Images     │       │
│  │  (BeTheme)  │  │  (Webpack)   │  │   (WebP)     │       │
│  └─────────────┘  └──────────────┘  └──────────────┘       │
└──────────────────────────┬──────────────────────────────────┘
                           │ HTTPS
┌──────────────────────────▼──────────────────────────────────┐
│                    Web Server (Apache/Nginx)                 │
│                       SSL/TLS Termination                    │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│                     WordPress Core (PHP 8.0+)                │
│  ┌────────────────┐  ┌──────────────┐  ┌────────────────┐  │
│  │  BeTheme       │  │  hubag-child │  │   Plugins      │  │
│  │  (Parent)      │  │  (Custom)    │  │   (Essential)  │  │
│  └────────────────┘  └──────────────┘  └────────────────┘  │
└──────────────────────────┬──────────────────────────────────┘
                           │
┌──────────────────────────▼──────────────────────────────────┐
│                     MySQL Database                           │
│                      (Optimized)                             │
└─────────────────────────────────────────────────────────────┘
```

### Request Flow

```
User Request
    │
    ▼
[CDN/Cloudflare] (Optional)
    │
    ▼
[Web Server]
    │
    ├─→ Static assets (CSS/JS/Images) → Cached → Response
    │
    ▼
[PHP-FPM / mod_php]
    │
    ▼
[WordPress Core]
    │
    ├─→ Cache hit (WP Super Cache) → Response
    │
    ▼
[Theme Processing]
    │   ├─→ BeTheme (Parent)
    │   └─→ hubag-child (Child)
    │
    ▼
[Database Query]
    │   ├─→ Posts/Pages
    │   ├─→ Options
    │   ├─→ Custom Post Types
    │   └─→ Taxonomies
    │
    ▼
[HTML Generation]
    │   ├─→ Header
    │   ├─→ Content
    │   └─→ Footer
    │
    ▼
[Response] → Cache → User
```

---

## Technology Stack

### Core Technologies

**Backend:**
- **WordPress:** 6.4+ (CMS)
- **PHP:** 8.0+ (Server-side language)
- **MySQL:** 8.0+ (Database)
- **Composer:** 2.x (PHP dependency management)

**Frontend:**
- **HTML5:** Semantic markup
- **CSS3:** Modern styling with CSS Variables
- **JavaScript:** ES6+ (Babel transpiled)
- **Bootstrap Grid:** Responsive layout
- **W3.CSS:** Additional utilities
- **Font Awesome:** Icon library

**Build Tools:**
- **Webpack:** 5.x (Module bundler)
- **Babel:** ES6+ to ES5 transpilation
- **PostCSS:** CSS processing
- **Autoprefixer:** Browser prefixes
- **Terser:** JavaScript minification
- **CSS Minimizer:** CSS minification

**Development:**
- **ESLint:** JavaScript linting
- **Stylelint:** CSS linting
- **PHP_CodeSniffer:** PHP linting
- **PHP-CS-Fixer:** PHP code formatting
- **Jest:** Unit testing
- **Cypress:** E2E testing

### WordPress Ecosystem

**Theme:**
- **BeTheme:** Commercial theme (parent)
- **hubag:** Production parent theme
- **hubag-child:** Custom child theme

**Essential Plugins:**
- **Wordfence:** Security
- **WP Super Cache:** Caching
- **Contact Form 7:** Forms
- **Yoast SEO / All in One SEO:** SEO
- **Redirection:** URL redirects
- **UpdraftPlus:** Backups
- **WP Mail SMTP:** Email delivery
- **Google Site Kit:** Analytics integration

---

## Directory Structure

```
trzebnica-elektryk.pl-wordpress-website/
│
├── .github/                      # GitHub workflows and templates
│   ├── workflows/
│   │   ├── ci-wordpress.yml
│   │   ├── lint-and-style.yml
│   │   ├── pagespeed-monitor.yml
│   │   ├── security.yml
│   │   └── seo-monitor.yml
│   ├── ISSUE_TEMPLATE/
│   ├── PULL_REQUEST_TEMPLATE.md
│   └── dependabot.yml
│
├── dist/                         # Production-ready code
│   └── wp-content/
│       ├── themes/
│       │   ├── hubag/           # Production parent theme
│       │   └── hubag-child/     # Custom child theme (PRIMARY)
│       │       ├── assets/
│       │       │   ├── css/
│       │       │   │   ├── brand-system.css
│       │       │   │   └── custom-styles.css
│       │       │   ├── js/
│       │       │   │   ├── custom-scripts.js
│       │       │   │   └── modules/
│       │       │   └── images/
│       │       ├── inc/
│       │       │   ├── schema-localbusiness.php
│       │       │   ├── custom-post-types.php
│       │       │   └── custom-taxonomies.php
│       │       ├── template-parts/
│       │       ├── functions.php  # Main theme functions
│       │       ├── style.css
│       │       └── screenshot.png
│       │
│       └── plugins/              # WordPress plugins
│
├── src/                          # Original source code
│   └── wp-content/
│       ├── themes/
│       │   ├── betheme/         # Original BeTheme
│       │   └── betheme-child/   # Original BeTheme child
│       └── plugins/
│
├── docs/                         # Documentation
│   ├── BRIEF-PROJECT.md
│   ├── FULL-BRAND-COLORS.scss
│   ├── KOLORYSTYKA-ROOT-BRAND-COLOR-CSS.md
│   └── documentation/           # BeTheme documentation
│
├── custom/                       # Custom standalone scripts
│   ├── cookie-consent.js
│   └── iframe-manager.js
│
├── instructions/                 # Development instructions
│   └── wordpress.instructions.md
│
├── prompts/                      # AI prompts and templates
│
├── .editorconfig                # Editor configuration
├── .eslint.config.mjs           # ESLint configuration
├── .gitignore
├── .markdownlint.json
├── .php-cs-fixer.dist.php       # PHP CS Fixer configuration
├── .stylelintrc.json            # Stylelint configuration
├── composer.json                # PHP dependencies
├── package.json                 # Node.js dependencies
├── webpack.config.js            # Webpack configuration
├── README.md
├── CLAUDE.md                    # AI assistant guidelines
├── CONTRIBUTING.md
├── SEO-STRATEGY.md
├── SECURITY.md
├── DESIGN-SYSTEM.md
├── DEPLOYMENT.md
└── ARCHITECTURE.md (this file)
```

### Key Directories Explained

**`dist/wp-content/themes/hubag-child/`**
- **Primary working directory** for all customizations
- All custom CSS, JavaScript, PHP
- Templates, shortcodes, custom post types

**`src/wp-content/themes/betheme/`**
- Original BeTheme source (reference only)
- Do not modify directly

**`docs/`**
- Brand guidelines
- Design system documentation
- Project briefs

**`custom/`**
- Standalone scripts (cookie consent, iframe manager)
- Loaded independently from theme

---

## WordPress Architecture

### Child Theme Implementation

**Inheritance Chain:**
```
WordPress Core
    ↓
BeTheme (Parent)
    ↓
hubag (Production Parent - modified BeTheme)
    ↓
hubag-child (Custom Child Theme)
```

**Template Hierarchy:**

```php
// WordPress searches in this order:
1. hubag-child/page-{slug}.php
2. hubag-child/page-{id}.php
3. hubag-child/page.php
4. hubag/page.php
5. hubag-child/singular.php
6. hubag/singular.php
7. hubag-child/index.php
8. hubag/index.php
```

### Custom Post Types

**Portfolio:**
```php
// Registration in inc/custom-post-types.php
register_post_type('portfolio', array(
    'labels' => array(
        'name' => 'Galeria Realizacji',
        'singular_name' => 'Realizacja',
    ),
    'public' => true,
    'has_archive' => true,
    'rewrite' => array('slug' => 'galeria-realizacji'),
    'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
    'menu_icon' => 'dashicons-images-alt2',
    'show_in_rest' => true,
));
```

**Taxonomies:**
```php
// Portfolio Categories
register_taxonomy('portfolio-types', 'portfolio', array(
    'labels' => array(
        'name' => 'Kategorie Realizacji',
        'singular_name' => 'Kategoria',
    ),
    'hierarchical' => true,
    'rewrite' => array('slug' => 'galeria-realizacji-kategoria'),
    'show_in_rest' => true,
));

// Page Categories (for offer pages)
register_taxonomy('page_category', 'page', array(
    'labels' => array(
        'name' => 'Kategorie Stron',
        'singular_name' => 'Kategoria Strony',
    ),
    'hierarchical' => true,
    'rewrite' => array('slug' => 'kategorie-oferty'),
));
```

### Hook System

**BeTheme Hooks:**
```php
// Available hooks in BeTheme
do_action('mfn_hook_top');              // Before </head>
do_action('mfn_hook_content_before');   // Before main content
do_action('mfn_hook_content_after');    // After main content
do_action('mfn_hook_bottom');           // Before </body>
```

**Custom Implementations:**
```php
// functions.php
// Display excerpt with icon before content
add_action('mfn_hook_content_before', 'child_theme_global_excerpt_or_term_description');

// Add emergency contact bar and social widgets
add_action('mfn_hook_bottom', 'kodydobody');

// Custom 404 redirect
add_action('template_redirect', 'voltmont_redirect_404_to_home');
```

---

## BeTheme Integration

### Muffin Builder

**Page Builder System:**
- Visual drag-and-drop editor
- Custom sections and elements
- Responsive settings per element
- Built-in animations (AOS library)

**Custom Shortcodes:**
```php
// [podoferta_shortcode]
add_shortcode('podoferta_shortcode', 'sub_offer_shortcode_func');

function sub_offer_shortcode_func($atts) {
    ob_start();
    get_template_part('template-parts/podoferta');
    return ob_get_clean();
}
```

### BeTheme Options

**Theme Options Panel:**
- Accessible via: Dashboard → Theme Options
- Controls global settings
- Layout, colors, typography, etc.

**Child Theme Overrides:**
```php
// Override BeTheme options in child theme
function voltmont_override_theme_options($options) {
    $options['header-height'] = 80;
    $options['logo-height'] = 50;
    return $options;
}
add_filter('mfn_theme_options', 'voltmont_override_theme_options');
```

---

## Asset Pipeline

### Webpack Configuration

**Entry Points:**
```javascript
// webpack.config.js
module.exports = {
    entry: {
        main: './src/js/main.js',
        admin: './src/js/admin.js',
    },
    output: {
        path: path.resolve(__dirname, 'dist/assets/js'),
        filename: '[name].bundle.js',
    },
    // ...
};
```

**Build Process:**

```
Source Files (src/)
    │
    ├─→ JavaScript (ES6+)
    │    ├─→ Babel (Transpile to ES5)
    │    └─→ Terser (Minify)
    │
    ├─→ CSS/SCSS
    │    ├─→ Sass Compiler
    │    ├─→ PostCSS (Autoprefixer)
    │    └─→ CSS Minimizer
    │
    └─→ Images
         ├─→ Image Optimization
         └─→ WebP Conversion
    │
    ▼
Output (dist/)
```

### Asset Loading Strategy

**CSS Loading Order:**
```php
// functions.php
function voltmont_enqueue_styles() {
    // 1. Brand System (CSS Variables)
    wp_enqueue_style('voltmont-brand-system', 
        get_stylesheet_directory_uri() . '/assets/css/brand-system.css', 
        array(), 
        '1.0.0'
    );
    
    // 2. Bootstrap Grid
    wp_enqueue_style('bootstrap-grid', 
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-grid.min.css'
    );
    
    // 3. W3.CSS
    wp_enqueue_style('w3-css', 
        'https://www.w3schools.com/w3css/4/w3.css'
    );
    
    // 4. Child Theme Styles
    wp_enqueue_style('voltmont-child-style', 
        get_stylesheet_uri(), 
        array('voltmont-brand-system'), 
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'voltmont_enqueue_styles', 5);
```

**JavaScript Loading:**
```php
function voltmont_enqueue_scripts() {
    // jQuery (included with WordPress)
    // Loaded in header
    
    // Custom scripts (footer, async)
    wp_enqueue_script('voltmont-main', 
        get_stylesheet_directory_uri() . '/assets/js/main.bundle.js', 
        array('jquery'), 
        '1.0.0', 
        true // Load in footer
    );
    
    // Add async/defer attributes
    add_filter('script_loader_tag', 'voltmont_async_scripts', 10, 2);
}
add_action('wp_enqueue_scripts', 'voltmont_enqueue_scripts', 10);

function voltmont_async_scripts($tag, $handle) {
    $async_scripts = array('voltmont-main', 'google-analytics');
    
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async defer src', $tag);
    }
    
    return $tag;
}
```

### Cache Busting

**Version-Based:**
```php
// Use theme version for cache busting
wp_enqueue_style('voltmont-styles', 
    get_stylesheet_uri(), 
    array(), 
    wp_get_theme()->get('Version') // e.g., '2.0.0'
);
```

**File Hash-Based (Webpack):**
```javascript
// webpack.config.js
output: {
    filename: '[name].[contenthash:8].js',
    path: path.resolve(__dirname, 'dist/assets/js'),
}
```

---

## Database Schema

### WordPress Core Tables

**Standard Tables (wp_ prefix):**
- `wp_posts` – Posts, pages, custom post types
- `wp_postmeta` – Post metadata
- `wp_users` – User accounts
- `wp_usermeta` – User metadata
- `wp_terms` – Taxonomy terms
- `wp_term_taxonomy` – Taxonomy definitions
- `wp_term_relationships` – Post-term relationships
- `wp_options` – Site options
- `wp_comments` – Comments (disabled)
- `wp_commentmeta` – Comment metadata (unused)

**Custom Prefix:**
```php
// wp-config.php
$table_prefix = 'voltmont_'; // Changed from 'wp_' for security
```

### Custom Tables

**None currently** – Using WordPress core tables with custom post types and taxonomies.

**Future Consideration:**
```sql
-- Analytics table (if needed)
CREATE TABLE voltmont_analytics (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(50) NOT NULL,
    event_data TEXT,
    user_id BIGINT UNSIGNED,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_event_type (event_type),
    INDEX idx_created_at (created_at)
);
```

### Database Optimization

**Indexes:**
```sql
-- Add index on post_name for faster slug lookups
ALTER TABLE voltmont_posts ADD INDEX idx_post_name (post_name);

-- Index on meta_key for faster meta queries
ALTER TABLE voltmont_postmeta ADD INDEX idx_meta_key (meta_key);
```

**Automated Optimization:**
```bash
# Cron job (weekly)
0 3 * * 0 wp db optimize && wp transient delete --all
```

---

## Security Architecture

### Defense Layers

**1. Server Level:**
- Firewall (UFW/iptables)
- ModSecurity (WAF)
- Fail2Ban (brute force protection)
- SSL/TLS encryption

**2. Application Level:**
- WordPress security hardening
- Wordfence firewall
- Input validation/sanitization
- Output escaping
- CSRF protection (nonces)
- SQL injection prevention (prepared statements)

**3. Authentication:**
- Strong password policy
- Two-factor authentication (2FA)
- Login attempt limiting
- Session management

**4. File System:**
- Correct permissions (644 files, 755 directories)
- Disabled file editing (DISALLOW_FILE_EDIT)
- Protected wp-config.php (600 permissions)

**5. Database:**
- Minimum privilege database user
- Custom table prefix
- Regular backups
- Encrypted backups

### Security Headers

```apache
# .htaccess
<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:;"
</IfModule>
```

---

## Performance Architecture

### Caching Strategy

**Multi-Layer Caching:**

```
1. Browser Cache (Expire headers)
    ↓
2. CDN Cache (Cloudflare, optional)
    ↓
3. Page Cache (WP Super Cache)
    ↓
4. Object Cache (Transients, optional Redis)
    ↓
5. Database Query Cache (MySQL)
```

**Transients for Expensive Operations:**
```php
function voltmont_get_recent_portfolio() {
    $cache_key = 'voltmont_recent_portfolio';
    $cached = get_transient($cache_key);
    
    if (false !== $cached) {
        return $cached;
    }
    
    // Expensive query
    $portfolio = new WP_Query(array(
        'post_type' => 'portfolio',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    
    $results = $portfolio->posts;
    
    // Cache for 1 hour
    set_transient($cache_key, $results, HOUR_IN_SECONDS);
    
    return $results;
}
```

### Image Optimization

**Strategy:**
1. **Resize:** Max width 2000px
2. **Compress:** 80-85% quality
3. **Format:** WebP with JPEG fallback
4. **Lazy Loading:** Native WordPress lazy loading

**Implementation:**
```php
// Enable WebP support
function voltmont_enable_webp_upload($mimes) {
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'voltmont_enable_webp_upload');

// Lazy load images
add_filter('wp_lazy_loading_enabled', '__return_true');
```

### Code Splitting

**Webpack Configuration:**
```javascript
optimization: {
    splitChunks: {
        chunks: 'all',
        cacheGroups: {
            vendor: {
                test: /[\\/]node_modules[\\/]/,
                name: 'vendors',
                priority: 10,
            },
            common: {
                minChunks: 2,
                priority: 5,
                reuseExistingChunk: true,
            },
        },
    },
}
```

### Database Query Optimization

**Best Practices:**
```php
// ✅ Good - Specific fields
$posts = $wpdb->get_results($wpdb->prepare(
    "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = %s",
    'portfolio'
));

// ❌ Bad - SELECT *
$posts = $wpdb->get_results("SELECT * FROM $wpdb->posts");

// ✅ Good - Use transients for repeated queries
$popular_posts = get_transient('voltmont_popular_posts');
if (false === $popular_posts) {
    // Query here
    set_transient('voltmont_popular_posts', $result, DAY_IN_SECONDS);
}

// ✅ Good - Limit results
$args = array(
    'post_type' => 'post',
    'posts_per_page' => 10, // Not -1
);
```

---

## Third-Party Integrations

### Google Services

**Google Analytics 4:**
```html
<!-- Global site tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```

**Google Tag Manager:**
```html
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXXX');</script>
```

**Google Search Console:**
- Sitemap submission
- URL inspection
- Performance monitoring

### Social Media

**Facebook:**
- OpenGraph tags
- Facebook Pixel (via GTM)
- Share buttons

**Instagram:**
- Instagram feed widget
- Embed posts

### Email

**WP Mail SMTP:**
- SMTP configuration
- Email logging
- Delivery monitoring

### Forms

**Contact Form 7:**
- Contact forms
- Service inquiry forms
- Newsletter signup

---

## Diagrams

### Component Architecture

```
┌─────────────────────────────────────────────────────┐
│                  WordPress Core                      │
├─────────────────────────────────────────────────────┤
│                                                      │
│  ┌──────────────────────────────────────────────┐  │
│  │           BeTheme (Parent)                   │  │
│  │  ┌────────────────────────────────────────┐  │  │
│  │  │       hubag-child (Custom)             │  │  │
│  │  │                                        │  │  │
│  │  │  ┌──────────────────────────────────┐  │  │  │
│  │  │  │   Custom Post Types             │  │  │  │
│  │  │  │   - Portfolio                   │  │  │  │
│  │  │  └──────────────────────────────────┘  │  │  │
│  │  │                                        │  │  │
│  │  │  ┌──────────────────────────────────┐  │  │  │
│  │  │  │   Custom Taxonomies             │  │  │  │
│  │  │  │   - portfolio-types             │  │  │  │
│  │  │  │   - portfolio-tags              │  │  │  │
│  │  │  │   - page_category               │  │  │  │
│  │  │  └──────────────────────────────────┘  │  │  │
│  │  │                                        │  │  │
│  │  │  ┌──────────────────────────────────┐  │  │  │
│  │  │  │   Custom Functions              │  │  │  │
│  │  │  │   - SEO meta generation         │  │  │  │
│  │  │  │   - Schema.org markup           │  │  │  │
│  │  │  │   - Security hardening          │  │  │  │
│  │  │  └──────────────────────────────────┘  │  │  │
│  │  │                                        │  │  │
│  │  └────────────────────────────────────────┘  │  │
│  │                                              │  │
│  └──────────────────────────────────────────────┘  │
│                                                      │
├─────────────────────────────────────────────────────┤
│                    Plugins                           │
│  - Wordfence (Security)                             │
│  - WP Super Cache (Performance)                     │
│  - Contact Form 7 (Forms)                           │
│  - Yoast SEO (SEO)                                  │
│  - UpdraftPlus (Backups)                            │
└─────────────────────────────────────────────────────┘
```

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintained by:** PB-MEDIA Architecture Team

**Next Review:** 2024-07-15
