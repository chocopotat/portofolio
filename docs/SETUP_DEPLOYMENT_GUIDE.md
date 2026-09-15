# 🚀 Portfolio Setup & Deployment Guide

A complete guide to setting up, configuring, testing, and deploying your Laravel portfolio landing page.

---

## 1. LOCAL DEVELOPMENT SETUP

### 1.1 Prerequisites
Ensure you have installed:
- **PHP 8.2+** (with extensions: openssl, pdo, mbstring, tokenizer, xml)
- **Composer** (dependency manager for PHP)
- **MySQL 8.0+** or **MariaDB**
- **Node.js 16+** (for Vite asset compilation)
- **Git**

### 1.2 Clone & Install

```bash
# Navigate to project directory
cd /path/to/portfolio

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate Laravel app key
php artisan key:generate

# Create symbolic link for storage
php artisan storage:link
```

### 1.3 Database Configuration

**Option A: SQLite (Quick Setup - Default)**
```bash
# SQLite is already configured in .env
# No additional setup needed - just run migrations
php artisan migrate:fresh --seed
```

**Option B: MySQL (Production Setup)**

1. **Create database:**
```sql
CREATE DATABASE portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. **Update `.env` file:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=portfolio
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

3. **Run migrations:**
```bash
php artisan migrate:fresh --seed
```

### 1.4 Mail Configuration

For local testing, you can use **Mailtrap** (free tier available):

1. **Sign up at:** https://mailtrap.io
2. **Get your credentials** from Mailtrap dashboard
3. **Update `.env`:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_FROM_ADDRESS=noreply@portfolio.local
MAIL_FROM_NAME=Portfolio
CONTACT_FORM_RECIPIENT=your-email@example.com
```

**Alternative: Log to Console (for development)**
```env
MAIL_MAILER=log
```
This will show emails in the Laravel log instead of sending.

### 1.5 Run Development Server

```bash
# Start Laravel development server (Artisan)
php artisan serve

# In another terminal, compile assets with Vite
npm run dev

# Application will be available at: http://localhost:8000
```

---

## 2. ASSET COMPILATION

### 2.1 Development Build
```bash
npm run dev
```
- Unminified assets
- Source maps for debugging
- Watch mode (auto-recompiles on file changes)
- Suitable for local development

### 2.2 Production Build
```bash
npm run build
```
- Minified CSS/JS
- Optimized bundle size
- No watch mode
- Required before deploying to production

### 2.3 Asset Pipeline

**CSS:**
- Input: `resources/css/app.css`
- Uses: Tailwind CSS with PostCSS
- Output: `public/build/assets/app-HASH.css`

**JavaScript:**
- Input: `resources/js/app.js`
- Uses: Vite ES module bundling
- Output: `public/build/assets/app-HASH.js`

---

## 3. TESTING THE APPLICATION

### 3.1 Manual Testing Checklist

**Homepage / Hero Section**
- [ ] Page loads without errors
- [ ] Hero section animations smooth (60 FPS)
- [ ] Text is readable on mobile (responsive)
- [ ] CTA buttons are clickable

**Skills Section**
- [ ] 7 skill cards display correctly
- [ ] Cards animate on scroll (Intersection Observer)
- [ ] Hover effects work on desktop
- [ ] Mobile layout stacks properly

**Projects Section**
- [ ] 2 project cards display with correct descriptions
- [ ] Gradient backgrounds render correctly
- [ ] Hover effects work (image scale, text change)
- [ ] Project metrics display properly
- [ ] Tech tags are visible and styled

**Contact Form**
- [ ] All form fields render
- [ ] Validation works (try submitting empty form)
- [ ] Success message appears after submission
- [ ] Error messages appear for invalid input
- [ ] Form disables during submission (loading state)

**Email Testing**
```bash
# Check Laravel logs for email contents
tail -f storage/logs/laravel.log

# Or use Mailtrap dashboard to view sent emails
```

### 3.2 Performance Testing

**Page Load Performance**
```bash
# Using Laravel built-in profiling
php artisan tinker
# Then run: \Laravel\Tinker\Shell::handle()

# Check Network tab in Chrome DevTools:
# - CSS file size: target < 50KB (gzipped)
# - JS file size: target < 100KB (gzipped)
# - Full page load: target < 2 seconds
```

