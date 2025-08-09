# Production Deployment Guide

## Overview
This guide covers deploying the FHIR-DICOM-With-Med-Gemma application to production.

## Prerequisites
- PHP 8.2 or higher
- Composer
- Web server (Apache/Nginx)
- Database (MySQL/PostgreSQL/SQLite)

## Production Setup

### 1. Environment Configuration

Copy the environment file and configure for production:
```bash
cp .env.example .env
```

Essential production settings in `.env`:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database (example for MySQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

# Security
ADMIN_BASIC_USER=your_admin_username
ADMIN_BASIC_PASSWORD=your_secure_password

# MedGemma Integration (optional)
MEDGEMMA_ENABLED=true
MEDGEMMA_ENDPOINT=https://your-medgemma-api.com
MEDGEMMA_API_KEY=your_api_key
MEDGEMMA_MODEL=medgemma

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 2. Installation Steps

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Generate application key
php artisan key:generate

# Create database tables
php artisan migrate --force

# Seed initial data (optional for production)
php artisan db:seed

# Cache configuration for performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 3. Web Server Configuration

#### Nginx Configuration
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    root /var/www/html/backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### Apache Configuration (.htaccess)
The application includes a `.htaccess` file in `public/` directory for Apache.

### 4. Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong, unique passwords for admin accounts
- [ ] Configure HTTPS/SSL certificates
- [ ] Set proper file permissions (755 for directories, 644 for files)
- [ ] Regularly update dependencies
- [ ] Monitor logs for security issues
- [ ] Configure firewall rules
- [ ] Use database credentials with minimal required permissions

### 5. Monitoring & Maintenance

#### Log Monitoring
```bash
# Monitor application logs
tail -f storage/logs/laravel.log

# Check web server logs
tail -f /var/log/nginx/error.log
```

#### Database Backups
```bash
# Example MySQL backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
```

#### Queue Processing (if using queues)
```bash
# Start queue worker
php artisan queue:work --daemon
```

### 6. SSL/HTTPS Setup

For production, always use HTTPS. You can use Let's Encrypt for free SSL certificates:

```bash
# Install certbot
sudo apt install certbot python3-certbot-nginx

# Get SSL certificate
sudo certbot --nginx -d yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### 7. Performance Optimization

```bash
# Enable OPcache in php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0

# Cache Laravel configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8. Troubleshooting

Common issues and solutions:

1. **Permission errors**: Ensure storage and bootstrap/cache are writable
2. **500 errors**: Check Laravel logs in `storage/logs/`
3. **Database connection**: Verify database credentials in `.env`
4. **API endpoints not working**: Clear route cache with `php artisan route:clear`

### 9. API Documentation

The application provides these API endpoints:

#### Public Endpoints
- `GET /api/integrations/medgemma` - MedGemma integration status
- `GET /api/reports/patients` - List all patients
- `GET /api/reports/patients/{id}` - Get patient details

#### MedGemma Endpoints
- `POST /api/medgemma/analyze/imaging/{study_id}` - Analyze imaging study
- `POST /api/medgemma/analyze/labs/{patient_id}` - Analyze lab results
- `POST /api/medgemma/second-opinion/{patient_id}` - Combined second opinion

### 10. Backup Strategy

Implement regular backups:
- Database backups (daily)
- File system backups (weekly)
- Configuration backups
- Test restore procedures regularly

## Support

For issues, check:
1. Application logs: `storage/logs/laravel.log`
2. Web server logs
3. PHP error logs
4. Database logs

## Security Considerations

This is a demo application. For production use with real medical data:
- Implement proper authentication and authorization
- Add HIPAA/GDPR compliance measures
- Use encrypted database connections
- Implement audit logging
- Add rate limiting
- Use proper session management
- Implement data encryption at rest