# Testing & Components Guide - trzebnica-elektryk.pl

**Complete guide to service templates, CSS components, and testing**

---

## 📋 Table of Contents

1. [Service Page Template](#service-page-template)
2. [CSS Components](#css-components)
3. [JavaScript Testing (Jest)](#javascript-testing-jest)
4. [PHP Testing (PHPUnit)](#php-testing-phpunit)
5. [Integration Testing](#integration-testing)

---

## 1. Service Page Template

### Created File

**`template-service.php`** - Professional service page template

### Features

✅ **SEO Optimized:**
- Schema.org Service markup
- Dynamic breadcrumbs with BreadcrumbList
- Proper heading hierarchy (H1 → H6)
- Meta descriptions from excerpt
- OpenGraph ready

✅ **Accessibility:**
- ARIA labels
- Keyboard navigation
- Focus management
- Screen reader friendly
- WCAG 2.2 AA compliant

✅ **Components:**
- Hero section with CTA
- Feature cards (4x grid)
- Content + sidebar layout
- Price card
- Contact card
- FAQ accordion
- Contact form
- Related services grid

✅ **Performance:**
- Lazy loading images
- Async decoding
- Minimal DOM operations
- Optimized queries

### Usage

1. **Assign template to page:**
   - WordPress Admin → Pages → Edit Page
   - Page Attributes → Template → "Service Page Template"

2. **Add custom fields:**
   ```php
   _service_duration → "2-5 dni"
   _service_price_from → 2500
   _service_price_note → "Cena zależy od zakresu prac"
   _faq_items → array of questions/answers
   ```

3. **Featured image:**
   - Recommended: 1200x630px
   - Format: WebP or JPEG
   - Alt text required

### Template Structure

```
template-service.php
├── Hero Section
│   ├── Breadcrumbs (schema.org)
│   ├── Title + Excerpt
│   ├── Meta info (duration, location)
│   ├── CTA buttons
│   └── Featured image
├── Features Grid (4 cards)
├── Main Content
│   ├── Article content
│   └── Sidebar
│       ├── Price card
│       └── Contact card
├── FAQ Section (if available)
├── CTA Section
├── Contact Form
└── Related Services (3x grid)
```

### Custom Fields Setup

Add to `functions.php`:

```php
function voltmont_service_meta_boxes() {
    add_meta_box(
        'service_details',
        'Service Details',
        'voltmont_service_details_callback',
        'page',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'voltmont_service_meta_boxes');

function voltmont_service_details_callback($post) {
    wp_nonce_field('voltmont_service_meta', 'voltmont_service_nonce');
    
    $duration = get_post_meta($post->ID, '_service_duration', true);
    $price_from = get_post_meta($post->ID, '_service_price_from', true);
    $price_note = get_post_meta($post->ID, '_service_price_note', true);
    
    ?>
    <p>
        <label>Duration:</label>
        <input type="text" name="service_duration" value="<?php echo esc_attr($duration); ?>" style="width:100%;">
    </p>
    <p>
        <label>Price From:</label>
        <input type="number" name="service_price_from" value="<?php echo esc_attr($price_from); ?>" style="width:100%;">
    </p>
    <p>
        <label>Price Note:</label>
        <textarea name="service_price_note" style="width:100%;"><?php echo esc_textarea($price_note); ?></textarea>
    </p>
    <?php
}

function voltmont_save_service_meta($post_id) {
    if (!isset($_POST['voltmont_service_nonce']) || !wp_verify_nonce($_POST['voltmont_service_nonce'], 'voltmont_service_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['service_duration'])) {
        update_post_meta($post_id, '_service_duration', sanitize_text_field($_POST['service_duration']));
    }
    
    if (isset($_POST['service_price_from'])) {
        update_post_meta($post_id, '_service_price_from', absint($_POST['service_price_from']));
    }
    
    if (isset($_POST['service_price_note'])) {
        update_post_meta($post_id, '_service_price_note', sanitize_textarea_field($_POST['service_price_note']));
    }
}
add_action('save_post', 'voltmont_save_service_meta');
```

---

## 2. CSS Components

### Navigation Component

**File:** `src/css/components/_navigation.css`

**Features:**
- Sticky header
- Dropdown menus
- Mobile hamburger menu
- Accessibility (keyboard nav, ARIA)
- Smooth animations
- Focus states

**Key Classes:**

```css
.site-header                    /* Main header container */
.site-header--scrolled          /* Scrolled state */
.site-logo                      /* Logo */
.main-nav                       /* Desktop navigation */
.main-nav__dropdown             /* Dropdown menu */
.mobile-menu-toggle             /* Hamburger button */
.mobile-nav                     /* Mobile menu overlay */
.nav-cta__button                /* CTA button */
```

**Usage:**

```html
<header class="site-header">
    <div class="site-header__container">
        <a href="/" class="site-logo">
            <img src="logo.png" alt="Voltmont" class="site-logo__image">
            <span class="site-logo__text">Voltmont</span>
        </a>
        
        <nav class="main-nav" aria-label="Main navigation">
            <ul class="main-nav__list">
                <li class="main-nav__item">
                    <a href="/" class="main-nav__link">Home</a>
                </li>
                <li class="main-nav__item main-nav__item--has-dropdown">
                    <a href="/services/" class="main-nav__link">Services</a>
                    <ul class="main-nav__dropdown">
                        <li class="main-nav__dropdown-item">
                            <a href="/service-1/" class="main-nav__dropdown-link">Service 1</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        
        <div class="nav-cta">
            <a href="/contact/" class="nav-cta__button">Contact Us</a>
        </div>
        
        <button class="mobile-menu-toggle" aria-label="Toggle menu">
            <span class="hamburger">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
    </div>
</header>
```

### Footer Component

**File:** `src/css/components/_footer.css`

**Features:**
- Multi-column layout
- Social media links
- Newsletter signup
- Contact information
- Copyright notice
- Responsive grid

**Key Classes:**

```css
.site-footer                    /* Main footer container */
.site-footer__top               /* Top section (columns) */
.site-footer__column            /* Footer column */
.site-footer__title             /* Column heading */
.site-footer__links             /* Link list */
.site-footer__social            /* Social media icons */
.site-footer__bottom            /* Copyright bar */
```

**Usage:**

```html
<footer class="site-footer">
    <div class="site-footer__top">
        <div class="container">
            <div class="site-footer__grid">
                <div class="site-footer__column">
                    <h3 class="site-footer__title">O Firmie</h3>
                    <ul class="site-footer__links">
                        <li><a href="/about/">O nas</a></li>
                        <li><a href="/services/">Usługi</a></li>
                    </ul>
                </div>
                
                <div class="site-footer__column">
                    <h3 class="site-footer__title">Kontakt</h3>
                    <address class="site-footer__contact">
                        <p>Trzebnica, Dolny Śląsk</p>
                        <p><a href="tel:+48691594820">+48 691 594 820</a></p>
                        <p><a href="mailto:biuro@trzebnica-elektryk.pl">biuro@trzebnica-elektryk.pl</a></p>
                    </address>
                </div>
                
                <div class="site-footer__column">
                    <h3 class="site-footer__title">Social Media</h3>
                    <div class="site-footer__social">
                        <a href="#" aria-label="Facebook">
                            <svg class="icon"><use xlink:href="#icon-facebook"></use></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="site-footer__bottom">
        <div class="container">
            <p>&copy; 2024 Voltmont. All rights reserved.</p>
        </div>
    </div>
</footer>
```

### Service Components

**File:** `src/css/components/_service.css`

**Components:**
- Hero section
- Feature cards
- Price card
- Contact card
- FAQ accordion
- Service grid
- CTA box

**Example - Feature Card:**

```html
<div class="feature-card">
    <div class="feature-card__icon">
        <svg class="icon icon--large"><use xlink:href="#icon-cert"></use></svg>
    </div>
    <h3 class="feature-card__title">Certified Electricians</h3>
    <p class="feature-card__text">All work performed by licensed professionals.</p>
</div>
```

---

## 3. JavaScript Testing (Jest)

### Setup

**Install dependencies:**

```bash
cd tests
npm install
```

**Run tests:**

```bash
npm test                # Run all tests
npm run test:watch      # Watch mode
npm run test:coverage   # Coverage report
```

### Test Structure

```
tests/
├── package.json
├── jest.setup.js
├── unit/
│   ├── navigation.test.js
│   ├── forms.test.js
│   ├── accordion.test.js
│   └── lazy-loading.test.js
└── integration/
    ├── contact-form.test.js
    └── search.test.js
```

### Example: Navigation Tests

**File:** `tests/unit/navigation.test.js`

```javascript
/**
 * Navigation Component Tests
 */

import { screen, waitFor } from '@testing-library/dom';
import '@testing-library/jest-dom';
import userEvent from '@testing-library/user-event';

describe('Navigation Component', () => {
    let container;
    
    beforeEach(() => {
        // Setup DOM
        container = document.createElement('div');
        container.innerHTML = `
            <header class="site-header">
                <button class="mobile-menu-toggle" aria-label="Toggle menu">
                    <span class="hamburger"></span>
                </button>
                <nav class="mobile-nav">
                    <ul class="mobile-nav__list">
                        <li class="mobile-nav__item">
                            <a href="/" class="mobile-nav__link">Home</a>
                        </li>
                    </ul>
                </nav>
            </header>
        `;
        document.body.appendChild(container);
        
        // Initialize navigation
        initMobileNav();
    });
    
    afterEach(() => {
        document.body.removeChild(container);
    });
    
    test('mobile menu toggle works', async () => {
        const user = userEvent.setup();
        const toggleButton = screen.getByLabelText('Toggle menu');
        const mobileNav = document.querySelector('.mobile-nav');
        
        // Initial state: menu closed
        expect(mobileNav).not.toHaveClass('mobile-nav--active');
        
        // Click to open
        await user.click(toggleButton);
        expect(mobileNav).toHaveClass('mobile-nav--active');
        
        // Click to close
        await user.click(toggleButton);
        expect(mobileNav).not.toHaveClass('mobile-nav--active');
    });
    
    test('mobile menu closes on escape key', async () => {
        const user = userEvent.setup();
        const toggleButton = screen.getByLabelText('Toggle menu');
        const mobileNav = document.querySelector('.mobile-nav');
        
        // Open menu
        await user.click(toggleButton);
        expect(mobileNav).toHaveClass('mobile-nav--active');
        
        // Press Escape
        await user.keyboard('{Escape}');
        expect(mobileNav).not.toHaveClass('mobile-nav--active');
    });
    
    test('dropdown menu opens on hover', async () => {
        // Add dropdown to DOM
        const dropdownItem = document.createElement('li');
        dropdownItem.className = 'main-nav__item main-nav__item--has-dropdown';
        dropdownItem.innerHTML = `
            <a href="#" class="main-nav__link">Services</a>
            <ul class="main-nav__dropdown">
                <li><a href="#">Service 1</a></li>
            </ul>
        `;
        container.querySelector('.mobile-nav__list').appendChild(dropdownItem);
        
        const user = userEvent.setup();
        const dropdownLink = screen.getByText('Services');
        
        // Hover over dropdown trigger
        await user.hover(dropdownLink);
        
        const dropdown = dropdownItem.querySelector('.main-nav__dropdown');
        await waitFor(() => {
            expect(dropdown).toBeVisible();
        });
    });
    
    test('navigation is accessible via keyboard', async () => {
        const user = userEvent.setup();
        const links = screen.getAllByRole('link');
        
        // Tab through links
        await user.tab();
        expect(links[0]).toHaveFocus();
        
        await user.tab();
        expect(links[1]).toHaveFocus();
    });
});

// Mock initialization function
function initMobileNav() {
    const toggleButton = document.querySelector('.mobile-menu-toggle');
    const mobileNav = document.querySelector('.mobile-nav');
    
    if (!toggleButton || !mobileNav) return;
    
    toggleButton.addEventListener('click', () => {
        mobileNav.classList.toggle('mobile-nav--active');
        document.body.classList.toggle('mobile-menu-open');
    });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileNav.classList.contains('mobile-nav--active')) {
            mobileNav.classList.remove('mobile-nav--active');
            document.body.classList.remove('mobile-menu-open');
        }
    });
}
```

### Example: Form Tests

**File:** `tests/unit/forms.test.js`

```javascript
/**
 * Form Component Tests
 */

describe('Contact Form', () => {
    test('validates required fields', async () => {
        const user = userEvent.setup();
        const form = screen.getByRole('form');
        const submitButton = screen.getByRole('button', { name: /submit/i });
        
        // Try to submit empty form
        await user.click(submitButton);
        
        // Check for validation errors
        const emailInput = screen.getByLabelText(/email/i);
        expect(emailInput).toBeInvalid();
    });
    
    test('validates email format', async () => {
        const user = userEvent.setup();
        const emailInput = screen.getByLabelText(/email/i);
        
        // Enter invalid email
        await user.type(emailInput, 'notanemail');
        await user.tab();
        
        expect(emailInput).toBeInvalid();
        
        // Enter valid email
        await user.clear(emailInput);
        await user.type(emailInput, 'test@example.com');
        await user.tab();
        
        expect(emailInput).toBeValid();
    });
    
    test('sanitizes user input', () => {
        const input = '<script>alert("xss")</script>Test';
        const sanitized = sanitizeInput(input);
        
        expect(sanitized).not.toContain('<script>');
        expect(sanitized).toBe('Test');
    });
});

function sanitizeInput(input) {
    return input.replace(/<[^>]*>/g, '');
}
```

### Example: Lazy Loading Tests

**File:** `tests/unit/lazy-loading.test.js`

```javascript
/**
 * Lazy Loading Tests
 */

describe('Lazy Loading', () => {
    test('images load when entering viewport', async () => {
        const img = document.createElement('img');
        img.dataset.src = 'image.jpg';
        img.loading = 'lazy';
        document.body.appendChild(img);
        
        // Mock IntersectionObserver
        const mockIntersectionObserver = jest.fn();
        mockIntersectionObserver.mockReturnValue({
            observe: () => null,
            unobserve: () => null,
            disconnect: () => null
        });
        window.IntersectionObserver = mockIntersectionObserver;
        
        // Initialize lazy loading
        initLazyLoading();
        
        expect(mockIntersectionObserver).toHaveBeenCalled();
    });
});

function initLazyLoading() {
    const images = document.querySelectorAll('[data-src]');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => observer.observe(img));
}
```

---

## 4. PHP Testing (PHPUnit)

### Setup

**Install PHPUnit:**

```bash
composer require --dev phpunit/phpunit
```

**Configuration:** `phpunit.xml`

```xml
<?xml version="1.0"?>
<phpunit bootstrap="tests/bootstrap.php" colors="true">
    <testsuites>
        <testsuite name="Voltmont Theme Tests">
            <directory>tests/php</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

### Test Structure

```
tests/php/
├── bootstrap.php
├── SeoFunctionsTest.php
├── PerformanceTest.php
└── SchemaTest.php
```

### Example: SEO Functions Tests

**File:** `tests/php/SeoFunctionsTest.php`

```php
<?php
/**
 * SEO Functions Tests
 */

use PHPUnit\Framework\TestCase;

class SeoFunctionsTest extends TestCase {
    
    public function setUp(): void {
        parent::setUp();
        require_once __DIR__ . '/../../dist/wp-content/themes/hubag-child/inc/functions-seo.php';
    }
    
    public function testMetaTitleLength() {
        // Mock WordPress functions
        $title = voltmont_get_meta_title();
        
        $this->assertLessThanOrEqual(60, strlen($title), 'Meta title should be 60 characters or less');
    }
    
    public function testMetaDescriptionLength() {
        $description = voltmont_get_meta_description();
        
        $this->assertGreaterThanOrEqual(150, strlen($description), 'Meta description should be at least 150 characters');
        $this->assertLessThanOrEqual(160, strlen($description), 'Meta description should be 160 characters or less');
    }
    
    public function testReadingTimeCalculation() {
        $content = str_repeat('word ', 200); // 200 words
        $reading_time = voltmont_get_reading_time($content);
        
        $this->assertEquals(1, $reading_time, 'Reading time should be 1 minute for 200 words');
    }
    
    public function testMetaKeywordsLimit() {
        $keywords = voltmont_get_meta_keywords();
        $keyword_array = explode(', ', $keywords);
        
        $this->assertLessThanOrEqual(10, count($keyword_array), 'Should have maximum 10 keywords');
    }
    
    public function testSanitization() {
        $input = '<script>alert("xss")</script>Test';
        $sanitized = sanitize_text_field($input);
        
        $this->assertStringNotContainsString('<script>', $sanitized);
    }
}
```

### Example: Performance Tests

**File:** `tests/php/PerformanceTest.php`

```php
<?php
/**
 * Performance Optimization Tests
 */

class PerformanceTest extends TestCase {
    
    public function testTransientCaching() {
        // Set transient
        $data = array('test' => 'value');
        set_transient('voltmont_test', $data, 3600);
        
        // Get transient
        $cached = get_transient('voltmont_test');
        
        $this->assertEquals($data, $cached);
    }
    
    public function testCacheClearing() {
        // Set multiple transients
        set_transient('voltmont_portfolio_1', array(), 3600);
        set_transient('voltmont_portfolio_2', array(), 3600);
        
        // Clear all Voltmont caches
        voltmont_clear_all_caches();
        
        $this->assertFalse(get_transient('voltmont_portfolio_1'));
        $this->assertFalse(get_transient('voltmont_portfolio_2'));
    }
    
    public function testWebPConversion() {
        // Mock image file
        $test_image = __DIR__ . '/fixtures/test-image.jpg';
        
        if (function_exists('imagewebp')) {
            $webp_path = str_replace('.jpg', '.webp', $test_image);
            
            // Test conversion function
            $result = voltmont_create_webp_image($test_image);
            
            $this->assertTrue($result);
            $this->assertFileExists($webp_path);
        } else {
            $this->markTestSkipped('WebP support not available');
        }
    }
    
    public function testCssMinification() {
        $css = "
        .test {
            color: red;
            /* Comment */
            font-size: 16px;
        }
        ";
        
        $minified = voltmont_minify_css($css);
        
        $this->assertStringNotContainsString('/*', $minified);
        $this->assertStringNotContainsString("\n", trim($minified));
        $this->assertLessThan(strlen($css), strlen($minified));
    }
}
```

### Example: Schema Tests

**File:** `tests/php/SchemaTest.php`

```php
<?php
/**
 * Schema.org Tests
 */

class SchemaTest extends TestCase {
    
    public function testLocalBusinessSchema() {
        ob_start();
        voltmont_output_localbusiness_schema();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('@type":"LocalBusiness', $output);
        $this->assertStringContainsString('Voltmont', $output);
        $this->assertStringContainsString('+48691594820', $output);
    }
    
    public function testArticleSchema() {
        // Mock post
        global $post;
        $post = (object) array(
            'post_title' => 'Test Article',
            'post_content' => 'Test content',
            'post_date' => '2024-01-01 00:00:00'
        );
        
        ob_start();
        voltmont_output_article_schema();
        $output = ob_get_clean();
        
        $this->assertStringContainsString('@type":"Article', $output);
        $this->assertStringContainsString('Test Article', $output);
    }
    
    public function testSchemaValidJson() {
        ob_start();
        voltmont_output_organization_schema();
        $output = ob_get_clean();
        
        // Extract JSON from script tag
        preg_match('/<script[^>]*>(.*?)<\/script>/s', $output, $matches);
        $json = $matches[1];
        
        $decoded = json_decode($json, true);
        
        $this->assertNotNull($decoded, 'Schema should be valid JSON');
        $this->assertArrayHasKey('@context', $decoded);
        $this->assertEquals('https://schema.org', $decoded['@context']);
    }
}
```

### Running PHP Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test
./vendor/bin/phpunit tests/php/SeoFunctionsTest.php

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage/
```

---

## 5. Integration Testing

### Example: Contact Form Integration Test

**File:** `tests/integration/contact-form.test.js`

```javascript
/**
 * Contact Form Integration Test
 */

describe('Contact Form Integration', () => {
    test('submits form successfully', async () => {
        const user = userEvent.setup();
        
        // Fill form
        await user.type(screen.getByLabelText(/name/i), 'Jan Kowalski');
        await user.type(screen.getByLabelText(/email/i), 'jan@example.com');
        await user.type(screen.getByLabelText(/phone/i), '+48 123 456 789');
        await user.type(screen.getByLabelText(/message/i), 'Test message');
        await user.click(screen.getByLabelText(/consent/i));
        
        // Submit
        await user.click(screen.getByRole('button', { name: /submit/i }));
        
        // Check success message
        await waitFor(() => {
            expect(screen.getByText(/dziękujemy/i)).toBeInTheDocument();
        });
    });
    
    test('handles server errors gracefully', async () => {
        // Mock failed API call
        global.fetch = jest.fn(() =>
            Promise.reject(new Error('Server error'))
        );
        
        const user = userEvent.setup();
        await user.click(screen.getByRole('button', { name: /submit/i }));
        
        await waitFor(() => {
            expect(screen.getByText(/błąd/i)).toBeInTheDocument();
        });
    });
});
```

---

## 🧪 Testing Checklist

### Pre-Deployment

- [ ] Run all Jest tests (`npm test`)
- [ ] Run all PHPUnit tests (`./vendor/bin/phpunit`)
- [ ] Check test coverage (>80%)
- [ ] Test navigation (desktop + mobile)
- [ ] Test forms (validation + submission)
- [ ] Test lazy loading
- [ ] Test FAQ accordion
- [ ] Test mobile menu
- [ ] Test keyboard navigation
- [ ] Test screen reader compatibility

### Manual Testing

- [ ] Test on Chrome, Firefox, Safari, Edge
- [ ] Test on mobile devices (iOS + Android)
- [ ] Test with slow 3G connection
- [ ] Test with JavaScript disabled
- [ ] Test with ad blockers
- [ ] Test form SPAM protection
- [ ] Test contact form emails
- [ ] Test 404 pages
- [ ] Test search functionality

---

## 📚 Resources

### Testing Libraries
- [Jest Documentation](https://jestjs.io/)
- [Testing Library](https://testing-library.com/)
- [PHPUnit Documentation](https://phpunit.de/)

### WordPress Testing
- [WordPress Handbook: Automated Testing](https://make.wordpress.org/core/handbook/testing/automated-testing/)
- [WP Test Utils](https://github.com/wp-phpunit/wp-phpunit)

---

**Questions?** Check documentation or create an issue!

---

*Last updated: 2024-11-26*  
*Version: 2.0*
