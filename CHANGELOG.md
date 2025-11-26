# Changelog – trzebnica-elektryk.pl

All notable changes to the Voltmont website project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

### Added
- Complete GitHub Issue Templates (bug, feature, security, accessibility, seo, performance, content, documentation)
- Pull Request Template with comprehensive checklist
- PR Review Guide (docs/PR_REVIEW_GUIDE.md)
- Onboarding Checklist for new developers (docs/ONBOARDING_CHECKLIST.md)
- GitHub Issue Template config.yml with contact links
- Documentation issue template
- Enhanced QUICK_START.md with Git conventions and learning resources
- Portfolio schema.org implementation (CreativeWork + ItemList)
- Portfolio meta box for schema data (client, date, location)
- Portfolio admin columns with sortable schema fields
- FAQ schema automatic extraction from page content
- FAQ meta box with dynamic fields in WordPress admin
- **Transient caching for expensive queries (portfolio, menus, terms)**
- **WebP automatic conversion for uploaded images (JPEG/PNG)**
- **Lazy loading for all images (native + background images)**
- **Inline CSS/JS minification in content**
- **Admin bar cache clear button**
- **Automatic cache invalidation on content updates**
- **Comprehensive SEO functions (30+ SEO enhancements)**
- **Article schema for blog posts**
- **Organization & WebSite schemas**
- **Video schema for embedded videos**
- **Person schema for about pages**
- **Automatic image alt text generation**
- **Mobile-specific meta tags**
- **Site verification meta tags (ready to configure)**
- **RSS feed links**
- **SEO-friendly pagination (rel prev/next)**
- **Reading time calculation**
- **Related posts functionality**
- **Professional service page template (template-service.php)**
- **Navigation CSS component**
- **Footer CSS component**
- **Jest testing setup and examples**
- **PHPUnit testing setup and examples**
- **Integration testing examples**
- **Comprehensive documentation reorganization**
- **Updated README.md with complete repository structure**
- **Updated docs/README.md with categorized documentation index**

### Changed
- Updated Pull Request Template with detailed quality, testing, accessibility, SEO, and security checklists
- Improved QUICK_START.md with Conventional Commits guidelines and branch naming conventions
- Enhanced issue reporting section with all available templates
- Optimized meta descriptions with dynamic generation (150-160 chars)
- Enhanced LocalBusiness schema with service offerings
- Improved OpenGraph tags with article-specific metadata
- **Expanded performance-optimization.php with caching, WebP, lazy loading, and minification**
- **Massively expanded functions-seo.php with 30+ SEO functions**
- **Dynamic meta tag generation for all page types**
- **Automatic noindex for search/404 pages**

### Fixed
- Meta description length optimization for better SEO
- Schema.org structured data priorities (proper execution order)
- **Performance improvements with transient caching (12-24h TTL)**
- **Image optimization with automatic WebP conversion**

### Planned
- Blog section implementation
- Video content integration (YouTube embeds)
- Local landing pages (Trzebnica, Wrocław, Olesnica)
- Newsletter subscription system
- Live chat integration
- Customer testimonials section

---

## [2.0.0] - 2024-01-15

### Added
- Complete project documentation (CLAUDE.md, SEO-STRATEGY.md, SECURITY.md, etc.)
- Brand design system (design-system.css with CSS variables)
- Schema.org LocalBusiness + Service markup
- Custom post type: Portfolio (galeria-realizacji)
- Custom taxonomies: portfolio-types, portfolio-tags, page_category
- Webpack build system for asset optimization
- GitHub Actions CI/CD workflows
- Pre-commit hooks (lint-staged + husky)
- AJAX portfolio loading
- Mobile-responsive navigation
- Emergency contact bar (sticky footer)
- Cookie consent manager
- Iframe security manager
- WebP image support

### Changed
- Migrated from BeTheme child to hubag-child theme
- Updated PHP requirement to 8.0+
- Improved SEO meta tag generation
- Enhanced security headers (.htaccess)
- Optimized database queries with transients
- Refactored CSS to use BEM methodology

### Fixed
- Mobile menu overflow issue on devices < 375px
- 404 redirect to homepage (301)
- Form validation edge cases
- Image lazy loading conflicts
- Contact form spam protection

### Security
- Implemented nonce verification for all forms
- Added CSRF protection
- Escaped all output (esc_html, esc_attr, esc_url)
- Sanitized all input (sanitize_text_field, etc.)
- Disabled file editing from dashboard (DISALLOW_FILE_EDIT)
- Restricted XML-RPC access
- Added security headers (X-Frame-Options, CSP, etc.)

---

## [1.5.0] - 2023-12-01

### Added
- Contact Form 7 integration
- Google Analytics 4 tracking
- Google Tag Manager setup
- Social media widgets (Facebook, Instagram)
- Service pages with Muffin Builder
- Portfolio gallery

### Changed
- Updated WordPress core to 6.4
- Theme options customization
- Header layout improvements

### Fixed
- Mobile responsiveness issues
- Cross-browser compatibility (Safari, Firefox)

---

## [1.0.0] - 2023-10-15

### Added
- Initial WordPress setup
- BeTheme installation and activation
- Basic content structure (pages, menus)
- Essential plugins (Wordfence, WP Super Cache)
- SSL certificate configuration
- Domain setup (trzebnica-elektryk.pl)

### Changed
- Default WordPress settings
- Permalink structure (/%postname%/)

---

## Version History Summary

| Version | Date | Description |
|---------|------|-------------|
| 2.0.0 | 2024-01-15 | Major refactor, documentation, optimization |
| 1.5.0 | 2023-12-01 | Forms, analytics, portfolio |
| 1.0.0 | 2023-10-15 | Initial launch |

---

## Upcoming Releases

### [2.1.0] - Planned Q1 2024
- Blog implementation
- Advanced portfolio filtering
- Video testimonials
- Live chat widget
- Performance optimizations

### [2.2.0] - Planned Q2 2024
- Multilingual support (Polish + English)
- Advanced SEO features
- Progressive Web App (PWA) capabilities
- Offline support

### [3.0.0] - Planned Q3 2024
- Full redesign based on user feedback
- Enhanced mobile experience
- Headless CMS consideration
- API-first architecture

---

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines on proposing changes.

---

**Maintained by:** PB-MEDIA Development Team  
**Last Updated:** 2024-01-15
