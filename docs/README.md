# Documentation Index - trzebnica-elektryk.pl

**Complete documentation for Voltmont WordPress project**

---

## 🚀 START HERE

### For New Developers
1. **[ONBOARDING_CHECKLIST.md](ONBOARDING_CHECKLIST.md)** - Complete onboarding guide
2. **[../QUICK_START.md](../QUICK_START.md)** - 15-minute setup
3. **[BASE-KNOWLEDGE.md](BASE-KNOWLEDGE.md)** - WordPress & BeTheme basics
4. **[../CLAUDE.md](../CLAUDE.md)** - Development guidelines

### Project Context
- **[BRIEF-PROJECT.md](BRIEF-PROJECT.md)** - Business requirements & goals
- **[../ARCHITECTURE.md](../ARCHITECTURE.md)** - Technical architecture
- **[../SEO-STRATEGY.md](../SEO-STRATEGY.md)** - SEO & content strategy

---

## 📚 Documentation Categories

### 🎨 Design & Brand

#### Brand System
- **[SUMMARY-BRAND-COLORS.md](SUMMARY-BRAND-COLORS.md)** - Brand color overview
- **[KOLORYSTYKA-ROOT-BRAND-COLOR-CSS.md](KOLORYSTYKA-ROOT-BRAND-COLOR-CSS.md)** - CSS color variables
- **[FULL-BRAND-COLORS.scss](FULL-BRAND-COLORS.scss)** - Complete design tokens
- **[../DESIGN-SYSTEM.md](../DESIGN-SYSTEM.md)** - CSS design system
- **[../src/css/components/README.md](../src/css/components/README.md)** - CSS components

---

## 🧪 Testing & Quality

### Testing Documentation
- **[TESTING_COMPONENTS_GUIDE.md](TESTING_COMPONENTS_GUIDE.md)** - Complete testing guide (26 KB)
- **[../TESTING.md](../TESTING.md)** - Testing strategy
- **[../tests/README.md](../tests/README.md)** - JavaScript (Jest) testing
- **[../tests/php/README.md](../tests/php/README.md)** - PHP (PHPUnit) testing
- **[SCHEMA_TESTING_CHECKLIST.md](SCHEMA_TESTING_CHECKLIST.md)** - Schema validation

### Code Quality
- **[PR_REVIEW_GUIDE.md](PR_REVIEW_GUIDE.md)** - Code review guidelines
- **[../CONTRIBUTING.md](../CONTRIBUTING.md)** - Contribution guidelines

---

## 📄 Templates & Components

- **[../templates/TEMPLATE-SERVICE-PAGE.md](../templates/TEMPLATE-SERVICE-PAGE.md)** - Service page docs
- **[../templates/TEMPLATE-PORTFOLIO-ITEM.md](../templates/TEMPLATE-PORTFOLIO-ITEM.md)** - Portfolio docs
- **Implementation:** `dist/wp-content/themes/hubag-child/template-service.php`

---

### Code Quality

- **[TESTING.md](../TESTING.md)** - Testing strategy and requirements
- **[PR_REVIEW_GUIDE.md](PR_REVIEW_GUIDE.md)** - Code review guidelines for reviewers
- **[PERFORMANCE_GUIDE.md](PERFORMANCE_GUIDE.md)** - Performance optimization guide

---

## 🤝 Contributing

### Process

- **[CONTRIBUTING.md](../CONTRIBUTING.md)** - Contribution guidelines
- **[CODE_OF_CONDUCT.md](../CODE_OF_CONDUCT.md)** - Community standards
- **[PR_REVIEW_GUIDE.md](PR_REVIEW_GUIDE.md)** - How to review Pull Requests

### Templates

- **[.github/PULL_REQUEST_TEMPLATE.md](../.github/PULL_REQUEST_TEMPLATE.md)** - PR template
- **[.github/ISSUE_TEMPLATE/](../.github/ISSUE_TEMPLATE/)** - Issue templates:
  - `bug_report.md` - Bug reports
  - `feature_request.md` - Feature requests
  - `security_vulnerability.md` - Security issues (use email!)
  - `accessibility_issue.md` - WCAG compliance issues
  - `seo_issue.md` - SEO problems
  - `performance_issue.md` - Performance issues
  - `content_update.md` - Content changes
  - `documentation.md` - Documentation updates

---

## 🚀 Deployment

- **[DEPLOYMENT.md](../DEPLOYMENT.md)** - Deployment process and CI/CD
- **[CHANGELOG.md](../CHANGELOG.md)** - Version history and releases

---

## 🔍 SEO & Marketing

