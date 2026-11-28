<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Akun PIC Aktif - FUTURiSE</title>
    <style>
        body { font-family: 'Kumbh Sans', Arial, sans-serif; background: #F7F7F7; padding: 28px 14px; color: #1E293B; }
        .card { max-width: 560px; margin: 0 auto; background: #FFFFFF; border: 1px solid #E2E8F0; border-top: 5px solid #2B2B5E; }
        .head { padding: 24px 28px 16px; border-bottom: 1px solid #E2E8F0; }
        .brand { font-size: 24px; font-weight: 800; color: #2B2B5E; margin: 0; }
        .brand span { color: #00CDE5; }
        .body { padding: 26px 28px; font-size: 14px; line-height: 1.7; }
        .btn { display: inline-block; margin-top: 14px; padding: 10px 18px; background: #017DC7; color: #FFFFFF; text-decoration: none; border-radius: 6px; font-weight: 700; font-size: 13px; }
        .foot { padding: 16px 28px; font-size: 12px; color: #64748B; border-top: 1px solid #E2E8F0; background: #F8FAFC; }
    </style>
</head>
<body>
<div class="card">
    <div class="head">
        <p class="brand">FUTU<span>RiSE</span></p>
        <div style="font-size: 12px; color: #64748B;">APTIKOM Jawa Barat &bull; Aktivasi Akun PIC</div>
    </div>
    <div class="body">
        <p>Halo <strong>{{ $user->name }}</strong>,</p>
        <p>Akun Penanggung Jawab (PIC) Anda pada platform <strong>FUTURiSE</strong> telah disetujui oleh panitia dan kini berstatus <strong>Aktif</strong>.</p>
        <p>Anda dapat masuk ke portal menggunakan email <strong>{{ $user->email }}</strong> untuk mengelola profil institusi dan pendaftaran sesuai peran Anda.</p>
        <a href="{{ url('/login') }}" class="btn">Masuk ke Portal FUTURiSE</a>
    </div>
    <div class="foot">
        Email ini dikirim otomatis oleh sistem FUTURiSE APTIKOM Jawa Barat.
    </div>
</div>
</body>
</html>
