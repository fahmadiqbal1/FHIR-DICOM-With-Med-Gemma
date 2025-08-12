# FHIR-DICOM-With-Med-Gemma Healthcare Platform

## Overview
A comprehensive, production-ready healthcare management platform integrating FHIR, DICOM, and MedGemma AI capabilities. Built with Laravel 12 and modern web technologies, this platform provides secure patient management, medical imaging analysis, and AI-powered clinical insights.

## Features

### Core Healthcare Functionality
- **FHIR Integration**: Standards-compliant healthcare data exchange
- **DICOM Support**: Medical imaging processing and storage
- **Patient Management**: Complete patient records and clinical data
- **Medical Imaging**: DICOM upload and analysis capabilities
- **Laboratory Results**: Lab data management and analysis
- **Clinical Notes**: Structured documentation system

### AI-Powered Analytics
- **MedGemma Integration**: Advanced AI model for medical analysis
- **Imaging Analysis**: AI-powered diagnostic assistance for medical images
- **Lab Analysis**: Intelligent interpretation of laboratory results
- **Second Opinion**: Combined AI analysis for comprehensive clinical insights
- **Predictive Analytics**: Risk assessment and outcome prediction

### Security & Compliance
- **Laravel Sanctum**: API authentication and authorization
- **Role-Based Access Control (RBAC)**: Admin, clinician, and user roles
- **Audit Logging**: Comprehensive activity tracking for compliance
- **Data Encryption**: Secure storage of sensitive medical data
- **HIPAA-Ready**: Built with healthcare privacy standards in mind

### User Interface
- **Professional Dashboard**: Clean, accessible healthcare interface
- **Responsive Design**: Works on all devices and screen sizes
- **Real-time Updates**: Live patient data and analysis updates
- **Accessibility**: WCAG-compliant interface design
- **Dark/Light Mode**: User preference support

## Technology Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: TailwindCSS, Alpine.js, Vite
- **Database**: SQLite (development) / MySQL/PostgreSQL (production)
- **Authentication**: Laravel Sanctum
- **Permissions**: Spatie Laravel Permission
- **Backup**: Spatie Laravel Backup
- **AI Integration**: MedGemma API

## Quick Start

### Prerequisites
- PHP 8.2 or higher
- Node.js 18+ and npm
- Composer
- SQLite (for development) or MySQL/PostgreSQL (for production)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/fahmadiqbal1/FHIR-DICOM-With-Med-Gemma.git
   cd FHIR-DICOM-With-Med-Gemma/backend
   ```

2. **Install dependencies**
   ```bash
   composer install --optimize-autoloader
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Set up database**
   ```bash
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000/app` to access the dashboard.

## Production Deployment

### Environment Configuration
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medgemma_prod
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# MedGemma Integration
MEDGEMMA_ENABLED=true
MEDGEMMA_ENDPOINT=https://api.medgemma.com
MEDGEMMA_API_KEY=your_api_key
MEDGEMMA_MODEL=medgemma

# Admin Panel Access
ADMIN_BASIC_USER=admin
ADMIN_BASIC_PASSWORD=your_secure_admin_password
```

### Deployment Steps
1. **Install production dependencies**
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   ```

2. **Configure database**
   ```bash
   php artisan migrate --force
   php artisan db:seed --class=RolesAndAdminSeeder --force
   ```

3. **Set up file permissions**
   ```bash
   php artisan storage:link
   chmod -R 755 storage bootstrap/cache
   ```

4. **Configure web server** (Apache/Nginx)
   - Point document root to `backend/public`
   - Enable HTTPS with SSL certificate
   - Configure URL rewriting for Laravel

5. **Set up background processes**
   ```bash
   # Add to crontab
   * * * * * cd /path/to/backend && php artisan schedule:run >> /dev/null 2>&1
   
   # Configure Supervisor for queues
   php artisan queue:work --daemon
   ```

## API Endpoints

### Authentication
- `POST /api/auth/login` - User authentication
- `POST /api/auth/logout` - User logout

### Patient Management
- `GET /reports/patients` - List all patients
- `GET /reports/patients/{id}` - Get patient details

### MedGemma AI Analysis
- `POST /medgemma/analyze/imaging/{study}` - Analyze medical imaging
- `POST /medgemma/analyze/labs/{patient}` - Analyze lab results
- `POST /medgemma/second-opinion/{patient}` - Get AI second opinion

### Admin Panel
- `GET /admin/users` - User management (Basic Auth required)
- `POST /admin/users` - Create new user

## Database Schema

### Core Tables
- `users` - System users with roles
- `patients` - Patient demographic and clinical data
- `imaging_studies` - DICOM imaging studies
- `lab_results` - Laboratory test results
- `prescriptions` - Medication prescriptions
- `clinical_notes` - Provider documentation
- `audit_logs` - System activity logging

### Permission System
- `roles` - User roles (admin, clinician, user)
- `permissions` - Granular permissions
- `role_has_permissions` - Role-permission mapping
- `model_has_roles` - User-role assignments

## Security Features

### Authentication & Authorization
- Sanctum-based API authentication
- Role-based access control
- Password hashing with bcrypt
- Session management

### Data Protection
- Encrypted sensitive fields
- HTTPS enforcement
- CSRF protection
- SQL injection prevention
- XSS protection

### Audit & Compliance
- Comprehensive audit logging
- User activity tracking
- Data access monitoring
- HIPAA-compliant data handling

## Configuration Options

### MedGemma Integration
```php
// config/services.php
'medgemma' => [
    'enabled' => env('MEDGEMMA_ENABLED', false),
    'endpoint' => env('MEDGEMMA_ENDPOINT'),
    'api_key' => env('MEDGEMMA_API_KEY'),
    'model' => env('MEDGEMMA_MODEL', 'medgemma'),
]
```

### Admin Panel Access
The admin panel at `/admin/users` uses HTTP Basic Authentication:
- Username: Set in `ADMIN_BASIC_USER` environment variable
- Password: Set in `ADMIN_BASIC_PASSWORD` environment variable

## Development

### Running Tests
```bash
vendor/bin/phpunit
```

### Code Style
```bash
vendor/bin/pint
```

### Development Server
```bash
# With queue processing and asset watching
composer run dev
```

## Troubleshooting

### Common Issues

1. **Permission Errors**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

2. **Database Issues**
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Asset Compilation Issues**
   ```bash
   npm run build
   php artisan config:clear
   php artisan view:clear
   ```

4. **MedGemma Integration Issues**
   - Check `MEDGEMMA_ENABLED=true` in `.env`
   - Verify API credentials
   - Check network connectivity to MedGemma API

## Support & Documentation

### Default Login Credentials
- **Admin User**: admin@example.com
- **Admin Panel**: `/admin/users` (Basic Auth with env credentials)

### Key Features Access
- **Dashboard**: `/app` - Main healthcare interface
- **Patient List**: Accessible from dashboard sidebar
- **AI Analysis**: Available in patient details panel
- **Admin Functions**: `/admin/users` with basic auth

### Technical Support
- **Repository**: https://github.com/fahmadiqbal1/FHIR-DICOM-With-Med-Gemma
- **Issues**: Submit via GitHub Issues
- **Documentation**: See `/docs` directory for additional guides

## License
This project is licensed under the MIT License. See LICENSE file for details.

## Contributing
Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.

