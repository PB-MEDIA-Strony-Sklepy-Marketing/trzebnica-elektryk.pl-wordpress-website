# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

WordPress production website for **Voltmont - Instalacje Elektryczne** (trzebnica-elektryk.pl), an electrical services company based in Trzebnica, Lower Silesia, Poland. Built on commercial BeTheme with custom parent and child theme implementations.

**Stack:** WordPress 6.4+, PHP 8.0+, BeTheme with Muffin Builder, Webpack build system

## Directory Structure

```
dist/wp-content/themes/
  ├── hubag/          # Production parent theme (based on BeTheme)
  └── hubag-child/    # All customizations (PRIMARY WORKING DIRECTORY)

src/wp-content/themes/
  ├── betheme/        # Original BeTheme source
  └── betheme-child/  # Original BeTheme child

docs/
  ├── BRIEF-PROJECT.md              # Business requirements & SEO strategy
  ├── FULL-BRAND-COLORS.scss        # Complete brand design system
  └── KOLORYSTYKA-ROOT-BRAND-COLOR-CSS.md
```

**Always work in `dist/wp-content/themes/hubag-child/` for production code.**

## Development Commands

### Asset Building (Webpack)
```bash
# Development with watch
npm run dev

# Production build
npm run build
npm run build:production

# Watch mode
npm run watch
```

### Code Quality
```bash
# Lint everything
npm run lint

# Individual linters
npm run lint:js
npm run lint:css

# Format code
npm run format

# PHP linting (requires php-cs-fixer globally)
php-cs-fixer fix
```

### Testing
```bash
# Unit tests
npm test
npm run test:coverage

# E2E tests
npm run test:e2e          # Interactive
npm run test:e2e:headless # Headless

# Accessibility tests
npm run test:a11y

# Lighthouse
npm run test:lighthouse
```

### WordPress Commands
```bash
# Update WordPress core and plugins
npm run wp:update

# Database backup
npm run wp:backup

# Optimize database
npm run wp:optimize
```

### Deployment
```bash
# Pre-deployment check
npm run preflight

# Deploy to staging
npm run deploy:staging

# Deploy to production
npm run deploy:production
```

## Architecture & Key Patterns

### BeTheme Integration
- **Muffin Builder**: Primary page builder system
- **Custom hooks**: `mfn_hook_top`, `mfn_hook_content_before`, `mfn_hook_bottom`
- **Template overrides**: Place same-named files in child theme to override parent
- **Shortcodes**: BeTheme shortcodes available throughout

### Asset Loading Strategy
All custom CSS/JS in `hubag-child/` loaded via `functions.php`:
- Bootstrap Grid + W3.CSS framework loaded with cache busting
- Brand system CSS (`assets/css/brand-system.css`) loaded first
- Custom scripts loaded in footer with async/defer attributes
- Cookie consent and iframe manager loaded from `/custom/` directory

### Custom Post Types & Taxonomies
```php
// Portfolio system
'portfolio'              // CPT with slug: galeria-realizacji
  ├── 'portfolio-types'  // Taxonomy: galeria-realizacji-kategoria
  └── 'portfolio-tags'   // Taxonomy: galeria-realizacji-tagi

// Page taxonomies (for offer pages)
'page_category'          // Kategorie stron oferta
'page_tag'               // Tagi stron oferta
```

### Key Functions in functions.php
- `child_theme_global_excerpt_or_term_description()` - Displays excerpt/term description with Font Awesome icon
- `voltmont_redirect_404_to_home()` - Redirects all 404s to homepage (301)
- `rd_duplicate_post_as_draft()` - Post/page duplication functionality
- `kodydobody()` - Injects emergency contact bar and social widgets via `mfn_hook_bottom`

### Brand System

**CSS Variables Location:** `dist/wp-content/themes/hubag-child/assets/css/brand-system.css`

**Primary Colors:**
```css
--color-primary: #4d81e9    /* Vibrant blue */
--color-secondary: #041028  /* Deep navy */
--color-text: #edf0fd       /* Light blue-white */
--color-bg: #163162         /* Dark blue */
--color-hover: #2a54a1      /* Interactive blue */
```

**Typography:** Modern sans-serif (Inter/Poppins family)

**Accessibility:** WCAG 2.2 AA compliance mandatory - verify color contrast ratios

## Coding Standards

### PHP (PSR-12)
```bash
# Check standards
php-cs-fixer fix --dry-run

# Auto-fix
php-cs-fixer fix
```

### CSS/SCSS (BEM + WordPress)
- Use BEM methodology for class naming
- Stylelint config: `.stylelintrc.json`
- WordPress-specific CSS standards applied

### JavaScript (ESLint + WordPress)
- Config: `.eslint.config.mjs`
- Use `@wordpress/*` packages for block/editor code
- Prefer async/defer for script loading

