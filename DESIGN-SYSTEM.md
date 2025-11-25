# Design System – trzebnica-elektryk.pl

**Voltmont - Instalacje Elektryczne**  
Complete brand design system and UI components library

---

## Table of Contents

1. [Brand Identity](#brand-identity)
2. [Color System](#color-system)
3. [Typography](#typography)
4. [Spacing & Layout](#spacing--layout)
5. [Components](#components)
6. [Iconography](#iconography)
7. [Motion & Animation](#motion--animation)
8. [Accessibility](#accessibility)
9. [Responsive Design](#responsive-design)
10. [Implementation](#implementation)

---

## Brand Identity

### Brand Essence

**Voltmont** represents:
- **Professionalism:** Expert electrical services
- **Reliability:** Trusted local partner
- **Innovation:** Modern solutions (Smart Home, automation)
- **Safety:** Compliance with norms and standards

### Brand Values

1. **Quality:** Premium workmanship
2. **Trust:** Transparent pricing, honest communication
3. **Expertise:** Years of experience, certified professionals
4. **Local:** Serving Trzebnica and Dolny Śląsk

### Voice & Tone

**Voice:**  Professional yet approachable

**Tone Guidelines:**
- **Professional:** Use industry-standard terminology when appropriate
- **Accessible:** Explain technical concepts in simple terms
- **Confident:** Assert expertise without arrogance
- **Helpful:** Always solution-oriented

**Examples:**
- ✅ "Zapewniamy kompleksową modernizację instalacji elektrycznych zgodnie z aktualnymi normami PN-EN."
- ❌ "Robimy prądy w blokach i domach."

---

## Color System

### Primary Colors

**Primary Blue** – Main brand color

```css
:root {
  --color-primary: #4d81e9;
  --color-primary-light: #7da3f0;
  --color-primary-lighter: #adc6f7;
  --color-primary-dark: #2a54a1;
  --color-primary-darker: #1a3366;
}
```

**Usage:**
- Primary CTAs (buttons)
- Links
- Active states
- Brand accents

**Accessibility:**
- On white: AA/AAA compliant (contrast 4.63:1)
- On dark bg: AAA compliant

**Secondary Navy** – Supporting color

```css
:root {
  --color-secondary: #041028;
  --color-secondary-light: #0f1f3d;
  --color-secondary-lighter: #1f2d4a;
  --color-secondary-dark: #020815;
}
```

**Usage:**
- Headers
- Footer background
- Dark sections
- Text on light backgrounds

### Background Colors

```css
:root {
  --color-bg-primary: #ffffff;
  --color-bg-secondary: #f8f9fb;
  --color-bg-dark: #163162;
  --color-bg-darker: #0a1c3d;
}
```

### Text Colors

```css
:root {
  --color-text-primary: #041028;    /* Dark navy - body text */
  --color-text-secondary: #546071;  /* Gray - secondary text */
  --color-text-tertiary: #8a94a6;   /* Light gray - muted text */
  --color-text-light: #edf0fd;      /* Light blue-white - text on dark */
  --color-text-white: #ffffff;      /* Pure white - text on dark */
}
```

### Interactive Colors

**Hover States:**
```css
:root {
  --color-hover: #2a54a1;           /* Darker blue for hover */
  --color-hover-light: #7da3f0;     /* Lighter blue for light backgrounds */
}
```

**Focus States:**
```css
:root {
  --color-focus: #4d81e9;
  --color-focus-outline: rgba(77, 129, 233, 0.4);
}
```

### Semantic Colors

**Success:**
```css
:root {
  --color-success: #28a745;
  --color-success-light: #d4edda;
  --color-success-dark: #1e7e34;
}
```

**Warning:**
```css
:root {
  --color-warning: #ffc107;
  --color-warning-light: #fff3cd;
  --color-warning-dark: #e0a800;
}
```

**Error:**
```css
:root {
  --color-error: #dc3545;
  --color-error-light: #f8d7da;
  --color-error-dark: #bd2130;
}
```

**Info:**
```css
:root {
  --color-info: #17a2b8;
  --color-info-light: #d1ecf1;
  --color-info-dark: #117a8b;
}
```

### Gradients

**Primary Gradient:**
```css
.gradient-primary {
  background: linear-gradient(135deg, #4d81e9 0%, #2a54a1 100%);
}
```

**Dark Gradient:**
```css
.gradient-dark {
  background: linear-gradient(180deg, #163162 0%, #041028 100%);
}
```

**Overlay Gradient:**
```css
.gradient-overlay {
  background: linear-gradient(180deg, rgba(4, 16, 40, 0) 0%, rgba(4, 16, 40, 0.8) 100%);
}
```

### Color Usage Guidelines

**Do's:**
- ✅ Use primary blue for CTAs and important actions
- ✅ Use secondary navy for headers and footers
- ✅ Maintain sufficient contrast (WCAG AA minimum)
- ✅ Use semantic colors for their intended purpose

**Don'ts:**
- ❌ Don't use error red for non-error states
- ❌ Don't mix too many colors in one section
- ❌ Don't use low-contrast color combinations

---

## Typography

### Font Families

**Primary Font:** Inter (Sans-serif)

```css
:root {
  --font-primary: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  --font-secondary: 'Poppins', 'Inter', sans-serif;
}
```

**Fallback Stack:**
```css
--font-fallback: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Helvetica Neue', sans-serif;
```

### Font Weights

```css
:root {
  --font-weight-light: 300;
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
  --font-weight-extrabold: 800;
}
```

### Type Scale

**Heading Sizes:**

```css
:root {
  --font-size-h1: 3rem;      /* 48px */
  --font-size-h2: 2.5rem;    /* 40px */
  --font-size-h3: 2rem;      /* 32px */
  --font-size-h4: 1.5rem;    /* 24px */
  --font-size-h5: 1.25rem;   /* 20px */
  --font-size-h6: 1rem;      /* 16px */
}

/* Mobile adjustments */
@media (max-width: 768px) {
  :root {
    --font-size-h1: 2rem;      /* 32px */
    --font-size-h2: 1.75rem;   /* 28px */
    --font-size-h3: 1.5rem;    /* 24px */
    --font-size-h4: 1.25rem;   /* 20px */
  }
}
```

**Body Sizes:**

```css
:root {
  --font-size-base: 1rem;        /* 16px - body text */
  --font-size-lg: 1.125rem;      /* 18px - large body */
  --font-size-sm: 0.875rem;      /* 14px - small text */
  --font-size-xs: 0.75rem;       /* 12px - captions */
}
```

### Line Heights

```css
:root {
  --line-height-tight: 1.2;
  --line-height-normal: 1.5;
  --line-height-relaxed: 1.75;
  --line-height-loose: 2;
}
```

**Usage:**
- Headings: `--line-height-tight` (1.2)
- Body text: `--line-height-normal` (1.5)
- Large paragraphs: `--line-height-relaxed` (1.75)

### Letter Spacing

```css
:root {
  --letter-spacing-tight: -0.02em;
  --letter-spacing-normal: 0;
  --letter-spacing-wide: 0.05em;
  --letter-spacing-wider: 0.1em;
}
```

### Typography Components

**Heading Styles:**

```css
h1, .h1 {
  font-size: var(--font-size-h1);
  font-weight: var(--font-weight-bold);
  line-height: var(--line-height-tight);
  letter-spacing: var(--letter-spacing-tight);
  color: var(--color-text-primary);
  margin-bottom: 1.5rem;
}

h2, .h2 {
  font-size: var(--font-size-h2);
  font-weight: var(--font-weight-semibold);
  line-height: var(--line-height-tight);
  color: var(--color-text-primary);
  margin-bottom: 1.25rem;
}

h3, .h3 {
  font-size: var(--font-size-h3);
  font-weight: var(--font-weight-semibold);
  line-height: var(--line-height-normal);
  color: var(--color-text-primary);
  margin-bottom: 1rem;
}
```

**Body Styles:**

```css
body, .body-text {
  font-family: var(--font-primary);
  font-size: var(--font-size-base);
  font-weight: var(--font-weight-normal);
  line-height: var(--line-height-normal);
  color: var(--color-text-primary);
}

.body-lg {
  font-size: var(--font-size-lg);
  line-height: var(--line-height-relaxed);
}

.body-sm {
  font-size: var(--font-size-sm);
}

.caption {
  font-size: var(--font-size-xs);
  color: var(--color-text-secondary);
}
```

**Special Text Styles:**

```css
.text-lead {
  font-size: 1.25rem;
  font-weight: var(--font-weight-normal);
  line-height: var(--line-height-relaxed);
  color: var(--color-text-secondary);
}

.text-uppercase {
  text-transform: uppercase;
  letter-spacing: var(--letter-spacing-wider);
  font-weight: var(--font-weight-semibold);
  font-size: 0.875rem;
}

.text-quote {
  font-size: 1.5rem;
  font-style: italic;
  line-height: var(--line-height-relaxed);
  border-left: 4px solid var(--color-primary);
  padding-left: 1.5rem;
}
```

---

## Spacing & Layout

### Spacing Scale

**Base Unit:** 8px (0.5rem)

```css
:root {
  --space-0: 0;
  --space-1: 0.25rem;   /* 4px */
  --space-2: 0.5rem;    /* 8px */
  --space-3: 0.75rem;   /* 12px */
  --space-4: 1rem;      /* 16px */
  --space-5: 1.5rem;    /* 24px */
  --space-6: 2rem;      /* 32px */
  --space-8: 3rem;      /* 48px */
  --space-10: 4rem;     /* 64px */
  --space-12: 6rem;     /* 96px */
  --space-16: 8rem;     /* 128px */
}
```

### Layout Containers

**Max Widths:**

```css
:root {
  --container-sm: 640px;
  --container-md: 768px;
  --container-lg: 1024px;
  --container-xl: 1280px;
  --container-2xl: 1536px;
}

.container {
  width: 100%;
  max-width: var(--container-xl);
  margin-left: auto;
  margin-right: auto;
  padding-left: var(--space-4);
  padding-right: var(--space-4);
}

@media (min-width: 768px) {
  .container {
    padding-left: var(--space-6);
    padding-right: var(--space-6);
  }
}
```

### Grid System

**12-Column Grid (Bootstrap-style):**

```css
.row {
  display: flex;
  flex-wrap: wrap;
  margin-left: calc(var(--space-4) * -1);
  margin-right: calc(var(--space-4) * -1);
}

.col {
  flex: 1 0 0%;
  padding-left: var(--space-4);
  padding-right: var(--space-4);
}

/* Column sizes */
.col-1 { flex: 0 0 8.333333%; max-width: 8.333333%; }
.col-2 { flex: 0 0 16.666667%; max-width: 16.666667%; }
.col-3 { flex: 0 0 25%; max-width: 25%; }
.col-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
.col-6 { flex: 0 0 50%; max-width: 50%; }
.col-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
.col-12 { flex: 0 0 100%; max-width: 100%; }
```

### Section Spacing

```css
.section {
  padding-top: var(--space-12);
  padding-bottom: var(--space-12);
}

.section-sm {
  padding-top: var(--space-8);
  padding-bottom: var(--space-8);
}

.section-lg {
  padding-top: var(--space-16);
  padding-bottom: var(--space-16);
}

@media (max-width: 768px) {
  .section {
    padding-top: var(--space-8);
    padding-bottom: var(--space-8);
  }
}
```

### Breakpoints

```css
:root {
  --breakpoint-xs: 0px;
  --breakpoint-sm: 576px;
  --breakpoint-md: 768px;
  --breakpoint-lg: 992px;
  --breakpoint-xl: 1200px;
  --breakpoint-2xl: 1536px;
}
```

**Media Queries:**

```css
/* Mobile first approach */
@media (min-width: 576px) { /* Small devices */ }
@media (min-width: 768px) { /* Tablets */ }
@media (min-width: 992px) { /* Desktops */ }
@media (min-width: 1200px) { /* Large desktops */ }
@media (min-width: 1536px) { /* Extra large screens */ }
```

---

## Components

### Buttons

**Primary Button:**

```css
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1.5rem;
  font-size: 1rem;
  font-weight: var(--font-weight-semibold);
  line-height: 1.5;
  text-align: center;
  text-decoration: none;
  border: 2px solid transparent;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: all 0.3s ease;
  user-select: none;
}

.btn-primary {
  background-color: var(--color-primary);
  color: var(--color-text-white);
  border-color: var(--color-primary);
}

.btn-primary:hover {
  background-color: var(--color-hover);
  border-color: var(--color-hover);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(77, 129, 233, 0.3);
}

.btn-primary:focus {
  outline: 2px solid var(--color-focus-outline);
  outline-offset: 2px;
}

.btn-primary:active {
  transform: translateY(0);
}
```

**Secondary Button:**

```css
.btn-secondary {
  background-color: transparent;
  color: var(--color-primary);
  border-color: var(--color-primary);
}

.btn-secondary:hover {
  background-color: var(--color-primary);
  color: var(--color-text-white);
}
```

**Ghost Button:**

```css
.btn-ghost {
  background-color: transparent;
  color: var(--color-text-white);
  border-color: var(--color-text-white);
}

.btn-ghost:hover {
  background-color: rgba(255, 255, 255, 0.1);
}
```

**Button Sizes:**

```css
.btn-sm {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
}

.btn-lg {
  padding: 1rem 2rem;
  font-size: 1.125rem;
}

.btn-block {
  display: flex;
  width: 100%;
}
```

### Cards

```css
.card {
  background: var(--color-bg-primary);
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}

.card:hover {
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
  transform: translateY(-4px);
}

.card-header {
  padding: 1.5rem;
  border-bottom: 1px solid var(--color-bg-secondary);
}

.card-body {
  padding: 1.5rem;
}

.card-footer {
  padding: 1rem 1.5rem;
  background: var(--color-bg-secondary);
  border-top: 1px solid var(--color-bg-secondary);
}
```

### Forms

**Input Fields:**

```css
.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: var(--font-weight-medium);
  color: var(--color-text-primary);
}

.form-input,
.form-textarea,
.form-select {
  width: 100%;
  padding: 0.75rem 1rem;
  font-size: 1rem;
  line-height: 1.5;
  color: var(--color-text-primary);
  background-color: var(--color-bg-primary);
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px var(--color-focus-outline);
}

.form-input::placeholder {
  color: var(--color-text-tertiary);
}

.form-input.error {
  border-color: var(--color-error);
}

.form-error {
  margin-top: 0.5rem;
  font-size: 0.875rem;
  color: var(--color-error);
}
```

**Contact Form Example:**

```html
<form class="contact-form">
  <div class="form-group">
    <label for="name" class="form-label">Imię i nazwisko</label>
    <input type="text" id="name" class="form-input" placeholder="Jan Kowalski" required>
  </div>
  
  <div class="form-group">
    <label for="email" class="form-label">Email</label>
    <input type="email" id="email" class="form-input" placeholder="jan.kowalski@example.com" required>
  </div>
  
  <div class="form-group">
    <label for="phone" class="form-label">Telefon</label>
    <input type="tel" id="phone" class="form-input" placeholder="+48 123 456 789">
  </div>
  
  <div class="form-group">
    <label for="message" class="form-label">Wiadomość</label>
    <textarea id="message" class="form-textarea" rows="5" placeholder="Opisz swoje potrzeby..." required></textarea>
  </div>
  
  <button type="submit" class="btn btn-primary btn-block">Wyślij wiadomość</button>
</form>
```

### Badges

```css
.badge {
  display: inline-flex;
  align-items: center;
  padding: 0.25rem 0.75rem;
  font-size: 0.75rem;
  font-weight: var(--font-weight-semibold);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-radius: 9999px;
}

.badge-primary {
  background-color: var(--color-primary-light);
  color: var(--color-primary-darker);
}

.badge-success {
  background-color: var(--color-success-light);
  color: var(--color-success-dark);
}
```

### Alerts

```css
.alert {
  padding: 1rem 1.5rem;
  border-radius: 0.375rem;
  border-left: 4px solid;
  margin-bottom: 1rem;
}

.alert-info {
  background-color: var(--color-info-light);
  border-color: var(--color-info);
  color: var(--color-info-dark);
}

.alert-success {
  background-color: var(--color-success-light);
  border-color: var(--color-success);
  color: var(--color-success-dark);
}

.alert-warning {
  background-color: var(--color-warning-light);
  border-color: var(--color-warning);
  color: var(--color-warning-dark);
}

.alert-error {
  background-color: var(--color-error-light);
  border-color: var(--color-error);
  color: var(--color-error-dark);
}
```

---

## Iconography

### Icon System

**Font Awesome 6** (Primary icon library)

**Icon Sizes:**

```css
.icon-sm { font-size: 1rem; }      /* 16px */
.icon-md { font-size: 1.5rem; }    /* 24px */
.icon-lg { font-size: 2rem; }      /* 32px */
.icon-xl { font-size: 3rem; }      /* 48px */
```

### Common Icons

**Services:**
- ⚡ `<i class="fa-solid fa-bolt"></i>` – Electrical services
- 🏠 `<i class="fa-solid fa-house"></i>` – Smart Home
- 🔧 `<i class="fa-solid fa-wrench"></i>` – Installations
- 🛡️ `<i class="fa-solid fa-shield"></i>` – Lightning protection
- 📋 `<i class="fa-solid fa-clipboard-check"></i>` – Supervision

**Contact:**
- ☎️ `<i class="fa-solid fa-phone"></i>` – Phone
- ✉️ `<i class="fa-solid fa-envelope"></i>` – Email
- 📍 `<i class="fa-solid fa-location-dot"></i>` – Location

**Social:**
- `<i class="fa-brands fa-facebook"></i>`
- `<i class="fa-brands fa-instagram"></i>`
- `<i class="fa-brands fa-linkedin"></i>`

### Icon Usage Guidelines

**Do's:**
- ✅ Use icons to enhance understanding
- ✅ Maintain consistent icon style throughout
- ✅ Provide text labels or aria-labels for accessibility

**Don'ts:**
- ❌ Don't use icons without context
- ❌ Don't mix icon styles (outline + solid)
- ❌ Don't use decorative icons excessively

---

## Motion & Animation

### Transition Timing

```css
:root {
  --transition-fast: 0.15s;
  --transition-base: 0.3s;
  --transition-slow: 0.5s;
  --transition-ease: cubic-bezier(0.4, 0, 0.2, 1);
}
```

### Common Animations

**Fade In:**

```css
@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.fade-in {
  animation: fadeIn 0.5s ease-in;
}
```

**Slide Up:**

```css
@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.slide-up {
  animation: slideUp 0.6s ease-out;
}
```

**Hover Effects:**

```css
.hover-lift {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}
```

### Animation Guidelines

**Performance:**
- ✅ Use `transform` and `opacity` (GPU-accelerated)
- ✅ Use `will-change` sparingly
- ❌ Avoid animating `width`, `height`, `top`, `left`

**Duration:**
- Small elements: 150-200ms
- Medium elements: 300ms (standard)
- Large elements / page transitions: 500ms

---

## Accessibility

### WCAG 2.2 AA Compliance

**Color Contrast:**
- Text: Minimum 4.5:1 ratio
- Large text (18pt+): Minimum 3:1 ratio
- UI components: Minimum 3:1 ratio

**Focus Indicators:**

```css
*:focus {
  outline: 2px solid var(--color-focus);
  outline-offset: 2px;
}

/* Custom focus for buttons */
.btn:focus {
  outline: 2px solid var(--color-focus-outline);
  outline-offset: 2px;
}
```

**Skip Links:**

```html
<a href="#main-content" class="skip-link">Przejdź do treści głównej</a>
```

```css
.skip-link {
  position: absolute;
  top: -100px;
  left: 0;
  background: var(--color-primary);
  color: white;
  padding: 0.5rem 1rem;
  z-index: 9999;
}

.skip-link:focus {
  top: 0;
}
```

**ARIA Labels:**

```html
<!-- For icon-only buttons -->
<button aria-label="Zamknij menu">
  <i class="fa-solid fa-times"></i>
</button>

<!-- For form fields -->
<input type="search" aria-label="Szukaj na stronie" placeholder="Szukaj...">
```

---

## Responsive Design

### Mobile-First Approach

**Base styles:** Mobile (320px+)  
**Progressive enhancement:** Tablet → Desktop

### Breakpoint Strategy

```css
/* Mobile (default) */
.hero-title {
  font-size: 2rem;
}

/* Tablet */
@media (min-width: 768px) {
  .hero-title {
    font-size: 2.5rem;
  }
}

/* Desktop */
@media (min-width: 1024px) {
  .hero-title {
    font-size: 3rem;
  }
}
```

### Touch Targets

**Minimum size:** 44x44px (WCAG guideline)

```css
.touch-target {
  min-width: 44px;
  min-height: 44px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
```

---

## Implementation

### CSS Architecture

**Methodology:** BEM (Block Element Modifier)

**Example:**

```css
/* Block */
.service-card { }

/* Element */
.service-card__header { }
.service-card__body { }
.service-card__icon { }

/* Modifier */
.service-card--featured { }
.service-card--large { }
```

### File Structure

```
dist/wp-content/themes/hubag-child/assets/css/
  ├── brand-system.css       # Design tokens (this file)
  ├── components/
  │   ├── buttons.css
  │   ├── cards.css
  │   ├── forms.css
  │   └── navigation.css
  ├── layouts/
  │   ├── header.css
  │   ├── footer.css
  │   └── sections.css
  └── utilities/
      ├── spacing.css
      └── typography.css
```

### Usage in WordPress

**Enqueue in functions.php:**

```php
function voltmont_enqueue_design_system() {
    wp_enqueue_style(
        'voltmont-brand-system',
        get_stylesheet_directory_uri() . '/assets/css/brand-system.css',
        array(),
        '1.0.0',
        'all'
    );
}
add_action( 'wp_enqueue_scripts', 'voltmont_enqueue_design_system', 5 );
```

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintained by:** PB-MEDIA Design Team
