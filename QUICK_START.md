# Quick Start Guide - trzebnica-elektryk.pl

**Welcome!** This guide will help you set up the development environment for the Voltmont website in under 15 minutes.

---

## 📋 Prerequisites

Before you begin, ensure you have:

- ✅ **Git** installed ([Download](https://git-scm.com/downloads))
- ✅ **Node.js** 18.20.0+ ([Download](https://nodejs.org/))
- ✅ **PHP** 8.0+ ([Download](https://www.php.net/downloads))
- ✅ **Composer** ([Download](https://getcomposer.org/download/))
- ✅ **Local WordPress Environment** (XAMPP, WAMP, Local by Flywheel, or similar)
- ✅ **Code Editor** (VS Code recommended)

---

## 🚀 Quick Setup (5 minutes)

### 1. Clone the Repository

```bash
# Clone the repo
git clone https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website.git

# Navigate to project directory
cd trzebnica-elektryk.pl-wordpress-website
```

### 2. Set Node Version (if using nvm)

```bash
# Install and use correct Node version
nvm install
nvm use
```

### 3. Install Dependencies

```bash
# Install npm packages
npm install

# Install PHP dependencies (optional, for linting)
composer install
```

### 4. Build Assets

```bash
# Development build with watch mode
npm run dev

# OR production build
npm run build:production
```

**Done!** Your development environment is ready. 🎉

---

## 🗂️ Project Structure

```
trzebnica-elektryk.pl-wordpress-website/
│
├── .github/              # GitHub Actions workflows and templates
├── dist/                 # Production WordPress files
│   └── wp-content/
│       └── themes/
│           ├── hubag/           # Parent theme (BeTheme)
│           └── hubag-child/     # Child theme (WORK HERE)
│               ├── assets/      # Compiled CSS/JS
│               ├── inc/         # PHP includes
│               ├── templates/   # PHP template files
│               └── functions.php
│
├── src/                  # Source files (if using build process)
│   ├── js/              # JavaScript source
│   ├── css/             # CSS/SCSS source
│   └── images/          # Raw images
│
├── templates/           # Content templates (markdown)
├── docs/               # Additional documentation
└── custom/             # Custom scripts (cookie consent, etc.)
```

---

## 🛠️ Development Workflow

### Daily Development

```bash
# 1. Pull latest changes
git pull origin main

# 2. Create feature branch
git checkout -b feature/your-feature-name

# 3. Start development server
npm run dev

# 4. Make your changes in:
#    - dist/wp-content/themes/hubag-child/

# 5. Test your changes
npm run lint
npm test

# 6. Commit changes
git add .
git commit -m "feat: add your feature description"

# 7. Push and create PR
git push origin feature/your-feature-name
```

### Important Commands

```bash
# Development
npm run dev              # Watch mode + BrowserSync
npm run watch           # Watch mode only

# Building
npm run build           # Development build
npm run build:production # Production build (minified)

# Code Quality
npm run lint            # Lint everything
npm run lint:js         # Lint JavaScript
npm run lint:css        # Lint CSS/SCSS
npm run format          # Format code with Prettier

# Testing
npm test                # Run unit tests
npm run test:watch      # Tests in watch mode
npm run test:coverage   # Tests with coverage report
npm run test:e2e        # End-to-end tests
npm run test:lighthouse # Performance audit
npm run test:a11y       # Accessibility audit

# WordPress
npm run wp:update       # Update WordPress core & plugins
npm run wp:backup       # Create database backup
```

---

## 📝 Making Changes

### Working with Child Theme

**Always work in:** `dist/wp-content/themes/hubag-child/`

#### Adding PHP Functionality

1. Edit `functions.php` or create new file in `inc/`
2. Follow WordPress Coding Standards
3. Always escape output: `esc_html()`, `esc_attr()`, `esc_url()`
4. Always sanitize input: `sanitize_text_field()`, etc.

**Example:**
```php
// inc/my-feature.php
<?php
function voltmont_my_feature() {
    $output = get_option('my_option');
    echo esc_html($output);
}
add_action('wp_footer', 'voltmont_my_feature');
```

#### Adding CSS Styles

1. Create/edit files in `assets/css/components/`
2. Use BEM naming convention
3. Use CSS variables from `brand-system.css`
4. Build with `npm run build`

**Example:**
```css
/* assets/css/components/my-component.css */
.my-component {
    background-color: var(--color-primary);
    padding: var(--space-4);
    border-radius: var(--radius-md);
    transition: all var(--transition-base);
}

.my-component:hover {
    background-color: var(--color-hover);
}
```

#### Adding JavaScript

1. Create/edit files in `src/js/` (if using webpack)
2. Import in `src/js/main.js`
3. Build with `npm run build`

**Example:**
```javascript
// src/js/modules/my-feature.js
export function myFeature() {
    console.log('Feature loaded');
}

// src/js/main.js
import { myFeature } from './modules/my-feature';
myFeature();
```

---

## 🎨 Design System

### CSS Variables

All design tokens are in `assets/css/brand-system.css`:

```css
/* Colors */
var(--color-primary)      /* #4d81e9 - Main brand blue */
var(--color-secondary)    /* #041028 - Deep navy */
var(--color-text-primary) /* #041028 - Main text */

/* Spacing (8px system) */
var(--space-2)  /* 8px */
var(--space-4)  /* 16px */
var(--space-6)  /* 32px */

/* Typography */
var(--font-size-base)     /* 16px */
var(--font-weight-bold)   /* 700 */

/* Transitions */
var(--transition-base)    /* 0.3s */
```

### Component Classes

Pre-built components available:

```html
<!-- Buttons -->
<button class="btn btn-primary">Primary CTA</button>
<button class="btn btn-secondary">Secondary</button>
<button class="btn btn-ghost">Ghost (on dark)</button>

<!-- Cards -->
<div class="card">
    <div class="card-header">Header</div>
    <div class="card-body">Content</div>
    <div class="card-footer">Footer</div>
</div>

<!-- Forms -->
<div class="form-group">
    <label class="form-label">Label</label>
    <input type="text" class="form-input">
</div>
```

See `assets/css/components/` for more.

---

## 🧪 Testing Your Changes

### Before Committing

```bash
# 1. Run linters
npm run lint

# 2. Run tests
npm test

# 3. Check build
npm run build:production

# 4. Test in browser
#    - Desktop (Chrome, Firefox, Safari)
#    - Mobile (responsive mode)
#    - Test with keyboard navigation
#    - Test with screen reader (if possible)
```

### Manual Testing Checklist

- [ ] Page loads without errors (check console)
- [ ] Responsive on mobile (320px+)
- [ ] All links work
- [ ] Forms validate and submit
- [ ] Images have alt text
- [ ] Keyboard navigation works (Tab, Enter, Esc)
- [ ] Focus indicators visible
- [ ] No console warnings/errors

---

## 🔍 Debugging

### Common Issues

**Issue: npm install fails**
```bash
# Solution: Use correct Node version
nvm use
rm -rf node_modules package-lock.json
npm install
```

**Issue: webpack build fails**
```bash
# Solution: Check for syntax errors
npm run lint:js
# Fix errors, then rebuild
npm run build
```

**Issue: CSS not updating**
```bash
# Solution: Clear cache and rebuild
npm run clean
npm run build:production
# Hard refresh browser (Ctrl+Shift+R)
```

**Issue: PHP errors**
```bash
# Solution: Check PHP logs
# Location varies by environment:
# XAMPP: C:\xampp\php\logs\php_error_log
# WAMP: C:\wamp\logs\php_error.log

# Enable WordPress debug mode in wp-config.php:
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

---

## 📚 Useful Resources

### Documentation
- [README.md](README.md) - Project overview
- [CLAUDE.md](CLAUDE.md) - Development guidelines
- [ARCHITECTURE.md](ARCHITECTURE.md) - Technical architecture
- [CONTRIBUTING.md](CONTRIBUTING.md) - Contribution guidelines
- [SECURITY.md](SECURITY.md) - Security best practices

### WordPress
- [WordPress Codex](https://codex.wordpress.org/)
- [BeTheme Documentation](https://themes.muffingroup.com/betheme/documentation/)
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)

### Frontend
- [CSS Design System](docs/DESIGN-SYSTEM.md)
- [Component Library](assets/css/components/)
- [Brand Colors](docs/FULL-BRAND-COLORS.scss)

### Tools
- [Lighthouse CI](https://github.com/GoogleChrome/lighthouse-ci)
- [WP-CLI](https://wp-cli.org/)
- [Schema.org Validator](https://validator.schema.org/)

---

## 🐛 Reporting Issues

### Found a Bug?

1. Check if issue already exists: [GitHub Issues](https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website/issues)
2. If not, create new issue using template
3. Include:
   - Clear description
   - Steps to reproduce
   - Expected vs actual behavior
   - Screenshots (if visual bug)
   - Browser/environment info

### Security Issues

**Do NOT create public GitHub issue!**

Email security issues to: biuro@pbmediaonline.pl

---

## 🤝 Getting Help

### Quick Questions
- Check [CLAUDE.md](CLAUDE.md) first
- Search [GitHub Issues](https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website/issues)

### Technical Support
- **Email:** biuro@pbmediaonline.pl
- **Team Lead:** Paweł Banach

### Code Reviews
- All PRs reviewed within 24-48 hours
- Address review comments promptly
- Ask questions in PR comments

---

## ✅ Next Steps

Now that you're set up:

1. **Explore the codebase**
   - Read through `functions.php`
   - Check out CSS components
   - Review existing pages

2. **Pick a task**
   - Check GitHub Issues for "good first issue" label
   - Or ask team lead for assignment

3. **Make your first contribution**
   - Follow workflow above
   - Create small, focused PR
   - Get feedback and iterate

---

## 📞 Contact

**Questions?** Don't hesitate to ask!

- Email: biuro@pbmediaonline.pl
- GitHub: Open a discussion or issue

---

**Welcome to the team! Happy coding! 🚀**

---

*Last updated: 2024-11-25*  
*Version: 1.0*