- **[SEO-STRATEGY.md](../SEO-STRATEGY.md)** - SEO strategy and keywords
- **[COMPREHENSIVE_SEO_GUIDE.md](COMPREHENSIVE_SEO_GUIDE.md)** - Complete SEO implementation guide (30+ features)
- **[SCHEMA_ORG_GUIDE.md](SCHEMA_ORG_GUIDE.md)** - Complete schema.org implementation guide
- **Schema.org implementations:**
  - **LocalBusiness** - `inc/schema-localbusiness.php` - Company info, services, location
  - **Service** - `inc/schema-localbusiness.php` - Individual service pages
  - **FAQPage** - `inc/faq-schema.php` - FAQ with admin interface
  - **CreativeWork** - `inc/schema-portfolio.php` - Portfolio items
  - **ItemList** - `inc/schema-portfolio.php` - Portfolio archives
  - **BreadcrumbList** - `inc/schema-localbusiness.php` - Navigation breadcrumbs
  - **Article** - `inc/functions-seo.php` - Blog posts
  - **Organization** - `inc/functions-seo.php` - Company data
  - **WebSite** - `inc/functions-seo.php` - Site structure
  - **VideoObject** - `inc/functions-seo.php` - Embedded videos
  - **Person** - `inc/functions-seo.php` - About page

---

## 👥 Team & Credits

- **[AUTHORS.md](../AUTHORS.md)** - Project contributors
- **[ACKNOWLEDGMENTS.md](../ACKNOWLEDGMENTS.md)** - Third-party libraries and credits

---

## 📄 Legal

- **[LICENSE.md](../LICENSE.md)** - Project license (Proprietary)
- **[CODE_OF_CONDUCT.md](../CODE_OF_CONDUCT.md)** - Community guidelines

---

## 📞 Support

- **[SUPPORT.md](../SUPPORT.md)** - How to get help
- **Email:** biuro@pbmediaonline.pl
- **GitHub Issues:** [Create issue](https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website/issues/new/choose)

---

## 🗂️ Documentation by Role

### For Developers

**Must read:**
1. [QUICK_START.md](../QUICK_START.md)
2. [CLAUDE.md](../CLAUDE.md)
3. [SECURITY.md](../SECURITY.md)
4. [TESTING.md](../TESTING.md)

**Should read:**
- [ARCHITECTURE.md](../ARCHITECTURE.md)
- [DESIGN-SYSTEM.md](../DESIGN-SYSTEM.md)
- [PR_REVIEW_GUIDE.md](PR_REVIEW_GUIDE.md)

### For Code Reviewers

**Must read:**
1. [PR_REVIEW_GUIDE.md](PR_REVIEW_GUIDE.md)
2. [SECURITY.md](../SECURITY.md)
3. [TESTING.md](../TESTING.md)

### For Content Managers

**Must read:**
1. [SEO-STRATEGY.md](../SEO-STRATEGY.md)
2. Content update template (`.github/ISSUE_TEMPLATE/content_update.md`)

### For DevOps

**Must read:**
1. [DEPLOYMENT.md](../DEPLOYMENT.md)
2. [SECURITY.md](../SECURITY.md)
3. [ARCHITECTURE.md](../ARCHITECTURE.md)

### For New Team Members

**Complete checklist:**
1. [ONBOARDING_CHECKLIST.md](ONBOARDING_CHECKLIST.md) - Follow step by step

---

## 🔄 Documentation Updates

### How to Update Documentation

1. Find the relevant document (see index above)
2. Create branch: `git checkout -b docs/update-xxx`
3. Make changes
4. Commit: `git commit -m "docs: update XXX documentation"`
5. Create PR with "documentation" label
6. Get review from team lead

### Documentation Standards

- **Format:** Markdown (.md)
- **Linting:** Use markdownlint (npm run lint:md if available)
- **Tone:** Professional but friendly
- **Language:** English for code/tech, Polish for business context OK
- **Links:** Always relative links within repo
- **Last updated:** Update date at bottom of changed files

---

## 📊 Documentation Metrics

**Current status:**
- ✅ 20+ documentation files
- ✅ 8 GitHub Issue Templates
- ✅ Comprehensive PR Template
- ✅ Developer Onboarding Guide
- ✅ Code Review Guide

**Coverage:**
- [x] Getting Started
- [x] Development Guidelines
- [x] Security
- [x] SEO Strategy
- [x] Testing
- [x] Deployment
- [x] Contributing
- [x] Code Review

---

## 🎯 Documentation Roadmap

### Planned Documentation

- [ ] Video tutorials (YouTube)
- [ ] Interactive onboarding (GitHub Codespaces)
- [ ] API documentation (Swagger/OpenAPI)
- [ ] Component Storybook
- [ ] Performance optimization guide
- [ ] Database schema documentation
- [ ] Troubleshooting guide (FAQ)

### Improvement Areas

- [ ] More code examples
- [ ] Architecture diagrams
- [ ] Flowcharts for complex processes
- [ ] Screenshots/GIFs for UI features
- [ ] Translations (full PL versions)

---

## 🆘 Can't Find What You Need?

**If documentation is missing or unclear:**

1. **Search:** Use GitHub search or Ctrl+F
2. **Ask:** Create [GitHub Discussion](https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website/discussions)
3. **Report:** Create issue using [documentation template](../.github/ISSUE_TEMPLATE/documentation.md)
4. **Email:** biuro@pbmediaonline.pl

---

**Documentation maintained by:** PB-MEDIA Development Team  
**Last reviewed:** 2024-11-25  
**Next review:** 2025-02-01

---

*This index is automatically updated when new documentation is added.*