### Security Practices
- **Always escape output**: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()`
- **Always sanitize input**: `sanitize_text_field()`, `sanitize_email()`, `absint()`
- **Database queries**: Use `$wpdb->prepare()` with placeholders
- **Nonces**: Verify with `wp_verify_nonce()` for forms/AJAX
- **Capabilities**: Check with `current_user_can()` for protected actions

See `instructions/wordpress.instructions.md` for complete WordPress security guidelines.

## SEO Implementation

### Target Keywords
- elektryk Trzebnica
- instalacje elektryczne Dolny Śląsk
- smart home Wrocław
- modernizacja instalacji elektrycznych
- instalacje odgromowe Trzebnica

### Meta Requirements
- **Title tags**: 50-60 characters
- **Meta descriptions**: 150-160 characters
- **OpenGraph**: Complete OG tags for social sharing
- **Schema.org**: LocalBusiness + Service + FAQPage JSON-LD

### Schema.org Structure
For service pages, implement:
```json
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Voltmont - Instalacje Elektryczne",
  "telephone": "+48 691 594 820",
  "email": "biuro@trzebnica-elektryk.pl",
  "address": {
    "@type": "PostalAddress",
    "addressRegion": "Dolnośląskie"
  },
  "areaServed": "Dolny Śląsk"
}
```

### Internal Linking Strategy
Cross-link between service pages (e.g., "instalacje odgromowe" ↔ "modernizacja instalacji" ↔ "nadzór elektryczny")

## Performance Targets

- **LCP**: < 2.5s
- **FID**: < 100ms
- **CLS**: < 0.1
- **Page Size**: < 2MB
- **HTTP Requests**: < 50

### Optimization Techniques
- Lazy loading enabled by default (WordPress 5.5+)
- WebP images supported (MIME type registered)
- CSS/JS minification via Webpack
- Database query optimization
- Transient caching for expensive operations

## CI/CD Workflows

### GitHub Actions
- `ci-wordpress.yml` - PHP linting & WordPress standards
- `lint-and-style.yml` - ESLint + Stylelint
- `pagespeed-monitor.yml` - Lighthouse monitoring
- `security.yml` - Security scanning
- `seo-monitor.yml` - SEO health checks

All workflows run on push/PR to main branch.

## Common Customizations

### Adding New Service Page
1. Create page in WordPress admin
2. Assign to `page_category` taxonomy (kategorie-oferty)
3. Add excerpt (displays with icon via `mfn_hook_content_before`)
4. Implement LocalBusiness + Service schema in page template
5. Add internal links to related services

### Modifying BeTheme Styles
1. Work in `hubag-child/style.css` or `assets/css/`
2. Use brand system variables from `brand-system.css`
3. Avoid `!important` - use specificity instead
4. Test across all breakpoints (320px - 2560px)

### Adding Custom JavaScript
```php
// In hubag-child/functions.php
wp_enqueue_script(
    'custom-feature',
    get_stylesheet_directory_uri() . '/assets/js/custom-feature.js',
    array('jquery'),
    '1.0.0',
    true // Load in footer
);
```

### BeTheme Hook Usage
```php
// Header area
add_action('mfn_hook_top', 'custom_header_function');

// Before content
add_action('mfn_hook_content_before', 'custom_before_content');

// Footer area
add_action('mfn_hook_bottom', 'custom_footer_function');
```

## Important Notes

### Comments Disabled Site-Wide
All commenting functionality is completely disabled via `functions.php`. Do not attempt to re-enable without discussing with client.

### User Access Restrictions
- Non-admin users cannot see/access:
  - WordPress core updates
  - Plugin management
  - Theme management
- Only user ID 1 (primary admin) has full access

### Portfolio System
- URLs use custom slugs (not default BeTheme slugs)
- Single portfolio: `/galeria-realizacji/{slug}`
- Category archive: `/galeria-realizacji-kategoria/{slug}`
- Tag archive: `/galeria-realizacji-tagi/{slug}`
- After changing permalinks, flush rewrite rules

### Custom Shortcodes
- `[podoferta_shortcode]` - Displays sub-offer section (uses `podoferta.php` template)

## Business Context

**Client:** Voltmont - Instalacje Elektryczne
**Contact:** +48 691 594 820, biuro@trzebnica-elektryk.pl
**Service Area:** Trzebnica and Dolnośląskie województwo (Lower Silesia)

**Services:**
- Comprehensive electrical installation projects
- Electrical supervision for industrial/commercial facilities
- Basic & specialized installations (RTV, Internet, Intercoms, Alarms)
- Smart home systems (SMART)
- Lightning protection systems
- Intercom installations
- Complete WLZ (internal supply lines) replacement
- Modernization of old installations in residential buildings

**Target Audience:** Residential clients, developers, building administrators in Lower Silesia region

## Design Philosophy

**Premium Appearance:** All custom components should have professional, modern aesthetics
**Smooth Interactions:** Use CSS transitions (0.3s ease) for hover states and animations
**Mobile-First:** Responsive design starting from 320px width
**Accessibility First:** All interactive elements must be keyboard accessible with visible focus states
**Local SEO Focus:** Emphasize location-based keywords and local business schema