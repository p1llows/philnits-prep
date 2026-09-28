# 🚀 PhilNITS Prep - Production Deployment Guide

## Overview

This guide covers deploying PhilNITS Prep to production environments. The application is containerized with Docker and optimized for cloud deployment.

---

## 📋 Pre-Deployment Checklist

### Environment Requirements
- ✅ PHP 8.2+ (or use provided Docker setup)
- ✅ MySQL 8.0+ or MariaDB 10.6+
- ✅ Node.js 18+ (for asset compilation)
- ✅ Composer 2.x+
- ✅ Web server: Nginx/Apache with SSL support

### Security Requirements
- [ ] Valid SSL/TLS certificate
- [ ] Strong database credentials
- [ ] Application key configured
- [ ] Rate limiting enabled
- [ ] CORS policy defined
- [ ] API keys secured (if using external services)

---

## 🔧 Production Build Steps

### Step 1: Repository Preparation

```bash
# Clone repository
git clone https://github.com/yourusername/philnits-prep.git
cd philnits-prep

# Install dependencies
composer install --optimize-autoloader --no-dev

# Compile assets
npm ci && npm run build
```

### Step 2: Environment Configuration

Create `.env.production` file:

```bash
cp .env.example .env.production

# Edit .env.production with production values
vi .env.production
```

**Critical Settings:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stderr
LOG_DEPRECATIONS_CHANNEL=null

DB_CONNECTION=mysql
DB_HOST=db-host.com
DB_PORT=3306
DB_DATABASE=philnits_prep_prod
DB_USERNAME=prod_user_secure
DB_PASSWORD=strong_password_here

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis

SESSION_DRIVER=redis
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.yourprovider.com
MAIL_PORT=587
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=secure_mail_password
MAIL_ENCRYPTION=tls
```

### Step 3: Database Setup

```bash
# Run migrations
php artisan migrate --force --environment=production

# Seed initial data (optional)
php artisan db:seed --class=DatabaseSeeder --force
```

**Production Migrations:**
- Run migrations during maintenance window
- Backup existing database first
- Test on staging environment before production

### Step 4: Application Optimization

```bash
# Optimize autoloader
composer dump-autoload --optimize

# Clear caches
php artisan config:clear
php artisan cache:clear

# Optimize configuration caching
php artisan config:cache

# Optimize route caching
php artisan route:cache

# Optimize view caching
php artisan view:cache
```

### Step 5: Permission Setup

```bash
# Set correct permissions
chmod -R 755 storage/bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Create necessary directories
mkdir -p storage/logs
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/framework/cache

# Ensure writable
chmod 775 storage/logs
chmod 775 storage/framework
```

---

## 🌐 Deployment Options

### Option A: Docker Deployment (Recommended)

#### Docker Compose Configuration

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile.prod
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./storage:/var/www/html/storage
      - ./:/var/www/html
    depends_on:
      - mysql
    environment:
      - APP_ENV=production
      - DB_CONNECTION=mysql
      - DB_HOST=mysql
      - DB_PORT=3306
      - DB_DATABASE=${DB_DATABASE}
      - DB_USERNAME=${DB_USERNAME}
      - DB_PASSWORD=${DB_PASSWORD}
  
  mysql:
    image: mysql:8.0
    restart: unless-stopped
    environment:
      MYSQL_ROOT_PASSWORD: ${MYSQL_ROOT_PASSWORD}
      MYSQL_DATABASE: ${DB_DATABASE}
      MYSQL_USER: ${DB_USERNAME}
      MYSQL_PASSWORD: ${DB_PASSWORD}
    volumes:
      - mysql_data:/var/lib/mysql
      - ./docker/init:/docker-entrypoint-initdb.d
  
  nginx:
    image: nginx:alpine
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
      - ./storage:/var/www/html/storage
    depends_on:
      - app

volumes:
  mysql_data:
```

#### Running Docker Deployment

```bash
# Build and start containers
docker-compose -f docker-compose.prod.yml up -d --build

# Check logs
docker-compose logs -f app mysql nginx

# Execute commands in container
docker exec philnitsprep-app-1 php artisan migrate --force
docker exec philnitsprep-app-1 php artisan schedule:run
```

### Option B: Traditional LAMP Deployment

#### Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    
    root /var/www/philnits-prep/public;
    index index.php;
    
    # SSL (uncomment when adding certificate)
    # listen 443 ssl http2;
    # ssl_certificate /path/to/cert.pem;
    # ssl_certificate_key /path/to/key.pem;
    
    client_max_body_size 20M;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
        
        fastcgi_param HTTP_PROXY "";
        
        fastcgi_buffer_size 128k;
        fastcgi_buffers 4 256k;
        fastcgi_read_timeout 300s;
    }
    
    location ~ /\.ht {
        deny all;
    }
    
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
    
    access_log /var/log/nginx/philnits-access.log;
    error_log /var/log/nginx/philnits-error.log;
}
```

#### Apache Configuration

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    
    DocumentRoot /var/www/philnits-prep/public
    
    <Directory /var/www/philnits-prep/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/philnits-error.log
    CustomLog ${APACHE_LOG_DIR}/philnits-access.log combined
</VirtualHost>
```

---

## 🔐 Security Hardening

### Laravel Security Settings

