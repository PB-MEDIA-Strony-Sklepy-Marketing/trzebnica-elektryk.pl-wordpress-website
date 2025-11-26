# Onboarding Checklist - Nowy Developer

**Witaj w zespole trzebnica-elektryk.pl! 👋**

Ta checklist pomoże Ci w pierwszych dniach pracy.

---

## ✅ Pre-work (przed pierwszym dniem)

### 1. Konta i dostępy

- [ ] Konto GitHub utworzone
- [ ] Dodany do repozytorium (poproś team lead)
- [ ] Skonfigurowane 2FA na GitHubie
- [ ] Dostęp do email teamowego (jeśli dotyczy)
- [ ] Dostęp do WordPress admin (staging/dev)

### 2. Zainstalowane narzędzia

- [ ] **Git** ([Download](https://git-scm.com/downloads))
- [ ] **Node.js 18+** ([Download](https://nodejs.org/)) lub nvm
- [ ] **PHP 8.0+** ([Download](https://www.php.net/downloads))
- [ ] **Composer** ([Download](https://getcomposer.org/))
- [ ] **Code Editor:** VS Code ([Download](https://code.visualstudio.com/))
- [ ] **Local WordPress:** XAMPP/WAMP/Local by Flywheel

### 3. VS Code Extensions (recommended)

- [ ] **PHP Intelephense** - PHP autocompletion
- [ ] **ESLint** - JavaScript linting
- [ ] **Stylelint** - CSS linting
- [ ] **EditorConfig** - consistent formatting
- [ ] **GitLens** - Git superpowers
- [ ] **Prettier** - code formatter
- [ ] **WordPress Snippets** - WP code snippets
- [ ] **Live Server** - local development server

---

## ✅ Dzień 1 - Setup

### 4. Clone repozytorium

```bash
git clone https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website.git
cd trzebnica-elektryk.pl-wordpress-website
```

- [ ] Repozytorium sklonowane
- [ ] Branch `main` aktualny

### 5. Install dependencies

```bash
# Set Node version (if using nvm)
nvm install
nvm use

# Install npm packages
npm install

# Install PHP dependencies (optional)
composer install
```

- [ ] `npm install` zakończony sukcesem
- [ ] `composer install` zakończony (opcjonalnie)
- [ ] Brak błędów

### 6. Konfiguracja środowiska

```bash
# Build assets
npm run build

# Run linters (should pass)
npm run lint
```

- [ ] Build przeszedł bez błędów
- [ ] Lintery przechodzą (0 errors)

### 7. Git configuration

```bash
# Set your identity
git config user.name "Your Name"
git config user.email "your.email@example.com"

# Recommended: Set pull rebase
git config pull.rebase true
```

- [ ] Git user.name ustawiony
- [ ] Git user.email ustawiony

---

## ✅ Dzień 1-2 - Poznanie projektu

### 8. Przeczytaj dokumentację

**Musi:**
- [ ] [README.md](../README.md) - project overview
- [ ] [QUICK_START.md](../QUICK_START.md) - quick start guide
- [ ] [CLAUDE.md](../CLAUDE.md) - development guidelines
- [ ] [SECURITY.md](../SECURITY.md) - security best practices

**Powinno:**
- [ ] [ARCHITECTURE.md](../ARCHITECTURE.md) - technical architecture
- [ ] [CONTRIBUTING.md](../CONTRIBUTING.md) - contribution guidelines
- [ ] [TESTING.md](../TESTING.md) - testing requirements
- [ ] [PR_REVIEW_GUIDE.md](PR_REVIEW_GUIDE.md) - code review guide

**Nice-to-have:**
- [ ] [SEO-STRATEGY.md](../SEO-STRATEGY.md) - SEO strategy
- [ ] [DESIGN-SYSTEM.md](../DESIGN-SYSTEM.md) - design system
- [ ] [DEPLOYMENT.md](../DEPLOYMENT.md) - deployment process

### 9. Explore codebase

**WordPress theme:**
```
dist/wp-content/themes/hubag-child/
├── assets/          # Compiled CSS/JS
├── inc/             # PHP includes
├── templates/       # PHP templates
└── functions.php    # Main theme functions
```

- [ ] Przejrzany `functions.php`
- [ ] Sprawdzone komponenty CSS w `assets/css/components/`
- [ ] Zrozumiana struktura katalogów
- [ ] Znaleziony przykład schema.org w `inc/`

### 10. Uruchom lokalnie

**Konfiguracja WordPress:**
1. Skopiuj `dist/` do swojego local WordPress
2. Aktywuj theme `hubag-child`
3. Otwórz w przeglądarce

- [ ] WordPress działa lokalnie
- [ ] Theme `hubag-child` aktywny
- [ ] Strona główna ładuje się poprawnie
- [ ] Brak błędów w konsoli

---

## ✅ Dzień 2-3 - Pierwsze zadania

### 11. Development workflow

```bash
# Create feature branch
git checkout -b feature/my-first-feature

# Make changes...

# Test changes
npm run lint
npm test
npm run build

# Commit (Conventional Commits)
git add .
git commit -m "feat: add my first feature"

# Push
git push origin feature/my-first-feature
```

- [ ] Stworzony test branch
- [ ] Zrobiona testowa zmiana
- [ ] Commitowane z proper message format
- [ ] Pushed do remote

### 12. Issue tracking

- [ ] Znaleziono [GitHub Issues](https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website/issues)
- [ ] Przefiltrowane issue z labelką "good first issue"
- [ ] Assigned do siebie issue (lub poproszony team lead)
- [ ] Przeczytany template dla różnych typów issues

### 13. Pierwszy Pull Request

**Stwórz małą zmianę (np. typo w dokumentacji):**

1. Create branch: `git checkout -b docs/fix-typo`
2. Make change
3. Commit: `git commit -m "docs: fix typo in QUICK_START"`
4. Push: `git push origin docs/fix-typo`
5. Create PR on GitHub using template

- [ ] Pierwszy PR stworzony
- [ ] Wypełniony PR template
- [ ] Requested review od team lead
- [ ] PR zaapproved i zmerged (gratulacje! 🎉)

---

## ✅ Tydzień 1 - Praca w zespole

### 14. Team communication

- [ ] Przedstawiony zespołowi (email/chat)
- [ ] Dodany do communication channel
- [ ] Znany team lead i kontakty
- [ ] Pytania mile widziane! 😊

### 15. Code review

**Review someone else's PR:**

- [ ] Znaleziony open PR do review
- [ ] Przeczytany [PR_REVIEW_GUIDE.md](PR_REVIEW_GUIDE.md)
- [ ] Zostawiony constructive feedback
- [ ] Nauczony się z kodu innych

### 16. Pierwsze "prawdziwe" zadanie

**Pick issue labeled "good first issue":**

- [ ] Issue assigned
- [ ] Branch stworzony
- [ ] Development setup working
- [ ] Changes committed
- [ ] PR created with full checklist
- [ ] Addressed review comments
- [ ] PR merged! 🚀

---

## ✅ Miesiąc 1 - Komfort w projekcie

### 17. WordPress expertise

- [ ] Rozumiem WordPress hooks (actions/filters)
- [ ] Potrafię dodać custom post type
- [ ] Znam WP sanitization/escaping functions
- [ ] Rozumiem theme hierarchy
- [ ] Wiem jak działa child theme (hubag-child)

### 18. Security awareness

- [ ] Zawsze sanitize input
- [ ] Zawsze escape output
- [ ] Verify nonce for forms
- [ ] Check user capabilities
- [ ] No SQL injection (use $wpdb->prepare)
- [ ] Przeczytane [SECURITY.md](../SECURITY.md) w całości

### 19. SEO & Accessibility

- [ ] Rozumiem schema.org markup
- [ ] Wiem jak testować WCAG compliance
- [ ] Używam semantic HTML
- [ ] Dbam o alt text w obrazach
- [ ] Meta tags correct dla każdej strony

### 20. Performance & Quality

- [ ] Run Lighthouse before every PR
- [ ] Check bundle sizes
- [ ] Optimize images (WebP, lazy loading)
- [ ] No N+1 queries
- [ ] Tests written (if applicable)

---

## ✅ Miesiąc 2-3 - Niezależność

### 21. Praca samodzielna

- [ ] Potrafię wziąć issue i zrobić od A do Z
- [ ] Nie boję się zadawać pytań
- [ ] Review innych PRs regularnie
- [ ] Contribute to documentation
- [ ] Sugeruję improvements

### 22. Obszary ekspertyzy

Wybierz 1-2 obszary do specjalizacji:

- [ ] **SEO** - schema.org, meta tags, performance
- [ ] **Accessibility** - WCAG compliance, screen readers
- [ ] **Performance** - Core Web Vitals, optimization
- [ ] **Security** - vulnerability prevention
- [ ] **Frontend** - CSS, JavaScript, animations
- [ ] **Backend** - PHP, WordPress APIs, custom features

### 23. Mentorship

- [ ] Help onboard next new developer
- [ ] Share knowledge in team meetings
- [ ] Write docs for complex features
- [ ] Answer questions from team

---

## 🎯 Success Metrics

**By end of Month 1:**
- [ ] 3+ merged PRs
- [ ] 5+ code reviews done
- [ ] 0 critical security issues in PRs
- [ ] Comfortable with WordPress + BeTheme
- [ ] Independent in development workflow

**By end of Month 3:**
- [ ] 15+ merged PRs
- [ ] Contributing to architecture decisions
- [ ] Mentoring new developers
- [ ] Expert in 1-2 specialized areas

---

## 🆘 Stuck? Need Help?

**Don't struggle alone! Ask for help:**

### Questions about:
- **Code/Development:** Create GitHub Discussion or ask in PR comments
- **Access/Permissions:** Email team lead (biuro@pbmediaonline.pl)
- **Process/Workflow:** Check [CONTRIBUTING.md](../CONTRIBUTING.md)
- **Urgent Issues:** Email or call team lead directly

### Resources:
- [QUICK_START.md](../QUICK_START.md) - getting started
- [CLAUDE.md](../CLAUDE.md) - development guidelines
- [PR_REVIEW_GUIDE.md](PR_REVIEW_GUIDE.md) - code review
- WordPress Slack/Forums - community help

---

## 🎉 Gratulacje!

Jeśli dotarłeś tutaj, jesteś gotowy do pracy w projekcie!

**Welcome to the team! 🚀**

---

**Questions or feedback about this checklist?**  
Create issue or PR to improve it!

---

*Last updated: 2024-11-25*  
*Version: 1.0*
