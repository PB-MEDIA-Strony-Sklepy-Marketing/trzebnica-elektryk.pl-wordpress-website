# ⚡ Voltmont - Instalacje Elektryczne | WordPress Website

[![WordPress](https://img.shields.io/badge/WordPress-6.4%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4.svg)](https://php.net/)
[![BeTheme](https://img.shields.io/badge/BeTheme-27.5%2B-orange.svg)](https://themeforest.net/item/betheme-responsive-multipurpose-wordpress-theme/7758048)
[![License](https://img.shields.io/badge/License-Proprietary-red.svg)](LICENSE)
[![WCAG 2.2](https://img.shields.io/badge/WCAG-2.2%20AA-green.svg)](https://www.w3.org/WAI/WCAG22/quickref/)
[![PageSpeed](https://img.shields.io/badge/PageSpeed-90%2B-brightgreen.svg)](https://pagespeed.web.dev/)

## 🏢 **O Projekcie**

Profesjonalna strona internetowa dla firmy **Voltmont - Instalacje Elektryczne** z Trzebnicy, specjalizującej się w kompleksowych usługach elektrycznych na terenie Dolnego Śląska. Projekt oparty na WordPress z motywem BeTheme, zoptymalizowany pod kątem SEO lokalnego i wydajności.

### 🌐 **Live**: [trzebnica-elektryk.pl](https://trzebnica-elektryk.pl)

## 🎯 **Kluczowe Funkcjonalności**

- ✅ **Responsywny design** mobile-first (320px - 2560px)
- ✅ **Optymalizacja SEO** dla fraz lokalnych
- ✅ **System rezerwacji** terminów online
- ✅ **Galeria realizacji** z lazy loading
- ✅ **Formularz kontaktowy** z zabezpieczeniem reCAPTCHA
- ✅ **Integracja Google My Business**
- ✅ **Schema.org** dla Local Business
- ✅ **WCAG 2.2 AA** compliance
- ✅ **PageSpeed 90+** optimization
- ✅ **Smart Home** showcase section

## 🚀 **Quick Start**

### **Wymagania systemowe**

- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- WordPress 6.4+
- Node.js 18+ (dla build tools)
- Composer 2.0+
- WP-CLI (opcjonalnie)

### **Instalacja lokalna**

```bash
# 1. Sklonuj repozytorium
git clone https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website.git
cd trzebnica-elektryk.pl-wordpress-website

# 2. Zainstaluj zależności PHP
composer install

# 3. Zainstaluj zależności Node.js
npm install

# 4. Skonfiguruj WordPress
cp wp-config-sample.php wp-config.php
# Edytuj wp-config.php z danymi bazy danych

# 5. Zaimportuj bazę danych
wp db import database/init.sql

# 6. Ustaw właściwe uprawnienia
chmod -R 755 wp-content
chmod -R 644 wp-content/themes/hubag-child/style.css

# 7. Build assets
npm run build

# 8. Uruchom lokalny serwer
npm run dev
```

### **Docker Setup** 🐳

```bash
# Uruchom kontener WordPress z Docker Compose
docker-compose up -d

# Strona dostępna pod: http://localhost:8080
# phpMyAdmin: http://localhost:8081
```

## 📁 **Struktura Projektu**

```
trzebnica-elektryk.pl-wordpress-website/
├── 📂 wp-content/
│   ├── 📂 themes/
│   │   ├── 📂 hubag/                 # Parent theme (BeTheme)
│   │   └── 📂 hubag-child/           # Child theme - wszystkie customizacje
│   │       ├── 📄 style.css          # Główne style
│   │       ├── 📄 functions.php      # Funkcje motywu
│   │       ├── 📂 assets/            # Zasoby (CSS, JS, images)
│   │       ├── 📂 template-parts/    # Komponenty wielokrotnego użytku
│   │       └── 📂 page-templates/    # Szablony stron
│   ├── 📂 plugins/                   # Wtyczki WordPress
│   └── 📂 uploads/                   # Media
├── 📂 docs/                          # Dokumentacja
│   ├── 📂 _brand-trzebnica-elektryk/ # Branding materials
│   ├── 📄 BRIEF-PROJECT.md          # Brief projektu
│   └── 📄 KOLORYSTYKA-ROOT-BRAND-COLOR-CSS.md
├── 📂 .github/
│   └── 📂 workflows/                # GitHub Actions CI/CD
├── 📄 composer.json                 # PHP dependencies
├── 📄 package.json                  # Node dependencies
├── 📄 webpack.config.js             # Build configuration
├── 📄 .env.example                  # Environment variables template
├── 📄 docker-compose.yml            # Docker configuration
└── 📄 README.md                     # Ten plik
```

## 🛠️ **Development**

### **Skrypty NPM**

```bash
npm run dev        # Uruchom serwer deweloperski
npm run build      # Build produkcyjny
npm run watch      # Watch mode dla CSS/JS
npm run lint       # Sprawdź kod (ESLint + Stylelint)
npm run format     # Formatuj kod (Prettier)
npm run test       # Uruchom testy
npm run analyze    # Webpack Bundle Analyzer
```

### **WP-CLI Commands**

```bash
wp cache flush              # Wyczyść cache
wp rewrite flush           # Odśwież permalinki
wp theme activate hubag-child  # Aktywuj child theme
wp plugin update --all     # Aktualizuj wszystkie pluginy
wp db optimize            # Optymalizuj bazę danych
```

## 🎨 **Customizacja**

### **Kolory brandowe (CSS Variables)**

```css
:root {
    --color-theme-primary: #4d81e9;
    --color-theme-secondary: #041028;
    --text-color: #edf0fd;
    --background-theme-color: #163162;
    --color-electric-yellow: #fbbf24;
}
```

### **BeTheme Hooks**

```php
// functions.php - przykład użycia hooków
add_action('mfn_hook_top', 'voltmont_custom_header');
add_action('mfn_hook_content_before', 'voltmont_breadcrumbs');
add_filter('mfn_opts_get', 'voltmont_modify_theme_options');
```

## 📊 **SEO & Performance**

### **Kluczowe frazy**
- elektryk Trzebnica
- instalacje elektryczne Dolny Śląsk
- smart home Wrocław
- modernizacja instalacji elektrycznych

### **Optymalizacje**
- ✅ Critical CSS inline
- ✅ Lazy loading images
- ✅ WebP format
- ✅ Minified CSS/JS
- ✅ Gzip compression
- ✅ Browser caching
- ✅ CDN integration ready

## 🧪 **Testing**

```bash
# Unit testy PHP
composer test

# E2E testy (Cypress)
npm run test:e2e

# Accessibility test
npm run test:a11y

# Performance test
npm run test:lighthouse
```

## 📦 **Deployment**

### **Staging**
```bash
npm run deploy:staging
```

### **Production**
```bash
npm run deploy:production
```

Szczegóły w [DEPLOYMENT.md](docs/DEPLOYMENT.md)

## 🤝 **Contributing**

Zobacz [CONTRIBUTING.md](docs/CONTRIBUTING.md) dla szczegółów.

### **Workflow**
1. Fork repozytorium
2. Stwórz feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit zmiany (`git commit -m 'Add AmazingFeature'`)
4. Push do brancha (`git push origin feature/AmazingFeature`)
5. Otwórz Pull Request

## 📝 **Licencja**

Projekt objęty licencją własnościową. Wszystkie prawa zastrzeżone © 2024 PB-MEDIA & Voltmont.

## 👥 **Zespół**

- **Developer**: [PB-MEDIA](https://pb-media.pl)
- **Klient**: Voltmont - Instalacje Elektryczne
- **Design**: PB-MEDIA Team

## 📞 **Support**

- **Email**: support@pb-media.pl
- **Issues**: [GitHub Issues](https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website/issues)
- **Docs**: [Wiki](https://github.com/PB-MEDIA-Strony-Sklepy-Marketing/trzebnica-elektryk.pl-wordpress-website/wiki)

---

<div align="center">
  <strong>⚡ Powered by WordPress & BeTheme</strong><br>
  Made with ❤️ by <a href="https://pb-media.pl">PB-MEDIA</a>
</div>