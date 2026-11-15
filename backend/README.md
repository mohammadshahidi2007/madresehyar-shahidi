# Madresehyar Shahidi API

Laravel backend bootstrap for the school platform.

## Requirements
- PHP 8.3+
- Composer 2
- PostgreSQL-compatible database (Neon Free is the current evaluation candidate)

## Local setup
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan serve
```

Set local database credentials in `.env`. Never commit that file.

## Endpoints
- `GET /up` — framework health
- `GET /api/health` — API health

## Tests
```bash
composer test
```
Feature tests use in-memory SQLite and synthetic data.

## Free-tier constraints
The intended prototype deployment is a free web-service tier plus Neon Free PostgreSQL.
Free infrastructure is for development and demonstration only: cold starts, quotas, pauses,
and limited backup/recovery guarantees mean it is not approved for real student records.
Choose hosting region only after confirming the school's jurisdiction.
