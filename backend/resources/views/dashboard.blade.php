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
            --color-primary-dark: #1F1F45;
            --color-accent: #017DC7;
            --color-cyan: #00CDE5;
            --color-canvas: #FFFFFF;
            --color-surface: #F8FAFC;
            --color-surface-hover: #F1F5F9;
            --color-ink: #64748B;
            --color-ink-dark: #1E293B;
            --color-hairline: #E2E8F0;
            --color-hairline-dark: #CBD5E1;
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
            color: var(--color-ink-dark);
            -webkit-font-smoothing: antialiased;
        }

        /* NAVBAR */
        .navbar {
            width: 100%;
            background: var(--color-primary);
            padding: 16px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--color-cyan);
        }

        .brand {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--color-canvas);
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .brand span {
            color: var(--color-cyan);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .user-greeting {
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            font-weight: 500;
        }

        .user-greeting strong {
            color: #FFFFFF;
        }

        .logout-btn {
            background: transparent;
            color: var(--color-canvas);
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 7px 16px;
            border-radius: var(--radius-btn);
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            font-family: var(--font-family);
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background: var(--color-canvas);
            color: var(--color-primary);
            border-color: var(--color-canvas);
        }

        /* CONTAINER */
        .container {
            max-width: 980px;
            margin: 36px auto 60px;
            padding: 0 20px;
        }

        /* ALERTS */
        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-btn);
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.5;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .alert-success {
            background: #F0FDF4;
            color: #166534;
            border: 1px solid #BBF7D0;
        }

        .alert-error {
            background: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        /* CARD */
        .card {
            background: var(--color-canvas);
            padding: 32px 36px;
            border-radius: var(--radius-card);
            border: 1px solid var(--color-hairline);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
        }

        .card-header {
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--color-hairline);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .card-tag {
            color: var(--color-ink);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.4px;
            color: var(--color-primary);
        }

        .card-subtitle {
            font-size: 13px;
            color: var(--color-ink);
            margin-top: 4px;
            line-height: 1.6;
        }

        /* STATS / INFO GRID */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }

        .info-box {
            background: var(--color-surface);
            padding: 14px 16px;
            border-radius: 4px;
            border: 1px solid var(--color-hairline);
        }

        .info-box-label {
            display: block;
            color: var(--color-ink);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .info-box-value {
            color: var(--color-primary);
            font-size: 14px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        /* STATUS PILLS */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .pill-draft {
            background: #F1F5F9;
            color: #475569;
            border: 1px solid #CBD5E1;
        }
        .pill-draft .status-dot { background: #64748B; }

        .pill-diajukan {
            background: #EFF6FF;
            color: #1E40AF;
            border: 1px solid #BFDBFE;
        }
        .pill-diajukan .status-dot { background: #3B82F6; }

        .pill-pemeriksaan {
            background: #FFFBEB;
            color: #B45309;
            border: 1px solid #FDE68A;
        }
        .pill-pemeriksaan .status-dot { background: #F59E0B; }

        .pill-revisi {
            background: #FEF2F2;
            color: #B91C1C;
            border: 1px solid #FECACA;
        }
        .pill-revisi .status-dot { background: #EF4444; }

        .pill-lolos {
            background: #F0FDF4;
            color: #15803D;
            border: 1px solid #BBF7D0;
        }
        .pill-lolos .status-dot { background: #16A34A; }

        /* FORM ELEMENTS */
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            margin-bottom: 18px;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        label {
            display: block;
            color: var(--color-ink-dark);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .req {
            color: #DC2626;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--color-hairline-dark);
            border-radius: var(--radius-btn);
            outline: none;
            font-size: 14px;
            font-family: var(--font-family);
            background: var(--color-canvas);
            color: var(--color-ink-dark);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(43, 43, 94, 0.08);
        }

        /* CUSTOM SELECT DROPDOWN */
        select {
            width: 100%;
            padding: 10px 38px 10px 14px;
            border: 1px solid var(--color-hairline-dark);
            border-radius: var(--radius-btn);
            outline: none;
            font-size: 14px;
            font-family: var(--font-family);
            background-color: var(--color-canvas);
            color: var(--color-ink-dark);
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%232B2B5E' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 15px;
            cursor: pointer;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        /* CUSTOM FILE DROPZONE */
        .file-dropzone {
            border: 2px dashed var(--color-hairline-dark);
            border-radius: var(--radius-btn);
            padding: 22px 18px;
            text-align: center;
            cursor: pointer;
            background: var(--color-surface);
            transition: all 0.2s ease;
            position: relative;
        }

        .file-dropzone:hover,
        .file-dropzone.dragover {
            border-color: var(--color-primary);
            background: rgba(43, 43, 94, 0.02);
        }

        .file-dropzone input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .dropzone-icon {
            color: var(--color-ink);
            margin-bottom: 8px;
        }

        .dropzone-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--color-primary);
        }

        .dropzone-hint {
            font-size: 11px;
            color: var(--color-ink);
            margin-top: 3px;
        }

        .dropzone-file-preview {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: var(--color-canvas);
            border: 1px solid var(--color-hairline);
            border-radius: var(--radius-btn);
            margin-top: 8px;
            font-size: 13px;
        }

        .dropzone-file-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--color-primary);
            font-weight: 600;
        }

        .dropzone-remove-btn {
            background: transparent;
            border: none;
            color: #DC2626;
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            transition: background 0.15s ease;
        }

        .dropzone-remove-btn:hover {
            background: #FEE2E2;
        }

        /* BUTTONS */
        .btn-primary {
            background: var(--color-primary);
            color: var(--color-canvas);
            border: none;
            padding: 11px 24px;
            border-radius: var(--radius-btn);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: var(--color-primary-dark);
        }

        .btn-outline {
            background: transparent;
            color: var(--color-primary);
            border: 1px solid var(--color-primary);
            padding: 10px 22px;
            border-radius: var(--radius-btn);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-outline:hover {
            background: var(--color-primary);
            color: var(--color-canvas);
        }

        .btn-secondary {
            background: var(--color-surface);
            color: var(--color-ink-dark);
            border: 1px solid var(--color-hairline-dark);
            padding: 8px 16px;
            border-radius: var(--radius-btn);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-secondary:hover {
            background: var(--color-surface-hover);
            border-color: var(--color-ink);
        }

        /* KARYA SUBMISSION ITEMS */
        .karya-card {
            border: 1px solid var(--color-hairline);
            border-radius: var(--radius-btn);
            padding: 20px 22px;
            margin-bottom: 16px;
            background: var(--color-canvas);
            transition: border-color 0.15s ease;
        }

        .karya-card:hover {
            border-color: var(--color-hairline-dark);
        }

        .karya-header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 8px;
        }

        .karya-heading {
            font-size: 17px;
            font-weight: 700;
            color: var(--color-primary);
            line-height: 1.4;
        }

        .karya-category-tag {
            display: inline-block;
            color: var(--color-ink);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .karya-desc {
            font-size: 13px;
            color: var(--color-ink);
            line-height: 1.6;
            margin-bottom: 14px;
        }

        .karya-meta-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 16px;
            font-size: 12px;
            color: var(--color-ink-dark);
            padding: 10px 14px;
            background: var(--color-surface);
            border-radius: 4px;
            margin-bottom: 14px;
        }

        .karya-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .karya-meta-item strong {
            color: var(--color-primary);
        }

        .karya-actions-row {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            padding-top: 14px;
            border-top: 1px solid var(--color-hairline);
        }

        /* TEAM MEMBERS FORM ROW */
        .member-input-row {
            display: grid;
            grid-template-columns: 2fr 1.5fr 2fr auto;
            gap: 10px;
            align-items: center;
            margin-bottom: 10px;
        }

        .btn-remove-row {
            background: transparent;
            border: 1px solid var(--color-hairline-dark);
            color: #DC2626;
            padding: 9px 12px;
            border-radius: var(--radius-btn);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
        }

        .btn-remove-row:hover {
            background: #FEF2F2;
            border-color: #F87171;
        }

        /* AUTOCOMPLETE DROPDOWN */
        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--color-canvas);
            border: 1px solid var(--color-hairline);
            margin-top: 4px;
            max-height: 240px;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-radius: 4px;
        }

        .suggestion-item {
            padding: 10px 14px;
            cursor: pointer;
            border-bottom: 1px solid var(--color-surface);
            transition: background 0.1s ease;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-item:hover {
            background: var(--color-surface-hover);
        }

        .suggestion-name {
            font-weight: 600;
            color: var(--color-primary);
            font-size: 13px;
        }

        .suggestion-meta {
            font-size: 11px;
            color: var(--color-ink);
            margin-top: 2px;
        }

        .footer {
            text-align: center;
            color: var(--color-ink);
            font-size: 12px;
            margin: 40px 0;
        }

        @media (max-width: 768px) {
            .card {
                padding: 24px 20px;
            }
            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .form-row,
            .member-input-row {
                grid-template-columns: 1fr;
            }
            .navbar {
                padding: 14px 20px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="brand">
            FUTURI<span>SE</span>
        </div>

        <div class="nav-right">
            <span class="user-greeting">
                Masuk sebagai: <strong>{{ $user->name }}</strong>
            </span>

            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main class="container">

        <!-- STATUS ALERTS -->
        @if (session('status'))
            <div class="alert alert-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <div>{{ session('status') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 1px;">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <div>
                    <strong style="display: block; margin-bottom: 4px;">Periksa kembali input Anda:</strong>
                    <ul style="padding-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- OVERVIEW / USER PROFILE -->
        <section class="card">
            <div class="card-header">
                <div>
                    <div class="card-tag">Informasi Akun</div>
                    <h1 class="card-title">{{ $user->name }}</h1>
                    <p class="card-subtitle">{{ $user->email }}</p>
                </div>

                <div>
                    <span class="status-pill pill-lolos">
                        <span class="status-dot"></span>
                        Akun Aktif
                    </span>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <span class="info-box-label">Status Email</span>
                    <div class="info-box-value">Terverifikasi</div>
                </div>

                <div class="info-box">
                    <span class="info-box-label">Nomor Telepon</span>
                    <div class="info-box-value">{{ $user->peserta?->no_telepon ?? $user->person?->phone ?? '-' }}</div>
                </div>

                <div class="info-box">
                    <span class="info-box-label">Institusi</span>
                    <div class="info-box-value">{{ $user->peserta?->institusi ?? 'Belum terdaftar' }}</div>
                </div>

                <div class="info-box">
                    <span class="info-box-label">Peran</span>
                    <div class="info-box-value">{{ $user->peserta ? strtoupper($user->peserta->jenis_peserta) : 'PENGUNJUNG' }}</div>
                </div>
            </div>
        </section>

        @if ($user->peserta)

            <!-- SECTION: SUBMISI KARYA SAYA -->
            <section class="card">
                <div class="card-header">
                    <div>
                        <div class="card-tag">Proyek Inovasi</div>
                        <h2 class="card-title">Karya yang Didaftarkan</h2>
                        <p class="card-subtitle">
                            Daftar inovasi yang Anda ajukan pada {{ $activeFestival->nama ?? 'FUTURiSE 2026' }}.
                        </p>
                    </div>

                    <a href="#form-submisi" class="btn-outline">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Submisi Baru
                    </a>
                </div>

                @if ($user->peserta->karyasAsLeader->count() > 0)
                    @foreach ($user->peserta->karyasAsLeader as $karya)
                        <div class="karya-card">
                            <div class="karya-category-tag">{{ $karya->kategori }}</div>

                            <div class="karya-header-row">
                                <h3 class="karya-heading">{{ $karya->judul }}</h3>

                                <div>
                                    @php
                                        $pillClass = match ($karya->status) {
                                            'diajukan'          => 'pill-diajukan',
                                            'dalam_pemeriksaan' => 'pill-pemeriksaan',
                                            'perlu_revisi'      => 'pill-revisi',
                                            'lolos'             => 'pill-lolos',
                                            default             => 'pill-draft',
                                        };
                                        $statusLabel = match ($karya->status) {
                                            'diajukan'          => 'Diajukan',
                                            'dalam_pemeriksaan' => 'Dalam Pemeriksaan',
                                            'perlu_revisi'      => 'Perlu Revisi',
                                            'lolos'             => 'Lolos Kurasi',
                                            'tidak_lolos'       => 'Tidak Lolos',
                                            default             => 'Draft',
                                        };
                                    @endphp
                                    <span class="status-pill {{ $pillClass }}">
                                        <span class="status-dot"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                            </div>

                            <p class="karya-desc">{{ $karya->deskripsi }}</p>

                            <!-- META ROW -->
                            <div class="karya-meta-row">
                                <div class="karya-meta-item">
                                    <span style="color: var(--color-ink);">Teknologi:</span>
                                    <strong>{{ $karya->teknologi }}</strong>
                                </div>

                                @if ($karya->url_demo)
                                    <div class="karya-meta-item">
                                        <span style="color: var(--color-ink);">Demo:</span>
                                        <a href="{{ $karya->url_demo }}" target="_blank" rel="noopener noreferrer" style="color: var(--color-accent); font-weight: 600; text-decoration: none;">
                                            Tautan Demo
                                        </a>
                                    </div>
                                @endif

                                @if ($karya->files->count() > 0)
                                    <div class="karya-meta-item">
                                        <span style="color: var(--color-ink);">Proposal:</span>
                                        @foreach ($karya->files as $file)
                                            <a href="{{ asset('storage/' . $file->path) }}" target="_blank" style="color: var(--color-accent); font-weight: 600; text-decoration: none;">
                                                {{ $file->nama_asli }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- ANGGOTA TIM -->
                            <div style="font-size: 12px; color: var(--color-ink); margin-bottom: 12px;">
                                Susunan Tim:
                                <strong style="color: var(--color-primary);">{{ $user->peserta->nama_lengkap }} (Ketua)</strong>
                                @foreach ($karya->anggotaTims as $anggota)
                                    &bull; <span>{{ $anggota->nama_lengkap }} ({{ $anggota->peran ?? 'Anggota' }})</span>
                                @endforeach
                            </div>

                            <!-- ACTIONS -->
                            @if (in_array($karya->status, ['draft', 'perlu_revisi']))
                                <div class="karya-actions-row">
                                    <form method="POST" action="{{ route('karya.submit', $karya->id) }}" onsubmit="return confirm('Ajukan karya ini untuk proses kurasi?');">
                                        @csrf
                                        <button type="submit" class="btn-primary" style="padding: 8px 16px; font-size: 12px;">
                                            Ajukan Karya ke Kurasi
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 36px 20px; background: var(--color-surface); border: 1px solid var(--color-hairline); border-radius: 4px;">
                        <p style="color: var(--color-ink); font-size: 14px; margin-bottom: 14px;">
                            Belum ada karya yang didaftarkan.
                        </p>
                        <a href="#form-submisi" class="btn-outline">
                            Mulai Submisi Karya
                        </a>
                    </div>
                @endif
            </section>

            <!-- SECTION: FORM SUBMISI BARU -->
            <section id="form-submisi" class="card">
                <div class="card-header">
                    <div>
                        <div class="card-tag">Formulir Submisi</div>
                        <h2 class="card-title">Pendaftaran Karya Inovasi Baru</h2>
                        <p class="card-subtitle">
                            Lengkapi informasi proyek karya yang akan diikutsertakan pada {{ $activeFestival->nama ?? 'FUTURiSE 2026' }}.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('karya.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- JUDUL KARYA -->
                    <div class="form-group">
                        <label for="judul">Judul Karya <span class="req">*</span></label>
                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            value="{{ old('judul') }}"
                            required
                        >
                    </div>

                    <div class="form-row">
                        <!-- KATEGORI LOMBA -->
                        <div class="form-group">
                            <label for="kategori">Kategori Lomba <span class="req">*</span></label>
                            <select id="kategori" name="kategori" required>
                                <option value="">Pilih kategori lomba</option>
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
                            <label for="teknologi">Stack Teknologi Utama <span class="req">*</span></label>
                            <input
                                type="text"
                                id="teknologi"
                                name="teknologi"
                                value="{{ old('teknologi') }}"
                                required
                            >
                        </div>
                    </div>

                    <!-- DESKRIPSI -->
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Karya <span class="req">*</span></label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="4"
                            required
                        >{{ old('deskripsi') }}</textarea>
                    </div>

                    <div class="form-row">
                        <!-- URL DEMO -->
                        <div class="form-group">
                            <label for="url_demo">Tautan Demo atau Video (Opsional)</label>
                            <input
                                type="url"
                                id="url_demo"
                                name="url_demo"
                                value="{{ old('url_demo') }}"
                            >
                        </div>

                        <!-- BERKAS PROPOSAL (CUSTOM DROPZONE) -->
                        <div class="form-group">
                            <label>Berkas Proposal (PDF, Maks. 20 MB)</label>
                            <div class="file-dropzone" id="proposal-dropzone">
                                <input type="file" id="proposal" name="proposal" accept=".pdf">
                                <div id="dropzone-empty">
                                    <div class="dropzone-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="17 8 12 3 7 8"/>
                                            <line x1="12" y1="3" x2="12" y2="15"/>
                                        </svg>
                                    </div>
                                    <div class="dropzone-title">Pilih berkas PDF atau seret ke area ini</div>
                                    <div class="dropzone-hint">Maksimal 20 MB</div>
                                </div>

                                <div class="dropzone-file-preview" id="dropzone-preview" style="display: none;">
                                    <div class="dropzone-file-info">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                        <span id="preview-filename"></span>
                                        <span id="preview-filesize" style="font-size: 11px; color: var(--color-ink); font-weight: 400;"></span>
                                    </div>
                                    <button type="button" class="dropzone-remove-btn" id="btn-remove-proposal" title="Hapus">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"/>
                                            <line x1="6" y1="6" x2="18" y2="18"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ANGGOTA TIM -->
                    <div class="form-group" style="margin-top: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <label style="margin-bottom: 0;">Anggota Tim Tambahan (Opsional)</label>
                            <button type="button" id="btn-add-member" class="btn-secondary">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Tambah Anggota
                            </button>
                        </div>

                        <div style="padding: 10px 14px; background: var(--color-surface); border: 1px solid var(--color-hairline); border-radius: var(--radius-btn); margin-bottom: 10px; font-size: 13px; color: var(--color-ink-dark);">
                            Ketua Tim: <strong>{{ $user->peserta->nama_lengkap }}</strong> ({{ $user->peserta->institusi }})
                        </div>

                        <div id="team-members-container"></div>
                    </div>

                    <!-- PERSETUJUAN PUBLIKASI -->
                    <div class="form-group" style="margin: 22px 0;">
                        <label style="display: flex; align-items: center; gap: 10px; font-weight: 500; cursor: pointer; font-size: 13px; color: var(--color-ink-dark);">
                            <input
                                type="checkbox"
                                name="persetujuan_publikasi"
                                value="1"
                                style="width: 16px; height: 16px; accent-color: var(--color-primary); cursor: pointer;"
                                @checked(old('persetujuan_publikasi', true))
                            >
                            Izinkan karya ini untuk dipamerkan pada Showcase Expo jika dinyatakan lolos kurasi.
                        </label>
                    </div>

                    <!-- SUBMIT BUTTONS -->
                    <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 24px;">
                        <button type="submit" name="action_type" value="submit" class="btn-primary">
                            Ajukan Karya
                        </button>
                        <button type="submit" name="action_type" value="draft" class="btn-outline">
                            Simpan Sebagai Draft
                        </button>
                    </div>
                </form>
            </section>

        @else

            <!-- FORM PENDAFTARAN PESERTA (JIKA BELUM TERDAFTAR) -->
            <section class="card">
                <div class="card-header">
                    <div>
                        <div class="card-tag">Pendaftaran Peserta</div>
                        <h2 class="card-title">Lengkapi Data Peserta</h2>
                        <p class="card-subtitle">
                            Masukkan data institusi dan identitas untuk mendaftar sebagai peserta lomba resmi.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('participant.register') }}">
                    @csrf

                    <div class="form-row">
                        <!-- AUTOCOMPLETE INSTITUSI -->
                        <div class="form-group">
                            <label for="institusi">Asal Kampus / Sekolah / Institusi <span class="req">*</span></label>
                            <div style="position: relative;">
                                <input
                                    type="text"
                                    id="institusi"
                                    name="institusi"
                                    value="{{ old('institusi') }}"
                                    autocomplete="off"
                                    required
                                >
                                <div id="institusi-spinner" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); display: none; font-size: 11px; color: var(--color-ink);">
                                    Memuat...
                                </div>
                            </div>
                            <div id="institusi-suggestions" class="suggestions-dropdown" style="display: none;"></div>
                        </div>

                        <!-- KATEGORI PESERTA -->
                        <div class="form-group">
                            <label for="jenis_peserta">Kategori Peserta <span class="req">*</span></label>
                            <select id="jenis_peserta" name="jenis_peserta" required>
                                <option value="mahasiswa" @selected(old('jenis_peserta') === 'mahasiswa')>Mahasiswa (Perguruan Tinggi)</option>
                                <option value="siswa" @selected(old('jenis_peserta') === 'siswa')>Siswa (SMA / SMK / Sederajat)</option>
                                <option value="umum" @selected(old('jenis_peserta') === 'umum')>Umum</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nomor_identitas">Nomor Identitas (NIM / NISN / KTP) <span class="req">*</span></label>
                            <input
                                type="text"
                                id="nomor_identitas"
                                name="nomor_identitas"
                                value="{{ old('nomor_identitas') }}"
                                required
                            >
                            <small id="identity-hint" style="display: block; margin-top: 5px; font-size: 11px; color: var(--color-ink);">
                                Gunakan NIM resmi dari perguruan tinggi Anda.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="no_telepon">Nomor WhatsApp / Telepon</label>
                            <input
                                type="tel"
                                id="no_telepon"
                                name="no_telepon"
                                value="{{ old('no_telepon', $user->person?->phone) }}"
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        Simpan Profil Peserta
                    </button>
                </form>
            </section>

        @endif

    </main>

    <footer class="footer">
        &copy; {{ date('Y') }} FUTURISE. Hak Cipta Dilindungi.
    </footer>

    <!-- JAVASCRIPT: AUTOCOMPLETE & DROPZONE & TEAM BUILDER -->
    <script>
        // 1. AUTOCOMPLETE INSTITUSI (Preload Client-Side + AbortController Anti-Stale)
        const institusiInput = document.getElementById('institusi');
        const suggestionsBox = document.getElementById('institusi-suggestions');
        const spinner = document.getElementById('institusi-spinner');
        const jenisPesertaSelect = document.getElementById('jenis_peserta');

        const INSTITUTION_ALIASES = {
            'itenas': 'institut teknologi nasional',
            'itb': 'institut teknologi bandung',
            'ui': 'universitas indonesia',
            'ugm': 'universitas gadjah mada',
            'unpad': 'universitas padjadjaran',
            'its': 'institut teknologi sepuluh nopember',
            'undip': 'universitas diponegoro',
            'unair': 'universitas airlangga',
            'ub': 'universitas brawijaya',
            'upi': 'universitas pendidikan indonesia',
            'ipb': 'institut pertanian bogor',
            'uny': 'universitas negeri yogyakarta',
            'unesa': 'universitas negeri surabaya',
            'uns': 'universitas sebelas maret',
            'unhas': 'universitas hasanuddin',
            'usu': 'universitas sumatera utara',
            'unand': 'universitas andalas',
            'unsri': 'universitas sriwijaya',
            'binus': 'universitas bina nusantara',
            'telkom': 'universitas telkom',
            'telkom university': 'universitas telkom',
            'polban': 'politeknik negeri bandung',
            'pens': 'politeknik elektronika negeri surabaya',
            'unikom': 'universitas komputer indonesia',
            'unpar': 'universitas katolik parahyangan',
            'maranatha': 'universitas kristen maranatha',
            'unisba': 'universitas islam bandung',
            'widyatama': 'universitas widyatama',
            'unjani': 'universitas jenderal achmad yani',
            'uin bandung': 'universitas islam negeri sunan gunung djati',
        };
        const WORD_EXPANSIONS = {
            'univ': 'universitas',
            'poltek': 'politeknik',
            'inst': 'institut',
        };

        const preloadedInstitutions = { perguruan_tinggi: null, smk: null };
        const preloadPromises = {};
        const queryCache = new Map();
        let debounceTimer = null;
        let activeAbortController = null;
        let requestSeq = 0;

        function getSelectedCategory() {
            const jenis = jenisPesertaSelect ? jenisPesertaSelect.value : '';
            if (jenis === 'mahasiswa') return 'perguruan_tinggi';
            if (jenis === 'siswa') return 'smk';
            return '';
        }

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function preloadCategory(kategori) {
            if (!kategori || preloadedInstitutions[kategori] || preloadPromises[kategori]) {
                return preloadPromises[kategori] || Promise.resolve(preloadedInstitutions[kategori]);
            }

            preloadPromises[kategori] = fetch(`/api/v1/institusi/search?preload=1&kategori=${encodeURIComponent(kategori)}`)
                .then(res => res.json())
                .then(res => {
                    if (res.success && Array.isArray(res.data)) {
                        preloadedInstitutions[kategori] = res.data.map(row => ({
                            nama: row[0],
                            namaLower: row[0].toLowerCase(),
                            provinsi: row[1] || '',
                            kab_kota: row[2] || '',
                            isJabar: (row[1] || '').includes('Jawa Barat') ? 0 : 1,
                        }));
                    }
                    return preloadedInstitutions[kategori];
                })
                .catch(() => null);

            return preloadPromises[kategori];
        }

        function searchLocal(dataset, rawQuery) {
            const lower = rawQuery.replace(/\s+/g, ' ').trim().toLowerCase();
            const alias = INSTITUTION_ALIASES[lower];
            const phrase = alias || lower.split(' ').map(w => WORD_EXPANSIONS[w] || w).join(' ');
            const words = phrase.split(' ').filter(Boolean);

            const matches = [];
            for (let i = 0; i < dataset.length; i++) {
                const item = dataset[i];
                const name = item.namaLower;
                let tier = -1;

                if (alias) {
                    if (name.includes(alias) || name.includes(lower)) {
                        tier = name === alias ? 0 : (name.startsWith(alias) ? 1 : 2);
                    }
                } else if (words.every(w => name.includes(w))) {
                    tier = name === phrase ? 0 : (name.startsWith(phrase) ? 1 : (name.includes(phrase) ? 2 : 3));
                }

                if (tier !== -1) {
                    matches.push({ item, tier });
                }
            }

            // Ringan typo fallback jika hasil ketat kosong
            if (matches.length === 0 && phrase.length >= 4) {
                const prefixes = words.map(w => w.slice(0, Math.min(3, w.length))).filter(Boolean);
                if (prefixes.length > 0) {
                    for (let i = 0; i < dataset.length; i++) {
                        const item = dataset[i];
                        if (prefixes.every(p => item.namaLower.includes(p))) {
                            const tier = item.namaLower.startsWith(phrase.slice(0, 4)) ? 1 : 3;
                            matches.push({ item, tier });
                        }
                    }
                }
            }

            matches.sort((a, b) => {
                if (a.tier !== b.tier) return a.tier - b.tier;
                if (a.item.isJabar !== b.item.isJabar) return a.item.isJabar - b.item.isJabar;
                if (a.item.nama.length !== b.item.nama.length) return a.item.nama.length - b.item.nama.length;
                return a.item.nama.localeCompare(b.item.nama);
            });

            return matches.slice(0, 15).map(m => m.item);
        }

        function renderSuggestions(items) {
            if (items && items.length > 0) {
                suggestionsBox.innerHTML = items.map(item => {
                    const location = [item.kab_kota, item.provinsi].filter(Boolean).join(', ');
                    return `
                        <div class="suggestion-item" data-name="${escapeHtml(item.nama)}">
                            <div class="suggestion-name">${escapeHtml(item.nama)}</div>
                            ${location ? `<div class="suggestion-meta">${escapeHtml(location)}</div>` : ''}
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
                    <div style="padding: 10px 14px; font-size: 12px; color: var(--color-ink);">
                        Institusi tidak ditemukan. Anda dapat mengetikkannya secara manual.
                    </div>
                `;
                suggestionsBox.style.display = 'block';
            }
        }

        if (institusiInput && suggestionsBox) {
            // Preload kategori aktif di latar belakang segera setelah halaman siap
            const initialCategory = getSelectedCategory();
            if (initialCategory) {
                preloadCategory(initialCategory);
            }

            institusiInput.addEventListener('focus', function() {
                const cat = getSelectedCategory();
                if (cat) preloadCategory(cat);
            });

            institusiInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(debounceTimer);

                if (activeAbortController) {
                    activeAbortController.abort();
                    activeAbortController = null;
                }
                const mySeq = ++requestSeq;

                if (query.length < 2) {
                    if (spinner) spinner.style.display = 'none';
                    suggestionsBox.style.display = 'none';
                    suggestionsBox.innerHTML = '';
                    return;
                }

                const kategori = getSelectedCategory();
                const localDataset = kategori ? preloadedInstitutions[kategori] : null;

                // Jika preload sudah siap, saring & urutkan langsung di browser (< 5 ms)
                if (localDataset) {
                    if (spinner) spinner.style.display = 'none';
                    renderSuggestions(searchLocal(localDataset, query));
                    return;
                }

                const cacheKey = `${kategori}|${query.toLowerCase()}`;
                if (queryCache.has(cacheKey)) {
                    if (spinner) spinner.style.display = 'none';
                    renderSuggestions(queryCache.get(cacheKey));
                    return;
                }

                // Bersihkan dropdown hasil lama agar hasil query sebelumnya tidak menyesatkan
                suggestionsBox.style.display = 'none';
                if (spinner) spinner.style.display = 'block';

                debounceTimer = setTimeout(() => {
                    activeAbortController = new AbortController();

                    fetch(`/api/v1/institusi/search?q=${encodeURIComponent(query)}&kategori=${encodeURIComponent(kategori)}`, {
                        signal: activeAbortController.signal,
                    })
                        .then(res => res.json())
                        .then(res => {
                            if (mySeq !== requestSeq || institusiInput.value.trim() !== query) {
                                return;
                            }
                            if (spinner) spinner.style.display = 'none';
                            const items = (res.success && Array.isArray(res.data)) ? res.data : [];
                            queryCache.set(cacheKey, items);
                            renderSuggestions(items);
                        })
                        .catch(err => {
                            if (err && err.name === 'AbortError') return;
                            if (mySeq === requestSeq && spinner) spinner.style.display = 'none';
                        });
                }, 300);
            });

            if (jenisPesertaSelect) {
                const identityHint = document.getElementById('identity-hint');
                const updateIdentityHint = () => {
                    if (!identityHint) return;
                    const hints = {
                        mahasiswa: 'Gunakan NIM resmi dari perguruan tinggi Anda.',
                        siswa: 'Masukkan 10 digit angka NISN resmi Anda.',
                        umum: 'Masukkan 16 digit angka NIK sesuai KTP Anda.',
                    };
                    identityHint.textContent = hints[jenisPesertaSelect.value] || hints.mahasiswa;
                };
                updateIdentityHint();

                jenisPesertaSelect.addEventListener('change', function() {
                    updateIdentityHint();
                    const cat = getSelectedCategory();
                    if (cat) preloadCategory(cat);
                    if (institusiInput.value.trim().length >= 2) {
                        institusiInput.dispatchEvent(new Event('input'));
                    }
                });
            }

            document.addEventListener('click', function(e) {
                if (!institusiInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.style.display = 'none';
                }
            });
        }

        // 2. FILE DROPZONE PREVIEW & REMOVE
        const proposalInput = document.getElementById('proposal');
        const dropzoneEmpty = document.getElementById('dropzone-empty');
        const dropzonePreview = document.getElementById('dropzone-preview');
        const previewFilename = document.getElementById('preview-filename');
        const previewFilesize = document.getElementById('preview-filesize');
        const btnRemoveProposal = document.getElementById('btn-remove-proposal');
        const proposalDropzone = document.getElementById('proposal-dropzone');

        if (proposalInput) {
            proposalInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    previewFilename.textContent = file.name;
                    previewFilesize.textContent = '(' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    dropzoneEmpty.style.display = 'none';
                    dropzonePreview.style.display = 'flex';
                }
            });

            if (btnRemoveProposal) {
                btnRemoveProposal.addEventListener('click', function(e) {
                    e.stopPropagation();
                    proposalInput.value = '';
                    dropzoneEmpty.style.display = 'block';
                    dropzonePreview.style.display = 'none';
                });
            }

            // Drag and drop visual state
            ['dragenter', 'dragover'].forEach(eventName => {
                proposalDropzone.addEventListener(eventName, () => proposalDropzone.classList.add('dragover'), false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                proposalDropzone.addEventListener(eventName, () => proposalDropzone.classList.remove('dragover'), false);
            });
        }

        // 3. DYNAMIC TEAM BUILDER
        const btnAddMember = document.getElementById('btn-add-member');
        const teamContainer = document.getElementById('team-members-container');

        if (btnAddMember && teamContainer) {
            const maxMembers = 4;

            btnAddMember.addEventListener('click', function() {
                if (teamContainer.children.length >= maxMembers) {
                    alert('Maksimal anggota tim tambahan adalah ' + maxMembers + ' orang.');
                    return;
                }

                const rowId = 'member-' + Date.now();
                const row = document.createElement('div');
                row.className = 'member-input-row';
                row.id = rowId;
                row.innerHTML = `
                    <input type="text" name="anggota_nama[]" placeholder="Nama Lengkap" required>
                    <input type="text" name="anggota_peran[]" placeholder="Peran">
                    <input type="email" name="anggota_email[]" placeholder="Alamat Email">
                    <button type="button" class="btn-remove-row" onclick="document.getElementById('${rowId}').remove()" title="Hapus">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                `;
                teamContainer.appendChild(row);
            });
        }
    </script>

</body>
</html>
