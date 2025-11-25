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
  ISSUE_TEMPLATE/
    bug_report.md
    feature_request.md
  PULL_REQUEST_TEMPLATE.md
  dependabot.yml
```

## Jak poruszać się po projekcie

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

## SEO & schema.org

- Dla kluczowych podstron (instalacje, modernizacje, WLZ, nadzór, SMART, odgromowe):
  - Używaj `LocalBusiness` + `Service` + `FAQPage` (JSON-LD)
- Meta:
  - Tytuł: 50–60 znaków
  - Opis: 150–160 znaków
- Open Graph:
  - Ustalone meta tagi dla głównych stron + grafika social
- Wewnętrzne linkowanie:
  - Linkuj pomiędzy usługami (np. „instalacje odgromowe” → „modernizacja instalacji w blokach”, „nadzór elektryczny” itp.)

## Dalsze kroki

Zobacz sekcję **"Co dalej?"** w tym README (poniżej), aby poznać sugerowane kolejne pliki i zadania.

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