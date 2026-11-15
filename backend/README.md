# Madresehyar Shahidi API

Laravel backend bootstrap for the school platform.

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

## Endpoints
- `GET /up` — framework health
- `GET /api/health` — API health

## Tests
```bash
composer test
```
Feature tests must use synthetic data and an in-memory SQLite database.

## Iran hosting decision
The school is in Iran. Do not deploy this project to Render: its Terms of Service explicitly exclude people located in or resident in Iran. Do not use a foreign provider unless its current terms and legal eligibility have been verified for this exact use.

No verified permanently-free Iranian managed Laravel + PostgreSQL hosting tier has been selected. Liara currently advertises a 500,000 toman introductory credit and paid hourly resources; this is a trial credit, not permanent free hosting. The genuinely no-cloud-cost development option is self-hosting on school-owned hardware, but it requires a continuously powered machine, stable internet, secure network configuration, and independent backups.

For now, keep development local/CI-only with synthetic data. Do not connect real student records or publish a live API until a compliant hosting option, backup plan, and operational owner are confirmed.
