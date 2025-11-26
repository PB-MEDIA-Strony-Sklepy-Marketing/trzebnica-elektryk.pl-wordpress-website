# Schema.org Implementation Guide - trzebnica-elektryk.pl

**Comprehensive guide to structured data implementation for Voltmont**

---

## 🎯 Overview

This document explains the schema.org structured data implementation for trzebnica-elektryk.pl. Proper structured data helps Google understand your content and display rich results in search.

---

## 📋 Table of Contents

1. [Implemented Schemas](#implemented-schemas)
2. [LocalBusiness Schema](#localbusiness-schema)
3. [FAQ Schema](#faq-schema)
4. [Portfolio Schema](#portfolio-schema)
5. [SEO Meta Tags](#seo-meta-tags)
6. [Testing & Validation](#testing--validation)
7. [Troubleshooting](#troubleshooting)

---

## 1. Implemented Schemas

### Current Implementation

| Schema Type | Location | File | Purpose |
|------------|----------|------|---------|
| **LocalBusiness** | Homepage, Service pages | `schema-localbusiness.php` | Company information, services, contact |
| **Service** | Service pages | `schema-localbusiness.php` | Individual service details |
| **FAQPage** | Pages with FAQs | `faq-schema.php` | FAQ markup for rich snippets |
| **CreativeWork** | Portfolio items | `schema-portfolio.php` | Portfolio project details |
| **ItemList** | Portfolio archive | `schema-portfolio.php` | List of portfolio items |
| **BreadcrumbList** | All pages (except homepage) | `schema-localbusiness.php` | Breadcrumb navigation |

### Execution Priority

Schemas are output in `wp_head` with specific priorities:

```php
Priority 5: LocalBusiness + Service (schema-localbusiness.php)
Priority 6: BreadcrumbList (schema-localbusiness.php)
Priority 8: FAQPage (faq-schema.php)
Priority 9: Portfolio CreativeWork (schema-portfolio.php)
Priority 10: Portfolio ItemList (schema-portfolio.php)
```

---

## 2. LocalBusiness Schema

### Location
**File:** `dist/wp-content/themes/hubag-child/inc/schema-localbusiness.php`

### What It Does
- Outputs company information on homepage
- Includes service catalog with 6 main services
- Provides contact details, opening hours, location
- Connects to social media profiles

### Schema Structure

```json
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "@id": "https://trzebnica-elektryk.pl/#organization",
  "name": "Voltmont - Instalacje Elektryczne",
  "description": "...",
  "telephone": "+48 691 594 820",
  "email": "biuro@trzebnica-elektryk.pl",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "Trzebnica",
    "addressRegion": "Dolnośląskie",
    "addressCountry": "PL"
  },
  "geo": {
    "@type": "GeoCoordinates",
    "latitude": "51.3094",
    "longitude": "17.0628"
  },
  "hasOfferCatalog": {
    "@type": "OfferCatalog",
    "itemListElement": [...]
  }
}
```

### Service Pages

On service pages (pages with `page_category` taxonomy), both LocalBusiness and Service schemas are output using `@graph`:

```json
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "LocalBusiness",
      ...
    },
    {
      "@type": "Service",
      "name": "Page Title",
      "description": "Page excerpt...",
      "provider": {
        "@type": "LocalBusiness",
        "@id": "https://trzebnica-elektryk.pl/#organization"
      }
    }
  ]
}
```

### Functions Available

```php
// Get LocalBusiness schema array
$schema = voltmont_get_localbusiness_schema();

// Get Service schema for specific service
$service = voltmont_get_service_schema($name, $description, $url);

// Get Breadcrumb schema
$breadcrumb = voltmont_get_breadcrumb_schema();
```

---

## 3. FAQ Schema

### Location
**File:** `dist/wp-content/themes/hubag-child/inc/faq-schema.php`

### What It Does
- Automatically extracts FAQs from page content
- Provides WordPress meta box for manual FAQ entry
- Generates FAQPage schema for Google Rich Results

### How to Use

#### Method 1: Auto-extraction from Content

FAQ schema will automatically detect:

**BeTheme Accordion:**
```
[accordion]
  [accordion_item title="Question?"]Answer here[/accordion_item]
[/accordion]
```

**HTML Headings:**
```html
<h3>What are your services?</h3>
<p>We offer electrical installations...</p>
```

#### Method 2: Manual Entry (WordPress Admin)

1. Edit any **Page**
2. Scroll to **"FAQ dla Schema.org"** meta box
3. Click **"Dodaj pytanie"**
4. Fill in:
   - **Pytanie:** Question text
   - **Odpowiedź:** Answer text
5. Add multiple Q&A pairs as needed
6. Click **Update/Publish**

**Screenshot:**
```
┌────────────────────────────────────────┐
│ FAQ dla Schema.org                     │
├────────────────────────────────────────┤
│ Pytanie: [Input field]                 │
│ Odpowiedź: [Textarea]                  │
│ [Usuń] button                          │
│                                        │
│ [+ Dodaj pytanie] button               │
└────────────────────────────────────────┘
```

### Schema Structure

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Jakie są koszty instalacji?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Koszty zależą od..."
      }
    }
  ]
}
```

### Functions Available

```php
// Get FAQ schema from array
$faqs = array(
    array('question' => '...', 'answer' => '...'),
);
$schema = voltmont_get_faq_schema($faqs);

// Extract FAQs from current post content
$faqs = voltmont_extract_faqs_from_content();
```

---

## 4. Portfolio Schema

### Location
**File:** `dist/wp-content/themes/hubag-child/inc/schema-portfolio.php`

### What It Does
- Generates CreativeWork schema for individual portfolio items
- Creates ItemList schema for portfolio archives
- Adds WordPress meta box for project details
- Extends admin with custom columns

### How to Use

#### Adding Portfolio Schema Data

1. Edit **Portfolio** post
2. Find **"Dane projektu (Schema.org)"** meta box (sidebar)
3. Fill in:
   - **Klient:** Client name (optional)
   - **Data realizacji:** Project date (optional)
   - **Lokalizacja:** Location (e.g., Trzebnica, Wrocław)
4. Click **Update/Publish**

#### Admin Columns

Portfolio admin list shows:
- **Klient** - Client name
- **Lokalizacja** - Project location
- **Data realizacji** - Project date

All columns are **sortable** by clicking column header.

### Single Portfolio Schema

```json
{
  "@context": "https://schema.org",
  "@type": "CreativeWork",
  "name": "Project Title",
  "description": "Project description...",
  "url": "https://trzebnica-elektryk.pl/realizacje/project-slug/",
  "image": {
    "@type": "ImageObject",
    "url": "featured-image.jpg",
    "width": 1200,
    "height": 630
  },
  "author": {
    "@type": "Organization",
    "name": "Voltmont - Instalacje Elektryczne"
  },
  "client": {
    "@type": "Organization",
    "name": "Client Name"
  },
  "locationCreated": {
    "@type": "Place",
    "name": "Trzebnica"
  },
  "genre": ["Instalacje elektryczne", "Smart Home"],
  "keywords": "elektryk, instalacja, smart home"
}
```

### Portfolio Archive Schema

```json
{
  "@context": "https://schema.org",
  "@type": "ItemList",
  "name": "Portfolio - Realizacje",
  "numberOfItems": 12,
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "url": "https://trzebnica-elektryk.pl/realizacje/project-1/",
      "name": "Project 1",
      "image": "image-url.jpg"
    }
  ]
}
```

### Functions Available

```php
// Get portfolio schema
$schema = voltmont_get_portfolio_schema($post);

// Get portfolio archive schema
$archive_schema = voltmont_get_portfolio_archive_schema();
```

---

## 5. SEO Meta Tags

### Location
**File:** `dist/wp-content/themes/hubag-child/inc/functions-seo.php`

### What It Does
- Generates dynamic meta descriptions (150-160 chars)
- Creates OpenGraph tags for social sharing
- Adds Twitter Card markup
- Outputs geo tags for local SEO
- Provides canonical URLs

### Meta Description Logic

**Homepage:**
```
"Profesjonalne instalacje elektryczne w Trzebnicy i na Dolnym Śląsku. 
Kompleksowa obsługa inwestycji, modernizacje, instalacje odgromowe, 
smart home. Tel: +48 691 594 820"
```

**Service Pages:**
- Uses page excerpt if available
- Falls back to first 25 words of content
- Appends location keywords if missing

**Archive Pages:**
- Uses term description if available
- Falls back to category name + site info

### OpenGraph Tags

Full OpenGraph implementation:
- `og:title` - Dynamic title (60 chars max)
- `og:description` - Meta description
- `og:image` - Featured image or fallback logo
- `og:url` - Canonical URL
- `og:type` - "website" or "article"
- `article:published_time` - For posts
- `article:modified_time` - For posts
- `business:contact_data:*` - Business details

### Twitter Cards

```html
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="...">
<meta name="twitter:description" content="...">
<meta name="twitter:image" content="...">
```

### Geo Tags (Local SEO)

```html
<meta name="geo.region" content="PL-DS">
<meta name="geo.placename" content="Trzebnica">
<meta name="geo.position" content="51.3094;17.0628">
<meta name="ICBM" content="51.3094, 17.0628">
```

---

## 6. Testing & Validation

### Google Tools

**1. Rich Results Test**
- URL: https://search.google.com/test/rich-results
- Test individual pages
- Check for errors/warnings
- Preview how schema appears in search

**2. Schema Markup Validator**
- URL: https://validator.schema.org/
- Validates schema.org syntax
- Shows schema structure
- Identifies issues

**3. Google Search Console**
- Menu: **Enhancements**
- Check:
  - **Rich Results** - FAQ, Breadcrumbs
  - **Unparsable structured data** - Errors

### Manual Testing

**Check Schema on Page:**

```javascript
// Open browser console on any page
// Look for <script type="application/ld+json">

// Or extract with:
const schemas = [...document.querySelectorAll('script[type="application/ld+json"]')]
    .map(s => JSON.parse(s.textContent));
console.log(schemas);
```

**WordPress Debug:**

Add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Check logs: `wp-content/debug.log`

---

## 7. Troubleshooting

### Common Issues

#### Schema Not Appearing

**Check:**
1. File is included in `functions.php`:
   ```php
   $voltmont_inc_files = array(
       'schema-localbusiness.php',
       'schema-portfolio.php',
       'faq-schema.php',
       // ...
   );
   ```

2. Action priority not conflicting:
   ```php
   add_action('wp_head', 'function_name', PRIORITY);
   ```

3. Conditional logic allows output:
   ```php
   if (is_front_page()) { // Correct page type?
       // Output schema
   }
   ```

#### Invalid JSON

**Common mistakes:**
- Trailing commas in arrays
- Unescaped quotes in strings
- Missing closing brackets

**Fix:**
Always use `wp_json_encode()`:
```php
echo '<script type="application/ld+json">' . 
     wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . 
     '</script>';
```

#### FAQ Not Detected

**If auto-extraction fails:**

1. Check content format matches patterns
2. Use manual meta box instead
3. Debug with:
   ```php
   $faqs = voltmont_extract_faqs_from_content();
   error_log(print_r($faqs, true));
   ```

#### Portfolio Schema Missing

**Checklist:**
- [ ] Post type is `portfolio`
- [ ] File `schema-portfolio.php` exists
- [ ] File is in `$voltmont_inc_files` array
- [ ] No PHP errors (check error log)

---

## 📊 Schema Priority Summary

```
wp_head priority 1:  SEO meta tags (description, canonical, robots)
wp_head priority 2:  OpenGraph tags
wp_head priority 3:  Twitter Card tags
wp_head priority 4:  Hreflang tags
wp_head priority 5:  LocalBusiness + Service schema
wp_head priority 6:  Breadcrumb schema
wp_head priority 7:  Page-specific schema (portfolio fallback in functions-seo.php)
wp_head priority 8:  FAQ schema
wp_head priority 9:  Portfolio CreativeWork schema
wp_head priority 10: Portfolio ItemList (archive)
```

---

## 🔧 Customization

### Adding New Schema Type

1. Create file: `inc/schema-yourtype.php`
2. Implement generator function:
   ```php
   function voltmont_get_yourtype_schema() {
       return array(
           '@context' => 'https://schema.org',
           '@type' => 'YourType',
           // ...
       );
   }
   ```

3. Output function with hook:
   ```php
   function voltmont_output_yourtype_schema() {
       if (/* condition */) {
           $schema = voltmont_get_yourtype_schema();
           echo '<script type="application/ld+json">' . 
                wp_json_encode($schema, JSON_UNESCAPED_UNICODE) . 
                '</script>';
       }
   }
   add_action('wp_head', 'voltmont_output_yourtype_schema', 11);
   ```

4. Include in `functions.php`:
   ```php
   $voltmont_inc_files = array(
       // ... existing files
       'schema-yourtype.php',
   );
   ```

### Modifying Existing Schema

Use filters:

```php
// Modify LocalBusiness schema
add_filter('voltmont_localbusiness_schema', function($schema) {
    $schema['additionalProperty'] = 'value';
    return $schema;
});

// Modify portfolio schema
add_filter('voltmont_portfolio_schema', function($schema, $post) {
    // Add custom data
    return $schema;
}, 10, 2);
```

---

## 📚 Resources

### Official Documentation
- [Schema.org](https://schema.org/)
- [Google Search Central - Structured Data](https://developers.google.com/search/docs/appearance/structured-data/intro-structured-data)
- [LocalBusiness Schema](https://schema.org/LocalBusiness)
- [FAQPage Schema](https://schema.org/FAQPage)
- [CreativeWork Schema](https://schema.org/CreativeWork)

### Testing Tools
- [Google Rich Results Test](https://search.google.com/test/rich-results)
- [Schema Markup Validator](https://validator.schema.org/)
- [Google Search Console](https://search.google.com/search-console)

### Internal Documentation
- [SEO-STRATEGY.md](../SEO-STRATEGY.md) - SEO strategy
- [CLAUDE.md](../CLAUDE.md) - Development guidelines
- [CODE_EXAMPLES.md](CODE_EXAMPLES.md) - Code examples

---

**Questions?** Check documentation or create an issue!

---

*Last updated: 2024-11-25*  
*Version: 1.0*
