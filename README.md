# FUTURiSE — West Java Digital Innovation Festival

Website resmi FUTURiSE, festival inovasi digital mahasiswa skala Jawa Barat yang diselenggarakan oleh **APTIKOM Jawa Barat**.

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11 (PHP 8.3+) |
| Frontend | Next.js 14+ |
| Database | MySQL 8.0+ |
| Cache/Queue | Redis |
| Auth | Laravel Sanctum |

## Struktur Repository

```
Futurise/
├── backend/     ← Laravel API
├── frontend/    ← Next.js
├── .gitignore
└── README.md
```

## Setup Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

## Branch Strategy

| Branch | Fungsi |
|--------|--------|
| `main` | Production — protected |
| `staging` | UAT — protected |
| `development` | Integrasi semua fitur — protected |
| `feat/backend/*` | Fitur backend |
| `feat/frontend/*` | Fitur frontend |
| `fix/backend/*` | Bug fix backend |
| `integration/*` | Testing integrasi FE-BE |

## Tahapan Development

| Tahap | Scope |
|-------|-------|
| 0 — Fondasi | Setup Laravel, convention, festival & auth |
| 1 — Master Data | Festival, periode, kategori, panitia, divisi |
| 2 — Registrasi & Karya | Peserta, karya, tim, file, submit |
| 3 — Publikasi & Program | Publikasi karya, jadwal juri, penilaian |
| 4 — Hari Acara | Pengunjung, QR, check-in, interaksi |
| 5 — Finalisasi | UAT, bugfix, deployment |

## Tim

- **Dev1**: Backend (Festival, Panitia, Peserta, Karya, Publikasi) + Merge & Integration
- **Dev2**: Backend (Juri, Booth, Sponsor, Mitra, Pengunjung) + Frontend

## Lisensi

Hak cipta © 2026 APTIKOM Jawa Barat. Seluruh hak dilindungi.
