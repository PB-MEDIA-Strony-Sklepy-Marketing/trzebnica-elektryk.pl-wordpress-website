# Contributing to Voltmont Website

Thank you for your interest in contributing to the Voltmont - Instalacje Elektryczne website! This document provides guidelines and standards for contributing to this WordPress project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Coding Standards](#coding-standards)
- [Commit Message Guidelines](#commit-message-guidelines)
- [Pull Request Process](#pull-request-process)
- [Testing Requirements](#testing-requirements)
- [Documentation](#documentation)

## Code of Conduct

- Be respectful and professional in all communications
- Focus on constructive feedback
- Prioritize code quality and user experience
- Maintain client confidentiality

## Getting Started

### Prerequisites

- Node.js 18.0.0 or higher
- npm 8.0.0 or higher
- PHP 8.0 or higher
- Composer (for PHP dependencies)
- Git

### Initial Setup

```bash
# Clone the repository
git clone https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website.git

# Navigate to project
cd trzebnica-elektryk.pl-wordpress-website

# Install dependencies
npm install

# Install PHP dependencies (if needed)
composer install

# Run initial build
npm run build
```

### Branch Naming Convention

Use descriptive branch names with prefixes:

- `feature/` - New features (e.g., `feature/faq-schema-implementation`)
- `fix/` - Bug fixes (e.g., `fix/mobile-menu-overflow`)
- `refactor/` - Code refactoring (e.g., `refactor/seo-functions-optimization`)
- `docs/` - Documentation updates (e.g., `docs/update-api-reference`)
- `style/` - CSS/styling changes (e.g., `style/improve-offer-card-hover`)
- `perf/` - Performance improvements (e.g., `perf/lazy-load-images`)
- `test/` - Adding or updating tests (e.g., `test/add-e2e-contact-form`)

**Example:**
```bash
git checkout -b feature/custom-contact-widget
```

## Development Workflow

### 1. Create Feature Branch

```bash
# Ensure main is up-to-date
git checkout main
git pull origin main

# Create and switch to feature branch
git checkout -b feature/your-feature-name
```

### 2. Make Changes

- Work in `dist/wp-content/themes/hubag-child/` for production code
- Follow coding standards (see below)
- Test your changes thoroughly
- Run linters before committing

### 3. Run Quality Checks

```bash
# Lint everything
npm run lint

# Fix auto-fixable issues
npm run format

# Run tests
npm test

# Check build
npm run build
```

### 4. Commit Changes

Follow the commit message format (see below).

```bash
git add .
git commit -m "feat: add custom FAQ schema implementation"
```

### 5. Push to Remote

```bash
git push origin feature/your-feature-name
```

### 6. Create Pull Request

Open a PR against `main` branch using the PR template.

## Coding Standards

### PHP Standards (PSR-12)

**File Structure:**
```php
<?php
/**
 * File description
 *
 * @package Hubag_Child
 * @since 2.0.0
 */

defined('ABSPATH') || exit;

// Code here
```

**Naming Conventions:**
- Functions: `snake_case` with `voltmont_` prefix
- Classes: `PascalCase` with `Voltmont_` prefix
- Constants: `UPPER_SNAKE_CASE`
- Variables: `snake_case`

**Example:**
```php
function voltmont_get_meta_description() {
    // Implementation
}

class Voltmont_SEO_Manager {
    // Implementation
}

define('VOLTMONT_VERSION', '2.0.0');

$meta_title = voltmont_get_meta_title();
```

**Security Best Practices:**
- Always escape output: `esc_html()`, `esc_attr()`, `esc_url()`
- Always sanitize input: `sanitize_text_field()`, `sanitize_email()`
- Use prepared statements: `$wpdb->prepare()`
- Verify nonces for forms: `wp_verify_nonce()`
- Check capabilities: `current_user_can()`

### CSS/SCSS Standards (BEM)

**BEM Naming:**
```scss
.block {}
.block__element {}
.block--modifier {}
```

**Example:**
```scss
.voltmont-offer-card {}
.voltmont-offer-card__title {}
.voltmont-offer-card__icon {}
.voltmont-offer-card--featured {}
```

**Brand Colors (Always Use Variables):**
```scss
$color-primary: #4d81e9;
$color-secondary: #041028;
$color-text: #edf0fd;
$color-bg: #163162;
$color-hover: #2a54a1;
```

**Responsive Design:**
- Mobile-first approach
- Test from 320px to 2560px
- Use `clamp()` for fluid typography

### JavaScript Standards

**ESLint Configuration:**
Follow `.eslint.config.mjs` settings.

**Naming Conventions:**
- Variables/Functions: `camelCase`
- Constants: `UPPER_SNAKE_CASE`
- Classes: `PascalCase`

**Example:**
```javascript
const API_ENDPOINT = 'https://api.example.com';

function fetchUserData(userId) {
    // Implementation
}

class ContactFormValidator {
    // Implementation
}
```

**Modern JavaScript:**
- Use ES6+ features (arrow functions, destructuring, template literals)
- Prefer `const` and `let` over `var`
- Use async/await for asynchronous operations

### Accessibility (WCAG 2.2 AA)

**Required:**
- Color contrast ratio ≥ 4.5:1 for text
- All interactive elements keyboard accessible
- Proper ARIA labels where needed
- Focus indicators visible
- Alt text for all images
- Semantic HTML structure

**Test Tools:**
```bash
npm run test:a11y
```

## Commit Message Guidelines

### Format

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Type

- `feat` - New feature
- `fix` - Bug fix
- `refactor` - Code refactoring
- `style` - CSS/styling changes
- `perf` - Performance improvement
- `test` - Adding/updating tests
- `docs` - Documentation changes
- `build` - Build system changes
- `ci` - CI/CD changes
- `chore` - Maintenance tasks

### Scope (Optional)

Indicates the area affected:
- `seo` - SEO-related changes
- `schema` - Schema.org markup
- `assets` - CSS/JS/images
- `functions` - PHP functions
- `config` - Configuration files
- `deps` - Dependencies

### Subject

- Use imperative mood ("add" not "added")
- Don't capitalize first letter
- No period at the end
- Max 50 characters

### Body (Optional)

- Explain what and why (not how)
- Wrap at 72 characters
- Separate from subject with blank line

### Footer (Optional)

- Reference issues: `Closes #123`, `Fixes #456`
- Breaking changes: `BREAKING CHANGE: ...`

### Examples

**Simple commit:**
```
feat(schema): add FAQ schema implementation
```

**Detailed commit:**
```
feat(seo): implement dynamic OpenGraph tags

- Add functions for title/description generation
- Include Twitter Card support
- Integrate with Muffin Builder custom fields
- Add hreflang tags for future multilingual support

Closes #45
```

**Bug fix:**
```
fix(mobile): resolve menu overflow on small screens

The mobile navigation was extending beyond viewport
width on devices smaller than 375px. Updated max-width
calculation and added horizontal scroll prevention.

Fixes #78
```

**Breaking change:**
```
refactor(functions)!: restructure SEO functions organization

BREAKING CHANGE: voltmont_meta_tags() function renamed
to voltmont_output_seo_meta_tags(). Update any custom
implementations using the old function name.

Closes #92
```

## Pull Request Process

### Before Submitting PR

- [ ] Code passes all linters (`npm run lint`)
- [ ] All tests pass (`npm test`)
- [ ] Build succeeds (`npm run build`)
- [ ] Changes tested in multiple browsers (Chrome, Firefox, Safari, Edge)
- [ ] Mobile responsiveness verified (320px - 768px)
- [ ] Accessibility checked (`npm run test:a11y`)
- [ ] Documentation updated if needed
- [ ] Screenshots added for UI changes

### PR Template

Use the provided `.github/PULL_REQUEST_TEMPLATE.md`:

```markdown
## Description
Brief description of changes

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Testing
How to test these changes

## Screenshots (if applicable)
Before/After screenshots for UI changes

## Checklist
- [ ] Code follows style guidelines
- [ ] Self-review completed
- [ ] Tests added/updated
- [ ] Documentation updated
```

### Review Process

1. **Automated Checks:** CI/CD must pass (linting, tests, build)
2. **Code Review:** At least one approval required
3. **Testing:** Reviewer tests changes locally
4. **Merge:** Squash and merge into main

### Review Timeframe

- Small changes: 1-2 business days
- Medium changes: 2-4 business days
- Large changes: 4-7 business days

## Testing Requirements

### Unit Tests

Write tests for new utility functions:

```javascript
// Example Jest test
describe('voltmont_get_meta_title', () => {
    it('should return title with site name', () => {
        // Test implementation
    });
});
```

### E2E Tests

Test critical user flows:

```bash
npm run test:e2e
```

### Manual Testing Checklist

- [ ] Test in Chrome, Firefox, Safari, Edge
- [ ] Test on mobile devices (iOS, Android)
- [ ] Test with keyboard navigation
- [ ] Test with screen reader (NVDA/JAWS)
- [ ] Test form validation
- [ ] Test all interactive elements
- [ ] Test page load performance

### Performance Testing

```bash
npm run test:lighthouse
```

**Target Scores:**
- Performance: ≥ 90
- Accessibility: ≥ 95
- Best Practices: ≥ 90
- SEO: ≥ 95

## Documentation

### Code Comments

**PHP DocBlocks:**
```php
/**
 * Generate dynamic meta description
 *
 * Creates SEO-optimized meta descriptions based on page type
 * and content. Automatically truncates to 160 characters.
 *
 * @since 2.0.0
 * @return string Optimized meta description
 */
function voltmont_get_meta_description() {
    // Implementation
}
```

**Inline Comments:**
```php
// Check for Muffin Builder custom fields
$muffin_data = get_post_meta($post->ID, 'mfn-post-meta', true);

// Service page detection via custom taxonomy
$page_categories = wp_get_post_terms($post->ID, 'page_category');
```

### README Updates

Update relevant README sections when:
- Adding new features
- Changing commands/scripts
- Modifying architecture
- Updating dependencies

### CLAUDE.md Updates

Update `CLAUDE.md` when:
- Changing development workflow
- Adding new commands
- Modifying architecture patterns
- Establishing new conventions

## Questions?

For questions or clarifications:
- Open an issue with `question` label
- Contact: biuro@pbmediaonline.pl
- Check existing documentation in `/docs/` directory

## License

This is a proprietary project. All contributions become property of PB-MEDIA and the client (Voltmont).
