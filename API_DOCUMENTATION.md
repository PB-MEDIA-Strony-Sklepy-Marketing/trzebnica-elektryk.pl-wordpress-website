# API Documentation – trzebnica-elektryk.pl

**Voltmont - Instalacje Elektryczne**  
Internal APIs and integration endpoints

---

## Overview

This document describes internal APIs, WordPress REST API extensions, and third-party integrations used in the project.

---

## WordPress REST API Extensions

### Custom Endpoints

#### Get Portfolio Items

**Endpoint:** `GET /wp-json/voltmont/v1/portfolio`

**Description:** Retrieve portfolio items with optional filtering

**Parameters:**
- `category` (string, optional) – Filter by portfolio category slug
- `per_page` (int, optional, default: 10) – Number of items per page
- `page` (int, optional, default: 1) – Page number

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 123,
            "title": "Modernizacja Instalacji - Blok Mieszkalny",
            "slug": "modernizacja-blok-trzebnica",
            "excerpt": "Kompleksowa modernizacja...",
            "featured_image": "https://trzebnica-elektryk.pl/wp-content/uploads/image.jpg",
            "categories": ["modernizacja", "wlz"],
            "date": "2024-01-15"
        }
    ],
    "pagination": {
        "total": 45,
        "per_page": 10,
        "current_page": 1,
        "total_pages": 5
    }
}
```

**Example:**
```javascript
fetch('/wp-json/voltmont/v1/portfolio?category=modernizacja&per_page=6')
    .then(response => response.json())
    .then(data => console.log(data));
```

---

#### Submit Contact Form

**Endpoint:** `POST /wp-json/voltmont/v1/contact`

**Description:** Submit contact form (alternative to Contact Form 7)

**Headers:**
```
Content-Type: application/json
X-WP-Nonce: [nonce_value]
```

**Body:**
```json
{
    "name": "Jan Kowalski",
    "email": "jan@example.com",
    "phone": "+48 123 456 789",
    "message": "Interesuje mnie modernizacja instalacji elektrycznej w bloku.",
    "service": "modernizacja",
    "consent": true
}
```

**Response (Success):**
```json
{
    "success": true,
    "message": "Dziękujemy za wiadomość! Skontaktujemy się wkrótce.",
    "id": 456
}
```

**Response (Error):**
```json
{
    "success": false,
    "message": "Błąd walidacji",
    "errors": {
        "email": "Nieprawidłowy adres email",
        "consent": "Wymagana zgoda na przetwarzanie danych"
    }
}
```

---

## AJAX Endpoints

### Load More Portfolio

**Action:** `voltmont_load_more_portfolio`

**Method:** POST to `admin-ajax.php`

**Parameters:**
```javascript
{
    action: 'voltmont_load_more_portfolio',
    nonce: voltmontAjax.nonce,
    page: 2,
    category: 'modernizacja'
}
```

**Response:**
```json
{
    "success": true,
    "html": "<div class=\"portfolio-item\">...</div>",
    "has_more": true
}
```

**JavaScript Example:**
```javascript
jQuery.ajax({
    url: voltmontAjax.ajaxurl,
    type: 'POST',
    data: {
        action: 'voltmont_load_more_portfolio',
        nonce: voltmontAjax.nonce,
        page: currentPage,
        category: selectedCategory
    },
    success: function(response) {
        if (response.success) {
            jQuery('.portfolio-grid').append(response.data.html);
        }
    }
});
```

---

## Third-Party APIs

### Google APIs

#### Google Analytics 4

**Measurement ID:** `G-XXXXXXXXXX`

**Event Tracking:**
```javascript
// Track custom event
gtag('event', 'phone_click', {
    'event_category': 'contact',
    'event_label': 'header_phone',
    'value': 1
});

// Track form submission
gtag('event', 'form_submit', {
    'event_category': 'lead',
    'event_label': 'contact_form',
    'value': 10
});
```

#### Google Maps API

**API Key:** [Stored in environment variables]

**Embed:**
```html
<iframe 
    width="600" 
    height="450" 
    frameborder="0" 
    src="https://www.google.com/maps/embed/v1/place?key=API_KEY&q=Trzebnica,Poland"
    allowfullscreen>
</iframe>
```

---

### Facebook APIs

#### Facebook Pixel

**Pixel ID:** [Stored in GTM]

**Standard Events:**
```javascript
// Page view (auto)
fbq('track', 'PageView');

