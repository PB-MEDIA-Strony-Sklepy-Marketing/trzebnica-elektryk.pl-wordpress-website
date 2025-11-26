# trzebnica-elektryk.pl – Voltmont Instalacje Elektryczne

Repozytorium produkcyjne WordPress dla strony **[trzebnica-elektryk.pl](https://trzebnica-elektryk.pl)**, realizowanej dla firmy **Voltmont – Instalacje Elektryczne** (Trzebnica, Dolny Śląsk).

Projekt opiera się na komercyjnym motywie **BeTheme**, z własnym motywem pochodnym:

- Motyw finalny na produkcję: `dist/wp-content/themes/hubag/` (parent)  
- Child theme (customizacje): `dist/wp-content/themes/hubag-child/`
- Oryginalny motyw BeTheme: `src/wp-content/themes/betheme/`
- Oryginalny child BeTheme: `src/wp-content/themes/betheme-child/`
- Dokumentacja motywu: `docs/documentation/`
- Brand colors (podstawowe): `docs/KOLORYSTYKA-ROOT-BRAND-COLOR-CSS.md`
- Rozszerzona paleta brandowa: `docs/FULL-BRAND-COLORS.scss`
- Brief projektu: `docs/BRIEF-PROJECT.md`

## Technologie i założenia

- **WordPress**: 6.4+
- **PHP**: 8.0+
- **BeTheme** (z Muffin Builderem)
- **Child theme**: wszystkie customizacje w `hubag-child`
- **Stylowanie**: BEM, SCSS/CSS
- **SEO i UX**: lokalne pozycjonowanie na frazy związane z usługami elektrycznymi w Trzebnicy i Dolnym Śląsku
- **Dostępność**: WCAG 2.2 AA (kontrast, focus, klawiatura, aria)

## Struktura repozytorium

```text
dist/
  wp-content/
    themes/
      hubag/         # Motyw parent oparty na BeTheme, gotowy do produkcji
      hubag-child/   # Customizacje dla trzebnica-elektryk.pl

src/
  wp-content/
    themes/
      betheme/       # Oryginalny motyw BeTheme
      betheme-child/ # Oryginalny child BeTheme

docs/
  documentation/                 # Dokumentacja oryginalnego motywu
  BRIEF-PROJECT.md              # Brief biznesowo-marketingowy
  KOLORYSTYKA-ROOT-BRAND-COLOR-CSS.md
  FULL-BRAND-COLORS.scss        # Rozszerzony system kolorów / design tokens

.github/
  workflows/
    ci-wordpress.yml            # Lint PHP + weryfikacja pod WordPress
    lint-and-style.yml          # Lint CSS/SCSS/JS
    pagespeed-monitor.yml       # (opcjonalnie) Lighthouse / PageSpeed
  ISSUE_TEMPLATE/               # 8 issue templates (bug, feature, security, etc.)
    bug_report.md
    feature_request.md
    security_vulnerability.md
    accessibility_issue.md
    seo_issue.md
    performance_issue.md
    content_update.md
    documentation.md
    config.yml                  # Issue template configuration
  PULL_REQUEST_TEMPLATE.md      # Comprehensive PR checklist

docs/
  README.md                     # Documentation index (START HERE!)
  PR_REVIEW_GUIDE.md            # Code review guidelines
  ONBOARDING_CHECKLIST.md       # New developer onboarding
  (other documentation files...)
```

## 🚀 Quick Start

**New developer?** Get started in 15 minutes:

1. **[QUICK_START.md](QUICK_START.md)** - Step-by-step setup guide
2. **[docs/ONBOARDING_CHECKLIST.md](docs/ONBOARDING_CHECKLIST.md)** - Complete onboarding checklist
3. Clone, install, build:
   ```bash
   git clone <repo-url>
   cd trzebnica-elektryk.pl-wordpress-website
   nvm use          # Use Node 18.20.0
   npm install      # Install dependencies
   npm run build    # Build assets
   ```

**Need help?** See [Documentation Index](docs/README.md)

---

## 📖 Essential Documentation

### For Developers
- **[CLAUDE.md](CLAUDE.md)** - Development guidelines (WordPress, security, SEO)
- **[ARCHITECTURE.md](ARCHITECTURE.md)** - Technical architecture
- **[SECURITY.md](SECURITY.md)** - Security best practices
- **[TESTING.md](TESTING.md)** - Testing strategy
- **[DESIGN-SYSTEM.md](DESIGN-SYSTEM.md)** - CSS variables, components

### For Contributors
- **[CONTRIBUTING.md](CONTRIBUTING.md)** - How to contribute
- **[docs/PR_REVIEW_GUIDE.md](docs/PR_REVIEW_GUIDE.md)** - Code review guidelines
- **[.github/PULL_REQUEST_TEMPLATE.md](.github/PULL_REQUEST_TEMPLATE.md)** - PR template
- **[CODE_OF_CONDUCT.md](CODE_OF_CONDUCT.md)** - Community standards

### For SEO/Content
- **[SEO-STRATEGY.md](SEO-STRATEGY.md)** - Keywords, schema.org strategy
- **[docs/BRIEF-PROJECT.md](docs/BRIEF-PROJECT.md)** - Business context

### All Documentation
- **[docs/README.md](docs/README.md)** - Complete documentation index

---

- **Front-end produkcyjny**: szukaj plików w `dist/wp-content/themes/hubag-child/`
  - Custom CSS/SCSS: `style.css`, dodatkowe pliki w folderach `css/`, `scss/`
  - Skrypty JS: `js/`
  - Szablony: `*.php` (np. `header.php`, `footer.php`, `page-*.php`)
- **Odwzorowanie BeTheme**:
  - jeśli chcesz zobaczyć oryginalną implementację, sprawdź `src/wp-content/themes/betheme`
- **Dokumentacja**:
  - Techniczne informacje o BeTheme: `docs/documentation`
  - Kontekst biznesowo-SEO: `docs/BRIEF-PROJECT.md`
  - Kolory i design system: `docs/FULL-BRAND-COLORS.scss`

## Brand & UI

- Główne fonty: nowoczesne sans-serif (Inter / Poppins lub podobne)
- Kolorystyka:
  - Primary: `#4d81e9`
  - Secondary: `#041028`
  - Tło bazowe: `#163162`
  - Tekst na ciemnym tle: `#edf0fd`
- Pełna paleta i zmienne CSS/SCSS: `docs/FULL-BRAND-COLORS.scss`

## Standardy kodowania

- **PHP**: PSR-12, sprawdzany przez `php-cs-fixer` (`.php-cs-fixer.dist.php`)
- **CSS/SCSS**: BEM, walidowany przez Stylelint (`.stylelintrc.json`)
- **JS**: ESLint (`.eslint.config.mjs`)
- **Markdown**: markdownlint (`.markdownlint.json`)

### Uruchomienie narzędzi lokalnie (przykład)

```bash
# PHP CS Fixer
composer global require friendsofphp/php-cs-fixer
php-cs-fixer fix

# ESLint + Stylelint (jeśli istnieje package.json)
npm install
npx eslint .
npx stylelint "**/*.{css,scss}"
```

## Workflow CI

- `ci-wordpress.yml`:
  - Lint PHP ( `php -l` ) w katalogach `src` i `dist`
  - `php-cs-fixer` w trybie `--dry-run`
- `lint-and-style.yml`:
  - Uruchamia ESLint dla plików JS
  - Uruchamia Stylelint dla plików CSS/SCSS
- `pagespeed-monitor.yml` (opcjonalny):
  - Możliwość monitorowania PageSpeed / Lighthouse dla wersji produkcyjnej

## Integracja z BeTheme

- Customizacje umieszczaj w `dist/wp-content/themes/hubag-child/`
- Wykorzystuj:
  - Muffin Builder i krótkie kody BeTheme
  - Hooki: `mfn_hook_top`, `mfn_hook_content_before` itp.
- Szablony nadpisujące BeTheme:
  - Twórz pliki o tej samej nazwie/ścieżce w child theme
- Animacje:
  - Delikatne, 0.3s `ease`, bez nadmiernych efektów – szczególnie dla CTA i sekcji usług

## 🔍 SEO & schema.org

### Implemented Structured Data

- **LocalBusiness + Service:** Homepage and service pages
  - Company information, contact details, opening hours
  - Service catalog with 6 main offerings
  - Location: Trzebnica, Dolnośląskie (51.3094, 17.0628)
  
- **FAQPage:** Service pages with FAQs
  - Auto-extraction from content (BeTheme accordion, HTML headings)
  - WordPress meta box for manual entry
  - Google Rich Results ready
  
- **CreativeWork (Portfolio):** Individual portfolio items
  - Project details (client, date, location)
  - Featured image integration
  - WordPress meta box for schema data
  
- **ItemList:** Portfolio archives
  - List of all portfolio items
  - Pagination support
  
- **BreadcrumbList:** All pages except homepage
  - Navigation hierarchy
  - Parent page support

### SEO Meta Tags

- **Dynamic meta descriptions** (150-160 chars)
  - Homepage optimized for "elektryk Trzebnica"
  - Service pages with location keywords
  - Auto-generated from content
  
- **OpenGraph tags** for social sharing
  - Facebook, LinkedIn optimization
  - Dynamic images from featured image
  - Business contact data
  
- **Twitter Cards**
  - Summary large image format
  - Dynamic content
  
- **Geo tags** for local SEO
  - PL-DS region
  - Trzebnica placename
  - GPS coordinates

**Full Documentation:** [docs/SCHEMA_ORG_GUIDE.md](docs/SCHEMA_ORG_GUIDE.md)

---

## ⚡ Performance Optimization

### Implemented Optimizations

**Transient Caching:**
- Portfolio queries (12-hour cache)
- Navigation menus (24-hour cache)
- Taxonomy terms (24-hour cache)
- Automatic cache invalidation on updates
- Admin bar cache clear button

**WebP Image Conversion:**
- Automatic JPEG/PNG → WebP conversion
- 85% quality (25-35% file size reduction)
- All image sizes converted
- Browser fallback support
- Transparent PNG support

**Lazy Loading:**
- Native lazy loading (`loading="lazy"`)
- Background image lazy loading (Intersection Observer)
- Async image decoding
- Applied to: content, featured images, avatars

**CSS/JS Minification:**
- Inline CSS minification
- Inline JavaScript minification
- Preserves JSON-LD schemas
- Applied to: content, wp_head output

**Additional:**
- Preconnect to external domains
- Heartbeat API optimization
- Post revisions limited (5)
- Autosave interval (2 minutes)

**Expected Results:**
- 🎯 PageSpeed Score: 90-95
- 📉 Page Size: -50% reduction
- ⚡ Load Time: -50% faster
- 📊 Requests: -40% fewer

**Full Documentation:** [docs/PERFORMANCE_GUIDE.md](docs/PERFORMANCE_GUIDE.md)

---

## 📚 SEO Strategy

For kluczowych podstron (instalacje, modernizacje, WLZ, nadzór, SMART, odgromowe):
  - Używaj `LocalBusiness` + `Service` + `FAQPage` (JSON-LD)
- Meta:
  - Tytuł: 50–60 znaków
  - Opis: 150–160 znaków
- Open Graph:
  - Ustalone meta tagi dla głównych stron + grafika social
- Wewnętrzne linkowanie:
  - Linkuj pomiędzy usługami (np. „instalacje odgromowe” → „modernizacja instalacji w blokach”, „nadzór elektryczny” itp.)

## 🐛 Reporting Issues

Found a bug or have a suggestion? We have templates for:

- **Bug Report** - functional issues
- **Feature Request** - new features or enhancements
- **Security Vulnerability** - ⚠️ **EMAIL ONLY:** biuro@pbmediaonline.pl
- **Accessibility Issue** - WCAG compliance problems
- **SEO Issue** - search engine optimization problems
- **Performance Issue** - speed/optimization issues
- **Content Update** - content changes needed
- **Documentation** - documentation improvements

**[Create an issue →](https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website/issues/new/choose)**

---

## 🤝 Contributing

We welcome contributions! Here's how:

1. **Read** [CONTRIBUTING.md](CONTRIBUTING.md)
2. **Check** [docs/PR_REVIEW_GUIDE.md](docs/PR_REVIEW_GUIDE.md) for review standards
3. **Create** a feature branch: `git checkout -b feature/your-feature`
4. **Follow** [Conventional Commits](https://www.conventionalcommits.org/):
   ```bash
   git commit -m "feat: add new feature"
   git commit -m "fix: resolve mobile menu issue"
   git commit -m "docs: update README"
   ```
5. **Submit** PR using our [template](.github/PULL_REQUEST_TEMPLATE.md)

**New contributor?** Look for issues labeled [`good first issue`](https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website/issues?q=is%3Aissue+is%3Aopen+label%3A%22good+first+issue%22)

---

## Co dalej?

Sugestie kolejnych zadań / plików do wygenerowania:

1. **Plik PHP z `schema.org` dla LocalBusiness + Service**
   - Lokalizacja: `dist/wp-content/themes/hubag-child/inc/schema-localbusiness.php`
   - Wstrzykiwanie JSON-LD w `wp_head`.
2. **Globalne `functions-seo.php` w child theme**
   - Konfiguracja:
     - dynamiczne meta title/description
     - OpenGraph + Twitter Cards
     - integracja z danymi z ACF / Muffin Builder (jeśli używane)
3. **Dedykowany SCSS/CSS dla sekcji "Oferta" i "Co nas wyróżnia?"**
   - Oparty o brand colors z `FULL-BRAND-COLORS.scss`
   - Dostosowany pod layout BeTheme/Muffin.
4. **Plik `CONTRIBUTING.md`**
   - Zasady PR, format commitów, standardy jakości.
5. **Konfiguracja NPM (`package.json`) pod linting/build frontendu**
   - Skrypty: `lint`, `lint:css`, `lint:js`, `build:css`, `build:js`.

Jeśli chcesz, w kolejnym kroku mogę:

- wygenerować kompletny moduł **schema.org LocalBusiness + Service + FAQ** dla Voltmont (PHP + JSON-LD),  
- lub przygotować **`package.json` + podstawowy pipeline SCSS → CSS** dla `hubag-child` z integracją `FULL-BRAND-COLORS.scss`.

Napisz, który z tych kierunków chcesz zrealizować jako następny.