**Animation Performance**
- Open Chrome DevTools → Performance tab
- Record page scroll through skills & projects
- Check for jank/dropped frames
- Should maintain consistent 60 FPS

**Database Queries**
```bash
# Enable query logging in .env
APP_DEBUG=true

# Check storage/logs/laravel.log for queries
# In production, disable with APP_DEBUG=false
```

### 3.3 Automated Testing (Optional)

```bash
# Run test suite
php artisan test

# Run specific test
php artisan test --filter=ContactFormTest

# Run with coverage report
php artisan test --coverage
```

### 3.4 Lighthouse Audit

Using Chrome DevTools Lighthouse:
1. Open DevTools → Lighthouse tab
2. Run audit for: Performance, Accessibility, Best Practices, SEO
3. Target scores: Performance > 90, Accessibility > 95, SEO > 100

---

## 4. PRODUCTION DEPLOYMENT

### 4.1 Pre-Deployment Checklist

```bash
# ✅ Install dependencies
composer install --optimize-autoloader --no-dev

# ✅ Build assets
npm run build

# ✅ Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# ✅ Run migrations
php artisan migrate --force

# ✅ Verify environment variables
cat .env | grep -E "APP_ENV|APP_DEBUG|DB_"
```

### 4.2 Deployment Options

#### Option A: Shared Hosting (cPanel / Plesk)

1. **Upload files via FTP**
   - Upload entire project to `public_html/`
   - Ensure `public/` folder is the web root

2. **Configure environment**
   ```
   Create .env file in project root with:
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://yourdomain.com
   ```

3. **Run migrations**
   ```
   SSH into server and run:
   php artisan migrate --force
   ```

4. **Set permissions**
   ```
   chmod -R 755 storage bootstrap/cache
   chmod -R 777 storage bootstrap/cache (if needed)
   ```

#### Option B: VPS with Nginx + PHP-FPM

```nginx
# /etc/nginx/sites-available/portfolio
server {
    listen 80;
    server_name portfolio.local;

    root /var/www/portfolio/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    # Gzip compression
    gzip on;
    gzip_types text/css application/javascript application/json;
}
```

#### Option C: Docker (Container Deployment)

```dockerfile
# Dockerfile
FROM php:8.2-fpm-alpine

WORKDIR /app

# Install dependencies
RUN apk add --no-cache \
    mysql-client \
    && docker-php-ext-install pdo_mysql

# Copy application
COPY . .

# Install PHP packages
RUN composer install --optimize-autoloader --no-dev

# Run migrations
RUN php artisan migrate --force

EXPOSE 9000

CMD ["php-fpm"]
```

#### Option D: Platform-as-a-Service (Heroku, Render, Railway)

**Heroku:**
```bash
# Install Heroku CLI
# heroku login
# heroku create your-app-name
# git push heroku main
```

**Render or Railway:**
- Connect GitHub repository
- Set environment variables in dashboard
- Auto-deploy on push to main branch

### 4.3 SSL Certificate

**Using Let's Encrypt (Free)**

```bash
# Install Certbot
sudo apt-get install certbot python3-certbot-nginx

# Generate certificate
sudo certbot certonly --nginx -d yourdomain.com

# Auto-renew
sudo certbot renew --quiet --no-eff-email

# Update nginx config to use SSL
```

**In `.env`, update:**
```env
APP_URL=https://yourdomain.com
```

### 4.4 Database Backup Strategy

**Automated MySQL Backups**

```bash
#!/bin/bash
# backup-database.sh
BACKUP_DIR="/backups/portfolio"
DATE=$(date +%Y-%m-%d_%H:%M:%S)

mysqldump -u root -p$DB_PASSWORD portfolio > $BACKUP_DIR/portfolio_$DATE.sql
gzip $BACKUP_DIR/portfolio_$DATE.sql

# Keep only last 30 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +30 -delete
```

**Schedule with cron:**
```bash
0 2 * * * /path/to/backup-database.sh
```

### 4.5 Email Configuration in Production

**Production Mail Provider Setup:**

**Option 1: Mailgun**
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.yourdomain.com
MAILGUN_SECRET=your-mailgun-api-key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

