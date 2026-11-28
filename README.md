# FUTURiSE — West Java Digital Innovation Festival

Platform terintegrasi resmi untuk **FUTURiSE (West Java Digital Innovation Festival)** yang diselenggarakan oleh **APTIKOM Jawa Barat**. Satu sumber data terpadu untuk website publik, portal peserta & juri, serta dashboard panitia.

## Tech Stack

| Layer | Teknologi |
|---|---|
| **Backend & Admin** | Laravel 13 (PHP 8.3+), Filament 5 (`/admin`), Laravel Sanctum 4, Fortify, Spatie Permission 8 |
| **Frontend Publik** | Next.js 16 (App Router, Turbopack), React 19, Tailwind CSS 4 |
| **Database** | MySQL 8.4.3 (lokal/production), SQLite `:memory:` (PHPUnit testing) |

## Struktur Repository (Monorepo)

```text
futurise/
├── backend/       # REST API Laravel 13, Web Portal Blade, & Panel Admin Filament 5
├── frontend/      # Website Publik Next.js 16
├── docs/          # Pusat dokumentasi internal & spesifikasi (tidak di-push ke GitHub)
└── README.md      # Panduan setup utama repository
```

## Menjalankan Proyek Secara Lokal

### 1. Backend (`backend/`)

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
# Sesuaikan DB_CONNECTION=mysql, DB_DATABASE=futurise, FRONTEND_URL, dan CORS_ALLOWED_ORIGINS di .env
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve
```

Untuk menjalankan pengujian otomatis backend (SQLite in-memory via `phpunit.xml`):

```bash
cd backend
php vendor/bin/phpunit
```

### 2. Frontend (`frontend/`)

```bash
cd frontend
npm install
cp .env.example .env.local
# Pastikan NEXT_PUBLIC_API_URL=http://localhost:8000/api/v1 dan NEXT_PUBLIC_BACKEND_URL=http://localhost:8000
npm run dev
```

Verifikasi kualitas kode frontend:

```bash
cd frontend
npm run lint
npm run build
```

## Pembagian & Aturan Repository

- **Branch:** `main` (production), `staging` (UAT), `development` (integrasi), serta branch fitur `feat/backend/*` dan `feat/frontend/*`.
- **Kebersihan Git:** Jangan pernah commit/push `.env`, `vendor/`, `node_modules/`, dokumentasi internal `docs/`, maupun catatan lokal selain `README.md`.
- **Dokumentasi Internal:** Lihat `docs/Source-of-Truth.md` (di workspace lokal) untuk spesifikasi produk lengkap, skema database, kontrak REST API, checklist Dev 1 & Dev 2, serta panduan keamanan/testing.

## Lisensi

Hak cipta &copy; 2026 APTIKOM Jawa Barat. Seluruh hak dilindungi.
