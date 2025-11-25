# Security Policy – trzebnica-elektryk.pl

**Voltmont - Instalacje Elektryczne**  
Security guidelines and best practices for WordPress development

---

## Table of Contents

1. [Security Philosophy](#security-philosophy)
2. [Reporting Security Issues](#reporting-security-issues)
3. [WordPress Core Security](#wordpress-core-security)
4. [Theme Security (hubag-child)](#theme-security)
5. [Plugin Security](#plugin-security)
6. [Database Security](#database-security)
7. [Server & Hosting Security](#server--hosting-security)
8. [User Authentication & Authorization](#user-authentication--authorization)
9. [File & Directory Permissions](#file--directory-permissions)
10. [Backup & Recovery](#backup--recovery)
11. [Security Monitoring](#security-monitoring)
12. [Incident Response Plan](#incident-response-plan)
13. [Compliance & Standards](#compliance--standards)
14. [Security Checklist](#security-checklist)

---

## Security Philosophy

**Defense in Depth:** Multiple layers of security controls to protect the website.

**Core Principles:**
1. **Trust No Input** – Always validate and sanitize user input
2. **Escape All Output** – Prevent XSS attacks by escaping data
3. **Principle of Least Privilege** – Users/processes get minimum permissions needed
4. **Fail Securely** – Errors should not expose sensitive information
5. **Keep Everything Updated** – WordPress core, themes, plugins

---

## Reporting Security Issues

### How to Report

**DO NOT** open public GitHub issues for security vulnerabilities.

**Contact:**
- **Email:** security@trzebnica-elektryk.pl
- **PGP Key:** [PGP key fingerprint if available]
- **Response Time:** Within 24 hours

**Include in Report:**
- Description of the vulnerability
- Steps to reproduce
- Potential impact
- Suggested fix (if available)

### What to Expect

1. **Acknowledgment:** Within 24 hours
2. **Assessment:** Within 48 hours
3. **Fix Development:** 1-7 days (depending on severity)
4. **Deployment:** Immediately for critical issues
5. **Disclosure:** Coordinated disclosure after fix is live

### Severity Levels

| Level | Description | Response Time | Examples |
|-------|-------------|---------------|----------|
| **Critical** | Complete site compromise | Immediate | SQL injection, RCE, authentication bypass |
| **High** | Significant data exposure | <24h | XSS with session theft, privilege escalation |
| **Medium** | Limited data exposure | <72h | CSRF, information disclosure |
| **Low** | Minor impact | <7 days | Open redirects, verbose error messages |

---

## WordPress Core Security

### Version Management

**Current Version:** WordPress 6.4+

**Update Policy:**
- ✅ **Minor updates:** Automatic (6.4.1, 6.4.2, etc.)
- ✅ **Major updates:** Manual review + staging test first
- ✅ **Security patches:** Immediate deployment

**Configuration in `wp-config.php`:**
```php
// Enable automatic updates for minor releases
define( 'WP_AUTO_UPDATE_CORE', 'minor' );

// Disable file editing from dashboard
define( 'DISALLOW_FILE_EDIT', true );

// Disable plugin/theme installation from dashboard (production)
define( 'DISALLOW_FILE_MODS', true );
```

### Security Keys & Salts

**Generate new keys:** https://api.wordpress.org/secret-key/1.1/salt/

```php
// wp-config.php
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');
```

**Rotation Policy:** Every 6 months or after any suspected breach.

### Database Prefix

**Default:** `wp_` (insecure)  
**Custom:** `voltmont_` or random string

```php
// wp-config.php
$table_prefix = 'voltmont_xyz_';
```

### wp-config.php Hardening

**Location:** Move outside web root (if possible)

**Permissions:** `chmod 600 wp-config.php`

**Hide PHP Errors in Production:**
```php
// wp-config.php
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', true ); // Log to wp-content/debug.log
@ini_set( 'display_errors', 0 );
```

### XML-RPC Protection

**Disable if not needed:**
```php
// functions.php
add_filter( 'xmlrpc_enabled', '__return_false' );
```

**Or limit access via `.htaccess`:**
```apache
<Files xmlrpc.php>
  Order Deny,Allow
  Deny from all
</Files>
```

---

## Theme Security (hubag-child)

### Code Security Standards

**PSR-12 Compliance:** All PHP code follows PSR-12 coding standard.

**WordPress Coding Standards:** Using `@wordpress/eslint-plugin`

### Input Validation & Sanitization

**Always sanitize user input:**

```php
// Text input
$clean_text = sanitize_text_field( $_POST['field_name'] );

// Email
$clean_email = sanitize_email( $_POST['email'] );

// URL
$clean_url = esc_url_raw( $_POST['url'] );

// Integer
$clean_id = absint( $_POST['id'] );

// Textarea
$clean_textarea = sanitize_textarea_field( $_POST['message'] );

// HTML (allow safe tags)
$clean_html = wp_kses_post( $_POST['content'] );

// File upload
$allowed_types = array( 'jpg', 'jpeg', 'png', 'gif' );
$file_ext = pathinfo( $_FILES['file']['name'], PATHINFO_EXTENSION );
if ( ! in_array( $file_ext, $allowed_types, true ) ) {
    wp_die( 'Invalid file type' );
}
```

### Output Escaping

**Always escape output:**

```php
// HTML context
echo esc_html( $user_data );

// Attribute context
echo '<div data-value="' . esc_attr( $value ) . '">';

// URL context
echo '<a href="' . esc_url( $link ) . '">';

// JavaScript context
echo '<script>var data = ' . wp_json_encode( $data ) . ';</script>';

// Textarea
echo '<textarea>' . esc_textarea( $text ) . '</textarea>';

// For HTML content (when you control the HTML)
echo wp_kses_post( $allowed_html_content );
```

### SQL Injection Prevention

**Never use direct SQL queries. Always use `$wpdb->prepare()`:**

```php
global $wpdb;

// ❌ WRONG - Vulnerable to SQL injection
$results = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_title = '$title'" );

// ✅ CORRECT - Use prepared statements
$results = $wpdb->get_results( 
    $wpdb->prepare( 
        "SELECT * FROM $wpdb->posts WHERE post_title = %s", 
        $title 
    ) 
);

// Multiple placeholders
$results = $wpdb->get_results( 
    $wpdb->prepare( 
        "SELECT * FROM $wpdb->posts WHERE post_author = %d AND post_status = %s", 
        $author_id, 
        $status 
    ) 
);
```

**Placeholder types:**
- `%s` – String
- `%d` – Integer
- `%f` – Float

### CSRF Protection (Nonces)

**Always use nonces for forms and AJAX:**

```php
// Create nonce (in form)
wp_nonce_field( 'my_action_name', 'my_nonce_field' );

// Verify nonce (on form submission)
if ( ! isset( $_POST['my_nonce_field'] ) || ! wp_verify_nonce( $_POST['my_nonce_field'], 'my_action_name' ) ) {
    wp_die( 'Security check failed' );
}

// AJAX nonce
wp_localize_script( 'my-script', 'myAjax', array(
    'ajaxurl' => admin_url( 'admin-ajax.php' ),
    'nonce'   => wp_create_nonce( 'my_ajax_action' )
) );

// Verify AJAX nonce
check_ajax_referer( 'my_ajax_action', 'nonce' );
```

### Capability Checks

**Always verify user permissions:**

```php
// Check if user is logged in
if ( ! is_user_logged_in() ) {
    wp_die( 'You must be logged in' );
}

// Check specific capability
if ( ! current_user_can( 'edit_posts' ) ) {
    wp_die( 'You do not have permission' );
}

// Check if user can edit specific post
if ( ! current_user_can( 'edit_post', $post_id ) ) {
    wp_die( 'You cannot edit this post' );
}

// Admin-only actions
if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Admin access required' );
}
```

### File Upload Security

**Validate file uploads:**

```php
function secure_file_upload( $file ) {
    // Check if file was uploaded
    if ( ! isset( $file['tmp_name'] ) || empty( $file['tmp_name'] ) ) {
        return new WP_Error( 'no_file', 'No file uploaded' );
    }
    
    // Allowed MIME types
    $allowed_mime_types = array(
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'pdf'  => 'application/pdf'
    );
    
    // Check file type
    $filetype = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $allowed_mime_types );
    
    if ( ! $filetype['type'] || ! in_array( $filetype['type'], $allowed_mime_types, true ) ) {
        return new WP_Error( 'invalid_type', 'Invalid file type' );
    }
    
    // Check file size (5MB max)
    if ( $file['size'] > 5 * 1024 * 1024 ) {
        return new WP_Error( 'file_too_large', 'File is too large' );
    }
    
    // Use WordPress file upload handler
    $upload = wp_handle_upload( $file, array( 'test_form' => false ) );
    
    if ( isset( $upload['error'] ) ) {
        return new WP_Error( 'upload_failed', $upload['error'] );
    }
    
    return $upload;
}
```

### XSS Prevention

**Prevent Cross-Site Scripting:**

```php
// ❌ WRONG - Vulnerable to XSS
echo $_GET['search'];
echo $user_comment;

// ✅ CORRECT - Escaped output
echo esc_html( $_GET['search'] );
echo wp_kses_post( $user_comment );

// For attributes
echo '<div class="' . esc_attr( $class ) . '">';

// For JavaScript
echo '<script>var search = "' . esc_js( $search ) . '";</script>';
```

### Directory Traversal Prevention

**Prevent path traversal attacks:**

```php
// Validate file path
$file = sanitize_file_name( $_GET['file'] );
$allowed_dir = WP_CONTENT_DIR . '/uploads/allowed/';

// Resolve real path and check if it's within allowed directory
$real_path = realpath( $allowed_dir . $file );

if ( ! $real_path || strpos( $real_path, $allowed_dir ) !== 0 ) {
    wp_die( 'Invalid file path' );
}

// Now safe to read file
$content = file_get_contents( $real_path );
```

---

## Plugin Security

### Plugin Selection Criteria

**Before installing any plugin, verify:**

1. ✅ **Last Updated:** Within 6 months
2. ✅ **Active Installations:** 10,000+
3. ✅ **Ratings:** 4+ stars
4. ✅ **Support:** Active support forum
5. ✅ **Known Vulnerabilities:** Check WPScan database
6. ✅ **Code Quality:** Review if possible

### Required Security Plugins

**1. Wordfence Security** (or equivalent)
- Firewall
- Malware scanner
- Login security
- 2FA support

**2. WP Mail SMTP**
- Secure email delivery
- Prevents email spoofing

**3. UpdraftPlus**
- Automated backups
- Remote storage

**4. Redirection**
- Manage redirects securely
- Monitor 404 errors

### Plugin Update Policy

- ✅ **Minor updates:** Within 48 hours
- ✅ **Major updates:** Test on staging first
- ✅ **Security updates:** Immediate
- ✅ **Unused plugins:** Delete (don't just deactivate)

### Plugin Audit Schedule

**Quarterly Review:**
- List all installed plugins
- Check for updates
- Remove unused plugins
- Verify each plugin is still necessary
- Check for security advisories

---

## Database Security

### MySQL User Privileges

**Database User:** Minimum required privileges only

```sql
-- Create database user with limited privileges
GRANT SELECT, INSERT, UPDATE, DELETE ON database_name.* TO 'wordpress_user'@'localhost' IDENTIFIED BY 'strong_password';

-- No need for: CREATE, DROP, ALTER, GRANT
```

### Database Password

**Requirements:**
- Minimum 20 characters
- Mix of uppercase, lowercase, numbers, special characters
- Use password manager to generate

**Example:**
```php
// wp-config.php
define( 'DB_PASSWORD', 'xK9$mN2#qL8@pR4&vT7!' );
```

### Database Backups

**Frequency:**
- **Daily:** Automated backups (retention: 7 days)
- **Weekly:** Long-term backups (retention: 4 weeks)
- **Pre-update:** Manual backup before any major changes

**Backup Storage:**
- ✅ Remote location (not on same server)
- ✅ Encrypted backups
- ✅ Test restoration quarterly

**Automated Backup Script:**
```bash
#!/bin/bash
# Daily database backup script

TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
BACKUP_DIR="/backups/wordpress"
DB_NAME="voltmont_db"
DB_USER="backup_user"
DB_PASS="backup_password"

# Create backup
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/backup_$TIMESTAMP.sql.gz

# Delete backups older than 7 days
find $BACKUP_DIR -type f -name "*.sql.gz" -mtime +7 -delete
```

### SQL Injection Protection

**Already covered in Theme Security section.**

Additional database-level protection:

```sql
-- Disable LOCAL INFILE
SET GLOBAL local_infile = 0;

-- Use parameterized queries (always)
-- Never concatenate user input in SQL strings
```

---

## Server & Hosting Security

### Server Configuration

**Required:**
- ✅ **HTTPS:** SSL/TLS certificate (Let's Encrypt or commercial)
- ✅ **PHP 8.0+:** Latest stable version
- ✅ **ModSecurity:** Web Application Firewall
- ✅ **Fail2Ban:** Brute force protection
- ✅ **Firewall:** UFW or iptables configured

### SSL/TLS Configuration

**Force HTTPS:**

```apache
# .htaccess
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

**WordPress configuration:**
```php
// wp-config.php
define( 'FORCE_SSL_ADMIN', true );

if ( $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
    $_SERVER['HTTPS'] = 'on';
}
```

### Security Headers

**Add to `.htaccess` or nginx config:**

```apache
# .htaccess
<IfModule mod_headers.c>
    # Prevent clickjacking
    Header always set X-Frame-Options "SAMEORIGIN"
    
    # Prevent MIME sniffing
    Header always set X-Content-Type-Options "nosniff"
    
    # Enable XSS protection
    Header always set X-XSS-Protection "1; mode=block"
    
    # Referrer Policy
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    
    # Content Security Policy (adjust as needed)
    Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' data:;"
    
    # Permissions Policy
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>
```

### .htaccess Hardening

**Complete `.htaccess` security configuration:**

```apache
# BEGIN WordPress Security

# Disable directory browsing
Options -Indexes

# Protect wp-config.php
<Files wp-config.php>
    Order allow,deny
    Deny from all
</Files>

# Protect .htaccess
<Files .htaccess>
    Order allow,deny
    Deny from all
</Files>

# Protect readme.html, license.txt, wp-config-sample.php
<FilesMatch "^(readme\.html|license\.txt|wp-config-sample\.php)">
    Order allow,deny
    Deny from all
</FilesMatch>

# Disable PHP execution in uploads directory
<Directory "/var/www/html/wp-content/uploads/">
    <FilesMatch "\.php$">
        Order allow,deny
        Deny from all
    </FilesMatch>
</Directory>

# Block access to wp-includes
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^wp-admin/includes/ - [F,L]
RewriteRule !^wp-includes/ - [S=3]
RewriteRule ^wp-includes/[^/]+\.php$ - [F,L]
RewriteRule ^wp-includes/js/tinymce/langs/.+\.php - [F,L]
RewriteRule ^wp-includes/theme-compat/ - [F,L]
</IfModule>

# Limit upload file size (20MB)
LimitRequestBody 20971520

# Block bad bots and scrapers
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTP_USER_AGENT} (havij|libwww-perl|wget|python|nikto|curl|scan|java|winhttp|clshttp|loader) [NC,OR]
RewriteCond %{HTTP_USER_AGENT} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{HTTP_USER_AGENT} (;|<|>|'|"|\)|\(|%0A|%0D|%22|%27|%28|%3C|%3E|%00).*(libwww-perl|wget|python|nikto|curl|scan|java|winhttp|HTTrack|clshttp|archiver|loader|email|harvest|extract|grab|miner) [NC,OR]
RewriteCond %{THE_REQUEST} (\?|\*|%2a)+(%20+|\\s+|%20+\\s+|\\s+%20+|\\s+%20+\\s+)(http|https)(:/|/) [NC,OR]
RewriteCond %{THE_REQUEST} etc/passwd [NC,OR]
RewriteCond %{THE_REQUEST} cgi-bin [NC,OR]
RewriteCond %{THE_REQUEST} (%0A|%0D) [NC,OR]
RewriteCond %{REQUEST_URI} owssvr\.dll [NC,OR]
RewriteCond %{HTTP_REFERER} (%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{HTTP_REFERER} \.opendirviewer\. [NC,OR]
RewriteCond %{HTTP_REFERER} users\.skynet\.be.* [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=http:// [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=(\.\.//?)+ [NC,OR]
RewriteCond %{QUERY_STRING} [a-zA-Z0-9_]=/([a-z0-9_.]//?)+ [NC,OR]
RewriteCond %{QUERY_STRING} \=PHP[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12} [NC,OR]
RewriteCond %{QUERY_STRING} (\.\./|\.\.) [OR]
RewriteCond %{QUERY_STRING} ftp\: [NC,OR]
RewriteCond %{QUERY_STRING} http\: [NC,OR]
RewriteCond %{QUERY_STRING} https\: [NC,OR]
RewriteCond %{QUERY_STRING} \=\|w\| [NC,OR]
RewriteCond %{QUERY_STRING} ^(.*)/self/(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} ^(.*)cPath=http://(.*)$ [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^s]*s)+cript.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (\<|%3C).*iframe.*(\>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} (<|%3C)([^i]*i)+frame.*(>|%3E) [NC,OR]
RewriteCond %{QUERY_STRING} base64_encode.*\(.*\) [NC,OR]
RewriteCond %{QUERY_STRING} base64_(en|de)code[^(]*\([^)]*\) [NC,OR]
RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2}) [OR]
RewriteCond %{QUERY_STRING} ^.*(\[|\]|\(|\)|<|>|ê|"|;|\?|\*|=$).* [NC,OR]
RewriteCond %{QUERY_STRING} (NULL|OUTFILE|LOAD_FILE) [OR]
RewriteCond %{QUERY_STRING} (\.{1,}/)+(motd|etc|bin) [NC,OR]
RewriteCond %{QUERY_STRING} (localhost|loopback|127\.0\.0\.1) [NC,OR]
RewriteCond %{QUERY_STRING} (<|>|'|%0A|%0D|%27|%3C|%3E|%00) [NC,OR]
RewriteCond %{QUERY_STRING} concat[^\(]*\( [NC,OR]
RewriteCond %{QUERY_STRING} union([^s]*s)+elect [NC,OR]
RewriteCond %{QUERY_STRING} union([^a]*a)+ll([^s]*s)+elect [NC,OR]
RewriteCond %{QUERY_STRING} (;|<|>|'|"|\)|%0A|%0D|%22|%27|%3C|%3E|%00).*(/\*|union|select|insert|drop|delete|update|cast|create|char|convert|alter|declare|order|script|set|md5|benchmark|encode) [NC,OR]
RewriteCond %{QUERY_STRING} (sp_executesql) [NC]
RewriteRule ^(.*)$ - [F,L]
</IfModule>

# END WordPress Security
```

### Server Monitoring

**Install monitoring tools:**
- **Uptime:** UptimeRobot, Pingdom
- **Security:** Sucuri, Wordfence Central
- **Performance:** New Relic, GTmetrix alerts

---

## User Authentication & Authorization

### Password Policy

**Requirements:**
- Minimum 12 characters
- Mix of uppercase, lowercase, numbers, special characters
- No dictionary words
- Different from username

**Enforce in functions.php:**
```php
function voltmont_password_requirements( $errors, $user_login, $user_email ) {
    if ( isset( $_POST['pass1'] ) && strlen( $_POST['pass1'] ) < 12 ) {
        $errors->add( 'password_length', 'Password must be at least 12 characters.' );
    }
    
    if ( isset( $_POST['pass1'] ) && ! preg_match( '/[A-Z]/', $_POST['pass1'] ) ) {
        $errors->add( 'password_uppercase', 'Password must contain at least one uppercase letter.' );
    }
    
    if ( isset( $_POST['pass1'] ) && ! preg_match( '/[a-z]/', $_POST['pass1'] ) ) {
        $errors->add( 'password_lowercase', 'Password must contain at least one lowercase letter.' );
    }
    
    if ( isset( $_POST['pass1'] ) && ! preg_match( '/[0-9]/', $_POST['pass1'] ) ) {
        $errors->add( 'password_number', 'Password must contain at least one number.' );
    }
    
    return $errors;
}
add_filter( 'registration_errors', 'voltmont_password_requirements', 10, 3 );
```

### Two-Factor Authentication (2FA)

**Recommended Plugin:** Wordfence or Two Factor

**Enforce for admin users:**
```php
// Require 2FA for admins
function voltmont_require_2fa_for_admins( $user ) {
    if ( user_can( $user, 'manage_options' ) && ! two_factor_enabled( $user->ID ) ) {
        wp_die( 'Two-factor authentication is required for admin accounts.' );
    }
}
add_action( 'wp_login', 'voltmont_require_2fa_for_admins' );
```

### Login Security

**Limit login attempts:**
```php
// Use Wordfence or implement custom solution
function voltmont_check_login_attempts( $user, $username, $password ) {
    $max_attempts = 3;
    $lockout_duration = 900; // 15 minutes
    
    $attempts = get_transient( 'failed_login_attempts_' . $username );
    
    if ( $attempts >= $max_attempts ) {
        return new WP_Error( 'too_many_attempts', 'Too many failed login attempts. Please try again in 15 minutes.' );
    }
    
    return $user;
}
add_filter( 'authenticate', 'voltmont_check_login_attempts', 30, 3 );

// Increment on failure
function voltmont_record_failed_login( $username ) {
    $attempts = get_transient( 'failed_login_attempts_' . $username ) ?: 0;
    set_transient( 'failed_login_attempts_' . $username, $attempts + 1, 900 );
}
add_action( 'wp_login_failed', 'voltmont_record_failed_login' );

// Reset on success
function voltmont_reset_login_attempts( $user_login ) {
    delete_transient( 'failed_login_attempts_' . $user_login );
}
add_action( 'wp_login', 'voltmont_reset_login_attempts' );
```

**Change admin username:**
- Never use "admin" as username
- Use unique, non-obvious username

**Hide login errors:**
```php
// Don't reveal if username or password was wrong
function voltmont_generic_login_error() {
    return 'Invalid credentials.';
}
add_filter( 'login_errors', 'voltmont_generic_login_error' );
```

### Session Management

**Force logout after inactivity:**
```php
function voltmont_logout_inactive_users() {
    $timeout = 1800; // 30 minutes
    
    if ( is_user_logged_in() ) {
        $last_activity = get_user_meta( get_current_user_id(), 'last_activity', true );
        
        if ( ! $last_activity ) {
            update_user_meta( get_current_user_id(), 'last_activity', time() );
        } elseif ( time() - $last_activity > $timeout ) {
            wp_logout();
            wp_redirect( home_url() );
            exit;
        } else {
            update_user_meta( get_current_user_id(), 'last_activity', time() );
        }
    }
}
add_action( 'init', 'voltmont_logout_inactive_users' );
```

### User Roles & Capabilities

**Principle of Least Privilege:**

```php
// Custom role for client access (read-only)
function voltmont_add_client_role() {
    add_role(
        'client_viewer',
        'Client Viewer',
        array(
            'read' => true,
        )
    );
}
add_action( 'init', 'voltmont_add_client_role' );

// Remove dangerous capabilities from editors
function voltmont_restrict_editor_capabilities() {
    $role = get_role( 'editor' );
    $role->remove_cap( 'edit_theme_options' );
    $role->remove_cap( 'delete_plugins' );
    $role->remove_cap( 'install_plugins' );
}
add_action( 'admin_init', 'voltmont_restrict_editor_capabilities' );
```

---

## File & Directory Permissions

### Recommended Permissions

**Directories:**
```bash
# Most directories
chmod 755 /path/to/directory

# wp-content and subdirectories
chmod 755 wp-content/
chmod 755 wp-content/themes/
chmod 755 wp-content/plugins/

# Uploads directory (needs write access)
chmod 755 wp-content/uploads/
```

**Files:**
```bash
# Most files
chmod 644 /path/to/file.php

# wp-config.php (most restrictive)
chmod 600 wp-config.php

# .htaccess
chmod 644 .htaccess
```

**Ownership:**
```bash
# Set correct ownership (example for Apache)
chown -R www-data:www-data /var/www/html/

# For nginx
chown -R nginx:nginx /var/www/html/
```

### Prevent Unauthorized File Modifications

**Make core files read-only:**
```bash
# Make WordPress core files immutable (after updates)
chattr +i wp-config.php
chattr +i index.php
chattr +i .htaccess

# To remove immutable flag (for updates)
chattr -i wp-config.php
```

### Disable File Editing in Dashboard

**Already covered in WordPress Core Security:**
```php
// wp-config.php
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', true ); // Production only
```

---

## Backup & Recovery

### Backup Strategy (3-2-1 Rule)

**3 copies of data:**
- Production server (primary)
- Local backup server
- Cloud storage (AWS S3, Google Cloud, Backblaze)

**2 different media types:**
- Server disk
- Cloud storage

**1 copy offsite:**
- Remote cloud backup

### Automated Backups

**Using UpdraftPlus (configured via plugin):**
- **Files:** Daily (7-day retention)
- **Database:** Every 6 hours (7-day retention)
- **Storage:** Google Drive + Amazon S3
- **Encryption:** AES-256

**Manual Backup Script:**
```bash
#!/bin/bash
# Complete WordPress backup script

DATE=$(date +"%Y%m%d_%H%M%S")
SITE_PATH="/var/www/html"
BACKUP_PATH="/backups/wordpress"
DB_NAME="wordpress_db"
DB_USER="backup_user"
DB_PASS="backup_password"

# Create backup directory
mkdir -p $BACKUP_PATH/$DATE

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_PATH/$DATE/database.sql.gz

# Backup files (exclude cache)
tar -czf $BACKUP_PATH/$DATE/files.tar.gz \
    --exclude='*/cache/*' \
    --exclude='*/logs/*' \
    $SITE_PATH

# Upload to S3 (optional)
aws s3 sync $BACKUP_PATH/$DATE s3://voltmont-backups/wordpress/$DATE/

# Delete local backups older than 7 days
find $BACKUP_PATH -type d -mtime +7 -exec rm -rf {} +

echo "Backup completed: $DATE"
```

### Disaster Recovery Plan

**RTO (Recovery Time Objective):** 4 hours  
**RPO (Recovery Point Objective):** 6 hours (last backup)

**Recovery Steps:**

1. **Assess Damage:**
   - Identify what was compromised
   - Document all findings

2. **Isolate Affected Systems:**
   - Take site offline (maintenance mode)
   - Disconnect from network if needed

3. **Restore from Backup:**
   ```bash
   # Restore database
   gunzip database.sql.gz
   mysql -u root -p wordpress_db < database.sql
   
   # Restore files
   tar -xzf files.tar.gz -C /var/www/html/
   
   # Set correct permissions
   chown -R www-data:www-data /var/www/html/
   chmod 644 wp-config.php
   ```

4. **Security Scan:**
   - Run malware scanner (Wordfence, Sucuri)
   - Check for backdoors
   - Review user accounts

5. **Update Everything:**
   - WordPress core
   - All themes
   - All plugins
   - Change all passwords
   - Regenerate security keys

6. **Test Thoroughly:**
   - Verify all pages load
   - Test forms and functionality
   - Check database integrity

7. **Bring Site Back Online:**
   - Disable maintenance mode
   - Monitor closely for 48 hours

### Testing Backups

**Quarterly Restoration Test:**
- Restore backup to staging environment
- Verify all data is intact
- Test functionality
- Document any issues

---

## Security Monitoring

### Real-Time Monitoring

**Wordfence Alerts:**
- File changes
- Failed login attempts
- Suspicious traffic
- Malware detection

**Server Monitoring:**
- CPU/Memory usage
- Disk space
- Server errors (via logs)

### Log Management

**Enable WordPress debug log (production):**
```php
// wp-config.php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

**Rotate logs:**
```bash
# /etc/logrotate.d/wordpress
/var/www/html/wp-content/debug.log {
    daily
    missingok
    rotate 7
    compress
    notifempty
    create 0644 www-data www-data
}
```

**Monitor Apache/Nginx logs:**
```bash
# Watch for suspicious activity
tail -f /var/log/apache2/error.log | grep -i "error\|warning\|alert"

# Find 404 errors
awk '$9 == 404' /var/log/apache2/access.log | awk '{print $7}' | sort | uniq -c | sort -rn | head -20
```

### Security Scanning

**Automated Scans:**
- **Daily:** Wordfence malware scan
- **Weekly:** WPScan vulnerability check
- **Monthly:** Manual code review

**WPScan (command-line tool):**
```bash
# Install WPScan
gem install wpscan

# Scan for vulnerabilities
wpscan --url https://trzebnica-elektryk.pl --api-token YOUR_TOKEN
```

### File Integrity Monitoring

**Monitor file changes:**
```bash
# Use AIDE (Advanced Intrusion Detection Environment)
apt-get install aide

# Initialize database
aideinit

# Check for changes
aide --check
```

**Or use Wordfence file change detection (simpler).**

---

## Incident Response Plan

### Incident Classification

**Severity Levels:**

1. **Critical:** Site completely compromised, data breach
2. **High:** Partial compromise, limited data exposure
3. **Medium:** Suspicious activity, no confirmed breach
4. **Low:** Minor security issue, no immediate threat

### Response Team

**Primary Contact:** security@trzebnica-elektryk.pl  
**Developer:** dev@pb-media.pl  
**Hosting Support:** [Hosting provider contact]

### Incident Response Workflow

**1. Detection & Analysis (0-1 hour)**
- Identify incident type and severity
- Document all evidence
- Preserve logs and files

**2. Containment (1-2 hours)**
- Isolate affected systems
- Enable maintenance mode
- Block malicious IPs (if known)
- Change all passwords

**3. Eradication (2-6 hours)**
- Remove malware/backdoors
- Patch vulnerabilities
- Update all software
- Review and remove suspicious users

**4. Recovery (6-24 hours)**
- Restore from clean backup (if needed)
- Bring systems back online
- Monitor for re-infection

**5. Post-Incident (24-72 hours)**
- Complete incident report
- Identify root cause
- Implement preventive measures
- Update security policies

### Communication Plan

**Internal:**
- Notify team immediately
- Hourly updates during incident
- Post-mortem meeting

**External:**
- Client notification within 24 hours (if data breach)
- Public disclosure (if required by law)

---

## Compliance & Standards

### GDPR Compliance

**Data Protection Measures:**
- ✅ SSL/TLS encryption
- ✅ Secure password storage (WordPress default hashing)
- ✅ Right to erasure (data deletion tools)
- ✅ Privacy policy page
- ✅ Cookie consent banner

**WordPress GDPR tools:**
```php
// Export user data
wp_privacy_generate_personal_data_export_file( $user_id );

// Erase user data
wp_privacy_anonymize_user( $user_id );
```

### PCI DSS (if accepting payments)

**Requirements:**
- Use payment gateway (don't store card data)
- SSL/TLS certificate
- Regular security scans
- Access control

**Recommended:** Use Stripe, PayPal, or similar (they handle PCI compliance)

### OWASP Top 10 Compliance

**Coverage:**

1. ✅ **Injection:** Prepared statements, input sanitization
2. ✅ **Broken Authentication:** Strong passwords, 2FA, session management
3. ✅ **Sensitive Data Exposure:** HTTPS, encrypted backups
4. ✅ **XML External Entities:** Disable XML-RPC
5. ✅ **Broken Access Control:** Capability checks, nonces
6. ✅ **Security Misconfiguration:** Hardened wp-config.php, .htaccess
7. ✅ **XSS:** Output escaping, CSP headers
8. ✅ **Insecure Deserialization:** Avoid unserialize() with user data
9. ✅ **Using Components with Known Vulnerabilities:** Regular updates
10. ✅ **Insufficient Logging & Monitoring:** Debug logs, Wordfence alerts

---

## Security Checklist

### Daily

- [ ] Review Wordfence alerts
- [ ] Check failed login attempts
- [ ] Monitor server resources

### Weekly

- [ ] Check for plugin/theme updates
- [ ] Review new user registrations (if enabled)
- [ ] Scan error logs for anomalies

### Monthly

- [ ] Full malware scan
- [ ] Review user roles and permissions
- [ ] Check backup integrity
- [ ] Audit installed plugins (remove unused)
- [ ] Review security headers

### Quarterly

- [ ] Test disaster recovery
- [ ] Rotate passwords for admin accounts
- [ ] Rotate security keys in wp-config.php
- [ ] Complete security audit
- [ ] Review and update security policies
- [ ] Update SSL certificate (if needed)

### Annually

- [ ] Comprehensive penetration test
- [ ] Security training for team
- [ ] Review incident response plan
- [ ] Compliance audit (GDPR, etc.)

---

## Resources & References

### Security Tools

- **Wordfence:** https://www.wordfence.com/
- **Sucuri:** https://sucuri.net/
- **WPScan:** https://wpscan.com/
- **Security Headers:** https://securityheaders.com/
- **SSL Labs:** https://www.ssllabs.com/ssltest/

### WordPress Security Guides

- **WordPress Codex - Hardening:** https://wordpress.org/support/article/hardening-wordpress/
- **OWASP WordPress Security:** https://owasp.org/www-project-wordpress-security/
- **WP White Security:** https://www.wpwhitesecurity.com/

### Security News

- **WPScan Vulnerability Database:** https://wpscan.com/wordpresses
- **WordPress Security Blog:** https://wordpress.org/news/category/security/

---

## Changelog

**Version 1.0** (2024-01-15)
- Initial security policy document
- Comprehensive security guidelines
- Incident response plan
- Compliance requirements

---

**Last Updated:** 2024-01-15  
**Document Owner:** PB-MEDIA Security Team  
**Review Schedule:** Quarterly

**For security concerns, contact:** security@trzebnica-elektryk.pl