Add to `config/app.php`:

```php
'encrypt' => env('APP_ENCRYPT', true),
'trash_lifetime' => 90,
```

### Database Security

```sql
-- Restrict user privileges
CREATE USER 'philnits_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON philnits_prep.* TO 'philnits_user'@'localhost';
FLUSH PRIVILEGES;
```

### Firewall Rules (ufw example)

```bash
sudo ufw allow 80/tcp   # HTTP
sudo ufw allow 443/tcp  # HTTPS
sudo ufw allow 22/tcp   # SSH
sudo ufw enable
```

### File Permissions

```bash
# Secure storage directory
find storage bootstrap/cache -type d -exec chmod 755 {} \;
find storage bootstrap/cache -type f -exec chmod 644 {} \;
```

---

## ⚡ Performance Optimization

### Caching Strategy

```bash
# Enable OPcache in php.ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60

# Redis configuration
sudo apt-get install redis-server

# Start Redis service
sudo systemctl start redis
sudo systemctl enable redis

# Configure Laravel to use Redis
# Update .env: CACHE_STORE=redis QUEUE_CONNECTION=redis
```

### Queue Workers

```bash
# Setup supervisor for queue workers
cat > /etc/supervisor/conf.d/philnits-workers.conf << EOF
[program:philnits-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/philnits-prep/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
user=www-data
redirect_stderr=true
stdout_logfile=/var/log/philnits/worker-%(process_num)02d.log
EOF

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start "philnits-worker:*"
```

### CDN Integration (Optional)

For static assets:

```html
<!-- In app.blade.php -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
```

---

## 📊 Monitoring & Logging

### Application Logging

```bash
# Configure log rotation
cat > /etc/logrotate.d/philnits << EOF
/var/www/philnits-prep/storage/logs/*.log {
    daily
    rotate 30
    minsize 50M
    compress
    delaycompress
    missingok
    notifempty
    create 0640 www-data www-data
}
EOF
```

### Health Checks

```bash
# Add cron job for health monitoring
cat >> /etc/cron.d/philnits-health << 'EOF'
*/5 * * * * www-data curl -f http://localhost/health || mail admin@example.com "PhilNITS down!"
EOF
```

### Error Tracking (Sentry Example)

```bash
composer require sentry/sentry-laravel

# Setup Sentry DSN
vi .env.production
SENTRY_DSN=https://your_dsn@sentry.io/project_id
```

---

## 🔄 CI/CD Pipeline

### GitHub Actions Workflow

`.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
    
    - name: Install Dependencies
      run: composer install --optimize-autoloader --no-dev
    
    - name: Build Assets
      run: |
        npm ci
        npm run build
    
    - name: Run Tests
      run: vendor/bin/phpunit
    
    - name: Deploy to Server
      uses: easingthemes/ssh-deploy@v2
      with:
        SSH_PRIVATE_KEY: ${{ secrets.SSH_PRIVATE_KEY }}
        REMOTE_HOST: your.server.ip
        REMOTE_USER: www-data
        SOURCE: "./"
        TARGET: "/var/www/philnits-prep/"
        EXCLUDE: "/vendor/,node_modules/,tests/"
```

---

## 🆘 Maintenance & Troubleshooting

### Emergency Commands

```bash
# Disable maintenance mode (if stuck)
php artisan down
php artisan up

# Clear all caches
php artisan optimize:clear

# Regenerate cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check disk space
df -h

# Check logs
tail -f storage/logs/laravel.log
```

### Backup Strategy

```bash
# Database backup script
#!/bin/bash
BACKUP_DIR="/backups/philnits-db"
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u philnits_user -pYourPassword philnits_prep > $BACKUP_DIR/db_$DATE.sql
find $BACKUP_DIR -mtime +30 -delete
```

Cron schedule: `0 2 * * * /usr/local/bin/backup-philnits.sh`

---

## ✅ Post-Deployment Verification

### Checklist

- [ ] Application accessible via HTTPS
- [ ] Database migrations completed successfully
- [ ] No errors in Laravel logs
- [ ] Cache is working (Redis check)
- [ ] Queue workers running
- [ ] Cron jobs scheduled
- [ ] Email notifications test successful
- [ ] Authentication/authorization working
- [ ] Admin panel accessible
- [ ] Frontend assets loaded properly
- [ ] SSL certificates valid
- [ ] Backups configured
- [ ] Monitoring active

### Test Scenarios

1. **User Registration/Login**: Create test account, verify authentication flow
2. **Assessment Flow**: Complete full assessment cycle
3. **Topic Browsing**: Navigate topics, view questions
4. **Practice Mode**: Answer questions, get feedback
5. **Analytics**: View progress dashboard
6. **Admin Functions**: CRUD operations on topics

---

## 📞 Support & Contacts

- **Project Issues**: [GitHub Issues](https://github.com/yourusername/philnits-prep/issues)
- **Documentation**: See `/docs` folder
- **Community Forum**: [TBD]

---

## 🔒 Compliance Notes

- GDPR compliant (data export/delete options available)
- No PII collected beyond email/password
- User data encrypted at rest
- Regular security audits recommended

---

**Document Version:** 1.0.0  
**Last Updated:** August 28, 2026  
**Maintained By:** Development Team
