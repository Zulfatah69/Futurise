<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FUTURISE</title>

    <!-- FONT KUMBH SANS (APTIKOM DESIGN SYSTEM) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kumbh+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: #2B2B5E;
            --color-accent: #017DC7;
            --color-success: #00CDE5;
            --color-canvas: #FFFFFF;
            --color-ink: #82828A;
            --color-body: #A3C4E0;
            --color-hairline: #E2DFEB;
            --color-surface: #F7F7F7;
            --font-family: 'Kumbh Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --radius-btn: 8px;
            --radius-card: 0px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-family);
        }

        body {
            min-height: 100vh;
            background-color: var(--color-surface);
            color: #1A1A36;
        }

        /* NAVBAR (APTIKOM DEEP NAVY ANCHOR) */
        .navbar {
            width: 100%;
            background: var(--color-primary);
            padding: 16px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--color-success);
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--color-canvas);
        }

        .brand span {
            color: var(--color-success);
        }

        .logout-btn {
            background: transparent;
            color: var(--color-canvas);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 8px 20px;
            border-radius: var(--radius-btn);
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            font-family: var(--font-family);
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: var(--color-canvas);
            color: var(--color-primary);
            border-color: var(--color-canvas);
        }

        .container {
            max-width: 1024px;
            margin: 40px auto 60px;
            padding: 0 20px;
        }

        /* SHARP CARDS (APTIKOM STANDARD: 0px RADIUS) */
        .card {
            background: var(--color-canvas);
            padding: 36px 40px;
            border-radius: var(--radius-card);
            border: 1px solid var(--color-hairline);
            box-shadow: 0 4px 16px rgba(43, 43, 94, 0.04);
            margin-bottom: 24px;
        }

        .card-header-stripe {
            border-top: 4px solid var(--color-primary);
        }

        .subtitle {
            color: var(--color-accent);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.8px;
            color: var(--color-primary);
            margin-bottom: 8px;
        }

        .description {
            color: var(--color-ink);
            line-height: 1.8;
            margin-bottom: 24px;
            font-size: 15px;
        }

        .alert-success {
            background: #ECFDF5;
            color: #065F46;
            padding: 14px 18px;
            border-radius: 4px;
            border-left: 4px solid var(--color-success);
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.6;
        }

        .alert-error {
            background: #FEF2F2;
            color: #991B1B;
            padding: 14px 18px;
            border-radius: 4px;
            border-left: 4px solid #EF4444;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 18px;
        }

        .info-box {
            background: var(--color-surface);
            padding: 20px 22px;
            border-radius: var(--radius-card);
            border: 1px solid var(--color-hairline);
        }

        .info-box small {
            display: block;
            color: var(--color-ink);
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-box strong {
            color: var(--color-primary);
            font-size: 16px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: var(--radius-btn);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: rgba(0, 205, 229, 0.15);
            color: var(--color-primary);
            border: 1px solid var(--color-success);
        }

        .badge-warning {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #F59E0B;
        }

        /* PESERTA REGISTRATION & KARYA STYLING */
        .register-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--color-hairline);
        }

        .karya-item-card {
            background: var(--color-surface);
            border: 1px solid var(--color-hairline);
            border-left: 4px solid var(--color-primary);
            padding: 24px;
            margin-bottom: 20px;
            border-radius: var(--radius-card);
        }

        .karya-item-card.status-lolos {
            border-left-color: #10B981;
        }

        .karya-item-card.status-diajukan {
            border-left-color: var(--color-accent);
        }

        .karya-item-card.status-perlu_revisi {
            border-left-color: #F59E0B;
        }

        .karya-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 12px;
        }

        .karya-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--color-primary);
            letter-spacing: -0.4px;
        }

        .karya-meta-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 14px;
        }

        .karya-meta-tag {
            background: rgba(43, 43, 94, 0.08);
            color: var(--color-primary);
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .karya-files-box {
            background: var(--color-canvas);
            border: 1px solid var(--color-hairline);
            padding: 14px 16px;
            margin: 14px 0;
            border-radius: 4px;
        }

        .file-link-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--color-accent);
            text-decoration: none;
            font-weight: 700;
            font-size: 13px;
        }

        .file-link-item:hover {
            text-decoration: underline;
        }

        .team-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .team-pill {
            background: var(--color-canvas);
            border: 1px solid var(--color-hairline);
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline {
            background: transparent;
            color: var(--color-primary);
            border: 1px solid var(--color-primary);
            padding: 10px 22px;
            border-radius: var(--radius-btn);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-outline:hover {
            background: var(--color-primary);
            color: var(--color-canvas);
        }

        .btn-success {
            background: #10B981;
            color: #FFFFFF;
            border: none;
            padding: 10px 22px;
            border-radius: var(--radius-btn);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-secondary {
            background: #E2DFEB;
            color: var(--color-primary);
            border: none;
            padding: 10px 20px;
            border-radius: var(--radius-btn);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: #D1CDE0;
        }

        .team-member-row {
            display: grid;
            grid-template-columns: 2fr 1.5fr 2fr auto;
            gap: 12px;
            align-items: center;
            margin-bottom: 10px;
        }

        .register-section-header h2 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.4px;
            color: var(--color-primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        label {
            display: block;
            color: var(--color-primary);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid var(--color-hairline);
            border-radius: var(--radius-btn);
            outline: none;
            font-size: 14px;
            font-family: var(--font-family);
            background: var(--color-canvas);
            color: #1A1A36;
            transition: all 0.2s ease;
        }

        input:focus, select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(43, 43, 94, 0.12);
        }

        /* BUTTON (APTIKOM STANDARD: 8px RADIUS, PRIMARY NAVY) */
        .btn-primary {
            background: var(--color-primary);
            color: var(--color-canvas);
            border: none;
            padding: 14px 32px;
            border-radius: var(--radius-btn);
            font-size: 15px;
            font-weight: 700;
            font-family: var(--font-family);
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary:hover {
            background: #1E1E45;
            box-shadow: 0 4px 12px rgba(43, 43, 94, 0.2);
        }

        .empty-peserta-banner {
            background: rgba(0, 205, 229, 0.08);
            border: 1px dashed var(--color-accent);
            border-radius: var(--radius-card);
            padding: 26px;
            text-align: center;
            margin-bottom: 26px;
        }

        .empty-peserta-banner h3 {
            color: var(--color-primary);
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 8px;
        }

        .empty-peserta-banner p {
            color: var(--color-ink);
            font-size: 14px;
            max-width: 640px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* AUTOCOMPLETE DROPDOWN */
        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--color-canvas);
            border: 1px solid var(--color-hairline);
            border-radius: var(--radius-card);
            margin-top: 4px;
            max-height: 280px;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 0 8px 24px rgba(43, 43, 94, 0.12);
        }

        .suggestion-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid var(--color-surface);
            transition: all 0.15s ease;
            text-align: left;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-item:hover {
            background: rgba(0, 205, 229, 0.08);
        }

        .suggestion-name {
            font-weight: 700;
            color: var(--color-primary);
            font-size: 13px;
        }

        .suggestion-meta {
            font-size: 11px;
            color: var(--color-ink);
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .suggestion-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-pt {
            background: rgba(43, 43, 94, 0.1);
            color: var(--color-primary);
        }

        .badge-smk {
            background: rgba(0, 205, 229, 0.15);
            color: #007A8A;
        }

        .footer {
            text-align: center;
            color: var(--color-ink);
            font-size: 13px;
            margin: 40px 0;
        }

        @media (max-width: 700px) {
            .card {
                padding: 26px 20px;
            }

            .info-grid, .form-row {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 16px 20px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="brand">FUTURI<span>SE</span></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                Keluar
            </button>
        </form>
    </nav>

    <main class="container">

        <!-- FLASH NOTIFICATION -->
        @if (session('status'))
            <div class="alert-success">
                ✅ {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin-top: 6px; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- WELCOME CARD -->
        <section class="card card-header-stripe">
            <div class="subtitle">PORTAL AKUN PENGGUNA</div>
            <h1>Halo, {{ $user->name }}!</h1>
            <p class="description">
                Selamat datang di Dashboard FUTURiSE. Akun Anda telah terverifikasi dan siap digunakan.
            </p>

            <div class="info-grid">
                <div class="info-box">
                    <small>Nama Lengkap</small>
                    <strong>{{ $user->name }}</strong>
                </div>

                <div class="info-box">
                    <small>Email Terverifikasi</small>
                    <strong>{{ $user->email }}</strong>
                </div>

                <div class="info-box">
                    <small>Nomor Telepon</small>
                    <strong>{{ $user->person?->phone ?? '-' }}</strong>
                </div>

                <div class="info-box">
                    <small>Status Akun</small>
                    <span class="badge badge-success">
                        AKTIF
                    </span>
                </div>
            </div>
        </section>

        <!-- KEPESERTAAN LOMBA -->
        @if ($user->peserta)
            <!-- 1. KARTU PROFIL KEPESERTAAN -->
            <section class="card">
                <div class="register-section-header">
                    <h2>🎖️ Profil Kepesertaan Lomba</h2>
                    <span class="badge badge-success">TERDAFTAR RESMI</span>
                </div>

                <div class="info-grid">
                    <div class="info-box">
                        <small>Asal Kampus / Institusi</small>
                        <strong>{{ $user->peserta->institusi }}</strong>
                    </div>

                    <div class="info-box">
                        <small>Kategori Peserta</small>
                        <strong>{{ strtoupper($user->peserta->jenis_peserta) }}</strong>
                    </div>

                    <div class="info-box">
                        <small>Nomor Identitas (NIM / NISN / KTP)</small>
                        <strong>{{ $user->peserta->nomor_identitas ?? '-' }}</strong>
                    </div>

                    <div class="info-box">
                        <small>No. Kontak Peserta</small>
                        <strong>{{ $user->peserta->no_telepon ?? '-' }}</strong>
                    </div>
                </div>
            </section>

            <!-- 2. DAFTAR KARYA YANG SUDAH DIAJUKAN -->
            <section class="card">
                <div class="register-section-header">
                    <div>
                        <h2>💡 Karya & Inovasi Terdaftar</h2>
                        <p style="font-size: 13px; color: var(--color-ink); margin-top: 4px;">
                            Daftar proyek inovasi yang Anda daftarkan pada ajang {{ $activeFestival->nama ?? 'FUTURiSE 2026' }}.
                        </p>
                    </div>

                    <a href="#form-karya" class="btn-outline" style="padding: 8px 16px; font-size: 12px;">
                        + Buat Submisi Baru
                    </a>
                </div>

                @if ($user->peserta->karyasAsLeader->count() > 0)
                    @foreach ($user->peserta->karyasAsLeader as $karya)
                        <div class="karya-item-card status-{{ $karya->status }}">
                            <div class="karya-title-row">
                                <div>
                                    <h3 class="karya-title">{{ $karya->judul }}</h3>
                                    <div class="karya-meta-tags">
                                        <span class="karya-meta-tag">{{ $karya->kategori }}</span>
                                        <span class="karya-meta-tag" style="background: rgba(1, 125, 199, 0.1); color: var(--color-accent);">
                                            🛠️ {{ $karya->teknologi }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    @php
                                        $statusBadges = [
                                            'draft'             => ['label' => 'DRAFT', 'bg' => '#E5E7EB', 'text' => '#374151', 'border' => '#9CA3AF'],
                                            'diajukan'          => ['label' => 'DIAJUKAN', 'bg' => '#DBEAFE', 'text' => '#1E40AF', 'border' => '#3B82F6'],
                                            'dalam_pemeriksaan' => ['label' => 'DALAM PEMERIKSAAN', 'bg' => '#FEF3C7', 'text' => '#92400E', 'border' => '#F59E0B'],
                                            'perlu_revisi'      => ['label' => 'PERLU REVISI', 'bg' => '#FEF3C7', 'text' => '#B45309', 'border' => '#D97706'],
                                            'lolos'             => ['label' => 'LOLOS KURASI', 'bg' => '#D1FAE5', 'text' => '#065F46', 'border' => '#10B981'],
                                            'tidak_lolos'       => ['label' => 'TIDAK LOLOS', 'bg' => '#FEE2E2', 'text' => '#991B1B', 'border' => '#EF4444'],
                                        ];
                                        $badge = $statusBadges[$karya->status] ?? $statusBadges['draft'];
                                    @endphp
                                    <span class="badge" style="background: {{ $badge['bg'] }}; color: {{ $badge['text'] }}; border: 1px solid {{ $badge['border'] }};">
                                        {{ $badge['label'] }}
                                    </span>
                                </div>
                            </div>

                            <p style="font-size: 14px; color: var(--color-ink); line-height: 1.7; margin-bottom: 14px;">
                                {{ $karya->deskripsi }}
                            </p>

                            @if ($karya->url_demo)
                                <div style="margin-bottom: 12px; font-size: 13px;">
                                    <strong>🔗 Tautan Demo / Video:</strong>
                                    <a href="{{ $karya->url_demo }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-accent); font-weight: 700;">
                                        {{ $karya->url_demo }} ↗
                                    </a>
                                </div>
                            @endif

                            <!-- BERKAS LAMPIRAN -->
                            <div class="karya-files-box">
                                <small style="display: block; font-size: 11px; font-weight: 700; color: var(--color-ink); text-transform: uppercase; margin-bottom: 6px;">
                                    📁 Berkas Lampiran & Proposal:
                                </small>
                                @if ($karya->files->count() > 0)
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        @foreach ($karya->files as $file)
                                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
                                                <a href="{{ asset('storage/' . $file->path) }}" target="_blank" class="file-link-item">
                                                    📄 {{ $file->nama_asli }}
                                                    <span style="font-size: 11px; color: var(--color-ink); font-weight: 400;">
                                                        ({{ number_format($file->ukuran_byte / 1024, 1) }} KB)
                                                    </span>
                                                </a>
                                                <span style="font-size: 11px; color: var(--color-ink);">
                                                    {{ $file->keterangan ?? $file->jenis }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p style="font-size: 12px; color: #DC2626;">
                                        ⚠️ Belum ada proposal PDF yang dilampirkan.
                                    </p>
                                @endif
                            </div>

                            <!-- SUSUNAN TIM -->
                            <div style="margin-top: 14px;">
                                <small style="display: block; font-size: 11px; font-weight: 700; color: var(--color-ink); text-transform: uppercase; margin-bottom: 4px;">
                                    👥 Anggota Tim Pengembang:
                                </small>
                                <div class="team-pills">
                                    <div class="team-pill" style="border-color: var(--color-primary); font-weight: 700; color: var(--color-primary);">
                                        👑 {{ $user->peserta->nama_lengkap }} (Ketua Tim)
                                    </div>
                                    @foreach ($karya->anggotaTims as $anggota)
                                        <div class="team-pill">
                                            👤 {{ $anggota->nama_lengkap }}
                                            <span style="color: var(--color-ink); font-size: 11px;">({{ $anggota->peran ?? 'Anggota' }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- AKSI KARYA -->
                            @if (in_array($karya->status, ['draft', 'perlu_revisi']))
                                <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: flex-end; margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--color-hairline);">
                                    <!-- UPLOAD BERKAS TAMBAHAN FORM -->
                                    <form method="POST" action="{{ route('karya.upload', $karya->id) }}" enctype="multipart/form-data" style="display: inline-flex; align-items: center; gap: 8px;">
                                        @csrf
                                        <input type="hidden" name="jenis" value="dokumen">
                                        <input type="file" name="file" accept=".pdf,.zip,.png,.jpg" style="padding: 6px 10px; font-size: 12px; width: auto;" required>
                                        <button type="submit" class="btn-outline" style="padding: 8px 14px; font-size: 12px;">
                                            Upload Berkas
                                        </button>
                                    </form>

                                    <!-- FINAL SUBMIT FORM -->
                                    <form method="POST" action="{{ route('karya.submit', $karya->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengajukan karya ini secara final? Setelah diajukan, data karya akan masuk tahap kurasi.');">
                                        @csrf
                                        <button type="submit" class="btn-success">
                                            🚀 Ajukan Karya ke Kurator
                                        </button>
                                    </form>
                                </div>
                            @elseif ($karya->status === 'diajukan')
                                <div style="margin-top: 16px; padding: 12px; background: rgba(1, 125, 199, 0.08); border-left: 3px solid var(--color-accent); font-size: 13px; color: var(--color-primary);">
                                    ⏳ <strong>Status: Diajukan.</strong> Karya Anda sedang dalam antrean kurasi administrasi panitia APTIKOM.
                                </div>
                            @elseif ($karya->status === 'lolos')
                                <div style="margin-top: 16px; padding: 12px; background: #ECFDF5; border-left: 3px solid #10B981; font-size: 13px; color: #065F46;">
                                    🏆 <strong>Selamat!</strong> Karya inovasi Anda telah dinyatakan <strong>Lolos Kurasi</strong> dan berhak dinilai oleh dewan juri ahli pada sesi presentasi final.
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 40px 20px; background: var(--color-surface); border: 1px dashed var(--color-hairline); border-radius: var(--radius-card);">
                        <div style="font-size: 36px; margin-bottom: 8px;">🚀</div>
                        <h4 style="color: var(--color-primary); font-size: 18px; font-weight: 700; margin-bottom: 6px;">
                            Belum Ada Karya yang Diajukan
                        </h4>
                        <p style="color: var(--color-ink); font-size: 14px; max-width: 500px; margin: 0 auto 18px;">
                            Anda sudah terdaftar resmi sebagai peserta. Sekarang, daftarkan proyek inovasi terbaik tim Anda melalui formulir di bawah ini!
                        </p>
                    </div>
                @endif
            </section>

            <!-- 3. FORM PENDAFTARAN KARYA BARU -->
            <section id="form-karya" class="card">
                <div class="register-section-header">
                    <div>
                        <h2>📝 Formulir Submisi Karya Inovasi Baru</h2>
                        <p style="font-size: 13px; color: var(--color-ink); margin-top: 4px;">
                            Event: <strong>{{ $activeFestival->nama ?? 'FUTURiSE 2026' }}</strong> (Penyelenggara: {{ $activeFestival->penyelenggara ?? 'APTIKOM Jawa Barat' }})
                        </p>
                    </div>
                    <span class="badge badge-success">SUBMISI AKTIF</span>
                </div>

                <form method="POST" action="{{ route('karya.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- JUDUL KARYA -->
                    <div class="form-group">
                        <label for="judul">Judul Karya / Proyek Inovasi <span style="color: #DC2626;">*</span></label>
                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            value="{{ old('judul') }}"
                            placeholder="Contoh: Sistem Deteksi Anomali Jaringan Berbasis AI untuk Smart Campus"
                            required
                        >
                    </div>

                    <div class="form-row">
                        <!-- KATEGORI LOMBA -->
                        <div class="form-group">
                            <label for="kategori">Kategori Lomba <span style="color: #DC2626;">*</span></label>
                            <select id="kategori" name="kategori" required>
                                <option value="">-- Pilih Kategori Kompetisi --</option>
                                @if (isset($activeFestival) && $activeFestival->kategoris->count() > 0)
                                    @foreach ($activeFestival->kategoris as $kat)
                                        <option value="{{ $kat->nama }}" @selected(old('kategori') === $kat->nama)>
                                            {{ $kat->nama }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="Web & Mobile App Innovation">Web & Mobile App Innovation</option>
                                    <option value="Artificial Intelligence & Big Data">Artificial Intelligence & Big Data</option>
                                    <option value="IoT, Robotics & Embedded Systems">IoT, Robotics & Embedded Systems</option>
                                    <option value="Game Development & Interactive Media">Game Development & Interactive Media</option>
                                    <option value="Cybersecurity & Infrastructure">Cybersecurity & Infrastructure</option>
                                @endif
                            </select>
                        </div>

                        <!-- STACK TEKNOLOGI -->
                        <div class="form-group">
                            <label for="teknologi">Stack Teknologi Utama <span style="color: #DC2626;">*</span></label>
                            <input
                                type="text"
                                id="teknologi"
                                name="teknologi"
                                value="{{ old('teknologi') }}"
                                placeholder="Contoh: Python, YOLOv8, Vue.js, Laravel, Docker"
                                required
                            >
                        </div>
                    </div>

                    <!-- DESKRIPSI KARYA -->
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi & Ringkasan Solusi Inovasi <span style="color: #DC2626;">*</span></label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="4"
                            placeholder="Jelaskan latar belakang masalah, solusi inovasi yang ditawarkan, dan dampak manfaatnya..."
                            style="width: 100%; padding: 13px 15px; border: 1px solid var(--color-hairline); border-radius: var(--radius-btn); outline: none; font-size: 14px; font-family: var(--font-family); background: var(--color-canvas); resize: vertical;"
                            required
                        >{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="form-row">
                        <!-- URL DEMO / VIDEO -->
                        <div class="form-group">
                            <label for="url_demo">Tautan Demo / Video YouTube (Opsional)</label>
                            <input
                                type="url"
                                id="url_demo"
                                name="url_demo"
                                value="{{ old('url_demo') }}"
                                placeholder="https://youtube.com/watch?v=... atau https://demo.app"
                            >
                        </div>

                        <!-- BERKAS PROPOSAL PDF -->
                        <div class="form-group">
                            <label for="proposal">Unggah Berkas Proposal (PDF, Maks. 20 MB)</label>
                            <input
                                type="file"
                                id="proposal"
                                name="proposal"
                                accept=".pdf"
                                style="padding: 10px 12px;"
                            >
                            <p style="font-size: 11px; color: var(--color-ink); margin-top: 4px;">
                                💡 <em>Format proposal sesuai panduan kompetisi APTIKOM (format PDF).</em>
                            </p>
                        </div>
                    </div>

                    <!-- SUSUNAN ANGGOTA TIM DINAMIS -->
                    <div class="form-group" style="margin-top: 10px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <label style="margin-bottom: 0;">Anggota Tim Pengembang (Maks. 3-5 Orang)</label>
                            <button type="button" id="btn-add-member" class="btn-secondary" style="padding: 6px 14px; font-size: 12px;">
                                + Tambah Anggota
                            </button>
                        </div>

                        <div style="padding: 12px 14px; background: var(--color-surface); border: 1px solid var(--color-hairline); border-radius: var(--radius-btn); margin-bottom: 12px; font-size: 13px; color: var(--color-primary);">
                            👑 <strong>Ketua Tim:</strong> {{ $user->peserta->nama_lengkap }} ({{ $user->peserta->institusi }}) — <em>Otomatis terdaftar sebagai penanggung jawab</em>
                        </div>

                        <div id="team-members-container">
                            <!-- Dynamic rows will be inserted here -->
                        </div>
                    </div>

                    <!-- PERSETUJUAN PUBLIKASI -->
                    <div class="form-group" style="margin: 20px 0;">
                        <label style="display: flex; align-items: center; gap: 10px; font-weight: 500; cursor: pointer; font-size: 14px; color: #1A1A36;">
                            <input
                                type="checkbox"
                                name="persetujuan_publikasi"
                                value="1"
                                style="width: 18px; height: 18px; accent-color: var(--color-primary); cursor: pointer;"
                                @checked(old('persetujuan_publikasi', true))
                            >
                            Saya mengizinkan karya inovasi ini untuk dipamerkan pada Galeri Publik (Showcase Expo) FUTURiSE jika dinyatakan lolos kurasi.
                        </label>
                    </div>

                    <!-- TOMBOL AKSI SUBMISI -->
                    <div style="display: flex; gap: 14px; flex-wrap: wrap; margin-top: 24px;">
                        <button type="submit" name="action_type" value="submit" class="btn-primary">
                            🚀 Ajukan Karya Sekarang
                        </button>
                        <button type="submit" name="action_type" value="draft" class="btn-outline">
                            💾 Simpan Sebagai Draft
                        </button>
                    </div>
                </form>
            </section>
        @else
            <!-- BELUM TERDAFTAR SEBAGAI PESERTA: TAMPILKAN FORM TAHAP 2 -->
            <section class="card">
                <div class="empty-peserta-banner">
                    <h3>🏆 Ingin Mengikuti Lomba FUTURiSE?</h3>
                    <p>
                        Lengkapi form pendaftaran peserta di bawah ini untuk mendaftarkan institusi dan identitas kamu sebagai peserta lomba resmi.
                    </p>
                </div>

                <div class="register-section-header">
                    <h2>Formulir Pendaftaran Peserta Lomba</h2>
                    <span class="badge badge-warning">BELUM TERDAFTAR</span>
                </div>

                <form method="POST" action="{{ route('participant.register') }}">
                    @csrf

                    <div class="form-row">
                        <!-- AUTOCOMPLETE INSTITUSI -->
                        <div class="form-group">
                            <label for="institusi">Asal Kampus / Sekolah / Institusi <span style="color: #DC2626;">*</span></label>
                            <div style="position: relative;">
                                <input
                                    type="text"
                                    id="institusi"
                                    name="institusi"
                                    value="{{ old('institusi') }}"
                                    placeholder="Ketik nama kampus (misal: ITENAS) atau SMK..."
                                    autocomplete="off"
                                    required
                                >
                                <div id="institusi-spinner" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); display: none; font-size: 12px; color: var(--color-ink);">
                                    ⏳
                                </div>
                            </div>
                            <div id="institusi-suggestions" class="suggestions-dropdown" style="display: none;"></div>
                            <p style="font-size: 11px; color: var(--color-ink); margin-top: 5px;">
                                💡 <em>Saran institusi akan otomatis muncul dari database (4.300+ PT & 14.000+ SMK).</em>
                            </p>
                        </div>

                        <!-- KATEGORI PESERTA -->
                        <div class="form-group">
                            <label for="jenis_peserta">Kategori Peserta <span style="color: #DC2626;">*</span></label>
                            <select id="jenis_peserta" name="jenis_peserta" required>
                                <option value="mahasiswa" @selected(old('jenis_peserta') === 'mahasiswa')>Mahasiswa (Perguruan Tinggi)</option>
                                <option value="siswa" @selected(old('jenis_peserta') === 'siswa')>Siswa (SMA / SMK / Sederajat)</option>
                                <option value="umum" @selected(old('jenis_peserta') === 'umum')>Umum</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nomor_identitas">Nomor Identitas (NIM / NISN / KTP) <span style="color: #DC2626;">*</span></label>
                            <input
                                type="text"
                                id="nomor_identitas"
                                name="nomor_identitas"
                                value="{{ old('nomor_identitas') }}"
                                placeholder="Contoh: 152024183"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="no_telepon">Nomor WhatsApp / Telepon Aktif</label>
                            <input
                                type="tel"
                                id="no_telepon"
                                name="no_telepon"
                                value="{{ old('no_telepon', $user->person?->phone) }}"
                                placeholder="Contoh: 081234567890"
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        Simpan & Daftarkan Sebagai Peserta
                    </button>
                </form>
            </section>
        @endif

    </main>

    <footer class="footer">
        © {{ date('Y') }} FUTURISE. All Rights Reserved.
    </footer>

    <!-- AUTOCOMPLETE SCRIPT -->
    <script>
        const institusiInput = document.getElementById('institusi');
        const suggestionsBox = document.getElementById('institusi-suggestions');
        const spinner = document.getElementById('institusi-spinner');
        const jenisPesertaSelect = document.getElementById('jenis_peserta');

        let debounceTimer = null;

        if (institusiInput && suggestionsBox) {
            institusiInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    suggestionsBox.style.display = 'none';
                    suggestionsBox.innerHTML = '';
                    return;
                }

                if (spinner) spinner.style.display = 'block';

                debounceTimer = setTimeout(() => {
                    const jenis = jenisPesertaSelect ? jenisPesertaSelect.value : '';
                    let kategori = '';
                    if (jenis === 'mahasiswa') kategori = 'perguruan_tinggi';
                    else if (jenis === 'siswa') kategori = 'smk';

                    fetch(`/api/v1/institusi/search?q=${encodeURIComponent(query)}&kategori=${encodeURIComponent(kategori)}`)
                        .then(res => res.json())
                        .then(res => {
                            if (spinner) spinner.style.display = 'none';
                            if (res.success && res.data && res.data.length > 0) {
                                suggestionsBox.innerHTML = res.data.map(item => {
                                    const badgeClass = item.kategori === 'perguruan_tinggi' ? 'badge-pt' : 'badge-smk';
                                    const badgeLabel = item.kategori === 'perguruan_tinggi' ? 'Perguruan Tinggi' : 'SMK';
                                    const location = [item.kab_kota, item.provinsi].filter(Boolean).join(', ');
                                    return `
                                        <div class="suggestion-item" data-name="${item.nama.replace(/"/g, '&quot;')}">
                                            <div class="suggestion-name">${item.nama}</div>
                                            <div class="suggestion-meta">
                                                <span class="suggestion-badge ${badgeClass}">${badgeLabel}</span>
                                                ${location ? `<span>📍 ${location}</span>` : ''}
                                            </div>
                                        </div>
                                    `;
                                }).join('');
                                suggestionsBox.style.display = 'block';

                                suggestionsBox.querySelectorAll('.suggestion-item').forEach(el => {
                                    el.addEventListener('click', function() {
                                        institusiInput.value = this.dataset.name;
                                        suggestionsBox.style.display = 'none';
                                    });
                                });
                            } else {
                                suggestionsBox.innerHTML = `
                                    <div style="padding: 12px; font-size: 12px; color: var(--color-ink); text-align: center;">
                                        Tidak ditemukan di daftar. Anda tetap dapat mengetik nama institusi Anda secara manual.
                                    </div>
                                `;
                                suggestionsBox.style.display = 'block';
                            }
                        })
                        .catch(() => {
                            if (spinner) spinner.style.display = 'none';
                        });
                }, 200);
            });

            document.addEventListener('click', function(e) {
                if (!institusiInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.style.display = 'none';
                }
            });
        }

        // DYNAMIC TEAM MEMBERS SCRIPT
        const btnAddMember = document.getElementById('btn-add-member');
        const teamContainer = document.getElementById('team-members-container');

        if (btnAddMember && teamContainer) {
            let memberCount = 0;
            const maxMembers = 4;

            btnAddMember.addEventListener('click', function() {
                if (teamContainer.children.length >= maxMembers) {
                    alert('Maksimal anggota tim tambahan adalah ' + maxMembers + ' orang.');
                    return;
                }

                memberCount++;
                const row = document.createElement('div');
                row.className = 'team-member-row';
                row.id = `member-row-${memberCount}`;
                row.innerHTML = `
                    <input type="text" name="anggota_nama[]" placeholder="Nama Lengkap Anggota" required style="padding: 10px 12px; font-size: 13px;">
                    <input type="text" name="anggota_peran[]" placeholder="Peran (misal: UI Designer, Frontend)" style="padding: 10px 12px; font-size: 13px;">
                    <input type="email" name="anggota_email[]" placeholder="Email Anggota (opsional)" style="padding: 10px 12px; font-size: 13px;">
                    <button type="button" class="btn-outline" style="padding: 9px 12px; color: #DC2626; border-color: #DC2626; font-size: 12px;" onclick="removeMemberRow('member-row-${memberCount}')">
                        ✕
                    </button>
                `;
                teamContainer.appendChild(row);
            });
        }

        function removeMemberRow(id) {
            const el = document.getElementById(id);
            if (el) el.remove();
        }
    </script>

</body>
</html>
