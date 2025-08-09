# FHIR-DICOM-With-Med-Gemma

A Laravel-based FHIR-ish demo backend with DICOM imaging models and a lightweight MedGemma AI integration. This repo now includes a simple front-end dashboard you can deploy immediately.

Front end URL (after starting the app):
- http://localhost:8000/app

## What’s included
- MedGemma integration endpoints (stubbed analysis service for demo):
  - GET /integrations/medgemma – check integration status
  - POST /medgemma/analyze/imaging/{study}
  - POST /medgemma/analyze/labs/{patient}
  - POST /medgemma/second-opinion/{patient}
- Admin panel (Basic Auth) to create users: GET/POST /admin/users
- Demo data seeders (patients, imaging studies, labs, meds, notes)
- Simple dashboard (Blade + vanilla JS) at /app to:
  - View MedGemma integration status
  - List patients and inspect details
  - Trigger MedGemma analysis actions

## Quick start (local)
Requirements: PHP 8.2+, Composer, SQLite extension enabled.

1. Clone and install dependencies
   ```bash
   cd backend
   composer install
   ```
2. Configure environment
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Create SQLite database file
   ```bash
   mkdir -p database
   touch database/database.sqlite
   ```
4. Migrate and seed demo data
   ```bash
   php artisan migrate --seed
   ```
5. Run the app
   ```bash
   php artisan serve
   ```
6. Open the dashboard
   - http://127.0.0.1:8000/app
   - Admin users panel: http://127.0.0.1:8000/admin/users (Basic Auth)

## Admin Basic Auth
Defaults are included in backend/.env.example:
```
ADMIN_BASIC_USER=admin
ADMIN_BASIC_PASSWORD=password
```
Set your own in .env for production.

## MedGemma configuration
Environment variables (backend/.env):
```
MEDGEMMA_ENABLED=false
MEDGEMMA_ENDPOINT=
MEDGEMMA_API_KEY=
MEDGEMMA_MODEL=medgemma
```
The included MedGemmaService is a safe, offline demo (no external calls). The status endpoint (/integrations/medgemma) reflects your env config.

## Deployment
- Point your web server document root to backend/public.
- Copy backend/.env.example to backend/.env and set:
  - APP_ENV=production
  - APP_DEBUG=false
  - Database settings (e.g., MySQL or SQLite) as needed
  - ADMIN_BASIC_USER / ADMIN_BASIC_PASSWORD
  - MedGemma envs if desired
- Run from backend directory:
  ```bash
  php artisan key:generate
  php artisan migrate --seed
  ```
- Ensure storage and bootstrap cache directories are writable by the web server user.

## API overview
- Dashboard (UI): GET /app
- Patients (JSON):
  - GET /reports/patients
  - GET /reports/patients/{patient}
- MedGemma:
  - GET /integrations/medgemma
  - POST /medgemma/analyze/imaging/{study}
  - POST /medgemma/analyze/labs/{patient}
  - POST /medgemma/second-opinion/{patient}
- Admin users (Basic Auth): GET/POST /admin/users

## Notes
- SecureStorageService is provided for encrypted file storage; if you wire it up, configure a 32-byte base64 key as documented in code comments.
- This is a demo-oriented setup; adjust roles, permissions, and data model per your production needs.