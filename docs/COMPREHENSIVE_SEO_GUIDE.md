# Comprehensive SEO Guide - trzebnica-elektryk.pl

**Complete guide to all SEO features and functions**

---

## 🎯 Overview

This document explains all SEO features implemented in the Voltmont WordPress theme. The site now has 30+ SEO enhancements targeting better rankings, rich results, and optimal crawlability.

---

## 📋 Table of Contents

1. [Meta Tags](#meta-tags)
2. [Schema.org Markup](#schemaorg-markup)
3. [OpenGraph & Social](#opengraph--social)
4. [Image Optimization](#image-optimization)
5. [Internal Linking](#internal-linking)
6. [Mobile SEO](#mobile-seo)
7. [Technical SEO](#technical-seo)
8. [SEO Functions Reference](#seo-functions-reference)

---

## 1. Meta Tags

### Dynamic Meta Title

**Function:** `voltmont_get_meta_title()`

**Format:**
- **Homepage:** "Elektryk Trzebnica - Instalacje Elektryczne Dolny Śląsk | Voltmont"
- **Single Pages:** "[Page Title] | Voltmont - Instalacje Elektryczne"
- **Archives:** "[Archive Name] | Voltmont - Instalacje Elektryczne"
- **Search:** "Wyniki wyszukiwania: [Query] | Voltmont"

**Length:** Automatically truncated to 60 characters

**Example:**
```php
$title = voltmont_get_meta_title();
// Output: "Instalacje Smart Home | Voltmont - Instalacje Elektryczne"
```

### Dynamic Meta Description

**Function:** `voltmont_get_meta_description()`

**Logic:**
1. **Homepage:** Predefined optimized description with keywords
2. **Single Posts/Pages:** 
   - Uses excerpt if available
   - Falls back to first 25 words of content
   - Adds location keywords if missing
3. **Archives:** Category/term description or generated text

**Length:** Automatically truncated to 160 characters

**Example:**
```php
$description = voltmont_get_meta_description();
// Output: "Profesjonalne instalacje smart home w Trzebnicy. Systemy automatyki domowej, oświetlenie LED, sterowanie klimatyzacją. Trzebnica, Dolny Śląsk."
```

### Meta Keywords (Optional)

**Function:** `voltmont_get_meta_keywords()`

**Features:**
- Auto-generated from tags and categories
- Includes location keywords
- Limited to 10 keywords
- Comma-separated format

**Enable:**
```php
// In functions-seo.php, uncomment:
add_action('wp_head', 'voltmont_output_meta_keywords', 1);
```

### Author Meta Tag

Automatically added to all singular pages:
```html
<meta name="author" content="Admin">
```

### Copyright Meta Tag

Dynamically includes current year:
```html
<meta name="copyright" content="© 2024 Voltmont - Instalacje Elektryczne. Wszelkie prawa zastrzeżone.">
```

---

## 2. Schema.org Markup

### Implemented Schemas

| Schema Type | Page Type | Priority | Function |
|------------|-----------|----------|----------|
| LocalBusiness | Homepage | 5 | `schema-localbusiness.php` |
| Service | Service pages | 5 | `schema-localbusiness.php` |
| Organization | Homepage | 12 | `voltmont_output_organization_schema()` |
| WebSite | Homepage | 13 | `voltmont_output_website_navigation_schema()` |
| Article | Blog posts | 11 | `voltmont_output_article_schema()` |
| FAQPage | FAQ pages | 8 | `faq-schema.php` |
| CreativeWork | Portfolio | 9 | `schema-portfolio.php` |
| ItemList | Portfolio archive | 10 | `schema-portfolio.php` |
| BreadcrumbList | All pages | 6 | `schema-localbusiness.php` |
| VideoObject | Pages with video | 14 | `voltmont_output_video_schema()` |
| Person | About page | 15 | `voltmont_output_person_schema()` |

### Organization Schema

**Output:** Homepage only

**Includes:**
- Company name, URL, logo
- Email and telephone
- Physical address
- Social media profiles
- Contact point information

**Code:**
```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "@id": "https://trzebnica-elektryk.pl/#organization",
  "name": "Voltmont - Instalacje Elektryczne",
  "telephone": "+48691594820",
  "email": "biuro@trzebnica-elektryk.pl",
  "address": {...}
}
```

### WebSite Schema

**Features:**
- Site-wide navigation
- Search functionality
- Sitelinks search box

**Benefits:**
- Google sitelinks search box in SERP
- Better site structure understanding

### Article Schema

**Function:** `voltmont_output_article_schema()`

**Triggers:** Blog posts only

**Includes:**
- Headline, description
- Author information
- Publisher (Voltmont)
- Publication/modification dates
- Featured image
- Word count
- Reading time (estimated)

**Example:**
```json
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "5 powodów aby zmodernizować instalację elektryczną",
  "wordCount": 1250,
  "timeRequired": "PT6M"
}
```

### Video Schema

**Function:** `voltmont_output_video_schema()`

**Auto-detects:**
- YouTube embeds
- Vimeo embeds

**Generates:**
```json
{
  "@context": "https://schema.org",
  "@type": "VideoObject",
  "name": "Page title",
  "embedUrl": "https://youtube.com/embed/..."
}
```

---

## 3. OpenGraph & Social

### OpenGraph Tags

**Full implementation:**
- `og:locale` - pl_PL
- `og:type` - website/article
- `og:title` - Dynamic title
- `og:description` - Meta description
- `og:url` - Canonical URL
- `og:site_name` - Voltmont
- `og:image` - Featured image or logo
- `og:image:width` - 1200
- `og:image:height` - 630

**Article-specific:**
- `article:published_time`
- `article:modified_time`
- `article:author`
- `article:section` - Category
- `article:tag` - All post tags

**Business data:**
- `business:contact_data:*` - Full contact info

### Twitter Cards

**Type:** summary_large_image

**Tags:**
- `twitter:card`
- `twitter:title`
- `twitter:description`
- `twitter:image`
- `twitter:image:alt`

### Facebook Preview

Test with Facebook Sharing Debugger:
https://developers.facebook.com/tools/debug/

### LinkedIn Preview

Properly formatted OpenGraph tags work for LinkedIn sharing.

---

## 4. Image Optimization

### Automatic Alt Text

**Function:** `voltmont_auto_image_alt()`

**Logic:**
1. If image has alt text → use it
2. If image attached to post → use post title + " - Voltmont"
3. If standalone → use image title

**Example:**
```html
<!-- Before -->
<img src="image.jpg" alt="">

<!-- After -->
<img src="image.jpg" alt="Instalacje Smart Home - Voltmont Instalacje Elektryczne">
```

### Image Dimensions

All images automatically get `width` and `height` attributes for better CLS (Cumulative Layout Shift) scores.

### Lazy Loading

See [PERFORMANCE_GUIDE.md](PERFORMANCE_GUIDE.md) for lazy loading implementation.

---

## 5. Internal Linking

### Related Posts

**Function:** `voltmont_get_related_posts()`

**Logic:**
- Finds posts in same category
- Excludes current post
- Returns 3 random related posts

**Usage:**
```php
$related = voltmont_get_related_posts();

if ($related) {
    echo '<h3>Powiązane artykuły:</h3>';
    echo '<ul>';
    foreach ($related as $post) {
        setup_postdata($post);
        echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
    }
    echo '</ul>';
    wp_reset_postdata();
}
```

### External Link Nofollow (Optional)

**Function:** `voltmont_add_nofollow_to_external_links()`

Automatically adds `rel="nofollow noopener"` to external links.

**Enable:**
```php
// In functions-seo.php, uncomment:
add_filter('the_content', 'voltmont_add_nofollow_to_external_links');
```

### Pagination Links

**Function:** `voltmont_output_pagination_links()`

Adds `rel="prev"` and `rel="next"` for paginated content.

**Example:**
```html
<link rel="prev" href="https://trzebnica-elektryk.pl/blog/page/2/">
<link rel="next" href="https://trzebnica-elektryk.pl/blog/page/4/">
```

---

## 6. Mobile SEO

### Mobile Meta Tags

**Function:** `voltmont_output_mobile_meta_tags()`

**Includes:**
```html
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<meta name="format-detection" content="telephone=yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Voltmont">
<meta name="theme-color" content="#4d81e9">
```

### Mobile Features

1. **Clickable phone numbers** - `format-detection`
2. **Add to home screen** - Web app capabilities
3. **Theme color** - Brand blue in browser UI
4. **iOS status bar** - Translucent for better UX

---

## 7. Technical SEO

### Canonical URLs

Automatically added to all pages:
```html
<link rel="canonical" href="https://trzebnica-elektryk.pl/page-slug/">
```

### Robots Meta Tag

**Logic:**
- **404 & Search pages:** `noindex, follow`
- **All other pages:** `index, follow, max-snippet:-1, max-image-preview:large`

### Hreflang Tags

**Function:** `voltmont_output_hreflang_tags()`

Currently Polish only, ready for expansion:
```html
<link rel="alternate" hreflang="pl" href="https://trzebnica-elektryk.pl/">
<link rel="alternate" hreflang="x-default" href="https://trzebnica-elektryk.pl/">
```

### RSS Feeds

**Function:** `voltmont_add_rss_feed_links()`

Adds RSS and Atom feed links:
```html
<link rel="alternate" type="application/rss+xml" title="Voltmont RSS Feed" href="...">
<link rel="alternate" type="application/atom+xml" title="Voltmont Atom Feed" href="...">
```

### XML Sitemap Link

```html
<link rel="sitemap" type="application/xml" title="Sitemap" href="/sitemap.xml">
```

### Removed Bloat

**Function:** `voltmont_remove_unnecessary_meta_tags()`

**Removes:**
- WordPress version (security)
- Windows Live Writer link
- RSD link
- Shortlink
- Emoji detection script

**Keeps:**
- REST API link (for plugins)

### 301 Redirects

**Function:** `voltmont_seo_redirects()`

Define old → new URL mappings:
```php
$redirects = array(
    'old-service-page' => 'new-service-page',
    'outdated-blog-post' => 'updated-blog-post',
);
```

### Reading Time

**Function:** `voltmont_get_reading_time()`

Calculates estimated reading time (200 words/minute):
```php
$reading_time = voltmont_get_reading_time();
// Returns: 6 (minutes)
```

### Last Modified Date (Optional)

**Function:** `voltmont_add_last_modified()`

Shows last update date on blog posts.

**Enable:**
```php
// In functions-seo.php, uncomment:
add_filter('the_content', 'voltmont_add_last_modified', 1);
```

---

## 8. SEO Functions Reference

### Core Functions

| Function | Purpose | Return Type |
|----------|---------|-------------|
| `voltmont_get_meta_title()` | Generate page title | string (60 chars) |
| `voltmont_get_meta_description()` | Generate meta description | string (160 chars) |
| `voltmont_get_meta_keywords()` | Generate keywords | string |
| `voltmont_get_og_image()` | Get OpenGraph image | string (URL) |
| `voltmont_get_reading_time()` | Calculate reading time | int (minutes) |
| `voltmont_get_related_posts()` | Get related posts | array |
| `voltmont_get_title_separator()` | Get separator based on context | string |

### Schema Functions

| Function | Schema Type | Triggers On |
|----------|-------------|-------------|
| `voltmont_output_organization_schema()` | Organization | Homepage |
| `voltmont_output_website_navigation_schema()` | WebSite | Homepage |
| `voltmont_output_article_schema()` | Article | Blog posts |
| `voltmont_output_video_schema()` | VideoObject | Pages with video |
| `voltmont_output_person_schema()` | Person | About page |

### Output Functions

| Function | Outputs | Priority |
|----------|---------|----------|
| `voltmont_output_seo_meta_tags()` | Basic meta tags | 1 |
| `voltmont_output_opengraph_tags()` | OpenGraph tags | 2 |
| `voltmont_output_twitter_card_tags()` | Twitter Card tags | 3 |
| `voltmont_output_hreflang_tags()` | Hreflang tags | 4 |
| `voltmont_output_pagination_links()` | Prev/next links | 5 |
| `voltmont_output_mobile_meta_tags()` | Mobile tags | 1 |
| `voltmont_add_rss_feed_links()` | RSS links | 2 |

---

## 🔧 Configuration

### Site Verification

Add verification codes in `voltmont_output_site_verification()`:

**Google Search Console:**
```php
echo '<meta name="google-site-verification" content="YOUR_CODE_HERE">' . "\n";
```

**Bing Webmaster Tools:**
```php
echo '<meta name="msvalidate.01" content="YOUR_CODE_HERE">' . "\n";
```

**Yandex Webmaster:**
```php
echo '<meta name="yandex-verification" content="YOUR_CODE_HERE">' . "\n";
```

### Enable Optional Features

**Meta Keywords:**
```php
add_action('wp_head', 'voltmont_output_meta_keywords', 1);
```

**Nofollow External Links:**
```php
add_filter('the_content', 'voltmont_add_nofollow_to_external_links');
```

**Last Modified Date:**
```php
add_filter('the_content', 'voltmont_add_last_modified', 1);
```

### Custom Redirects

In `voltmont_seo_redirects()`:
```php
$redirects = array(
    'old-url' => 'new-url',
    'another-old-url' => 'another-new-url',
);
```

---

## 🧪 Testing

### Google Tools

**1. Google Search Console**
- URL: https://search.google.com/search-console
- Check: Coverage, Enhancements, Core Web Vitals

**2. Google Rich Results Test**
- URL: https://search.google.com/test/rich-results
- Test each page type for schema validity

**3. PageSpeed Insights**
- URL: https://pagespeed.web.dev/
- Check mobile & desktop scores
- Target: 90+ score

### Social Media Tools

**Facebook Sharing Debugger:**
- URL: https://developers.facebook.com/tools/debug/
- Check OpenGraph tags

**Twitter Card Validator:**
- URL: https://cards-dev.twitter.com/validator
- Check Twitter Card preview

**LinkedIn Post Inspector:**
- URL: https://www.linkedin.com/post-inspector/
- Check LinkedIn sharing preview

### Schema Validation

**Schema.org Validator:**
- URL: https://validator.schema.org/
- Paste JSON-LD from page source

**Google Structured Data Testing Tool:**
- URL: https://search.google.com/structured-data/testing-tool
- Test individual URLs

### SEO Debugging

Add `?seo_debug=1` to URL to see debug comments:
```
https://trzebnica-elektryk.pl/page/?seo_debug=1
```

View page source for:
```html
<!-- Voltmont SEO Debug -->
<!-- Title: [Generated Title] -->
<!-- Description: [Generated Description] -->
<!-- Keywords: [Generated Keywords] -->
<!-- Reading Time: X minutes -->
<!-- Page Type: Single/Homepage/Archive -->
```

---

## 📊 SEO Checklist

### Pre-Launch

- [ ] Add Google Search Console verification
- [ ] Add Bing Webmaster verification
- [ ] Configure 301 redirects if needed
- [ ] Test all schemas with Rich Results Test
- [ ] Verify OpenGraph with Facebook Debugger
- [ ] Check mobile meta tags
- [ ] Test canonical URLs
- [ ] Verify robots meta tag logic
- [ ] Check RSS feed links
- [ ] Test reading time calculation

### Post-Launch

- [ ] Submit sitemap to GSC
- [ ] Monitor Core Web Vitals
- [ ] Check schema enhancements in GSC
- [ ] Monitor coverage issues
- [ ] Track mobile usability
- [ ] Review search performance weekly
- [ ] Check for crawl errors
- [ ] Monitor indexing status

### Ongoing

- [ ] Update content with target keywords
- [ ] Add internal links to new content
- [ ] Update meta descriptions for underperforming pages
- [ ] Create content for search intent
- [ ] Build quality backlinks
- [ ] Monitor competitor rankings
- [ ] A/B test meta titles/descriptions
- [ ] Update schema as content changes

---

## 🎯 Target Keywords

### Primary Keywords

- elektryk Trzebnica
- instalacje elektryczne Trzebnica
- instalacje elektryczne Dolny Śląsk
- elektryk Wrocław
- smart home Trzebnica

### Secondary Keywords

- modernizacja instalacji elektrycznych
- instalacje odgromowe Trzebnica
- wymiana WLZ Trzebnica
- nadzór elektryczny Dolny Śląsk
- instalacje przemysłowe Wrocław

### Long-Tail Keywords

- ile kosztuje instalacja elektryczna w domu
- jak zmodernizować instalację elektryczną
- instalacje elektryczne smart home cena
- najlepszy elektryk w Trzebnicy
- profesjonalny elektryk Dolny Śląsk

**Full keyword strategy:** [SEO-STRATEGY.md](../SEO-STRATEGY.md)

---

## 📚 Resources

### Official Documentation
- [Google Search Central](https://developers.google.com/search)
- [Schema.org](https://schema.org/)
- [Open Graph Protocol](https://ogp.me/)
- [Twitter Cards](https://developer.twitter.com/en/docs/twitter-for-websites/cards)

### SEO Tools
- [Google Search Console](https://search.google.com/search-console)
- [Google Analytics](https://analytics.google.com/)
- [Ahrefs](https://ahrefs.com/)
- [SEMrush](https://www.semrush.com/)

### Internal Documentation
- [SCHEMA_ORG_GUIDE.md](SCHEMA_ORG_GUIDE.md) - Complete schema guide
- [PERFORMANCE_GUIDE.md](PERFORMANCE_GUIDE.md) - Performance optimization
- [SEO-STRATEGY.md](../SEO-STRATEGY.md) - SEO strategy
- [CODE_EXAMPLES.md](CODE_EXAMPLES.md) - Code examples

---

**Questions?** Check documentation or create an issue!

---

*Last updated: 2024-11-26*  
*Version: 2.0*
