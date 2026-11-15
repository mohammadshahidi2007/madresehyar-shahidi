# Madresehyar Shahidi API

Laravel backend for the school platform. This branch is a development scaffold, not a production service.

## Requirements
- PHP 8.3+
- Composer 2
- PostgreSQL 16+ for shared development and production

## Local setup
```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan serve
```

Set local database credentials in `.env`. Never commit that file.

## Current endpoints
- `GET /up` — framework health
- `GET /api/health` — API health

No student, classroom, or academic-year write endpoints are exposed yet. The current FormRequest classes intentionally deny authorization until role-based policies are implemented.

## Tests
```bash
composer test
vendor/bin/phpunit -c phpunit.pgsql.xml
```

GitHub Actions runs feature tests against in-memory SQLite and PostgreSQL 16. Tests must use synthetic data only.

## School schema and validation

The initial migration creates academic years, classrooms, students, subjects, and classroom enrollments. Domain request validation currently covers:
- academic year date ordering and unique name;
- grades 7, 8, and 9 only;
- Persian-compatible class section names and uniqueness within academic year + grade;
- unique internal student codes;
- enrollment status and date ordering.

Write authorization remains deny-by-default. Validation is not a substitute for authentication or authorization.

The initial student record intentionally excludes national ID, address, phone, birth date, and health information.

## Iran hosting decision

The school is in Iran. Do not deploy this project to Render: its current Terms of Service exclude people located in or resident in Iran. Do not bypass provider restrictions.

No permanently-free Iranian managed Laravel + PostgreSQL hosting tier has been verified. Liara's introductory credit is a trial credit, not permanent free hosting. No cloud account, paid resource, database, or deployment has been created.

For now, keep development/CI synthetic-data-only. Do not connect real student records or publish a live API until eligible hosting, backup/recovery, operational ownership, HTTPS, and security review are established.
