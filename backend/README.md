# Schoolyar Backend — Phase 1

Laravel 12 / PHP 8.2+ foundation for the Schoolyar identity and access-control phase.

## Local setup

1. Install PHP 8.2+, Composer and PostgreSQL.
2. Copy .env.example to .env; configure a dedicated database and credentials.
3. From this directory run:

   ```bash
   composer install
   php artisan key:generate
   php artisan migrate --seed
   php artisan schoolyar:make-admin admin "School Administrator"
   php artisan serve
   ```

The initial admin password is entered interactively. Never commit credentials or real student data. Set SESSION_SECURE_COOKIE=true in staging/production and use HTTPS.

## Browser session / CSRF

Routes are intentionally defined in routes/web.php and use Laravel's session and CSRF middleware. First request GET /api/v1/csrf-token to establish the session and obtain the CSRF token; send it in X-CSRF-TOKEN on state-changing requests. Keep frontend and API same-site where possible.

## Endpoints

- POST /api/v1/auth/login
- POST /api/v1/auth/logout
- POST /api/v1/auth/password
- GET /api/v1/me
- GET /api/v1/admin/users
- POST /api/v1/admin/users
- PATCH /api/v1/admin/users/{user}
- POST /api/v1/admin/users/{user}/reset-password

Only administrators create teacher/student accounts. Account creation and reset return a one-time temporary password. Display it only to the authorized administrator and never log it. The user must change it before accessing other authenticated endpoints.

## Tests

Run composer test. Automated tests use SQLite in-memory; perform migration and integration checks against PostgreSQL before deployment.

This is an implementation scaffold, not a production-ready release. Complete security review, audit logging, deployment hardening and restore testing before real school data is used.
