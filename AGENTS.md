# AI Agents Configuration – trzebnica-elektryk.pl

**Configuration and guidelines for AI development assistants**

---

## Overview

This document provides configuration and context for AI coding assistants (Claude, GitHub Copilot, ChatGPT, etc.) working on the Voltmont WordPress project.

---

## Primary Agent: Claude Code

### Configuration File

Primary instructions are in: `CLAUDE.md`

### Agent Role

Claude acts as a senior WordPress developer with expertise in:
- PHP 8.0+ and WordPress 6.4+
- BeTheme customization
- Security best practices
- Performance optimization
- SEO implementation
- Accessibility (WCAG 2.2 AA)

### Working Directory

**Always work in:** `dist/wp-content/themes/hubag-child/`

### Key Constraints

1. **Security First:** Always sanitize input, escape output
2. **WordPress Standards:** Follow WP Coding Standards
3. **No Breaking Changes:** Minimize modifications to working code
4. **Documentation:** Update relevant docs when making architectural changes
5. **Testing:** Verify changes don't break existing functionality

---

## GitHub Copilot

### Configuration File

Instructions: `copilot-instructions.md`

### Use Cases

- **Code completion:** Function implementations, WordPress hooks
- **Pattern matching:** Similar code structures within the project
- **Quick fixes:** Common WordPress security patterns
- **Documentation:** PHPDoc and inline comments

### Context Awareness

Copilot is aware of:
- WordPress core functions
- BeTheme hooks and functions
- Project-specific prefixes (`voltmont_`)
- Brand system CSS variables
- Security patterns (nonce verification, escaping)

---

## ChatGPT / GPT-4

### Recommended Prompts

**For new features:**
```
I'm working on a WordPress site (trzebnica-elektryk.pl) for an electrical services company. 
The site uses BeTheme with a custom child theme (hubag-child).

Task: [Describe feature]

Requirements:
- Use voltmont_ prefix for functions
- Follow WordPress Coding Standards
- Implement proper security (sanitization, escaping, nonces)
- Include PHPDoc comments
- Output production-ready code

Context files to review:
- CLAUDE.md (project overview)
- SECURITY.md (security guidelines)
- ARCHITECTURE.md (system architecture)
```

**For debugging:**
```
I'm debugging an issue on trzebnica-elektryk.pl (WordPress + BeTheme + hubag-child theme).

Issue: [Describe problem]
Error message: [If any]
Expected behavior: [What should happen]
Actual behavior: [What's happening]

Code snippet:
[Paste relevant code]

Please suggest a fix following WordPress best practices and security guidelines from SECURITY.md.
```

---

## Agent Memory / Context

### Essential Project Facts

**Client:**
- Name: Voltmont - Instalacje Elektryczne
- Location: Trzebnica, Dolny Śląsk, Poland
- Phone: +48 691 594 820
- Email: biuro@trzebnica-elektryk.pl
- Website: https://trzebnica-elektryk.pl

**Technology Stack:**
- WordPress 6.4+
- PHP 8.0+
- BeTheme (commercial)
- hubag-child (custom child theme)
- MySQL 8.0+
- Webpack 5.x (build system)

**Services Offered:**
- Electrical installations
- Smart home systems
- Lightning protection
- Electrical supervision
- WLZ replacement
- Modernization of old installations

**Target Keywords:**
- elektryk Trzebnica
- instalacje elektryczne Dolny Śląsk
- smart home Wrocław
- modernizacja instalacji elektrycznych
- instalacje odgromowe Trzebnica

**Brand Colors:**
- Primary: #4d81e9 (vibrant blue)
- Secondary: #041028 (deep navy)
- Text: #edf0fd (light blue-white)
- Background: #163162 (dark blue)
- Hover: #2a54a1 (interactive blue)

---

## Task-Specific Agents

### SEO Agent

**Focus:** Search engine optimization

