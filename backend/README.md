# Schoolyar Backend — Phase 1

Laravel 12 / PHP 8.2+ foundation. PostgreSQL is the intended development and production database.

## Local setup

1. Install PHP 8.2+, Composer and PostgreSQL.
2. Copy .env.example to .env and set a dedicated database and credentials.
3. Run from this directory:

   ```bash
   composer install
   php artisan key:generate
   php artisan migrate --seed
   ```

4. Create the initial administrator through a secure, interactive bootstrap process before enabling access. Do not commit credentials or real student data.

## Session security

Endpoints are intentionally defined in routes/web.php and use Laravel's web middleware (session and CSRF protection). Browser clients must follow Laravel's CSRF requirements. Use HTTPS and SESSION_SECURE_COOKIE=true in staging/production; keep frontend and API same-site where possible.

## Endpoints

- POST /api/v1/auth/login
- POST /api/v1/auth/logout
- POST /api/v1/auth/password
- GET /api/v1/me
- GET /api/v1/admin/users
- POST /api/v1/admin/users
- PATCH /api/v1/admin/users/{user}
- POST /api/v1/admin/users/{user}/reset-password

This is an initial scaffold, not a production-ready release. Complete profile validation, initial-admin bootstrap command, full authorization tests, audit logging, and deployment hardening before real school data is used.
