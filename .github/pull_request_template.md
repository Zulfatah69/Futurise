## Ringkasan Perubahan (Summary)
<!-- Jelaskan secara singkat fitur, perbaikan, atau perubahan yang dilakukan dalam PR ini -->

### Jenis Perubahan (Type of Change)
<!-- Centang salah satu yang sesuai -->
- [ ] `[FEAT]` Penambahan fitur baru
- [ ] `[FIX]` Perbaikan bug
- [ ] `[REFACTOR]` Refaktor kode tanpa mengubah fungsionalitas
- [ ] `[DOCS]` Pembaruan dokumentasi atau panduan API
- [ ] `[CHORE]` Pembaruan dependency atau build tool

### Branch Tujuan (Target Branch)
- [ ] `development` *(Sesuai aturan panduan GitHub: PR fitur selalu ditujukan ke development)*
- [ ] `staging` *(Hanya untuk rilis integrasi dari development)*
- [ ] `main` *(Hanya untuk rilis production dari staging)*

---

## Checklist Pengembang (Developer Checklist)
<!-- Pastikan semua poin di bawah telah dipenuhi sebelum meminta review -->
- [ ] **Target Folder:** Kode ditempatkan pada folder yang sesuai (`backend/` untuk Laravel atau `frontend/` untuk Next.js).
- [ ] **Pengujian Lokal:**
  - Backend: `php artisan test` lulus 100% tanpa error.
  - Frontend: `npm run build` berhasil tanpa TypeScript / Linting error.
- [ ] **UI/UX Policy:** Mematuhi *Zero Emoji Policy* (bebas emoji, ikon menggunakan SVG minimalis, tanpa teks contoh placeholder).
- [ ] **Database & Migration (Khusus Backend):**
  - Migration baru menyertakan method `up()` dan `down()`.
  - Tidak mengubah skema atau migration milik developer lain tanpa koordinasi sebelumnya.
- [ ] **Kontrak API:** Response JSON mengembalikan format standar `{ "success": true, "message": "...", "data": ..., "meta": ... }`.
- [ ] **Zero Leaks:** Tidak ada file `.env`, file `.md` non-README, atau kredensial rahasia yang ter-commit.

---

## Catatan Tambahan untuk Merge Team
<!-- Tuliskan catatan khusus atau instruksi pengujian integrasi jika diperlukan -->