**Context:**
- Target keywords in `SEO-STRATEGY.md`
- Schema.org templates for LocalBusiness, Service, FAQPage
- Meta tag requirements (50-60 char titles, 150-160 char descriptions)
- Internal linking strategy

**Output Format:**
```php
// Schema.org JSON-LD
// Meta tags
// OpenGraph tags
// Twitter Cards
```

### Security Agent

**Focus:** Security hardening and vulnerability prevention

**Context:**
- Security guidelines in `SECURITY.md`
- OWASP Top 10 compliance
- WordPress-specific vulnerabilities
- Input validation and output escaping

**Output Format:**
```php
// Sanitization examples
// Escaping examples
// Capability checks
// Nonce verification
```

### Performance Agent

**Focus:** Speed optimization

**Context:**
- Target metrics: LCP < 2.5s, FID < 100ms, CLS < 0.1
- Caching strategies (transients, page cache)
- Database query optimization
- Asset optimization (minification, lazy loading)

**Output Format:**
```php
// Optimized queries
// Transient implementation
// Lazy loading setup
```

### Accessibility Agent

**Focus:** WCAG 2.2 AA compliance

**Context:**
- Color contrast requirements (4.5:1 for text)
- Keyboard navigation
- Screen reader compatibility
- ARIA labels
- Focus indicators

**Output Format:**
```html
<!-- Semantic HTML -->
<!-- ARIA labels -->
<!-- Alt text -->
```

```css
/* Focus styles */
/* Color contrast verification */
```

---

## Agent Collaboration

### Multi-Agent Workflow

**Example: Adding New Service Page**

1. **SEO Agent:** Generate schema.org markup, meta tags
2. **Security Agent:** Review for vulnerabilities, add sanitization
3. **Accessibility Agent:** Verify WCAG compliance, add ARIA labels
4. **Performance Agent:** Optimize queries, implement caching
5. **Primary Agent (Claude):** Integrate all components, test, document

### Communication Protocol

**Between Agents:**
```markdown
## Task: [Task name]
## Agent: [Agent type]
## Output:
[Code/Documentation]

## Notes:
- [Any caveats or considerations]
- [Dependencies on other agents]
- [Testing recommendations]

## Next Agent:
[Which agent should review/continue]
```

---

## Common Agent Patterns

### Pattern 1: Adding Custom Post Type

```php
/**
 * Register custom post type
 *
 * @since 2.0.0
 */
function voltmont_register_cpt() {
    register_post_type( 'service', array(
        'labels' => array(
            'name' => __( 'Services', 'voltmont' ),
            'singular_name' => __( 'Service', 'voltmont' ),
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array( 'slug' => 'uslugi' ),
        'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-hammer',
    ) );
}
add_action( 'init', 'voltmont_register_cpt' );
```

### Pattern 2: AJAX Handler

```php
/**
 * AJAX handler for contact form
 *
 * @since 2.0.0
 */
function voltmont_ajax_contact_form() {
    // Security check
    check_ajax_referer( 'voltmont-contact-nonce', 'nonce' );
    
    // Sanitize input
    $name = sanitize_text_field( $_POST['name'] );
    $email = sanitize_email( $_POST['email'] );
    $message = sanitize_textarea_field( $_POST['message'] );
    
    // Validate
    if ( empty( $name ) || empty( $email ) || ! is_email( $email ) ) {
        wp_send_json_error( array(
            'message' => 'Please fill all required fields correctly.',
        ) );
    }
    
    // Process (send email, save to DB, etc.)
    $result = wp_mail( 
        'biuro@trzebnica-elektryk.pl', 
        'New Contact Form Submission', 
        $message 
    );
    
    if ( $result ) {
        wp_send_json_success( array(
            'message' => 'Thank you! We will contact you soon.',
        ) );
    } else {
        wp_send_json_error( array(
            'message' => 'Something went wrong. Please try again.',
        ) );
    }
}
add_action( 'wp_ajax_voltmont_contact', 'voltmont_ajax_contact_form' );
add_action( 'wp_ajax_nopriv_voltmont_contact', 'voltmont_ajax_contact_form' );
```