**Option 2: SendGrid**
```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your-sendgrid-api-key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

**Option 3: Amazon SES**
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-aws-key
AWS_SECRET_ACCESS_KEY=your-aws-secret
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

---

## 5. MONITORING & MAINTENANCE

### 5.1 Error Tracking

**Using Sentry (Free tier available)**

1. **Sign up:** https://sentry.io
2. **Create Laravel project**
3. **Install Sentry package:**
```bash
composer require sentry/sentry-laravel
php artisan sentry:publish --dsn=your-sentry-dsn
```

4. **Update `.env`:**
```env
SENTRY_LARAVEL_DSN=https://your-sentry-dsn@sentry.io/123456
```

### 5.2 Logging Best Practices

```php
// In your controller:
Log::info('User submitted contact form', [
    'email' => $request->email,
    'ip' => $request->ip()
]);

Log::error('Mail sending failed', [
    'exception' => $e->getMessage()
]);
```

**Monitor logs:**
```bash
tail -f storage/logs/laravel.log
```

### 5.3 Security Hardening

**Update dependencies regularly:**
```bash
# Check for security vulnerabilities
composer audit

# Update dependencies
composer update

# Update Node packages
npm audit fix
npm update
```

**Security headers in nginx:**
```nginx
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "geolocation=(), microphone=(), camera=()" always;
```

### 5.4 Performance Optimization

**Enable opcache (PHP):**
```ini
# In php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

**Enable Redis caching (Optional):**
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

**CDN Setup (CloudFlare, AWS CloudFront):**
```env
# In your blade template
<link rel="stylesheet" href="https://cdn.yourdomain.com/build/assets/app.css">
<script src="https://cdn.yourdomain.com/build/assets/app.js"></script>
```

---

## 6. TROUBLESHOOTING

### 6.1 Common Issues

**Issue: `php artisan migrate` fails**
```bash
# Check database connection
php artisan tinker
DB::connection()->getPdo();

# If fails, verify .env DB credentials
cat .env | grep DB_
```

**Issue: Assets not loading (404 errors)**
```bash
# Rebuild assets
npm run build

# Clear cache
php artisan cache:clear
php artisan view:clear
```

**Issue: Emails not sending**
```bash
# Check mail driver in .env
echo $MAIL_MAILER  # should be smtp or mailgun

# Test mail sending
php artisan tinker
Mail::raw('Test email', function($msg) { $msg->to('test@example.com'); });

# Check logs
tail -f storage/logs/laravel.log
```

**Issue: Slow page load**
```bash
# Check if APP_DEBUG is off in production
grep APP_DEBUG .env

# Profile queries
php artisan query:builder
```

### 6.2 Debug Mode

**Enable debug for local:**
```env
APP_ENV=local
APP_DEBUG=true
```

**Disable debug for production (CRITICAL):**
```env
APP_ENV=production
APP_DEBUG=false
```

When `APP_DEBUG=true`, Laravel shows detailed error pages (reveals code structure).

---

## 7. QUICK COMMANDS REFERENCE

```bash
# Development
php artisan serve                    # Start dev server
npm run dev                         # Watch & compile assets

# Database
php artisan migrate                 # Run migrations
php artisan migrate:fresh           # Reset & migrate
php artisan db:seed                 # Run seeders

# Caching
php artisan cache:clear             # Clear cache
php artisan route:cache             # Cache routes (production)
php artisan config:cache            # Cache config (production)

# Maintenance
php artisan down                     # Put app in maintenance mode
php artisan up                       # Bring app back up

# Deployment
composer install --no-dev           # Install prod dependencies
npm run build                        # Build production assets
php artisan optimize                # Optimize autoloader

# Testing
php artisan test                     # Run tests
php artisan tinker                   # Interactive shell
```

---

## 8. HELPFUL RESOURCES

- **Laravel Documentation:** https://laravel.com/docs
- **Tailwind CSS:** https://tailwindcss.com
- **Vite Guide:** https://vitejs.dev
- **Alpine.js:** https://alpinejs.dev
- **MySQL Best Practices:** https://dev.mysql.com
- **Server Deployment:** https://laravel.com/docs/deployment

---

**Last Updated:** 2024
**Version:** 1.0
