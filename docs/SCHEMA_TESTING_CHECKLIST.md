# Schema.org Testing Checklist

**Quick validation checklist for structured data implementation**

---

## ✅ Pre-Deployment Tests

### 1. LocalBusiness Schema (Homepage)

- [ ] Navigate to homepage: https://trzebnica-elektryk.pl
- [ ] Test with [Google Rich Results Test](https://search.google.com/test/rich-results)
- [ ] Verify:
  - [ ] Company name: "Voltmont - Instalacje Elektryczne"
  - [ ] Phone: +48 691 594 820
  - [ ] Email: biuro@trzebnica-elektryk.pl
  - [ ] Address: Trzebnica, Dolnośląskie, PL
  - [ ] Geo coordinates: 51.3094, 17.0628
  - [ ] Logo image URL valid
  - [ ] 6 service offerings present
  - [ ] Opening hours correct
  - [ ] Social media links work

### 2. Service Schema (Service Pages)

Pick 2-3 service pages to test:

**Page 1:** _________________________

- [ ] Both LocalBusiness AND Service schemas present
- [ ] Service name = page title
- [ ] Service description present
- [ ] Provider references LocalBusiness (@id)
- [ ] Area served = Dolnośląskie
- [ ] Service URL matches page URL

**Page 2:** _________________________

- [ ] (Same checks as above)

**Page 3:** _________________________

- [ ] (Same checks as above)

### 3. FAQ Schema

Test 2 pages with FAQs:

**Page 1:** _________________________

- [ ] Navigate to page
- [ ] Test with Rich Results Test
- [ ] Verify:
  - [ ] FAQPage type detected
  - [ ] At least 2-3 Q&A pairs
  - [ ] Questions end with "?"
  - [ ] Answers are meaningful (not truncated)
  - [ ] Polish characters display correctly (ą, ć, ę, etc.)

**Page 2:** _________________________

- [ ] (Same checks as above)

### 4. Portfolio Schema

**Single Portfolio Item:**

- [ ] Navigate to any portfolio post
- [ ] Test with Rich Results Test
- [ ] Verify:
  - [ ] CreativeWork type detected
  - [ ] Name = portfolio title
  - [ ] Description present
  - [ ] Image URL valid
  - [ ] Author = Voltmont organization
  - [ ] datePublished present
  - [ ] dateModified present
  - [ ] Client name (if added in admin)
  - [ ] Location (if added in admin)
  - [ ] Project date (if added in admin)

**Portfolio Archive:**

- [ ] Navigate to: /realizacje/ (or portfolio archive)
- [ ] Test with Rich Results Test
- [ ] Verify:
  - [ ] ItemList type detected
  - [ ] numberOfItems = correct count
  - [ ] At least first 10 items in list
  - [ ] Each item has: position, url, name
  - [ ] Images present for items with featured image

### 5. Breadcrumb Schema

Test on 3 different page types:

**Service Page:**
- [ ] BreadcrumbList present
- [ ] Path: Home → Service Name
- [ ] Position numbers correct (1, 2)

**Portfolio Item:**
- [ ] BreadcrumbList present
- [ ] Path: Home → Portfolio Title
- [ ] Position numbers correct

**Child Page (if exists):**
- [ ] BreadcrumbList present
- [ ] Path includes parent pages
- [ ] All levels present

---

## 🔍 SEO Meta Tags Tests

### Meta Description

Check on 5 different pages:

**Homepage:**
- [ ] Meta description present
- [ ] Length: 150-160 characters
- [ ] Contains: "Trzebnica", "Dolny Śląsk"
- [ ] Phone number included
- [ ] Compelling copy

**Service Page 1:** _________________
- [ ] Description matches page content
- [ ] Length: 150-160 characters
- [ ] Location keywords present

**Service Page 2:** _________________
- [ ] (Same checks)

**Portfolio Item:**
- [ ] Description from excerpt or content
- [ ] Length: 150-160 characters

**Portfolio Archive:**
- [ ] Generic but descriptive
- [ ] Location keywords present

### OpenGraph Tags

Check on 2 pages:

**Page 1:** _________________________

- [ ] `og:title` present
- [ ] `og:description` present
- [ ] `og:image` valid URL
- [ ] `og:url` = canonical URL
- [ ] `og:type` = "website" or "article"
- [ ] `og:site_name` = Voltmont
- [ ] `og:locale` = pl_PL
- [ ] Business contact data tags present
- [ ] Preview looks good in [Facebook Debugger](https://developers.facebook.com/tools/debug/)

**Page 2:** _________________________

- [ ] (Same checks as above)

### Twitter Cards

- [ ] `twitter:card` = summary_large_image
- [ ] `twitter:title` present
- [ ] `twitter:description` present
- [ ] `twitter:image` valid
- [ ] Preview looks good in [Twitter Card Validator](https://cards-dev.twitter.com/validator)

### Geo Tags

- [ ] `geo.region` = PL-DS
- [ ] `geo.placename` = Trzebnica
- [ ] `geo.position` = 51.3094;17.0628
- [ ] `ICBM` present

---

## 🛠️ WordPress Admin Tests

### FAQ Meta Box

- [ ] Go to Pages → Edit any page
- [ ] "FAQ dla Schema.org" meta box visible
- [ ] Click "Dodaj pytanie"
- [ ] Fill in question and answer
- [ ] Click "Usuń" to remove
- [ ] Save page
- [ ] Reload page
- [ ] FAQs persist after save
- [ ] Check frontend - schema appears in head

### Portfolio Meta Box

- [ ] Go to Portfolio → Edit any portfolio item
- [ ] "Dane projektu (Schema.org)" meta box visible (sidebar)
- [ ] Fill in:
  - [ ] Klient: "Test Client"
  - [ ] Data realizacji: Select date
  - [ ] Lokalizacja: "Trzebnica"
- [ ] Save portfolio item
- [ ] Reload page
- [ ] Data persists
- [ ] Check frontend - schema includes client, date, location

### Portfolio Admin Columns

- [ ] Go to Portfolio → All Items
- [ ] Verify columns visible:
  - [ ] Klient
  - [ ] Lokalizacja
  - [ ] Data realizacji
- [ ] Click "Klient" column header → sorts alphabetically
- [ ] Click "Lokalizacja" column header → sorts alphabetically
- [ ] Click "Data realizacji" column header → sorts by date
- [ ] Data displays correctly (not "—" for filled items)

---

## 🌐 Browser Tests

Test on multiple browsers:

### Chrome/Edge
- [ ] All schemas validate
- [ ] No console errors
- [ ] Meta tags display in source

### Firefox
- [ ] All schemas validate
- [ ] No console errors
- [ ] Meta tags display in source

### Safari (if available)
- [ ] All schemas validate
- [ ] No console errors

---

## 📱 Mobile Tests

### Mobile Chrome
- [ ] Navigate to homepage
- [ ] View source → schemas present
- [ ] OpenGraph image appropriate size
- [ ] No mobile-specific errors

### Mobile Safari (if available)
- [ ] Same checks as mobile Chrome

---

## 🔬 Validation Tools

### Google Rich Results Test

For each page type:

- [ ] Homepage: https://search.google.com/test/rich-results?url=https://trzebnica-elektryk.pl
- [ ] Service page: Test 2-3 service pages
- [ ] FAQ page: Test 2 pages with FAQs
- [ ] Portfolio: Test 1 item + archive
- [ ] **Result:** 0 errors, 0 warnings (ideally)

### Schema.org Validator

- [ ] Homepage: https://validator.schema.org/
- [ ] Paste schema JSON from page source
- [ ] **Result:** Valid schema

### Google Search Console

(After deployment, check weekly)

- [ ] Login to GSC
- [ ] Go to Enhancements
- [ ] Check:
  - [ ] FAQ rich results - Valid count
  - [ ] Breadcrumbs - Valid count
  - [ ] No "Unparsable structured data" errors
  - [ ] No "Missing field" warnings

---

## 🚨 Common Issues Checklist

If validation fails, check:

- [ ] File included in functions.php `$voltmont_inc_files` array
- [ ] No PHP syntax errors (check error log)
- [ ] No trailing commas in JSON
- [ ] All strings properly escaped
- [ ] Using `wp_json_encode()` not manual JSON
- [ ] Priority not conflicting with other plugins
- [ ] Conditional logic allows output on correct pages
- [ ] All required fields present for schema type

---

## ✅ Final Checklist

Before marking implementation as complete:

- [ ] All schemas on checklist tested
- [ ] Google Rich Results Test shows 0 errors
- [ ] Schema.org Validator confirms valid JSON-LD
- [ ] Meta descriptions 150-160 chars on key pages
- [ ] OpenGraph images work in social media debuggers
- [ ] WordPress admin interfaces work correctly
- [ ] Documentation updated (if needed)
- [ ] Screenshots taken for records
- [ ] Tested in at least 2 browsers
- [ ] Mobile testing completed
- [ ] Client approved (if applicable)

---

## 📋 Test Results

**Tester Name:** _______________________  
**Date:** _______________________  
**Environment:** □ Staging  □ Production

**Overall Result:**
- [ ] ✅ All tests passed
- [ ] ⚠️ Minor issues found (documented below)
- [ ] ❌ Major issues found (blocked for deployment)

**Issues Found:**

1. ________________________________________________
2. ________________________________________________
3. ________________________________________________

**Notes:**

___________________________________________________
___________________________________________________
___________________________________________________

---

**Sign-off:** _______________________  
**Date:** _______________________

---

*Last updated: 2024-11-25*  
*Version: 1.0*
