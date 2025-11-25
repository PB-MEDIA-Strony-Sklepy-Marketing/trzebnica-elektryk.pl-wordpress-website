# Testing Guide – trzebnica-elektryk.pl

**Voltmont - Instalacje Elektryczne**  
Comprehensive testing strategy and procedures

---

## Table of Contents

1. [Testing Philosophy](#testing-philosophy)
2. [Testing Types](#testing-types)
3. [Unit Testing](#unit-testing)
4. [E2E Testing](#e2e-testing)
5. [Accessibility Testing](#accessibility-testing)
6. [Performance Testing](#performance-testing)
7. [Security Testing](#security-testing)
8. [SEO Testing](#seo-testing)
9. [Manual Testing](#manual-testing)
10. [CI/CD Integration](#cicd-integration)

---

## Testing Philosophy

**Test Pyramid:**
```
        /\
       /  \  E2E Tests (Few)
      /    \
     /------\  Integration Tests (Some)
    /        \
   /----------\ Unit Tests (Many)
```

**Principles:**
- Write tests before fixing bugs
- Test behavior, not implementation
- Maintain test independence
- Keep tests fast and reliable

---

## Testing Types

### Overview

| Type | Tools | Frequency | Coverage Target |
|------|-------|-----------|-----------------|
| Unit | Jest | Every commit | 80%+ |
| E2E | Cypress | Before deploy | Critical paths |
| Accessibility | pa11y-ci | Every deploy | 100% |
| Performance | Lighthouse | Daily | Score ≥90 |
| Security | Wordfence, WPScan | Daily | 0 vulnerabilities |
| SEO | Custom scripts | Weekly | 100% pages |

---

## Unit Testing

### Setup

**Framework:** Jest

**Configuration:** `jest.config.js`

**Run Tests:**
```bash
# All tests
npm test

# Watch mode
npm test -- --watch

# Coverage
npm run test:coverage

# Specific file
npm test -- path/to/test.spec.js
```

### Writing Unit Tests

**Example: Test utility function**

```javascript
// utils.js
export function sanitizePhoneNumber(phone) {
    return phone.replace(/\D/g, '').replace(/^48/, '+48');
}

// utils.spec.js
import { sanitizePhoneNumber } from './utils';

describe('sanitizePhoneNumber', () => {
    it('should remove non-digit characters', () => {
        expect(sanitizePhoneNumber('+48 123-456-789')).toBe('+48123456789');
    });
    
    it('should add country code if missing', () => {
        expect(sanitizePhoneNumber('123456789')).toBe('+48123456789');
    });
    
    it('should handle empty input', () => {
        expect(sanitizePhoneNumber('')).toBe('+48');
    });
});
```

### PHP Unit Testing

**Framework:** PHPUnit

**Run Tests:**
```bash
# Via Composer
composer test

# Specific test
./vendor/bin/phpunit tests/SomeTest.php
```

**Example:**

```php
// tests/SchemaTest.php
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase {
    public function testLocalBusinessSchemaHasRequiredFields() {
        $schema = voltmont_get_local_business_schema();
        
        $this->assertArrayHasKey('@context', $schema);
        $this->assertArrayHasKey('@type', $schema);
        $this->assertArrayHasKey('name', $schema);
        $this->assertArrayHasKey('telephone', $schema);
        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('LocalBusiness', $schema['@type']);
    }
}
```

---

## E2E Testing

### Framework: Cypress

**Setup:**
```bash
npm install --save-dev cypress
```

**Run Tests:**
```bash
# Interactive mode
npm run test:e2e

# Headless mode
npm run test:e2e:headless

# Specific spec
npx cypress run --spec "cypress/e2e/contact-form.cy.js"
```

### Test Examples

**Contact Form Submission:**

```javascript
// cypress/e2e/contact-form.cy.js
describe('Contact Form', () => {
    beforeEach(() => {
        cy.visit('/kontakt');
    });
    
    it('should submit contact form successfully', () => {
        cy.get('#name').type('Jan Kowalski');
        cy.get('#email').type('jan@example.com');
        cy.get('#phone').type('+48 123 456 789');
        cy.get('#message').type('Test message from Cypress');
        cy.get('#consent').check();
        
        cy.get('button[type="submit"]').click();
        
        cy.get('.success-message')
          .should('be.visible')
          .and('contain', 'Dziękujemy za wiadomość');
    });
    
    it('should show validation errors for empty fields', () => {
        cy.get('button[type="submit"]').click();
        
        cy.get('.error').should('have.length.at.least', 3);
    });
});
```

**Portfolio Navigation:**

```javascript
// cypress/e2e/portfolio.cy.js
describe('Portfolio', () => {
    it('should filter portfolio by category', () => {
        cy.visit('/galeria-realizacji');
        
        // Click "Modernizacja" category
        cy.contains('button', 'Modernizacja').click();
        
        // Verify filtered results
        cy.get('.portfolio-item').should('have.length.gte', 1);
        cy.get('.portfolio-item').each(($item) => {
            cy.wrap($item).find('.category-badge')
              .should('contain', 'Modernizacja');
        });
    });
    
    it('should load more portfolio items on button click', () => {
        cy.visit('/galeria-realizacji');
        
        const initialCount = Cypress.$('.portfolio-item').length;
        
        cy.get('.load-more-btn').click();
        cy.wait(1000); // Wait for AJAX
        
        cy.get('.portfolio-item').should('have.length.gt', initialCount);
    });
});
```

---

## Accessibility Testing

### Automated Testing

**Tool:** pa11y-ci

**Configuration:** `.pa11yci.json`

```json
{
    "defaults": {
        "standard": "WCAG2AA",
        "runners": ["axe", "htmlcs"],
        "timeout": 30000
    },
    "urls": [
        "https://trzebnica-elektryk.pl/",
        "https://trzebnica-elektryk.pl/kontakt/",
        "https://trzebnica-elektryk.pl/uslugi/instalacje-elektryczne/",
        "https://trzebnica-elektryk.pl/galeria-realizacji/"
    ]
}
```

**Run:**
```bash
npm run test:a11y
```

### Manual Accessibility Checks

**Keyboard Navigation:**
- [ ] Tab through all interactive elements
- [ ] Verify visible focus indicators
- [ ] Check skip links functionality
- [ ] Test dropdown menus with keyboard

**Screen Reader:**
- [ ] Test with NVDA (Windows) or VoiceOver (Mac)
- [ ] Verify heading hierarchy (H1 → H2 → H3)
- [ ] Check ARIA labels on icon buttons
- [ ] Verify form labels

**Color Contrast:**
```bash
# Check contrast ratios
npm run test:contrast
```

---

## Performance Testing

### Lighthouse

**Run:**
```bash
npm run test:lighthouse
```

**Targets:**
- Performance: ≥ 90
- Accessibility: ≥ 95
- Best Practices: ≥ 90
- SEO: ≥ 95

**Custom Configuration:**

```javascript
// lighthouse.config.js
module.exports = {
    extends: 'lighthouse:default',
    settings: {
        onlyCategories: ['performance', 'accessibility', 'seo'],
        formFactor: 'mobile',
        throttling: {
            rttMs: 150,
            throughputKbps: 1600,
            cpuSlowdownMultiplier: 4,
        },
    },
};
```

### Core Web Vitals

**Monitor:**
- **LCP (Largest Contentful Paint):** < 2.5s
- **FID (First Input Delay):** < 100ms
- **CLS (Cumulative Layout Shift):** < 0.1

**Test:**
```bash
# Using Lighthouse
lighthouse https://trzebnica-elektryk.pl --only-categories=performance

# Using WebPageTest
# https://www.webpagetest.org/
```

---

## Security Testing

### Automated Scans

**Wordfence:**
- Daily automatic scans
- Review alerts in dashboard

**WPScan:**
```bash
# Install
gem install wpscan

# Scan for vulnerabilities
wpscan --url https://trzebnica-elektryk.pl --api-token YOUR_TOKEN
```

### Manual Security Checks

**Common Vulnerabilities:**

```bash
# XSS Testing
# Test input fields with:
<script>alert('XSS')</script>
<img src=x onerror=alert('XSS')>

# SQL Injection Testing
# Test parameters with:
' OR '1'='1
1'; DROP TABLE wp_posts;--

# Directory Traversal
../../../etc/passwd
```

**Security Headers:**
```bash
# Check security headers
curl -I https://trzebnica-elektryk.pl | grep -E "(X-Frame-Options|X-Content-Type-Options|X-XSS-Protection|Content-Security-Policy)"
```

---

## SEO Testing

### Automated Checks

**Schema Validation:**
```bash
# Using Google Rich Results Test
# https://search.google.com/test/rich-results
```

**Meta Tags:**
```javascript
// cypress/e2e/seo.cy.js
describe('SEO Meta Tags', () => {
    it('should have proper title on homepage', () => {
        cy.visit('/');
        cy.title().should('match', /elektryk trzebnica/i);
        cy.title().should('have.length.lte', 60);
    });
    
    it('should have meta description on all pages', () => {
        const pages = ['/', '/kontakt/', '/o-nas/'];
        
        pages.forEach((page) => {
            cy.visit(page);
            cy.get('meta[name="description"]')
              .should('have.attr', 'content')
              .and('have.length.gte', 150)
              .and('have.length.lte', 160);
        });
    });
});
```

### Manual SEO Checks

- [ ] All pages have unique titles (50-60 chars)
- [ ] All pages have meta descriptions (150-160 chars)
- [ ] Images have alt text
- [ ] Heading hierarchy (one H1 per page)
- [ ] Internal links present
- [ ] Sitemap accessible (`/sitemap.xml`)
- [ ] Robots.txt configured (`/robots.txt`)
- [ ] Schema.org markup valid

---

## Manual Testing

### Browser Compatibility

**Test in:**
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile Safari (iOS)
- Chrome Mobile (Android)

**Checklist per browser:**
- [ ] Homepage loads correctly
- [ ] Navigation works
- [ ] Forms submit successfully
- [ ] Images load
- [ ] No console errors
- [ ] Responsive design (320px - 2560px)

### Mobile Testing

**Devices:**
- iPhone SE (375px)
- iPhone 12/13 (390px)
- Samsung Galaxy S21 (360px)
- iPad (768px)

**Test:**
- [ ] Touch targets ≥ 44x44px
- [ ] Text readable without zoom
- [ ] No horizontal scroll
- [ ] Forms usable on mobile
- [ ] Phone/email links work

### Functional Testing

**Contact Form:**
- [ ] Submit with valid data
- [ ] Validation for empty fields
- [ ] Email validation
- [ ] Phone formatting
- [ ] Success message displays
- [ ] Email received

**Portfolio:**
- [ ] Gallery loads
- [ ] Filtering works
- [ ] Load more button
- [ ] Single portfolio page
- [ ] Images open in lightbox

---

## CI/CD Integration

### GitHub Actions

**On Pull Request:**
```yaml
# .github/workflows/test.yml
name: Tests

on: [pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
        with:
          node-version: '18'
      - run: npm ci
      - run: npm run lint
      - run: npm test
      - run: npm run build
```

**Pre-Deployment:**
```yaml
# .github/workflows/pre-deploy.yml
name: Pre-Deploy Checks

on:
  workflow_dispatch:

jobs:
  checks:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - run: npm ci
      - run: npm run lint
      - run: npm test
      - run: npm run test:a11y
      - run: npm run build:production
```

---

## Test Coverage

### Current Coverage

```
File                    | % Stmts | % Branch | % Funcs | % Lines |
------------------------|---------|----------|---------|---------|
All files               |   82.5  |   75.3   |   88.1  |   83.2  |
 utils/                 |   95.2  |   90.1   |   100   |   96.4  |
 components/            |   78.4  |   68.7   |   82.3  |   79.1  |
 api/                   |   85.6  |   80.2   |   88.9  |   86.3  |
```

### Coverage Goals

- **Overall:** ≥ 80%
- **Critical Paths:** ≥ 95%
- **Utilities:** 100%

---

## Reporting Issues

**Test Failure Report:**
```markdown
## Test Failure

**Test:** [Test name]
**File:** [Test file path]
**Environment:** [Browser/Node version]

**Expected:**
[What should happen]

**Actual:**
[What happened]

**Steps to Reproduce:**
1. [Step 1]
2. [Step 2]

**Error Message:**
[Error message/stack trace]

**Screenshots:** [If applicable]
```

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintained by:** PB-MEDIA QA Team
