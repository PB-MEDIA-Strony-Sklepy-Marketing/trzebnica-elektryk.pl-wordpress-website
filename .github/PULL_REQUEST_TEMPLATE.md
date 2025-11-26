<!-- 
Dziękujemy za wkład w projekt trzebnica-elektryk.pl!
Proszę wypełnij poniższy szablon, aby ułatwić review.
-->

## 📝 Opis zmian

<!-- Krótki opis, co zostało wprowadzone w tym PR -->

Wprowadzone zmiany:
- 
- 
- 

## 🔗 Powiązane Issue

<!-- Link do GitHub Issue, jeśli dotyczy -->
Closes #

## 🏷️ Typ zmian

Zaznacz odpowiednie opcje:

- [ ] 🐛 Poprawka błędu (bugfix)
- [ ] ✨ Nowa funkcja (feature)
- [ ] 🔧 Zmiana techniczna (refactor, konfiguracja)
- [ ] 📚 Zmiany w dokumentacji
- [ ] 🎨 Zmiany w stylu/UI
- [ ] ⚡ Optymalizacja wydajności
- [ ] ♿ Poprawa dostępności (a11y)
- [ ] 🔒 Poprawka bezpieczeństwa
- [ ] 🔍 Poprawa SEO

## ✅ Lista kontrolna - Kod

### PHP

- [ ] Kod zgodny z WordPress Coding Standards
- [ ] Sprawdzono przez `php -l` (brak błędów składni)
- [ ] Uruchomiono `php-cs-fixer` (PSR-12)
- [ ] Wszystkie dane wejściowe są sanityzowane
- [ ] Wszystkie dane wyjściowe są escapowane (esc_html, esc_attr, esc_url)
- [ ] Weryfikacja nonce dla formularzy/AJAX
- [ ] Sprawdzono uprawnienia użytkownika (capability checks)
- [ ] Dodano PHPDoc do nowych funkcji
- [ ] Prefiks `voltmont_` dla wszystkich funkcji

### JavaScript

- [ ] Uruchomiono `npm run lint:js` (brak błędów)
- [ ] Brak błędów w konsoli przeglądarki
- [ ] Sprawdzono w Chrome DevTools
- [ ] Kod ES6+ transpilowany przez Babel
- [ ] Event listeners prawidłowo dodane i usuwane
- [ ] Obsługa błędów (try/catch, error callbacks)

### CSS

- [ ] Uruchomiono `npm run lint:css` (brak błędów)
- [ ] Użyto zmiennych CSS z `brand-system.css`
- [ ] Nazewnictwo BEM dla nowych komponentów
- [ ] Brak `!important` (chyba że konieczne)
- [ ] Responsive design (mobile-first)
- [ ] Sprawdzono w różnych przeglądarkach

## 🧪 Lista kontrolna - Testy

### Testy manualne

- [ ] Przetestowano na desktop (Chrome, Firefox, Safari/Edge)
- [ ] Przetestowano na mobile (min. 360px szerokości)
- [ ] Przetestowano w trybie responsive (DevTools)
- [ ] Wszystkie linki działają
- [ ] Formularze walidują się i wysyłają poprawnie
- [ ] Brak błędów w konsoli przeglądarki
- [ ] Brak błędów PHP (sprawdzono logi)

### Testy automatyczne

- [ ] `npm run lint` przeszedł pomyślnie
- [ ] `npm test` przeszedł pomyślnie (jeśli są testy)
- [ ] `npm run build:production` buduje bez błędów

### Wydajność

- [ ] PageSpeed Insights score ≥ 85 (mobile i desktop)
- [ ] Lighthouse Performance ≥ 85
- [ ] LCP < 2.5s
- [ ] FID < 100ms
- [ ] CLS < 0.1
- [ ] Sprawdzono rozmiar bundle (max 50kB JS, 30kB CSS)

## ♿ Lista kontrolna - Dostępność (WCAG 2.2 AA)

