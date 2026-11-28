# FUTURiSE Backend — Laravel 13 API, Portal & Filament 5 Admin

Direktori `backend/` berisi REST API `/api/v1/*` untuk frontend Next.js, portal web peserta/non-admin (`/login`, `/register`, `/dashboard`), serta panel administrasi panitia berbasis **Filament 5** (`/admin`).

## Stack Aktual

- **PHP:** `^8.3`
- **Framework:** Laravel `^13.17`
- **Admin Panel:** Filament `^5.11` (dengan 2FA App/Email MFA)
- **Auth & RBAC:** Laravel Sanctum `^4.0`, Fortify `^1.41`, Spatie Laravel Permission `^8.4`
- **Testing:** PHPUnit `^12.5` (SQLite `:memory:`)
- **Database Produksi / Lokal:** MySQL `8.4.3`

## Instalasi & Setup Lokal

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
```

Konfigurasi `.env` lokal Anda:
- Isi koneksi database (`DB_CONNECTION=mysql`, `DB_DATABASE=futurise`, dll.) atau gunakan SQLite untuk uji cepat.
- Pastikan `FRONTEND_URL=http://localhost:3000` dan `CORS_ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:3000`.
- Jalankan migrasi dan seeder:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

> Untuk kebutuhan pengujian role di lingkungan non-production, tersedia `php artisan db:seed --class=DemoAccountSeeder`.

## Menjalankan Automated Test

```bash
php vendor/bin/phpunit
```
