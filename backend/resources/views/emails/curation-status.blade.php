<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembaruan Status Kurasi - FUTURiSE</title>
    <style>
        body { font-family: 'Kumbh Sans', Arial, sans-serif; background: #F7F7F7; padding: 28px 14px; color: #1E293B; }
        .card { max-width: 560px; margin: 0 auto; background: #FFFFFF; border: 1px solid #E2E8F0; border-top: 5px solid #2B2B5E; }
        .head { padding: 24px 28px 16px; border-bottom: 1px solid #E2E8F0; }
        .brand { font-size: 24px; font-weight: 800; color: #2B2B5E; margin: 0; }
        .brand span { color: #00CDE5; }
        .body { padding: 26px 28px; font-size: 14px; line-height: 1.7; }
        .badge { display: inline-block; padding: 6px 12px; font-size: 12px; font-weight: 700; border-radius: 6px; background: #EFF6FF; color: #017DC7; margin: 10px 0; }
        .note { background: #FFFBEB; border: 1px solid #FDE68A; padding: 14px; margin-top: 14px; font-size: 13px; color: #92400E; }
        .foot { padding: 16px 28px; font-size: 12px; color: #64748B; border-top: 1px solid #E2E8F0; background: #F8FAFC; }
    </style>
</head>
<body>
@php
    $statusLabel = match ($statusBaru) {
        'dalam_pemeriksaan' => 'Sedang Dalam Pemeriksaan',
        'perlu_revisi'      => 'Perlu Revisi',
        'lolos'             => 'Lolos Kurasi',
        'tidak_lolos'       => 'Tidak Lolos Kurasi',
        default             => ucfirst($statusBaru),
    };
@endphp
<div class="card">
    <div class="head">
        <p class="brand">FUTU<span>RiSE</span></p>
        <div style="font-size: 12px; color: #64748B;">APTIKOM Jawa Barat &bull; Pemberitahuan Kurasi Karya</div>
    </div>
    <div class="body">
        <p>Halo <strong>{{ $karya->submitter?->nama_lengkap ?? 'Peserta' }}</strong>,</p>
        <p>Status kurasi karya Anda berjudul <strong>{{ $karya->judul }}</strong> telah diperbarui:</p>
        <div class="badge">{{ $statusLabel }}</div>
        @if (! empty($catatan))
            <div class="note">
                <strong>Catatan Kurator:</strong><br>
                {{ $catatan }}
            </div>
        @endif
        <p style="margin-top: 16px;">Silakan masuk ke portal peserta pada menu <strong>Hasil Kurasi dan Showcase</strong> untuk melihat riwayat revisi atau menindaklanjuti pembaruan karya Anda.</p>
    </div>
    <div class="foot">
        Email ini dikirim otomatis oleh sistem FUTURiSE APTIKOM Jawa Barat.
    </div>
</div>
</body>
</html>
