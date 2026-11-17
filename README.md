# FUTURiSE — West Java Digital Innovation Festival

Website resmi FUTURiSE, festival inovasi digital mahasiswa skala Jawa Barat yang diselenggarakan oleh **APTIKOM Jawa Barat**.

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11 (PHP 8.3+) |
| Frontend | Next.js 14+ |
| Database | PostgreSQL 16+ |
| Cache/Queue | Redis |
| Auth | Laravel Sanctum |

## Fitur Utama

- Website publik (katalog karya, agenda, direktori industri)
- Portal peserta (kampus, industri, sponsor, sekolah, pengunjung)
- Dashboard panitia lengkap (kurasi, booth, keuangan, check-in, penjurian)
- Portal juri (penilaian & rubrik)
- QR check-in & voting People's Choice
- Laporan & audit terpusat

## Setup Lokal (Backend)

```bash
# Install dependencies
composer install

# Copy env
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Jalankan server
php artisan serve

# Queue worker (terminal terpisah)
php artisan queue:work
```

## Struktur Tahapan Development

| Tahap | Scope |
|-------|-------|
| 1 — Siap Pendaftaran | Website publik, CMS, akun, pendaftaran, karya, kurasi, keuangan, katalog |
| 2 — Siap Hari-H | Booth, agenda, pertemuan, QR check-in, penjurian, voting, survei |
| 3 — Pelaporan | Laporan H+30, sertifikat, integrasi lanjutan |

## Tim

- **Dev1**: Backend Laravel + Merge FE-BE
- **Dev2**: Frontend Next.js

## Lisensi

Hak cipta © 2026 APTIKOM Jawa Barat. Seluruh hak dilindungi.
