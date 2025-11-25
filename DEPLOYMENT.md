# Deployment Guide – trzebnica-elektryk.pl

**Voltmont - Instalacje Elektryczne**  
Production deployment procedures and guidelines

---

## Table of Contents

1. [Deployment Overview](#deployment-overview)
2. [Environments](#environments)
3. [Pre-Deployment Checklist](#pre-deployment-checklist)
4. [Deployment Process](#deployment-process)
5. [Rollback Procedures](#rollback-procedures)
6. [Post-Deployment](#post-deployment)
7. [CI/CD Pipeline](#cicd-pipeline)
8. [Emergency Procedures](#emergency-procedures)
9. [Monitoring](#monitoring)

---

## Deployment Overview

### Deployment Strategy

**Blue-Green Deployment** (when applicable)  
**Zero-Downtime:** Maintenance mode only for database changes

### Deployment Schedule

**Regular Deployments:**
- **Staging:** Daily (Mon-Fri)
- **Production:** Weekly (Thursday, 14:00 CET)
- **Hotfixes:** As needed (with approval)

**Blackout Periods:**
- Major holidays
- Peak business hours (09:00-12:00, 14:00-17:00)
- Weekends (unless emergency)

---

## Environments

### Local Development

**Purpose:** Individual developer work

**URL:** `http://localhost:8080` or custom

**Database:** Local MySQL

**Setup:**
```bash
npm install
composer install
npm run dev
```

### Staging Environment

**Purpose:** Pre-production testing and client review

**URL:** `https://staging.trzebnica-elektryk.pl`

**Server:** 
- Host: [Staging server details]
- SSH: `ssh user@staging.trzebnica-elektryk.pl`

**Database:**
- Name: `voltmont_staging`
- Copy from production weekly

**Deployment Method:** Automated via `npm run deploy:staging`

### Production Environment

**Purpose:** Live website

**URL:** `https://trzebnica-elektryk.pl`

**Server:**
- Host: [Production server details]
- SSH: `ssh user@trzebnica-elektryk.pl`
- Backup server: [Backup server details]

**Database:**
- Name: `voltmont_production`
- Automated backups: Every 6 hours

**Deployment Method:** Automated via `npm run deploy:production`

---

## Pre-Deployment Checklist

### Code Quality

- [ ] All tests passing
  ```bash
  npm test
  npm run test:coverage
  ```

- [ ] Linters passing
  ```bash
  npm run lint
  php-cs-fixer fix --dry-run
  ```

- [ ] Build successful
  ```bash
  npm run build:production
  ```

- [ ] Code reviewed and approved (minimum 1 reviewer)

### Functionality Testing

- [ ] All new features tested locally
- [ ] All new features tested on staging
- [ ] Regression testing completed
- [ ] Cross-browser testing (Chrome, Firefox, Safari, Edge)
- [ ] Mobile testing (iOS, Android)
- [ ] Form submissions working
- [ ] Contact information correct

### Performance Testing

- [ ] Lighthouse score ≥ 90 (performance)
  ```bash
  npm run test:lighthouse
  ```

- [ ] Page load time < 3s
- [ ] Core Web Vitals meet targets:
  - LCP < 2.5s
  - FID < 100ms
  - CLS < 0.1

### Security Checks

- [ ] No console.log() in production code
- [ ] No debug mode enabled
- [ ] All passwords/keys in environment variables
- [ ] Security scan completed (Wordfence)
- [ ] SSL certificate valid and not expiring soon

### SEO Verification

- [ ] All pages have proper title tags
- [ ] All pages have meta descriptions
- [ ] Schema.org markup validated
- [ ] XML sitemap generated
- [ ] robots.txt correct
- [ ] No noindex tags on public pages

### Content Review

- [ ] All content finalized and approved
- [ ] Images optimized (WebP format)
- [ ] All links working (no 404s)
- [ ] Contact information accurate
- [ ] Legal pages updated (privacy policy, terms)

### Database

- [ ] Database backup created
- [ ] Database migrations tested on staging
- [ ] No breaking schema changes
- [ ] Rollback plan prepared

### Communication

- [ ] Team notified of deployment schedule
- [ ] Client notified (if significant changes)
- [ ] Maintenance mode message prepared (if needed)

---

## Deployment Process

### Staging Deployment

**Automated Deployment:**

```bash
# Ensure you're on the correct branch
git checkout main

# Pull latest changes
git pull origin main

# Run deployment script
npm run deploy:staging
```

**What happens:**
1. Code synced to staging server via rsync
2. Composer dependencies installed
3. npm dependencies installed
4. Assets built (production mode)
5. WordPress cache cleared
6. Object cache flushed

**Manual Steps (if needed):**

```bash
# SSH into staging
ssh user@staging.trzebnica-elektryk.pl

# Navigate to project
cd /var/www/staging.trzebnica-elektryk.pl

# Pull changes
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader
npm ci

# Build assets
npm run build:production

# Clear caches
wp cache flush
wp rewrite flush

# Set correct permissions
sudo chown -R www-data:www-data .
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;
sudo chmod 600 wp-config.php
```

### Production Deployment

**Pre-Deployment Announcement:**

Post maintenance notification 30 minutes before:
```text
Maintenance notification: trzebnica-elektryk.pl will undergo a brief update
at 14:00 CET. Expected downtime: < 5 minutes.
```

**Deployment Steps:**

1. **Create Backup:**
   ```bash
   npm run wp:backup
   ```

2. **Enable Maintenance Mode (if needed):**
   ```bash
   ssh user@trzebnica-elektryk.pl
   cd /var/www/trzebnica-elektryk.pl
   wp maintenance-mode activate
   ```

3. **Deploy Code:**
   ```bash
   npm run deploy:production
   ```

4. **Run Database Migrations (if any):**
   ```bash
   ssh user@trzebnica-elektryk.pl
   cd /var/www/trzebnica-elektryk.pl
   # Run migration script
   php migration-script.php
   ```

5. **Clear All Caches:**
   ```bash
   wp cache flush
   wp rewrite flush
   wp transient delete --all
   # Clear CDN cache (if applicable)
   ```

6. **Verify Deployment:**
   - Check homepage loads correctly
   - Test contact form submission
   - Verify portfolio pages
   - Check admin dashboard

7. **Disable Maintenance Mode:**
   ```bash
   wp maintenance-mode deactivate
   ```

8. **Post-Deployment Verification:**
   - Run smoke tests
   - Check Google Analytics tracking
   - Verify GTM firing
   - Test from different locations/IPs

**Deployment Script (`npm run deploy:production`):**

```bash
#!/bin/bash
# deploy-production.sh

set -e  # Exit on error

echo "Starting production deployment..."

# Build assets
echo "Building production assets..."
npm run build:production

# Sync to production (excluding sensitive files)
echo "Syncing files to production..."
rsync -avz --delete \
  --exclude '.git' \
  --exclude 'node_modules' \
  --exclude '.env' \
  --exclude 'wp-config.php' \
  --exclude 'backups' \
  dist/ user@trzebnica-elektryk.pl:/var/www/trzebnica-elektryk.pl/

# SSH into server and run post-deployment tasks
echo "Running post-deployment tasks..."
ssh user@trzebnica-elektryk.pl << 'EOF'
cd /var/www/trzebnica-elektryk.pl

# Install/update composer dependencies
composer install --no-dev --optimize-autoloader

# Clear caches
wp cache flush
wp rewrite flush
wp transient delete --all

# Set permissions
sudo chown -R www-data:www-data .
sudo find . -type f -exec chmod 644 {} \;
sudo find . -type d -exec chmod 755 {} \;
sudo chmod 600 wp-config.php

echo "Deployment completed successfully!"
EOF
```

---

## Rollback Procedures

### When to Rollback

**Immediate rollback triggers:**
- Site completely down
- Critical functionality broken
- Security vulnerability introduced
- Data corruption detected

### Rollback Process

**1. Quick Rollback (Code Only):**

```bash
# SSH into production
ssh user@trzebnica-elektryk.pl
cd /var/www/trzebnica-elektryk.pl

# Revert to previous commit
git reset --hard HEAD~1

# Rebuild assets
npm run build:production

# Clear caches
wp cache flush
wp rewrite flush
```

**2. Full Rollback (Code + Database):**

```bash
# Enable maintenance mode
wp maintenance-mode activate

# Restore database from backup
mysql -u root -p voltmont_production < /backups/backup_YYYYMMDD_HHMMSS.sql

# Revert code
git reset --hard [previous_commit_hash]
npm run build:production

# Clear caches
wp cache flush
wp rewrite flush

# Disable maintenance mode
wp maintenance-mode deactivate
```

**3. Verify Rollback:**
- Test critical functionality
- Check database integrity
- Verify forms and contact methods
- Test from multiple browsers/devices

**4. Post-Rollback:**
- Document what went wrong
- Create incident report
- Fix issues before next deployment
- Inform team and client

---

## Post-Deployment

### Verification Checklist

**Immediately After Deployment:**

- [ ] Homepage loads correctly
- [ ] All service pages accessible
- [ ] Portfolio/gallery working
- [ ] Contact form submits successfully
- [ ] Phone/email links working
- [ ] Admin dashboard accessible
- [ ] SSL certificate valid
- [ ] No JavaScript errors in console
- [ ] No PHP errors in logs

**Within 1 Hour:**

- [ ] Check Google Search Console for crawl errors
- [ ] Verify Google Analytics tracking
- [ ] Test from different locations (VPN)
- [ ] Check mobile site (multiple devices)
- [ ] Monitor server resources (CPU, memory, disk)
- [ ] Review error logs

**Within 24 Hours:**

- [ ] Monitor traffic patterns
- [ ] Check conversion rates (forms, calls)
- [ ] Review Wordfence security alerts
- [ ] Check uptime monitoring (UptimeRobot, Pingdom)
- [ ] Verify all scheduled tasks running (backups, etc.)

### Documentation

**Update Deployment Log:**

```markdown
## Deployment - 2024-01-15 14:00 CET

**Version:** 2.1.0
**Deployed By:** Jan Kowalski
**Duration:** 4 minutes
**Downtime:** 0 minutes

**Changes:**
- Implemented FAQ schema on service pages
- Optimized images for WebP format
- Fixed mobile menu overflow issue
- Updated WordPress core to 6.4.2

**Issues:** None
**Rollback:** Not required
```

### Client Communication

**Post-Deployment Email Template:**

```
Subject: Website Update Completed - trzebnica-elektryk.pl

Dzień dobry,

Informujemy, że aktualizacja strony trzebnica-elektryk.pl została pomyślnie zakończona.

Zmiany:
- [List major changes]
- [Performance improvements]
- [New features]

Wszystkie funkcje działają poprawnie i strona jest dostępna.

W razie jakichkolwiek pytań, prosimy o kontakt.

Pozdrawiam,
[Your Name]
PB-MEDIA
```

---

## CI/CD Pipeline

### GitHub Actions Workflows

**1. CI Workflow (`ci-wordpress.yml`):**

Runs on: Push, Pull Request

```yaml
name: CI WordPress

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'
      - name: Install dependencies
        run: npm ci
      - name: Run linters
        run: npm run lint
        
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'
      - name: Install dependencies
        run: npm ci
      - name: Run tests
        run: npm test
        
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup Node.js
        uses: actions/setup-node@v3
        with:
          node-version: '18'
      - name: Install dependencies
        run: npm ci
      - name: Build production
        run: npm run build:production
```

**2. Deploy Workflow (Manual Trigger):**

```yaml
name: Deploy to Production

on:
  workflow_dispatch:
    inputs:
      environment:
        description: 'Environment to deploy to'
        required: true
        default: 'staging'
        type: choice
        options:
          - staging
          - production

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Deploy to ${{ github.event.inputs.environment }}
        run: npm run deploy:${{ github.event.inputs.environment }}
        env:
          SSH_KEY: ${{ secrets.SSH_PRIVATE_KEY }}
```

### Continuous Monitoring

**Automated Checks:**

- **PageSpeed Monitor:** Daily Lighthouse runs
- **Security Scan:** Daily Wordfence scans
- **SEO Monitor:** Weekly SEO health checks
- **Uptime Monitor:** 5-minute interval checks

---

## Emergency Procedures

### Site Down

**1. Immediate Actions:**
```bash
# Check server status
ssh user@trzebnica-elektryk.pl
systemctl status apache2  # or nginx

# Check error logs
tail -f /var/log/apache2/error.log

# Check WordPress debug log
tail -f /var/www/trzebnica-elektryk.pl/wp-content/debug.log
```

**2. Common Issues:**

**Server down:**
```bash
sudo systemctl restart apache2
# or
sudo systemctl restart nginx
```

**Database connection error:**
```bash
# Check MySQL status
systemctl status mysql

# Restart if needed
sudo systemctl restart mysql
```

**Out of disk space:**
```bash
# Check disk usage
df -h

# Clean up old backups
find /backups -type f -mtime +30 -delete

# Clear temp files
sudo rm -rf /tmp/*
```

### Security Breach

**1. Immediate Response:**
- Take site offline (maintenance mode)
- Change all passwords
- Rotate security keys
- Scan for malware (Wordfence)

**2. Investigation:**
- Review access logs
- Check file modifications
- Identify entry point
- Document findings

**3. Remediation:**
- Remove malicious code
- Patch vulnerabilities
- Restore from clean backup
- Update all software

**4. Prevention:**
- Implement additional security measures
- Enable 2FA for all admin accounts
- Review user permissions
- Update incident response plan

### DDoS Attack

**1. Mitigation:**
- Enable Cloudflare (if available)
- Implement rate limiting
- Block malicious IPs
- Contact hosting provider

**2. Monitoring:**
- Watch server resources
- Monitor traffic patterns
- Check for unusual requests

---

## Monitoring

### Key Metrics

**Uptime:**
- Target: 99.9% uptime
- Monitor: UptimeRobot, Pingdom
- Alert: Slack, Email, SMS

**Performance:**
- Page load time < 3s
- Time to First Byte (TTFB) < 600ms
- Core Web Vitals within targets

**Traffic:**
- Google Analytics
- Server access logs
- Unusual traffic patterns

**Errors:**
- PHP errors (debug.log)
- JavaScript errors (console)
- HTTP errors (4xx, 5xx)

### Alert Thresholds

**Critical Alerts (Immediate Response):**
- Site down (no response to ping)
- SSL certificate expired
- Database connection failure
- Security breach detected

**Warning Alerts (Response within 1 hour):**
- High server load (CPU > 80%)
- Disk space low (< 10% free)
- High error rate (> 5%)
- Slow page load times (> 5s)

**Info Alerts (Review daily):**
- Failed login attempts
- Plugin updates available
- Backup completed
- Traffic anomalies

### Monitoring Tools

**Installed:**
- Wordfence (security)
- Google Analytics (traffic)
- Google Search Console (SEO)
- UptimeRobot (uptime)

**Recommended:**
- New Relic (performance)
- Sentry (error tracking)
- LogRocket (user sessions)

---

## Troubleshooting

### Common Issues

**White Screen of Death:**
```bash
# Enable debug mode
# Edit wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

# Check debug log
tail -f wp-content/debug.log
```

**Memory Limit Errors:**
```php
// Increase memory limit in wp-config.php
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
```

**Plugin Conflicts:**
```bash
# Disable all plugins
wp plugin deactivate --all

# Enable one by one to identify culprit
wp plugin activate plugin-name
```

**Theme Issues:**
```bash
# Switch to default theme
wp theme activate twentytwentyfour

# Test if issue persists
# If resolved, issue is in custom theme
```

---

## Deployment Contacts

**Primary Contact:**
- Name: [Developer Name]
- Email: dev@pb-media.pl
- Phone: [Emergency Phone]

**Hosting Support:**
- Provider: [Hosting Provider Name]
- Support: [Support Email/Phone]
- Account: [Account Number]

**Client Contact:**
- Name: [Client Name - Voltmont]
- Email: biuro@trzebnica-elektryk.pl
- Phone: +48 691 594 820

---

**Last Updated:** 2024-01-15  
**Version:** 1.0  
**Maintained by:** PB-MEDIA Development Team

**Next Review:** 2024-04-15
