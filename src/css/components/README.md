# CSS Components - Voltmont Theme

**Reusable CSS components for trzebnica-elektryk.pl**

---

## 📦 Available Components

### 1. Navigation (`_navigation.css`)
**Features:**
- Sticky header
- Desktop dropdown menus
- Mobile hamburger menu
- Keyboard navigation
- Smooth animations
- Focus states

**Usage:**
```html
<header class="site-header">
    <div class="site-header__container">
        <!-- Logo -->
        <a href="/" class="site-logo">...</a>
        
        <!-- Desktop Nav -->
        <nav class="main-nav">...</nav>
        
        <!-- Mobile Toggle -->
        <button class="mobile-menu-toggle">...</button>
    </div>
</header>
```

---

### 2. Footer (`_footer.css`)
**Features:**
- Multi-column layout
- Contact information
- Social media links
- Newsletter signup
- Responsive grid

**Usage:**
```html
<footer class="site-footer">
    <div class="site-footer__top">
        <div class="site-footer__grid">
            <div class="site-footer__column">...</div>
        </div>
    </div>
    <div class="site-footer__bottom">...</div>
</footer>
```

---

### 3. Service Components (`_service.css`)
**Components:**
- `.service-hero` - Hero section
- `.feature-card` - Feature cards
- `.price-card` - Pricing card
- `.contact-card` - Contact info card
- `.faq-accordion` - FAQ accordion
- `.service-grid` - Related services grid
- `.cta-box` - Call-to-action box

**Usage:**
See `template-service.php` for implementation examples.

---

## 🎨 Design Tokens

All components use CSS custom properties from the brand system:

```css
--color-primary: #4d81e9;
--color-secondary: #041028;
--color-background: #163162;
--color-text: #edf0fd;
--color-hover: #2a54a1;
```

---

## 📱 Responsive Breakpoints

- **Mobile:** < 768px
- **Tablet:** 768px - 1023px
- **Desktop:** ≥ 1024px

---

## ♿ Accessibility

All components follow WCAG 2.2 AA guidelines:
- Proper ARIA labels
- Keyboard navigation
- Focus indicators
- Color contrast 4.5:1+
- Screen reader friendly

---

## 🔗 Integration

Import components in your main stylesheet:

```css
@import 'components/navigation';
@import 'components/footer';
@import 'components/service';
```

Or in webpack:

```javascript
import './components/_navigation.css';
import './components/_footer.css';
import './components/_service.css';
```

---

*See `docs/TESTING_COMPONENTS_GUIDE.md` for testing examples*