- [ ] Wszystkie obrazy mają alt text
- [ ] Kontrast kolorów ≥ 4.5:1 (tekst) i 3:1 (UI)
- [ ] Nawigacja klawiaturą działa (Tab, Enter, Esc)
- [ ] Focus indicators są widoczne
- [ ] Formularze mają odpowiednie `<label>` lub `aria-label`
- [ ] Semantyczny HTML (nagłówki h1-h6 w kolejności)
- [ ] ARIA attributes gdzie potrzebne
- [ ] Brak automatycznego odtwarzania audio/video
- [ ] Sprawdzono z czytnikiem ekranu (opcjonalnie)
- [ ] Uruchomiono `npm run test:a11y` (jeśli skonfigurowane)

## 🔍 Lista kontrolna - SEO

- [ ] Meta title (50-60 znaków)
- [ ] Meta description (150-160 znaków)
- [ ] Heading structure (h1 → h2 → h3)
- [ ] Alt text dla obrazów (z keyword jeśli sensownie)
- [ ] Internal linking (linki do innych podstron)
- [ ] Schema.org markup (jeśli dotyczy)
- [ ] OpenGraph tags (dla social media)
- [ ] Canonical URL (jeśli dotyczy)
- [ ] Sprawdzono w Google Rich Results Test (schema)
- [ ] URL przyjazne SEO (slug)

## 🔒 Lista kontrolna - Bezpieczeństwo

- [ ] Brak hardcoded credentials
- [ ] Brak SQL injection (używaj `$wpdb->prepare()`)
- [ ] Brak XSS (escapowanie output)
- [ ] Brak CSRF (weryfikacja nonce)
- [ ] Sprawdzono uprawnienia (`current_user_can()`)
- [ ] Walidacja i sanityzacja WSZYSTKICH inputów
- [ ] File uploads bezpieczne (jeśli dotyczy)
- [ ] Brak ujawniania wrażliwych danych w kodzie/logach
- [ ] Przejrzano przez `npm audit` (brak critical vulnerabilities)

## 📱 Lista kontrolna - Responsive & Cross-browser

### Urządzenia mobilne

- [ ] iPhone SE (375x667)
- [ ] iPhone 12/13/14 (390x844)
- [ ] Samsung Galaxy (360x640)
- [ ] iPad (768x1024)
- [ ] Landscape orientation

### Przeglądarki desktop

- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari/Edge (latest)

### Breakpoints

- [ ] Mobile: 320px - 767px
- [ ] Tablet: 768px - 1023px
- [ ] Desktop: 1024px+
- [ ] Large desktop: 1440px+

## 📸 Zrzuty ekranu

<!-- Dodaj screeny Before/After jeśli są zmiany wizualne -->

### Before (opcjonalnie)


### After


## 🧰 Jak testować?

<!-- Instrukcja krok po kroku jak zweryfikować zmiany -->

1. Przełącz na branch: `git checkout feature/branch-name`
2. Zainstaluj dependencies: `npm install`
3. Zbuduj assets: `npm run build`
4. Otwórz stronę: `https://trzebnica-elektryk.pl/...`
5. Sprawdź:
   - 
   - 
   - 

## 📋 Deployment Checklist (dla maintainerów)

- [ ] Code review zakończony
- [ ] Testy automatyczne przeszły (CI/CD)
- [ ] Changelog zaktualizowany
- [ ] Dokumentacja zaktualizowana (jeśli potrzeba)
- [ ] Backup bazy danych wykonany
- [ ] Deploy na staging przeszedł pomyślnie
- [ ] Client approval (jeśli dotyczy)
- [ ] Ready to merge do `main`

## 💬 Dodatkowe uwagi

<!-- Wszelkie inne informacje dla reviewerów -->

## 🏷️ Labels

<!-- Sugerowane labels dla tego PR (ustawi maintainer) -->
- `bug` / `feature` / `enhancement` / `documentation`
- `priority:high` / `priority:medium` / `priority:low`
- `needs-review` / `work-in-progress`

---

**Autor PR:** @<!-- Twój username -->  
**Data stworzenia:** <!-- Auto-fill -->  
**Target branch:** `main`

<!-- 
Podziękowania za wkład w projekt! 🚀
W razie pytań, skontaktuj się z team lead: biuro@pbmediaonline.pl
-->