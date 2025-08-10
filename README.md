# MedGemma Healthcare Platform

## Overview
A secure, production-ready healthcare management platform with MedGemma AI integration, built with Laravel and Bootstrap.

## Features
- Secure authentication (Sanctum, RBAC)
- Patient management (add, edit, view)
- MedGemma AI analysis (imaging, labs, second opinion)
- Reports dashboard
- User notifications (success, error, info)
- Audit logging for compliance
- Responsive, accessible UI (Bootstrap)
- Onboarding & help page

## Deployment Checklist
1. **Clone the repository** to your VPS.
2. **Install dependencies:**
   - `composer install --optimize-autoloader --no-dev`
   - `npm install && npm run build` (if using JS assets)
3. **Configure environment:**
   - Copy `.env.example` to `.env`
   - Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, database, Redis, and MedGemma API credentials
   - Run `php artisan key:generate`
4. **Database:**
   - `php artisan migrate --force`
   - `php artisan db:seed --force`
5. **Storage & Permissions:**
   - `php artisan storage:link`
   - Ensure `storage` and `bootstrap/cache` are writable
6. **Queue & Scheduler:**
   - Set up Supervisor for `php artisan queue:work`
   - Add cron for `php artisan schedule:run`
7. **SSL & Security:**
   - Enable HTTPS
   - Use strong passwords and keep system updated
8. **Backups & Monitoring:**
   - Configure spatie/laravel-backup
   - Integrate Sentry/New Relic if desired

## User Notifications
- Success, error, and info messages appear at the top of each page after actions.
- Example controller usage:
  ```php
  return redirect()->back()->with('success', 'Action completed!');
  return redirect()->back()->with('error', 'Something went wrong.');
  return redirect()->back()->with('info', 'This is an informational message.');
  ```

## Accessibility & Best Practices
- All forms and modals are keyboard accessible
- ARIA labels and roles are used where appropriate
- Responsive design for all devices

## Support
- See the Help page in the app
- Email: support@medgemma.com