// Custom event
fbq('track', 'Contact', {
    source: 'contact_form',
    value: 1
});
```

---

### Email Services

#### WP Mail SMTP

**SMTP Configuration:**
- Host: [SMTP server]
- Port: 587 (TLS)
- Authentication: Yes
- From Email: biuro@trzebnica-elektryk.pl
- From Name: Voltmont - Instalacje Elektryczne

**Sending Email:**
```php
wp_mail(
    'recipient@example.com',
    'Subject Line',
    'Email body content',
    array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Voltmont <biuro@trzebnica-elektryk.pl>'
    )
);
```

---

## Internal Function APIs

### SEO Functions

#### `voltmont_get_meta_title()`

**Description:** Generate optimized page title

**Parameters:** None (uses current page context)

**Returns:** `string` – SEO-optimized title (50-60 chars)

**Usage:**
```php
$title = voltmont_get_meta_title();
echo '<title>' . esc_html( $title ) . '</title>';
```

---

#### `voltmont_get_meta_description()`

**Description:** Generate meta description

**Returns:** `string` – Meta description (150-160 chars)

**Usage:**
```php
$description = voltmont_get_meta_description();
echo '<meta name="description" content="' . esc_attr( $description ) . '">';
```

---

### Schema Functions

#### `voltmont_output_local_business_schema()`

**Description:** Output LocalBusiness JSON-LD schema

**Hooked to:** `wp_head`

**Output:**
```html
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Voltmont - Instalacje Elektryczne",
    ...
}
</script>
```

---

#### `voltmont_output_service_schema()`

**Description:** Output Service schema for service pages

**Parameters:** None (auto-detects service pages)

**Hooked to:** `wp_head`

---

### Utility Functions

#### `voltmont_get_portfolio_categories()`

**Returns:** `array` – List of portfolio categories

**Example:**
```php
$categories = voltmont_get_portfolio_categories();
foreach ( $categories as $category ) {
    echo '<li>' . esc_html( $category->name ) . '</li>';
}
```

---

## Webhooks

### Contact Form Submission

**Triggered:** When Contact Form 7 form is submitted

**Action Hook:** `wpcf7_mail_sent`

**Handler:**
```php
function voltmont_cf7_submission( $contact_form ) {
    $submission = WPCF7_Submission::get_instance();
    $data = $submission->get_posted_data();
    
    // Log to database or send to CRM
    voltmont_log_lead( $data );
}
add_action( 'wpcf7_mail_sent', 'voltmont_cf7_submission' );
```

---

## Rate Limiting

### API Requests

**Default Limits:**
- Authenticated users: 100 requests/hour
- Non-authenticated: 20 requests/hour

**Implementation:**
```php
function voltmont_rate_limit() {
    $user_id = get_current_user_id() ?: $_SERVER['REMOTE_ADDR'];
    $transient_key = 'voltmont_rate_limit_' . md5( $user_id );
    
    $requests = get_transient( $transient_key ) ?: 0;
    
    if ( $requests >= 100 ) {
        wp_send_json_error( array(
            'message' => 'Rate limit exceeded. Try again in 1 hour.',
        ), 429 );
    }
    
    set_transient( $transient_key, $requests + 1, HOUR_IN_SECONDS );
}
```

---

## Error Codes

| Code | Description | Resolution |
|------|-------------|------------|
| 400 | Bad Request | Check request parameters |
| 401 | Unauthorized | Include valid nonce |
| 403 | Forbidden | Check user permissions |
| 404 | Not Found | Verify endpoint URL |
| 429 | Too Many Requests | Wait before retrying |
| 500 | Server Error | Check server logs |

---

## Authentication

### Nonce Verification

```javascript
// Get nonce (localized in script)
const nonce = voltmontAjax.nonce;

// Include in AJAX request
jQuery.ajax({
    url: voltmontAjax.ajaxurl,
    type: 'POST',
    data: {
        action: 'voltmont_action',
        nonce: nonce,
        // other data
    }
});
```

```php
// Verify nonce in handler
if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'voltmont-ajax-nonce' ) ) {
    wp_send_json_error( 'Security check failed' );
}
```

---

## Testing APIs

### REST API

```bash
# Test GET endpoint
curl https://trzebnica-elektryk.pl/wp-json/voltmont/v1/portfolio

# Test POST endpoint
curl -X POST https://trzebnica-elektryk.pl/wp-json/voltmont/v1/contact \
  -H "Content-Type: application/json" \
  -H "X-WP-Nonce: YOUR_NONCE" \
  -d '{"name":"Test","email":"test@example.com","message":"Test message","consent":true}'
```

### AJAX

```javascript
// Test AJAX endpoint
jQuery.post(voltmontAjax.ajaxurl, {
    action: 'voltmont_load_more_portfolio',
    nonce: voltmontAjax.nonce,
    page: 2
}, function(response) {
    console.log(response);
});
```

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintained by:** PB-MEDIA API Team