### Pattern 3: Schema.org Output

```php
/**
 * Output LocalBusiness schema
 *
 * @since 2.0.0
 */
function voltmont_output_local_business_schema() {
    if ( ! is_front_page() ) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'LocalBusiness',
        'name' => 'Voltmont - Instalacje Elektryczne',
        'url' => home_url(),
        'telephone' => '+48 691 594 820',
        'email' => 'biuro@trzebnica-elektryk.pl',
        'address' => array(
            '@type' => 'PostalAddress',
            'addressLocality' => 'Trzebnica',
            'addressRegion' => 'Dolnośląskie',
            'addressCountry' => 'PL',
        ),
        'geo' => array(
            '@type' => 'GeoCoordinates',
            'latitude' => '51.3096',
            'longitude' => '17.0628',
        ),
        'areaServed' => array(
            '@type' => 'State',
            'name' => 'Dolnośląskie',
        ),
    );
    
    echo '<script type="application/ld+json">';
    echo wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
    echo '</script>' . "\n";
}
add_action( 'wp_head', 'voltmont_output_local_business_schema' );
```

---

## Agent Testing Protocol

### Before Proposing Changes

1. ✅ **Syntax Check:** PHP linter passes (`php -l`)
2. ✅ **Standards Check:** Follows WordPress Coding Standards
3. ✅ **Security Check:** Input sanitized, output escaped
4. ✅ **Performance Check:** No N+1 queries, transients used
5. ✅ **Accessibility Check:** Proper ARIA labels, semantic HTML
6. ✅ **Documentation:** PHPDoc comments included

### Testing Commands

```bash
# Lint PHP
php -l file.php
php-cs-fixer fix --dry-run

# Lint CSS/JS
npm run lint

# Run tests
npm test

# Build production
npm run build:production
```

---

## Agent Error Handling

### Common Errors

**1. Direct Database Access:**
```php
// ❌ Wrong
$results = $wpdb->get_results( "SELECT * FROM wp_posts WHERE post_title = '$title'" );

// ✅ Correct
$results = $wpdb->get_results( $wpdb->prepare( 
    "SELECT * FROM $wpdb->posts WHERE post_title = %s", 
    $title 
) );
```

**2. Unescaped Output:**
```php
// ❌ Wrong
echo $user_input;
echo '<div>' . $_POST['data'] . '</div>';

// ✅ Correct
echo esc_html( $user_input );
echo '<div>' . esc_html( sanitize_text_field( $_POST['data'] ) ) . '</div>';
```

**3. Missing Nonce Verification:**
```php
// ❌ Wrong
if ( isset( $_POST['action'] ) ) {
    // Process form
}

// ✅ Correct
if ( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'my_action' ) ) {
    // Process form
}
```

---

## Agent Resources

### Documentation

- **Project Docs:** `/docs/` directory
- **WordPress Codex:** https://codex.wordpress.org/
- **PHP Manual:** https://www.php.net/manual/
- **BeTheme Docs:** `/docs/documentation/`

### Code Examples

- **Theme Functions:** `dist/wp-content/themes/hubag-child/functions.php`
- **Schema Implementations:** `dist/wp-content/themes/hubag-child/inc/schema-localbusiness.php`
- **Custom Post Types:** `dist/wp-content/themes/hubag-child/inc/custom-post-types.php`

---

## Agent Version Control

### Commit Message Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

**Types:**
- `feat` – New feature
- `fix` – Bug fix
- `refactor` – Code refactoring
- `style` – CSS/styling
- `perf` – Performance improvement
- `docs` – Documentation
- `test` – Testing

**Example:**
```
feat(schema): implement FAQ schema for service pages

- Add FAQPage schema.org markup
- Generate questions from ACF fields
- Include on all service pages
- Validate with Google Rich Results Test

Closes #45
```

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintained by:** PB-MEDIA AI Team
