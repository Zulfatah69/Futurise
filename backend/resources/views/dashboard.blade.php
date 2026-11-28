<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pengguna - FUTURiSE</title>

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
            --color-ink: #1E293B;
            --color-muted: #64748B;
            --color-hairline: #E2E8F0;
            --color-success-bg: #ECFDF5;
            --color-success-border: #A7F3D0;
            --color-success-ink: #065F46;
            --color-warn-bg: #FFFBEB;
            --color-warn-border: #FDE68A;
            --color-warn-ink: #92400E;
            --color-danger-bg: #FEF2F2;
            --color-danger-border: #FECACA;
            --color-danger-ink: #991B1B;
            --font-family: 'Kumbh Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --role-accent: #017DC7;
            --role-accent-soft: #EFF6FF;
            --role-banner-start: #2B2B5E;
            --role-banner-end: #017DC7;
        }

        .theme-pic-kampus,
        .theme-tim-mahasiswa,
        .theme-pic-sekolah,
        .theme-pic-industri,
        .theme-pic-sponsor,
        .theme-juri,
        .theme-pengunjung {
            --role-accent: #017DC7;
            --role-accent-soft: #EFF6FF;
            --role-banner-start: #2B2B5E;
            --role-banner-end: #017DC7;
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
            color: var(--color-ink);
            -webkit-font-smoothing: antialiased;
        }

        /* Top Navigation Bar (APTIKOM Deep Navy + Cyan Border) */
        .topbar {
            background: var(--color-primary);
            border-bottom: 3px solid var(--color-cyan);
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-inner {
            max-width: 1380px;
            margin: 0 auto;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            background: rgba(0, 205, 229, 0.14);
            border: 1px solid rgba(0, 205, 229, 0.4);
            color: var(--color-cyan);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-title {
            font-size: 18px;
            font-weight: 800;
            color: #FFFFFF;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .brand-title span {
            color: var(--color-cyan);
        }

        .brand-sub {
            font-size: 11.5px;
            color: rgba(255, 255, 255, 0.78);
            font-weight: 500;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .festival-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.12);
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.22);
        }

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            background: rgba(0, 205, 229, 0.18);
            color: var(--color-cyan);
            border: 1px solid rgba(0, 205, 229, 0.45);
        }

        .user-chip {
            text-align: right;
            line-height: 1.25;
        }

        .user-chip strong {
            display: block;
            font-size: 13px;
            color: #FFFFFF;
        }

        .user-chip span {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.75);
        }

        .logout-btn {
            background: transparent;
            color: #FFFFFF;
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 7px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: #FFFFFF;
            color: var(--color-primary);
            border-color: #FFFFFF;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 15px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.18s ease;
        }

        .btn-sm {
            padding: 6px 11px;
            font-size: 12px;
        }

        .btn-primary {
            background: var(--role-accent);
            color: #FFFFFF;
        }

        .btn-primary:hover {
            filter: brightness(0.92);
        }

        .btn-navy {
            background: var(--color-primary);
            color: #FFFFFF;
        }

        .btn-navy:hover {
            filter: brightness(1.1);
        }

        .btn-outline {
            background: #FFFFFF;
            border-color: var(--color-hairline);
            color: var(--color-ink);
        }

        .btn-outline:hover {
            background: var(--color-surface);
            border-color: var(--role-accent);
            color: var(--role-accent);
        }

        .btn-danger-outline {
            background: #FFFFFF;
            border-color: #FECACA;
            color: #B91C1C;
        }

        .btn-danger-outline:hover {
            background: #FEF2F2;
        }

        .mobile-menu-toggle {
            display: none;
        }

        /* App Shell Layout */
        .app-shell {
            max-width: 1380px;
            margin: 0 auto;
            padding: 24px;
            display: grid;
            grid-template-columns: 270px minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }

        .sidebar {
            background: var(--color-canvas);
            border: 1px solid var(--color-hairline);
            border-radius: 14px;
            padding: 16px;
            position: sticky;
            top: 84px;
        }

        .sidebar-role-card {
            padding: 14px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--role-banner-start), var(--role-banner-end));
            color: #FFFFFF;
            margin-bottom: 16px;
        }

        .sidebar-role-tag {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.85;
            margin-bottom: 4px;
        }

        .sidebar-role-title {
            font-size: 15px;
            font-weight: 700;
            line-height: 1.3;
        }

        .sidebar-section-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-muted);
            padding: 6px 10px;
            margin-top: 8px;
        }

        .nav-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 9px;
            border: 1px solid transparent;
            background: transparent;
            color: var(--color-ink);
            font-size: 13px;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.16s ease;
        }

        .nav-item:hover {
            background: var(--color-surface);
        }

        .nav-item.active {
            background: var(--role-accent-soft);
            color: var(--role-accent);
            border-color: rgba(1, 125, 199, 0.22);
        }

        .nav-item-left {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            min-width: 0;
        }

        .nav-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            background: var(--color-surface);
            color: var(--color-muted);
            flex-shrink: 0;
        }

        .nav-item.active .nav-badge {
            background: var(--role-accent);
            color: #FFFFFF;
        }

        .nav-badge-dev2 {
            background: #F1F5F9;
            color: #64748B;
            font-weight: 600;
        }

        /* Main Workspace */
        .main-col {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .hero-banner {
            border-radius: 16px;
            padding: 24px 28px;
            background: linear-gradient(125deg, var(--role-banner-start) 0%, var(--role-banner-end) 100%);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .hero-eyebrow {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.88;
            margin-bottom: 6px;
        }

        .hero-title {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .hero-desc {
            font-size: 13.5px;
            line-height: 1.55;
            opacity: 0.92;
            max-width: 680px;
        }

        .hero-stat-box {
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.24);
            border-radius: 12px;
            padding: 14px 18px;
            min-width: 190px;
        }

        .hero-stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            opacity: 0.85;
            margin-bottom: 4px;
        }

        .hero-stat-value {
            font-size: 22px;
            font-weight: 800;
        }

        .hero-stat-sub {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 2px;
        }

        /* Alerts */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            line-height: 1.5;
            border: 1px solid transparent;
        }

        .alert-success {
            background: var(--color-success-bg);
            border-color: var(--color-success-border);
            color: var(--color-success-ink);
        }

        .alert-info {
            background: #EFF6FF;
            border-color: #BFDBFE;
            color: #1E40AF;
        }

        .alert-warning {
            background: var(--color-warn-bg);
            border-color: var(--color-warn-border);
            color: var(--color-warn-ink);
        }

        .alert-error {
            background: var(--color-danger-bg);
            border-color: var(--color-danger-border);
            color: var(--color-danger-ink);
        }

        .alert ul {
            margin-left: 18px;
            margin-top: 6px;
        }

        /* Cards & Grids */
        .panel-section {
            display: none;
            flex-direction: column;
            gap: 20px;
        }

        .panel-section.active {
            display: flex;
        }

        .card {
            background: var(--color-canvas);
            border: 1px solid var(--color-hairline);
            border-radius: 14px;
            padding: 22px 24px;
        }

        .card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 4px;
        }

        .card-subtitle {
            font-size: 13px;
            color: var(--color-muted);
            line-height: 1.5;
        }

        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .kpi-card {
            background: var(--color-canvas);
            border: 1px solid var(--color-hairline);
            border-radius: 12px;
            padding: 16px 18px;
        }

        .kpi-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-muted);
            margin-bottom: 6px;
        }

        .kpi-value {
            font-size: 24px;
            font-weight: 800;
            color: var(--color-primary);
        }

        .kpi-note {
            font-size: 11.5px;
            color: var(--color-muted);
            margin-top: 4px;
        }

        .two-col-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .three-col-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .info-kv {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .kv-item {
            padding: 12px 14px;
            background: var(--color-surface);
            border-radius: 10px;
            border: 1px solid var(--color-hairline);
        }

        .kv-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--color-muted);
            margin-bottom: 4px;
        }

        .kv-value {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--color-ink);
            word-break: break-word;
        }

        /* Status Pills */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            text-transform: capitalize;
        }

        .status-draft { background: #F1F5F9; color: #475569; }
        .status-diajukan { background: #EFF6FF; color: #1D4ED8; }
        .status-perlu_revisi { background: #FFFBEB; color: #B45309; }
        .status-lolos { background: #ECFDF5; color: #047857; }
        .status-tidak_lolos { background: #FEF2F2; color: #B91C1C; }
        .status-menunggu_persetujuan { background: #FEF3C7; color: #92400E; }
        .status-terjadwal { background: #E0F2FE; color: #0369A1; }
        .status-terbit { background: #ECFDF5; color: #047857; }
        .status-ditarik { background: #F3F4F6; color: #4B5563; }

        /* Forms */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            position: relative;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: var(--color-ink);
        }

        .req {
            color: #DC2626;
        }

        input[type="text"],
        input[type="email"],
        input[type="url"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            padding: 10px 13px;
            border: 1px solid var(--color-hairline);
            border-radius: 8px;
            font-size: 13.5px;
            color: var(--color-ink);
            background: #FFFFFF;
            outline: none;
            transition: border-color 0.16s ease, box-shadow 0.16s ease;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            padding-right: 36px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%235C6282' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
        }

        input:focus, select:focus, textarea:focus {
            border-color: var(--role-accent);
            box-shadow: 0 0 0 3px rgba(1, 125, 199, 0.12);
        }

        textarea {
            resize: vertical;
            min-height: 84px;
        }

        .field-hint {
            font-size: 12px;
            color: var(--color-muted);
        }

        /* Autocomplete Dropdown */
        .autocomplete-list {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            margin-top: 4px;
            background: #FFFFFF;
            border: 1px solid var(--color-hairline);
            border-radius: 10px;
            box-shadow: 0 12px 28px rgba(43, 43, 94, 0.14);
            max-height: 240px;
            overflow-y: auto;
            z-index: 60;
            display: none;
        }

        .autocomplete-list.open {
            display: block;
        }

        .autocomplete-item {
            padding: 10px 13px;
            cursor: pointer;
            border-bottom: 1px solid #F1EFF7;
            font-size: 13px;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-item:hover {
            background: var(--role-accent-soft);
        }

        .autocomplete-item strong {
            display: block;
            color: var(--color-primary);
        }

        .autocomplete-item span {
            font-size: 11.5px;
            color: var(--color-muted);
        }

        /* Work Card & Sub-tabs */
        .work-box {
            border: 1px solid var(--color-hairline);
            border-radius: 12px;
            padding: 18px 20px;
            background: #FFFFFF;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .work-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .work-meta {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 12px;
            color: var(--color-muted);
        }

        .member-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 8px;
            background: var(--color-surface);
            border: 1px solid var(--color-hairline);
            font-size: 13px;
        }

        .timeline-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .timeline-item {
            padding: 12px 14px;
            border-left: 3px solid var(--role-accent);
            background: var(--color-surface);
            border-radius: 0 8px 8px 0;
            font-size: 13px;
        }

        .dev2-box {
            border: 1px dashed #CBD5E1;
            background: #F8FAFC;
            border-radius: 12px;
            padding: 20px;
        }

        .dev2-tag {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 4px 10px;
            border-radius: 999px;
            background: #E2E8F0;
            color: #334155;
            margin-bottom: 10px;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th, td {
            padding: 11px 12px;
            text-align: left;
            border-bottom: 1px solid var(--color-hairline);
        }

        th {
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--color-muted);
            background: var(--color-surface);
        }

        .collapse-panel {
            display: none;
            margin-top: 8px;
            padding-top: 14px;
            border-top: 1px solid var(--color-hairline);
        }

        .collapse-panel.open {
            display: block;
        }

        @media (max-width: 980px) {
            .app-shell {
                grid-template-columns: 1fr;
                padding: 16px;
            }
            .sidebar {
                position: static;
                display: none;
            }
            .sidebar.mobile-open {
                display: block;
            }
            .mobile-menu-toggle {
                display: inline-flex;
            }
            .kpi-grid, .two-col-grid, .three-col-grid, .form-grid, .info-kv {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
@php
    $portalRole = match (true) {
        $isAdminRole => 'admin',
        $isPicKampus => 'pic-kampus',
        $isTimMahasiswa => 'tim-mahasiswa',
        $isPicSekolah => 'pic-sekolah',
        $isPicIndustri => 'pic-industri',
        $isPicSponsor => 'pic-sponsor',
        $isJuri => 'juri',
        default => 'pengunjung',
    };

    $roleMeta = match ($portalRole) {
        'admin' => [
            'label' => 'Panitia Admin',
            'portalTitle' => 'Akses Panitia Admin',
            'subtitle' => 'Gunakan panel Filament untuk pengelolaan operasional panitia, kurasi, dan publikasi resmi.',
            'defaultTab' => 'ringkasan',
        ],
        'pic-kampus' => [
            'label' => 'PIC Kampus',
            'portalTitle' => 'Portal Koordinator Kampus',
            'subtitle' => 'Kelola profil institusi, anggota tim pengusul, dokumen proposal, revisi kurasi, dan pengajuan publikasi (maksimal 2 karya per edisi festival).',
            'defaultTab' => 'ringkasan',
        ],
        'tim-mahasiswa' => [
            'label' => 'Tim Mahasiswa',
            'portalTitle' => 'Portal Tim Karya Mahasiswa',
            'subtitle' => 'Kelola karya inovasi yang ditugaskan kepada tim Anda, lengkapi anggota, unggah berkas, pantau catatan kurasi, dan ajukan showcase.',
            'defaultTab' => 'ringkasan',
        ],
        'pic-sekolah' => [
            'label' => 'PIC Sekolah',
            'portalTitle' => 'Portal Sekolah dan Tur Edukasi',
            'subtitle' => 'Ruang khusus koordinator sekolah untuk persiapan data sekolah, rombongan siswa, dan jadwal slot tur kunjungan pameran.',
            'defaultTab' => 'ringkasan',
        ],
        'pic-industri' => [
            'label' => 'PIC Industri / Mitra',
            'portalTitle' => 'Portal Organisasi Industri dan Mitra',
            'subtitle' => 'Pantau karya inovasi terpublikasi yang sesuai kebutuhan kolaborasi industri serta persiapan modul kemitraan.',
            'defaultTab' => 'ringkasan',
        ],
        'pic-sponsor' => [
            'label' => 'PIC Sponsor',
            'portalTitle' => 'Portal Kemitraan dan Sponsor Resmi',
            'subtitle' => 'Tinjau showcase inovasi festival serta persiapan ruang pengelolaan kesepakatan, materi sponsor, dan pemenuhan manfaat.',
            'defaultTab' => 'ringkasan',
        ],
        'juri' => [
            'label' => 'Dewan Juri',
            'portalTitle' => 'Portal Dewan Juri',
            'subtitle' => 'Daftar karya yang telah lolos kurasi tahap awal untuk referensi penilaian serta ruang penugasan juri.',
            'defaultTab' => 'ringkasan',
        ],
        default => [
            'label' => 'Pengunjung',
            'portalTitle' => 'Portal Pengunjung Festival',
            'subtitle' => 'Jelajahi showcase karya inovasi terpublikasi, informasi acara resmi, atau daftarkan diri sebagai peserta kompetisi bila memenuhi syarat.',
            'defaultTab' => 'ringkasan',
        ],
    };

    $requestedTab = old('redirect_tab', request()->query('tab'));
    $allowedTabsByRole = match ($portalRole) {
        'pic-kampus', 'tim-mahasiswa' => ['ringkasan', 'profil', 'karya', 'dokumen', 'kurasi', 'dev2_modul'],
        'pic-sekolah' => ['ringkasan', 'profil_sekolah', 'rombongan', 'slot_tur'],
        'pic-industri' => ['ringkasan', 'showcase_katalog', 'organisasi_mitra', 'pertemuan_booth'],
        'pic-sponsor' => ['ringkasan', 'kesepakatan', 'tagihan_materi', 'showcase_sponsor'],
        'juri' => ['ringkasan', 'daftar_karya', 'penilaian_juri'],
        'pengunjung' => ['ringkasan', 'showcase', 'tiket_aktivitas', 'daftar_peserta'],
        default => ['ringkasan'],
    };

    $activeTab = in_array($requestedTab, $allowedTabsByRole, true) ? $requestedTab : $roleMeta['defaultTab'];
    if (! $requestedTab && $errors->any() && in_array($portalRole, ['pic-kampus', 'tim-mahasiswa'], true)) {
        if ($errors->has('peran') || $errors->hasAny(['judul', 'kategori', 'teknologi', 'deskripsi'])) {
            $activeTab = 'karya';
        } elseif ($errors->has('file')) {
            $activeTab = 'dokumen';
        } elseif ($errors->hasAny(['institusi', 'nomor_identitas', 'nama_lengkap'])) {
            $activeTab = 'profil';
        }
    }

    $peserta = $user->peserta;
    $statusLabels = [
        'draft' => 'Draft',
        'diajukan' => 'Diajukan ke Kurasi',
        'perlu_revisi' => 'Perlu Revisi',
        'lolos' => 'Lolos Kurasi',
        'tidak_lolos' => 'Tidak Lolos',
    ];
    $pubStatusLabels = [
        'draft' => 'Draft Publikasi',
        'menunggu_persetujuan' => 'Menunggu Persetujuan',
        'terjadwal' => 'Terjadwal Terbit',
        'terbit' => 'Terbit di Showcase',
        'ditarik' => 'Ditarik',
    ];
    $workCategories = $activeFestival?->kategoris?->pluck('nama')->filter()->values()
        ?? collect([
            'Artificial Intelligence & Big Data',
            'IoT, Robotics & Embedded Systems',
            'Web, Mobile & Cloud Computing',
            'Cybersecurity & Digital Forensics',
            'UI/UX, Game & Digital Media',
            'Sistem Informasi & Enterprise Software',
        ]);
    $quotaRemaining = max(0, $maxWorksQuota - $institutionActiveWorksCount);
@endphp
<body class="theme-{{ $portalRole }}">
    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand-group">
                <button type="button" class="logout-btn mobile-menu-toggle" onclick="toggleMobileSidebar()">
                    Menu
                </button>
                <div class="brand-mark" aria-hidden="true">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 2 7 12 12 22 7 12 2"></polygon>
                        <polyline points="2 17 12 22 22 17"></polyline>
                        <polyline points="2 12 12 17 22 12"></polyline>
                    </svg>
                </div>
                <div>
                    <div class="brand-title">FUTU<span>RiSE</span></div>
                    <div class="brand-sub">APTIKOM Innovation Festival Portal</div>
                </div>
            </div>

            <div class="topbar-right">
                @if ($activeFestival)
                    <span class="festival-pill">{{ $activeFestival->nama }}</span>
                @endif
                <span class="role-pill">{{ $roleMeta['label'] }}</span>
                <div class="user-chip">
                    <strong>{{ $user->name }}</strong>
                    <span>{{ $user->email }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <div class="app-shell">
        <aside class="sidebar" id="portalSidebar">
            <div class="sidebar-role-card">
                <div class="sidebar-role-tag">Peran Aktif</div>
                <div class="sidebar-role-title">{{ $roleMeta['portalTitle'] }}</div>
            </div>

            @if ($portalRole === 'pic-kampus' || $portalRole === 'tim-mahasiswa')
                <div class="sidebar-section-label">Menu Utama (Dev 1)</div>
                <nav class="nav-list">
                    <button type="button" class="nav-item {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-tab-btn="ringkasan">
                        <span class="nav-item-left">Dashboard</span>
                        <span class="nav-badge">{{ $institutionActiveWorksCount }}/{{ $maxWorksQuota }}</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'profil' ? 'active' : '' }}" data-tab-btn="profil">
                        <span class="nav-item-left">{{ $isPicKampus ? 'Profil Kampus' : 'Profil Peserta' }}</span>
                        <span class="nav-badge">{{ $peserta ? 'Aktif' : 'Belum' }}</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'karya' ? 'active' : '' }}" data-tab-btn="karya">
                        <span class="nav-item-left">{{ $isPicKampus ? 'Tim dan Karya Kampus' : 'Karya Ditugaskan' }}</span>
                        <span class="nav-badge">{{ $managedWorks->count() }}</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'dokumen' ? 'active' : '' }}" data-tab-btn="dokumen">
                        <span class="nav-item-left">Dokumen dan Media</span>
                        <span class="nav-badge">{{ $managedWorks->sum(fn ($w) => $w->files->count()) }}</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'kurasi' ? 'active' : '' }}" data-tab-btn="kurasi">
                        <span class="nav-item-left">Hasil Kurasi dan Showcase</span>
                        <span class="nav-badge">{{ $managedWorks->where('status', 'lolos')->count() }}</span>
                    </button>
                </nav>

                <div class="sidebar-section-label">Operasional Lanjutan</div>
                <nav class="nav-list">
                    <button type="button" class="nav-item {{ $activeTab === 'dev2_modul' ? 'active' : '' }}" data-tab-btn="dev2_modul">
                        <span class="nav-item-left">Booth, Jadwal &amp; Tagihan</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                </nav>
            @elseif ($portalRole === 'pic-sekolah')
                <div class="sidebar-section-label">Menu PIC Sekolah</div>
                <nav class="nav-list">
                    <button type="button" class="nav-item {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-tab-btn="ringkasan">
                        <span class="nav-item-left">Ringkasan Sekolah</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'profil_sekolah' ? 'active' : '' }}" data-tab-btn="profil_sekolah">
                        <span class="nav-item-left">Profil Sekolah</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'rombongan' ? 'active' : '' }}" data-tab-btn="rombongan">
                        <span class="nav-item-left">Rombongan &amp; Pendamping</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'slot_tur' ? 'active' : '' }}" data-tab-btn="slot_tur">
                        <span class="nav-item-left">Slot Tur &amp; Arahan</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                </nav>
            @elseif ($portalRole === 'pic-industri')
                <div class="sidebar-section-label">Menu Industri &amp; Mitra</div>
                <nav class="nav-list">
                    <button type="button" class="nav-item {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-tab-btn="ringkasan">
                        <span class="nav-item-left">Ringkasan Mitra</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'showcase_katalog' ? 'active' : '' }}" data-tab-btn="showcase_katalog">
                        <span class="nav-item-left">Katalog Inovasi</span>
                        <span class="nav-badge">{{ $publishedWorks->count() }}</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'organisasi_mitra' ? 'active' : '' }}" data-tab-btn="organisasi_mitra">
                        <span class="nav-item-left">Profil Organisasi &amp; Kebutuhan</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'pertemuan_booth' ? 'active' : '' }}" data-tab-btn="pertemuan_booth">
                        <span class="nav-item-left">Booth, Pertemuan &amp; Tagihan</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                </nav>
            @elseif ($portalRole === 'pic-sponsor')
                <div class="sidebar-section-label">Menu Sponsor</div>
                <nav class="nav-list">
                    <button type="button" class="nav-item {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-tab-btn="ringkasan">
                        <span class="nav-item-left">Ringkasan Sponsor</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'kesepakatan' ? 'active' : '' }}" data-tab-btn="kesepakatan">
                        <span class="nav-item-left">Kesepakatan &amp; Paket</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'tagihan_materi' ? 'active' : '' }}" data-tab-btn="tagihan_materi">
                        <span class="nav-item-left">Tagihan, Materi &amp; Manfaat</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'showcase_sponsor' ? 'active' : '' }}" data-tab-btn="showcase_sponsor">
                        <span class="nav-item-left">Showcase Karya Pilihan</span>
                        <span class="nav-badge">{{ $publishedWorks->count() }}</span>
                    </button>
                </nav>
            @elseif ($portalRole === 'juri')
                <div class="sidebar-section-label">Menu Dewan Juri</div>
                <nav class="nav-list">
                    <button type="button" class="nav-item {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-tab-btn="ringkasan">
                        <span class="nav-item-left">Ringkasan Juri</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'daftar_karya' ? 'active' : '' }}" data-tab-btn="daftar_karya">
                        <span class="nav-item-left">Karya Lolos Kurasi</span>
                        <span class="nav-badge">{{ $curatedWorks->count() }}</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'penilaian_juri' ? 'active' : '' }}" data-tab-btn="penilaian_juri">
                        <span class="nav-item-left">Rubrik &amp; Skor Penilaian</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                </nav>
            @elseif ($portalRole === 'pengunjung')
                <div class="sidebar-section-label">Menu Pengunjung</div>
                <nav class="nav-list">
                    <button type="button" class="nav-item {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-tab-btn="ringkasan">
                        <span class="nav-item-left">Beranda Pengunjung</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'showcase' ? 'active' : '' }}" data-tab-btn="showcase">
                        <span class="nav-item-left">Showcase Karya</span>
                        <span class="nav-badge">{{ $publishedWorks->count() }}</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'tiket_aktivitas' ? 'active' : '' }}" data-tab-btn="tiket_aktivitas">
                        <span class="nav-item-left">Tiket QR, Voting &amp; Survei</span>
                        <span class="nav-badge nav-badge-dev2">Dev 2</span>
                    </button>
                    <button type="button" class="nav-item {{ $activeTab === 'daftar_peserta' ? 'active' : '' }}" data-tab-btn="daftar_peserta">
                        <span class="nav-item-left">Pendaftaran Peserta Lomba</span>
                    </button>
                </nav>
            @endif
        </aside>

        <main class="main-col">
            {{-- Role Hero Header --}}
            <section class="hero-banner">
                <div>
                    <div class="hero-eyebrow">{{ $roleMeta['label'] }} @if($activeFestival) &bull; {{ $activeFestival->tema ?? $activeFestival->nama }} @endif</div>
                    <h1 class="hero-title">{{ $roleMeta['portalTitle'] }}</h1>
                    <p class="hero-desc">{{ $roleMeta['subtitle'] }}</p>
                </div>

                @if ($portalRole === 'pic-kampus' || $portalRole === 'tim-mahasiswa')
                    <div class="hero-stat-box">
                        <div class="hero-stat-label">Kuota Karya Institusi</div>
                        <div class="hero-stat-value">{{ $institutionActiveWorksCount }} / {{ $maxWorksQuota }}</div>
                        <div class="hero-stat-sub">
                            {{ $peserta?->institusi ? $peserta->institusi : 'Lengkapi profil institusi' }}
                        </div>
                    </div>
                @elseif ($portalRole === 'juri')
                    <div class="hero-stat-box">
                        <div class="hero-stat-label">Karya Siap Dinilai</div>
                        <div class="hero-stat-value">{{ $curatedWorks->count() }}</div>
                        <div class="hero-stat-sub">Status Lolos Kurasi</div>
                    </div>
                @elseif ($portalRole === 'pic-sekolah')
                    <div class="hero-stat-box">
                        <div class="hero-stat-label">Status Akun Sekolah</div>
                        <div class="hero-stat-value">{{ strtoupper($user->account_status) }}</div>
                        <div class="hero-stat-sub">Kategori Kunjungan Tur</div>
                    </div>
                @elseif ($portalRole === 'pic-industri' || $portalRole === 'pic-sponsor')
                    <div class="hero-stat-box">
                        <div class="hero-stat-label">Karya di Showcase</div>
                        <div class="hero-stat-value">{{ $publishedWorks->count() }}</div>
                        <div class="hero-stat-sub">Terbit di Website Resmi</div>
                    </div>
                @elseif ($portalRole === 'pengunjung')
                    <div class="hero-stat-box">
                        <div class="hero-stat-label">Karya Terpublikasi</div>
                        <div class="hero-stat-value">{{ $publishedWorks->count() }}</div>
                        <div class="hero-stat-sub">Akses Terbuka Pengunjung</div>
                    </div>
                @endif
            </section>

            {{-- Global Session Alerts & Validation Errors --}}
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Periksa kembali isian formulir berikut:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ================================================================ --}}
            {{-- ADMIN REDIRECT BANNER                                            --}}
            {{-- ================================================================ --}}
            @if ($portalRole === 'admin')
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h2 class="card-title">Akun Panitia Terdeteksi</h2>
                            <p class="card-subtitle">Akun Anda memiliki otoritas panel panitia Filament untuk mengelola data operasional sesuai divisi.</p>
                        </div>
                        <a href="/admin" class="btn btn-primary">Buka Panel Admin Filament</a>
                    </div>
                    <div class="info-kv">
                        <div class="kv-item">
                            <div class="kv-label">Nama Pengguna</div>
                            <div class="kv-value">{{ $user->name }}</div>
                        </div>
                        <div class="kv-item">
                            <div class="kv-label">Peran Sistem</div>
                            <div class="kv-value">{{ $user->getRoleNames()->implode(', ') }}</div>
                        </div>
                    </div>
                </div>

            {{-- ================================================================ --}}
            {{-- PORTAL PIC KAMPUS & TIM MAHASISWA (DEV 1 FUNCTIONAL)             --}}
            {{-- ================================================================ --}}
            @elseif ($portalRole === 'pic-kampus' || $portalRole === 'tim-mahasiswa')

                {{-- TAB: RINGKASAN --}}
                <section class="panel-section {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-panel="ringkasan">
                    @if (! $peserta)
                        <div class="alert alert-warning">
                            <strong>Profil {{ $isPicKampus ? 'Kampus' : 'Peserta' }} Belum Dilengkapi.</strong>
                            Silakan buka menu <strong>{{ $isPicKampus ? 'Profil Kampus' : 'Profil Peserta' }}</strong> untuk mengisi nama institusi dan nomor identitas sebelum mendaftarkan tim serta karya inovasi.
                        </div>
                    @endif

                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <div class="kpi-label">Kuota Karya Aktif</div>
                            <div class="kpi-value">{{ $institutionActiveWorksCount }} / {{ $maxWorksQuota }}</div>
                            <div class="kpi-note">Sisa slot pengajuan: {{ $quotaRemaining }} karya</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-label">Draft &amp; Revisi</div>
                            <div class="kpi-value">{{ $managedWorks->whereIn('status', ['draft', 'perlu_revisi'])->count() }}</div>
                            <div class="kpi-note">Masih dapat disunting tim</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-label">Dalam Kurasi / Lolos</div>
                            <div class="kpi-value">{{ $managedWorks->whereIn('status', ['diajukan', 'lolos'])->count() }}</div>
                            <div class="kpi-note">{{ $managedWorks->where('status', 'lolos')->count() }} karya lolos kurasi</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-label">Total Lampiran</div>
                            <div class="kpi-value">{{ $managedWorks->sum(fn ($w) => $w->files->count()) }}</div>
                            <div class="kpi-note">Proposal, demo, gambar &amp; pendukung</div>
                        </div>
                    </div>

                    <div class="two-col-grid">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h2 class="card-title">Identitas {{ $isPicKampus ? 'Koordinator Kampus' : 'Ketua / Anggota Tim' }}</h2>
                                    <p class="card-subtitle">Data utama yang tertaut dengan kuota pengajuan institusi pada festival aktif.</p>
                                </div>
                                <button type="button" class="btn btn-outline btn-sm" onclick="switchPortalTab('profil')">Kelola Profil</button>
                            </div>
                            <div class="info-kv">
                                <div class="kv-item">
                                    <div class="kv-label">Nama Lengkap</div>
                                    <div class="kv-value">{{ $peserta?->nama_lengkap ?? $user->name }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Institusi / Kampus</div>
                                    <div class="kv-value">{{ $peserta?->institusi ?? 'Belum diisi' }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Nomor Identitas</div>
                                    <div class="kv-value">{{ $peserta?->nomor_identitas ?? 'Belum diisi' }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Nomor Telepon / WA</div>
                                    <div class="kv-value">{{ $peserta?->no_telepon ?? $user->person?->phone ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h2 class="card-title">Status &amp; Batas Periode Festival</h2>
                                    <p class="card-subtitle">{{ $activeFestival?->nama ?? 'Belum ada festival aktif' }}</p>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" onclick="switchPortalTab('karya')">Kelola Karya</button>
                            </div>
                            @if ($activeFestival && $activeFestival->periodes->isNotEmpty())
                                <div class="timeline-list">
                                    @foreach ($activeFestival->periodes->take(4) as $periode)
                                        <div class="timeline-item">
                                            <strong>{{ $periode->tahap }}</strong>
                                            <div class="field-hint">
                                                {{ $periode->mulai?->format('d M Y') }} &ndash; {{ $periode->selesai?->format('d M Y') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="card-subtitle">Jadwal lini masa periode submisi dan kurasi akan tampil setelah ditetapkan oleh panitia.</p>
                            @endif
                        </div>
                    </div>
                </section>

                {{-- TAB: PROFIL KAMPUS / PESERTA --}}
                <section class="panel-section {{ $activeTab === 'profil' ? 'active' : '' }}" data-panel="profil">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">{{ $isPicKampus ? 'Profil Institusi Kampus' : 'Profil Peserta dan Ketua Tim' }}</h2>
                                <p class="card-subtitle">
                                    @if ($peserta)
                                        Perbarui nama penanggung jawab, kontak aktif, nama kampus terverifikasi, dan nomor identitas.
                                    @else
                                        Lengkapi data institusi dan identitas terlebih dahulu untuk mengaktifkan pendaftaran tim dan karya inovasi.
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if (! $peserta)
                            <form method="POST" action="{{ route('participant.register') }}">
                                @csrf
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="jenis_peserta_reg">Kategori Pendaftar <span class="req">*</span></label>
                                        <select name="jenis_peserta" id="jenis_peserta_reg" required>
                                            @if ($isPicKampus)
                                                <option value="pic" selected>PIC / Koordinator Kampus</option>
                                                <option value="mahasiswa">Mahasiswa Pengusul</option>
                                            @else
                                                <option value="mahasiswa" selected>Mahasiswa / Tim Perguruan Tinggi</option>
                                                <option value="siswa_smk">Siswa SMK / Sederajat</option>
                                                <option value="umum">Inovator Umum</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nomor_identitas_reg">{{ $isPicKampus ? 'NIDN / NIP / Kode PIC' : 'NIM / NISN / NIK' }} <span class="req">*</span></label>
                                        <input type="text" name="nomor_identitas" id="nomor_identitas_reg" value="{{ old('nomor_identitas') }}" placeholder="Contoh: 0412058801 atau 152026001" required>
                                    </div>
                                    <div class="form-group full">
                                        <label for="institusi_input_profil">Asal Kampus / Institusi Resmi <span class="req">*</span></label>
                                        <input type="text" name="institusi" id="institusi_input_profil" class="js-institusi-autocomplete" data-list="institusiListProfil" value="{{ old('institusi') }}" placeholder="Ketik minimal 2 huruf nama perguruan tinggi..." autocomplete="off" required>
                                        <div class="autocomplete-list" id="institusiListProfil"></div>
                                    </div>
                                    <div class="form-group full">
                                        <label for="no_telepon_reg">Nomor Telepon / WhatsApp Aktif</label>
                                        <input type="text" name="no_telepon" id="no_telepon_reg" value="{{ old('no_telepon', $user->person?->phone) }}" placeholder="Contoh: 081234567890">
                                    </div>
                                </div>
                                <div style="margin-top: 18px;">
                                    <button type="submit" class="btn btn-primary">Simpan &amp; Aktifkan Profil</button>
                                </div>
                            </form>
                        @else
                            <form method="POST" action="{{ route('participant.profile.update') }}">
                                @csrf
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="nama_lengkap_upd">Nama Lengkap Penanggung Jawab <span class="req">*</span></label>
                                        <input type="text" name="nama_lengkap" id="nama_lengkap_upd" value="{{ old('nama_lengkap', $peserta->nama_lengkap) }}" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_telepon_upd">Nomor Telepon / WhatsApp</label>
                                        <input type="text" name="no_telepon" id="no_telepon_upd" value="{{ old('no_telepon', $peserta->no_telepon) }}" placeholder="08xxxxxxxxxx">
                                    </div>
                                    <div class="form-group">
                                        <label for="institusi_input_upd">Asal Kampus / Institusi <span class="req">*</span></label>
                                        <input type="text" name="institusi" id="institusi_input_upd" class="js-institusi-autocomplete" data-list="institusiListUpdate" value="{{ old('institusi', $peserta->institusi) }}" autocomplete="off" required>
                                        <div class="autocomplete-list" id="institusiListUpdate"></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="nomor_identitas_upd">Nomor Identitas ({{ strtoupper($peserta->jenis_peserta) }}) <span class="req">*</span></label>
                                        <input type="text" name="nomor_identitas" id="nomor_identitas_upd" value="{{ old('nomor_identitas', $peserta->nomor_identitas) }}" required>
                                    </div>
                                </div>
                                <div style="margin-top: 18px;">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan Profil</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </section>

                {{-- TAB: TIM & KARYA --}}
                <section class="panel-section {{ $activeTab === 'karya' ? 'active' : '' }}" data-panel="karya">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Karya yang Didaftarkan ({{ $managedWorks->count() }})</h2>
                                <p class="card-subtitle">
                                    @if ($isPicKampus)
                                        Menampilkan seluruh karya yang tertaut dengan {{ $peserta?->institusi ?? 'kampus Anda' }}.
                                    @else
                                        Tim mahasiswa hanya dapat menyunting karya yang dipimpin atau ditugaskan kepada akun Anda.
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if ($managedWorks->isEmpty())
                            <div class="alert alert-info">
                                Belum ada karya yang terdaftar pada portal ini. Gunakan formulir Pendaftaran Karya Inovasi Baru di bawah setelah melengkapi profil.
                            </div>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 16px;">
                                @foreach ($managedWorks as $karya)
                                    @php
                                        $canEditWork = in_array($karya->status, ['draft', 'perlu_revisi'], true);
                                    @endphp
                                    <div class="work-box">
                                        <div class="work-head">
                                            <div>
                                                <div style="font-size: 16px; font-weight: 700; color: var(--color-primary); margin-bottom: 4px;">
                                                    {{ $karya->judul }}
                                                </div>
                                                <div class="work-meta">
                                                    <span>Kategori: <strong>{{ $karya->kategori }}</strong></span>
                                                    <span>Teknologi: <strong>{{ $karya->teknologi }}</strong></span>
                                                    <span>Pengusul: <strong>{{ $karya->submitter?->nama_lengkap ?? '-' }}</strong></span>
                                                </div>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                                <span class="status-badge status-{{ $karya->status }}">
                                                    {{ $statusLabels[$karya->status] ?? $karya->status }}
                                                </span>
                                                @if ($canEditWork)
                                                    <button type="button" class="btn btn-outline btn-sm" onclick="toggleCollapse('edit-work-{{ $karya->id }}')">Edit Karya</button>
                                                    <button type="button" class="btn btn-outline btn-sm" onclick="toggleCollapse('team-work-{{ $karya->id }}')">Anggota Tim ({{ $karya->anggotaTims->count() }})</button>
                                                    <form method="POST" action="{{ route('karya.submit', $karya->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="redirect_tab" value="karya">
                                                        <button type="submit" class="btn btn-primary btn-sm">Ajukan Kurasi</button>
                                                    </form>
                                                    @if ($karya->status === 'draft')
                                                        <form method="POST" action="{{ route('karya.destroy', $karya->id) }}">
                                                            @csrf
                                                            <input type="hidden" name="redirect_tab" value="karya">
                                                            <button type="submit" class="btn btn-danger-outline btn-sm">Hapus Draft</button>
                                                        </form>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        <p style="font-size: 13px; color: var(--color-muted); line-height: 1.55;">
                                            {{ $karya->deskripsi }}
                                        </p>

                                        @if ($karya->catatan_kurasi)
                                            <div class="alert alert-warning" style="padding: 10px 14px;">
                                                <strong>Catatan Kurator Terbaru:</strong> {{ $karya->catatan_kurasi }}
                                            </div>
                                        @endif

                                        {{-- Panel Kelola Anggota Tim --}}
                                        <div class="collapse-panel {{ $canEditWork ? '' : '' }}" id="team-work-{{ $karya->id }}">
                                            <div style="font-size: 13.5px; font-weight: 700; color: var(--color-primary); margin-bottom: 10px;">
                                                Daftar Anggota Tim Karya
                                            </div>
                                            <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 14px;">
                                                <div class="member-row">
                                                    <div>
                                                        <strong>{{ $karya->submitter?->nama_lengkap ?? $user->name }}</strong>
                                                        <span class="field-hint">&mdash; Ketua Tim ({{ $karya->submitter?->institusi ?? '-' }})</span>
                                                    </div>
                                                    <span class="status-badge status-lolos">Ketua</span>
                                                </div>
                                                @foreach ($karya->anggotaTims as $member)
                                                    <div class="member-row">
                                                        <div>
                                                            <strong>{{ $member->nama_lengkap }}</strong>
                                                            <span class="field-hint">&mdash; {{ $member->peran }} @if($member->email) ({{ $member->email }}) @endif</span>
                                                        </div>
                                                        @if ($canEditWork && ! $member->is_ketua)
                                                            <form method="POST" action="{{ route('karya.members.destroy', [$karya->id, $member->id]) }}">
                                                                @csrf
                                                                <input type="hidden" name="redirect_tab" value="karya">
                                                                <button type="submit" class="btn btn-danger-outline btn-sm">Hapus</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if ($canEditWork)
                                                <form method="POST" action="{{ route('karya.members.store', $karya->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="redirect_tab" value="karya">
                                                    <div class="form-grid">
                                                        <div class="form-group">
                                                            <label>Nama Anggota Baru <span class="req">*</span></label>
                                                            <input type="text" name="nama_lengkap" required placeholder="Nama lengkap mahasiswa">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Peran di Tim <span class="req">*</span></label>
                                                            <input type="text" name="peran" required placeholder="Contoh: Backend Developer / UI Designer">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Email Anggota</label>
                                                            <input type="email" name="email" placeholder="nama@kampus.ac.id">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>NIM / Nomor Identitas</label>
                                                            <input type="text" name="nomor_identitas" placeholder="Opsional">
                                                        </div>
                                                    </div>
                                                    <div style="margin-top: 10px;">
                                                        <button type="submit" class="btn btn-navy btn-sm">Tambah Anggota Tim</button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>

                                        {{-- Panel Edit Detail Karya --}}
                                        @if ($canEditWork)
                                            <div class="collapse-panel" id="edit-work-{{ $karya->id }}">
                                                <form method="POST" action="{{ route('karya.update', $karya->id) }}">
                                                    @csrf
                                                    <input type="hidden" name="redirect_tab" value="karya">
                                                    <div class="form-grid">
                                                        <div class="form-group full">
                                                            <label>Judul Karya <span class="req">*</span></label>
                                                            <input type="text" name="judul" value="{{ $karya->judul }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Kategori <span class="req">*</span></label>
                                                            <select name="kategori" required>
                                                                @foreach ($workCategories as $cat)
                                                                    <option value="{{ $cat }}" {{ $karya->kategori === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Teknologi / Stack <span class="req">*</span></label>
                                                            <input type="text" name="teknologi" value="{{ $karya->teknologi }}" required>
                                                        </div>
                                                        <div class="form-group full">
                                                            <label>Ringkasan Karya <span class="req">*</span></label>
                                                            <textarea name="deskripsi" required>{{ $karya->deskripsi }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Masalah yang Diangkat</label>
                                                            <textarea name="masalah">{{ $karya->masalah }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Solusi yang Ditawarkan</label>
                                                            <textarea name="solusi">{{ $karya->solusi }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Pengguna Sasaran</label>
                                                            <input type="text" name="pengguna_sasaran" value="{{ $karya->pengguna_sasaran }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Tahap Kesiapan</label>
                                                            <input type="text" name="tahap_kesiapan" value="{{ $karya->tahap_kesiapan }}" placeholder="Contoh: Prototipe / Pilot">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Fitur Utama</label>
                                                            <textarea name="fitur_utama">{{ $karya->fitur_utama }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Hasil Uji</label>
                                                            <textarea name="hasil_uji">{{ $karya->hasil_uji }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Keterbatasan Karya</label>
                                                            <textarea name="keterbatasan">{{ $karya->keterbatasan }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Kebutuhan Kolaborasi</label>
                                                            <textarea name="kebutuhan_kolaborasi">{{ $karya->kebutuhan_kolaborasi }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Tautan Demo / Repositori</label>
                                                            <input type="url" name="url_demo" value="{{ $karya->url_demo }}" placeholder="https://...">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Catatan Pembaruan Versi</label>
                                                            <input type="text" name="catatan_perbaikan" placeholder="Ringkasan perubahan yang dilakukan">
                                                        </div>
                                                    </div>
                                                    <div style="margin-top: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                                        <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 500;">
                                                            <input type="checkbox" name="persetujuan_publikasi" value="1" {{ $karya->persetujuan_publikasi ? 'checked' : '' }}>
                                                            Izinkan kurasi &amp; publikasi di Showcase jika lolos
                                                        </label>
                                                        <button type="submit" class="btn btn-primary">Simpan Perubahan Karya</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Form Pendaftaran Karya Inovasi Baru --}}
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Pendaftaran Karya Inovasi Baru</h2>
                                <p class="card-subtitle">
                                    Kuota aktif institusi: <strong>{{ $institutionActiveWorksCount }} dari {{ $maxWorksQuota }} karya</strong>.
                                </p>
                            </div>
                        </div>

                        @if (! $peserta)
                            <div class="alert alert-warning">
                                Silakan lengkapi profil pada menu <strong>{{ $isPicKampus ? 'Profil Kampus' : 'Profil Peserta' }}</strong> terlebih dahulu sebelum mendaftarkan karya baru.
                            </div>
                        @elseif ($quotaRemaining <= 0)
                            <div class="alert alert-warning">
                                Kuota maksimal {{ $maxWorksQuota }} karya aktif untuk institusi <strong>{{ $peserta->institusi }}</strong> pada edisi festival ini telah tercapai.
                            </div>
                        @else
                            <form method="POST" action="{{ route('karya.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="redirect_tab" value="karya">
                                <div class="form-grid">
                                    <div class="form-group full">
                                        <label for="judul">Judul Karya Inovasi <span class="req">*</span></label>
                                        <input type="text" name="judul" id="judul" value="{{ old('judul') }}" placeholder="Contoh: Sistem Pemantauan Kualitas Air Berbasis Sensor LoRa" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kategori">Bidang / Kategori <span class="req">*</span></label>
                                        <select name="kategori" id="kategori" required>
                                            <option value="">-- Pilih Bidang Karya --</option>
                                            @foreach ($workCategories as $cat)
                                                <option value="{{ $cat }}" {{ old('kategori') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="teknologi">Teknologi Utama <span class="req">*</span></label>
                                        <input type="text" name="teknologi" id="teknologi" value="{{ old('teknologi') }}" placeholder="Contoh: Python, TensorFlow, Laravel, ESP32" required>
                                    </div>
                                    <div class="form-group full">
                                        <label for="deskripsi">Deskripsi Singkat Karya <span class="req">*</span></label>
                                        <textarea name="deskripsi" id="deskripsi" placeholder="Jelaskan ringkasan konsep inovasi dan manfaat utama karya..." required>{{ old('deskripsi') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="masalah">Masalah yang Diselesaikan</label>
                                        <textarea name="masalah" id="masalah" placeholder="Latar belakang persoalan di lapangan...">{{ old('masalah') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="solusi">Solusi yang Ditawarkan</label>
                                        <textarea name="solusi" id="solusi" placeholder="Pendekatan dan mekanisme kerja solusi...">{{ old('solusi') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="pengguna_sasaran">Pengguna Sasaran</label>
                                        <input type="text" name="pengguna_sasaran" id="pengguna_sasaran" value="{{ old('pengguna_sasaran') }}" placeholder="Contoh: Puskesmas, UMKM Kuliner, Dinas Lingkungan">
                                    </div>
                                    <div class="form-group">
                                        <label for="tahap_kesiapan">Tahap Kesiapan</label>
                                        <input type="text" name="tahap_kesiapan" id="tahap_kesiapan" value="{{ old('tahap_kesiapan') }}" placeholder="Contoh: Prototipe Teruji / Uji Lapangan">
                                    </div>
                                    <div class="form-group">
                                        <label for="fitur_utama">Fitur Utama</label>
                                        <textarea name="fitur_utama" id="fitur_utama">{{ old('fitur_utama') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="hasil_uji">Hasil Uji Awal</label>
                                        <textarea name="hasil_uji" id="hasil_uji">{{ old('hasil_uji') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterbatasan">Keterbatasan Karya</label>
                                        <textarea name="keterbatasan" id="keterbatasan">{{ old('keterbatasan') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="kebutuhan_kolaborasi">Kebutuhan Kolaborasi Industri</label>
                                        <textarea name="kebutuhan_kolaborasi" id="kebutuhan_kolaborasi">{{ old('kebutuhan_kolaborasi') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="url_demo">Tautan Demo / Video</label>
                                        <input type="url" name="url_demo" id="url_demo" value="{{ old('url_demo') }}" placeholder="https://...">
                                    </div>
                                    <div class="form-group">
                                        <label for="proposal">Proposal PDF (Opsional, Maks 20 MB)</label>
                                        <input type="file" name="proposal" id="proposal" accept=".pdf">
                                    </div>

                                    <div class="form-group full">
                                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;">
                                            <label>Anggota Tim Pendamping (Opsional)</label>
                                            <button type="button" class="btn btn-outline btn-sm" onclick="addTeamRow()">Tambah Baris Anggota</button>
                                        </div>
                                        <div id="teamRowsContainer" style="display: flex; flex-direction: column; gap: 8px;">
                                            <div class="two-col-grid" style="grid-template-columns: 1fr 1fr 1fr;">
                                                <input type="text" name="anggota_nama[]" placeholder="Nama lengkap anggota">
                                                <input type="text" name="anggota_peran[]" placeholder="Peran (mis. Frontend)">
                                                <input type="email" name="anggota_email[]" placeholder="Email anggota">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group full">
                                        <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 500;">
                                            <input type="checkbox" name="persetujuan_publikasi" value="1" checked>
                                            Bersedia menampilkan profil ringkas karya di Showcase publik apabila lolos kurasi.
                                        </label>
                                    </div>
                                </div>

                                <div style="margin-top: 18px; display: flex; gap: 10px; flex-wrap: wrap;">
                                    <button type="submit" name="action_type" value="draft" class="btn btn-outline">Simpan Sebagai Draft</button>
                                    <button type="submit" name="action_type" value="submit" class="btn btn-primary">Simpan &amp; Ajukan Kurasi</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </section>

                {{-- TAB: DOKUMEN & BERKAS --}}
                <section class="panel-section {{ $activeTab === 'dokumen' ? 'active' : '' }}" data-panel="dokumen">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Unggah Berkas &amp; Media Pendukung Karya</h2>
                                <p class="card-subtitle">Unggah proposal PDF, thumbnail poster, tangkapan layar aplikasi, video demo, atau dokumen teknis (maksimal 25 MB).</p>
                            </div>
                        </div>

                        @if ($managedWorks->isEmpty())
                            <div class="alert alert-info">Anda perlu mendaftarkan minimal satu karya terlebih dahulu sebelum mengunggah dokumen pendukung.</div>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 18px;">
                                @foreach ($managedWorks as $karya)
                                    <div class="work-box">
                                        <div class="work-head">
                                            <div>
                                                <strong>{{ $karya->judul }}</strong>
                                                <div class="field-hint">{{ $karya->files->count() }} berkas tersimpan</div>
                                            </div>
                                            <span class="status-badge status-{{ $karya->status }}">{{ $statusLabels[$karya->status] ?? $karya->status }}</span>
                                        </div>

                                        @if ($karya->files->isNotEmpty())
                                            <div class="table-wrap">
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Berkas</th>
                                                            <th>Jenis</th>
                                                            <th>Ukuran</th>
                                                            <th>Keterangan</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($karya->files as $file)
                                                            <tr>
                                                                <td><strong>{{ $file->nama_asli }}</strong></td>
                                                                <td>{{ ucfirst($file->jenis) }}</td>
                                                                <td>{{ number_format(($file->ukuran_byte ?? 0) / 1024, 1) }} KB</td>
                                                                <td>{{ $file->keterangan ?? '-' }}</td>
                                                                <td>
                                                                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                                                                        @if ($file->path)
                                                                            <a href="{{ asset('storage/' . ltrim($file->path, '/')) }}" target="_blank" rel="noopener" class="btn btn-outline btn-sm">Lihat Berkas</a>
                                                                        @endif
                                                                        @if (in_array($karya->status, ['draft', 'perlu_revisi'], true))
                                                                            <form method="POST" action="{{ route('karya.files.destroy', [$karya->id, $file->id]) }}">
                                                                                @csrf
                                                                                <input type="hidden" name="redirect_tab" value="dokumen">
                                                                                <button type="submit" class="btn btn-danger-outline btn-sm">Hapus</button>
                                                                            </form>
                                                                        @endif
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @endif

                                        <form method="POST" action="{{ route('karya.upload', $karya->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="redirect_tab" value="dokumen">
                                            <div class="form-grid" style="grid-template-columns: 1fr 1.2fr 1fr auto; align-items: end;">
                                                <div class="form-group">
                                                    <label>Jenis Berkas</label>
                                                    <select name="jenis" required>
                                                        <option value="dokumen">Dokumen / Proposal</option>
                                                        <option value="thumbnail">Thumbnail Showcase</option>
                                                        <option value="utama">Berkas Utama</option>
                                                        <option value="galeri">Galeri Gambar</option>
                                                        <option value="video">Video Demo</option>
                                                        <option value="lainnya">Lampiran Lainnya</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Pilih Berkas</label>
                                                    <input type="file" name="file" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Keterangan</label>
                                                    <input type="text" name="keterangan" placeholder="Mis. Deck Presentasi v2">
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm">Unggah</button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                {{-- TAB: HASIL KURASI, RIWAYAT REVISI & PUBLIKASI SHOWCASE --}}
                <section class="panel-section {{ $activeTab === 'kurasi' ? 'active' : '' }}" data-panel="kurasi">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Hasil Kurasi, Riwayat Revisi &amp; Pengajuan Showcase</h2>
                                <p class="card-subtitle">Pantau catatan kurator, riwayat versi perbaikan karya, serta ajukan persetujuan publikasi showcase untuk karya yang telah lolos kurasi.</p>
                            </div>
                        </div>

                        @if ($managedWorks->isEmpty())
                            <div class="alert alert-info">Belum ada karya untuk ditinjau status kurasinya.</div>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 18px;">
                                @foreach ($managedWorks as $karya)
                                    @php
                                        $pub = $karya->publikasiWeb;
                                    @endphp
                                    <div class="work-box">
                                        <div class="work-head">
                                            <div>
                                                <div style="font-size: 16px; font-weight: 700; color: var(--color-primary);">{{ $karya->judul }}</div>
                                                <div class="work-meta">
                                                    <span>Status Kurasi: <strong>{{ $statusLabels[$karya->status] ?? $karya->status }}</strong></span>
                                                    @if ($pub)
                                                        <span>Status Showcase: <strong>{{ $pubStatusLabels[$pub->status] ?? $pub->status }}</strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="status-badge status-{{ $karya->status }}">{{ $statusLabels[$karya->status] ?? $karya->status }}</span>
                                        </div>

                                        @if ($karya->catatan_kurasi)
                                            <div class="alert alert-warning" style="padding: 12px 14px;">
                                                <strong>Catatan Kurator:</strong> {{ $karya->catatan_kurasi }}
                                            </div>
                                        @endif

                                        <div>
                                            <div style="font-size: 13px; font-weight: 700; margin-bottom: 8px; color: var(--color-primary);">Riwayat Versi Karya</div>
                                            @if ($karya->versis->isEmpty())
                                                <p class="field-hint">Belum ada catatan perubahan versi.</p>
                                            @else
                                                <div class="timeline-list">
                                                    @foreach ($karya->versis as $versi)
                                                        <div class="timeline-item">
                                                            <strong>Versi {{ $versi->versi }} &mdash; {{ $statusLabels[$versi->status_baru] ?? $versi->status_baru }}</strong>
                                                            <div>{{ $versi->catatan ?? 'Pembaruan data karya.' }}</div>
                                                            <div class="field-hint">
                                                                Oleh: {{ $versi->updater?->name ?? 'Sistem' }} &bull; {{ $versi->created_at?->format('d M Y H:i') }}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Pengajuan Publikasi Showcase (Dev 1) --}}
                                        <div style="padding-top: 12px; border-top: 1px solid var(--color-hairline);">
                                            <div style="font-size: 13.5px; font-weight: 700; margin-bottom: 8px; color: var(--color-primary);">
                                                Publikasi Showcase Resmi
                                            </div>
                                            @if ($karya->status !== 'lolos')
                                                <p class="field-hint">Pengajuan publikasi Showcase terbuka setelah karya berstatus <strong>Lolos Kurasi</strong>.</p>
                                            @else
                                                @if ($pub)
                                                    <div class="alert {{ $pub->status === 'terbit' ? 'alert-success' : 'alert-info' }}" style="margin-bottom: 12px;">
                                                        Status publikasi saat ini: <strong>{{ $pubStatusLabels[$pub->status] ?? $pub->status }}</strong>
                                                        @if ($pub->status === 'terbit')
                                                            &bull; Slug publik: <code>/showcase/{{ $pub->slug }}</code>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if (! $pub || in_array($pub->status, ['draft', 'ditarik'], true))
                                                    <form method="POST" action="{{ route('karya.publication.request', $karya->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="redirect_tab" value="kurasi">
                                                        <div class="form-grid">
                                                            <div class="form-group">
                                                                <label>Judul Tampil di Showcase</label>
                                                                <input type="text" name="judul_publik" value="{{ $pub?->judul_publik ?? $karya->judul }}" maxlength="180">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Ringkasan Singkat Publik</label>
                                                                <input type="text" name="ringkasan_publik" value="{{ $pub?->ringkasan_publik ?? \Illuminate\Support\Str::limit($karya->deskripsi, 220) }}" maxlength="500">
                                                            </div>
                                                            <div class="form-group full">
                                                                <label style="display: inline-flex; align-items: center; gap: 8px; font-weight: 500;">
                                                                    <input type="checkbox" name="izin_peserta" value="1" required checked>
                                                                    Saya menyetujui publikasi karya ini di Showcase resmi FUTURiSE (kontak pribadi tidak ditampilkan ke publik).
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div style="margin-top: 10px;">
                                                            <button type="submit" class="btn btn-primary btn-sm">Ajukan Publikasi ke Showcase</button>
                                                        </div>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                {{-- TAB: MODUL DEV 2 (TERPISAH JELAS) --}}
                <section class="panel-section {{ $activeTab === 'dev2_modul' ? 'active' : '' }}" data-panel="dev2_modul">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Modul Operasional Lanjutan (Tahap Developer 2)</h2>
                                <p class="card-subtitle">Menu berikut merupakan ruang integrasi untuk fitur yang sedang dibangun oleh tim Developer 2.</p>
                            </div>
                        </div>
                        <div class="two-col-grid">
                            <div class="dev2-box">
                                <span class="dev2-tag">Menunggu Dev 2</span>
                                <h3 class="card-title">Alokasi Booth &amp; Kebutuhan Teknis</h3>
                                <p class="card-subtitle">Pengajuan daya listrik, area demo, perangkat pameran, dan penempatan nomor booth pameran.</p>
                            </div>
                            <div class="dev2-box">
                                <span class="dev2-tag">Menunggu Dev 2</span>
                                <h3 class="card-title">Jadwal Pitching &amp; Penjurian</h3>
                                <p class="card-subtitle">Jadwal sesi pitching finalis, penempatan meja temu industri, serta rubrik hasil penilaian dewan juri.</p>
                            </div>
                            <div class="dev2-box">
                                <span class="dev2-tag">Menunggu Dev 2</span>
                                <h3 class="card-title">Tagihan &amp; Administrasi Pembayaran</h3>
                                <p class="card-subtitle">Penerbitan tagihan partisipasi, unggah bukti transfer, dan status verifikasi dari Divisi Keuangan.</p>
                            </div>
                            <div class="dev2-box">
                                <span class="dev2-tag">Menunggu Dev 2</span>
                                <h3 class="card-title">Feedback Pengunjung &amp; Tindak Lanjut</h3>
                                <p class="card-subtitle">Komentar masukan dari mitra industri/pengunjung serta pencatatan rencana tindak lanjut H+30.</p>
                            </div>
                        </div>
                    </div>
                </section>

            {{-- ================================================================ --}}
            {{-- PORTAL PIC SEKOLAH (TERPISAH DARI PIC KAMPUS)                    --}}
            {{-- ================================================================ --}}
            @elseif ($portalRole === 'pic-sekolah')
                <section class="panel-section {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-panel="ringkasan">
                    <div class="two-col-grid">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h2 class="card-title">Identitas Koordinator Sekolah</h2>
                                    <p class="card-subtitle">Portal ini dikhususkan bagi perwakilan SMA/SMK yang mengoordinasikan kunjungan siswa ke area pameran.</p>
                                </div>
                            </div>
                            <div class="info-kv">
                                <div class="kv-item">
                                    <div class="kv-label">Nama Koordinator</div>
                                    <div class="kv-value">{{ $user->name }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Email Akun</div>
                                    <div class="kv-value">{{ $user->email }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Status Verifikasi</div>
                                    <div class="kv-value">{{ ucfirst($user->account_status) }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Jenis Akses</div>
                                    <div class="kv-value">Kunjungan Rombongan &amp; Tur Sekolah</div>
                                </div>
                            </div>
                        </div>

                        <div class="dev2-box">
                            <span class="dev2-tag">Modul Sekolah &amp; Tur (Dev 2)</span>
                            <h2 class="card-title">Status Kesiapan Portal Sekolah</h2>
                            <p class="card-subtitle">
                                Akun PIC Sekolah Anda telah terverifikasi pada sistem autentikasi (Dev 1). Modul penjadwalan slot tur, kuota siswa pendamping, pemandu lapangan, dan konfirmasi kedatangan akan tersedia melalui fitur <strong>Sekolah &amp; Tur</strong> dari Developer 2.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'profil_sekolah' ? 'active' : '' }}" data-panel="profil_sekolah">
                    <div class="dev2-box">
                        <span class="dev2-tag">Menunggu Dev 2</span>
                        <h2 class="card-title">Profil Sekolah &amp; Pendamping</h2>
                        <p class="card-subtitle">Pengisian profil resmi sekolah, NPSN, jumlah rombongan guru pendamping, serta kontak darurat kunjungan akan disediakan pada modul Sekolah (Dev 2).</p>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'rombongan' ? 'active' : '' }}" data-panel="rombongan">
                    <div class="dev2-box">
                        <span class="dev2-tag">Menunggu Dev 2</span>
                        <h2 class="card-title">Daftar Rombongan Siswa</h2>
                        <p class="card-subtitle">Pendataan daftar rombongan siswa per kloter dan surat konfirmasi sekolah ditangani pada modul Sekolah &amp; Tur (Dev 2).</p>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'slot_tur' ? 'active' : '' }}" data-panel="slot_tur">
                    <div class="dev2-box">
                        <span class="dev2-tag">Menunggu Dev 2</span>
                        <h2 class="card-title">Pemilihan Slot Tur &amp; Arahan Kedatangan</h2>
                        <p class="card-subtitle">Pemilihan slot waktu tur pameran, konfirmasi kehadiran, dan titik kumpul kedatangan bus sekolah akan aktif pada modul Developer 2.</p>
                    </div>
                </section>

            {{-- ================================================================ --}}
            {{-- PORTAL PIC INDUSTRI / MITRA (TERPISAH DARI SPONSOR)              --}}
            {{-- ================================================================ --}}
            @elseif ($portalRole === 'pic-industri')
                <section class="panel-section {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-panel="ringkasan">
                    <div class="kpi-grid">
                        <div class="kpi-card">
                            <div class="kpi-label">Karya di Showcase</div>
                            <div class="kpi-value">{{ $publishedWorks->count() }}</div>
                            <div class="kpi-note">Inovasi siap dikaji untuk kolaborasi</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-label">Status Akun Mitra</div>
                            <div class="kpi-value">{{ ucfirst($user->account_status) }}</div>
                            <div class="kpi-note">Terverifikasi panitia</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-label">Permintaan Pertemuan</div>
                            <div class="kpi-value">Dev 2</div>
                            <div class="kpi-note">Modul Meeting &amp; Booth</div>
                        </div>
                        <div class="kpi-card">
                            <div class="kpi-label">Tindak Lanjut</div>
                            <div class="kpi-value">Dev 2</div>
                            <div class="kpi-note">Agenda kolaborasi H+30</div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Sorotan Inovasi untuk Mitra Industri</h2>
                                <p class="card-subtitle">Karya berikut telah lolos kurasi dan diterbitkan di Showcase resmi FUTURiSE beserta kebutuhan kolaborasinya.</p>
                            </div>
                            <button type="button" class="btn btn-outline btn-sm" onclick="switchPortalTab('showcase_katalog')">Lihat Semua Pratinjau</button>
                        </div>
                        @if ($publishedWorks->isEmpty())
                            <p class="card-subtitle">Belum ada karya yang berstatus terbit di showcase pada festival saat ini.</p>
                        @else
                            <div class="three-col-grid">
                                @foreach ($publishedWorks->take(3) as $pub)
                                    <div class="work-box">
                                        <div style="font-size: 14.5px; font-weight: 700; color: var(--color-primary);">{{ $pub->judul_publik }}</div>
                                        <div class="field-hint">{{ $pub->karya?->submitter?->institusi ?? '-' }} &bull; {{ $pub->karya?->kategori }}</div>
                                        <p class="field-hint">{{ \Illuminate\Support\Str::limit($pub->ringkasan_publik, 110) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'showcase_katalog' ? 'active' : '' }}" data-panel="showcase_katalog">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Katalog Inovasi Terpublikasi</h2>
                                <p class="card-subtitle">Daftar karya mahasiswa yang terbuka untuk penjajakan adopsi dan kemitraan teknologi.</p>
                            </div>
                        </div>
                        @if ($publishedWorks->isEmpty())
                            <div class="alert alert-info">Belum ada karya terbit di showcase.</div>
                        @else
                            <div class="two-col-grid">
                                @foreach ($publishedWorks as $pub)
                                    <div class="work-box">
                                        <div class="work-head">
                                            <strong>{{ $pub->judul_publik }}</strong>
                                            <span class="status-badge status-terbit">Terbit</span>
                                        </div>
                                        <div class="work-meta">
                                            <span>Kampus: {{ $pub->karya?->submitter?->institusi ?? '-' }}</span>
                                            <span>Bidang: {{ $pub->karya?->kategori ?? '-' }}</span>
                                        </div>
                                        <p class="card-subtitle">{{ $pub->ringkasan_publik }}</p>
                                        @if ($pub->karya?->kebutuhan_kolaborasi)
                                            <div class="alert alert-info" style="padding: 8px 12px;">
                                                <strong>Kebutuhan Kolaborasi:</strong> {{ $pub->karya->kebutuhan_kolaborasi }}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'organisasi_mitra' ? 'active' : '' }}" data-panel="organisasi_mitra">
                    <div class="dev2-box">
                        <span class="dev2-tag">Menunggu Dev 2</span>
                        <h2 class="card-title">Profil Organisasi Industri &amp; Kebutuhan Masalah</h2>
                        <p class="card-subtitle">Pengelolaan profil sektor industri, direktori solusi/kebutuhan bisnis, serta daftar kontak teknis organisasi akan disediakan pada modul Mitra Industri (Dev 2).</p>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'pertemuan_booth' ? 'active' : '' }}" data-panel="pertemuan_booth">
                    <div class="dev2-box">
                        <span class="dev2-tag">Menunggu Dev 2</span>
                        <h2 class="card-title">Partisipasi, Booth, Jadwal Pertemuan &amp; Tagihan</h2>
                        <p class="card-subtitle">Reservasi booth organisasi, permintaan pertemuan langsung dengan tim inovator di venue, tagihan kemitraan, dan tindak lanjut H+30 berada di cakupan Developer 2.</p>
                    </div>
                </section>

            {{-- ================================================================ --}}
            {{-- PORTAL PIC SPONSOR (TERPISAH DARI INDUSTRI)                      --}}
            {{-- ================================================================ --}}
            @elseif ($portalRole === 'pic-sponsor')
                <section class="panel-section {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-panel="ringkasan">
                    <div class="two-col-grid">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h2 class="card-title">Profil Akun Sponsor</h2>
                                    <p class="card-subtitle">Ruang representatif lembaga sponsor resmi untuk memantau publikasi inovasi dan paket sponsorship.</p>
                                </div>
                            </div>
                            <div class="info-kv">
                                <div class="kv-item">
                                    <div class="kv-label">Nama PIC Sponsor</div>
                                    <div class="kv-value">{{ $user->name }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Email Terdaftar</div>
                                    <div class="kv-value">{{ $user->email }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Status Akun</div>
                                    <div class="kv-value">{{ ucfirst($user->account_status) }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Showcase Terbit</div>
                                    <div class="kv-value">{{ $publishedWorks->count() }} Karya</div>
                                </div>
                            </div>
                        </div>

                        <div class="dev2-box">
                            <span class="dev2-tag">Modul Sponsorship &amp; Keuangan (Dev 2)</span>
                            <h2 class="card-title">Status Fitur Kemitraan Sponsor</h2>
                            <p class="card-subtitle">
                                Akun Sponsor Anda sudah aktif untuk otentikasi portal dan pemantauan showcase (Dev 1). Pengelolaan paket sponsorship, kontrak kesepakatan, materi promosi, tagihan, dan laporan pemenuhan manfaat akan tersedia pada modul Developer 2.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'kesepakatan' ? 'active' : '' }}" data-panel="kesepakatan">
                    <div class="dev2-box">
                        <span class="dev2-tag">Menunggu Dev 2</span>
                        <h2 class="card-title">Kesepakatan &amp; Paket Sponsorship</h2>
                        <p class="card-subtitle">Rincian tingkat paket (Platinum/Gold/Silver/In-Kind), nilai dukungan, serta dokumen kesepakatan akan dikelola melalui modul Sponsorship (Dev 2).</p>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'tagihan_materi' ? 'active' : '' }}" data-panel="tagihan_materi">
                    <div class="dev2-box">
                        <span class="dev2-tag">Menunggu Dev 2</span>
                        <h2 class="card-title">Tagihan, Materi Sponsor &amp; Bukti Manfaat</h2>
                        <p class="card-subtitle">Unggah aset logo dan materi brand sponsor, status tagihan pembayaran, serta bukti pemenuhan hak manfaat pameran disediakan pada modul Developer 2.</p>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'showcase_sponsor' ? 'active' : '' }}" data-panel="showcase_sponsor">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Karya Terpublikasi yang Didukung Festival</h2>
                                <p class="card-subtitle">Daftar karya mahasiswa yang tampil di Showcase resmi.</p>
                            </div>
                        </div>
                        @if ($publishedWorks->isEmpty())
                            <div class="alert alert-info">Belum ada karya terpublikasi di showcase.</div>
                        @else
                            <div class="three-col-grid">
                                @foreach ($publishedWorks as $pub)
                                    <div class="work-box">
                                        <strong>{{ $pub->judul_publik }}</strong>
                                        <div class="field-hint">{{ $pub->karya?->submitter?->institusi ?? '-' }}</div>
                                        <p class="field-hint">{{ \Illuminate\Support\Str::limit($pub->ringkasan_publik, 100) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

            {{-- ================================================================ --}}
            {{-- PORTAL DEWAN JURI                                                --}}
            {{-- ================================================================ --}}
            @elseif ($portalRole === 'juri')
                <section class="panel-section {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-panel="ringkasan">
                    <div class="two-col-grid">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h2 class="card-title">Ringkasan Tugas Dewan Juri</h2>
                                    <p class="card-subtitle">Status kesiapan evaluasi karya pada festival aktif.</p>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" onclick="switchPortalTab('daftar_karya')">Lihat Daftar Karya</button>
                            </div>
                            <div class="info-kv">
                                <div class="kv-item">
                                    <div class="kv-label">Nama Juri</div>
                                    <div class="kv-value">{{ $user->name }}</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Peran Sistem</div>
                                    <div class="kv-value">JURI</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Karya Lolos Kurasi</div>
                                    <div class="kv-value">{{ $curatedWorks->count() }} Karya</div>
                                </div>
                                <div class="kv-item">
                                    <div class="kv-label">Festival Aktif</div>
                                    <div class="kv-value">{{ $activeFestival?->nama ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="dev2-box">
                            <span class="dev2-tag">Modul Penilaian Juri (Dev 2)</span>
                            <h2 class="card-title">Form Input Skor &amp; Penguncian Nilai</h2>
                            <p class="card-subtitle">
                                Dewan Juri dapat membaca daftar karya yang telah lolos kurasi pada tab <strong>Karya Lolos Kurasi</strong> (data Dev 1). Formulir input skor per rubrik berbobot dan penguncian final penilaian berada pada modul Developer 2.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'daftar_karya' ? 'active' : '' }}" data-panel="daftar_karya">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Daftar Karya Lolos Kurasi ({{ $curatedWorks->count() }})</h2>
                                <p class="card-subtitle">Karya inovasi yang telah melewati tahap kurasi administrasi dan substansi oleh panitia.</p>
                            </div>
                        </div>

                        @if ($curatedWorks->isEmpty())
                            <div class="alert alert-info">Belum ada karya dengan status Lolos Kurasi untuk dinilai saat ini.</div>
                        @else
                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Judul Karya</th>
                                            <th>Asal Kampus / Institusi</th>
                                            <th>Kategori</th>
                                            <th>Teknologi</th>
                                            <th>Berkas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($curatedWorks as $karya)
                                            <tr>
                                                <td><strong>{{ $karya->judul }}</strong></td>
                                                <td>{{ $karya->submitter?->institusi ?? '-' }}</td>
                                                <td>{{ $karya->kategori }}</td>
                                                <td>{{ $karya->teknologi }}</td>
                                                <td>{{ $karya->files->count() }} file</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'penilaian_juri' ? 'active' : '' }}" data-panel="penilaian_juri">
                    <div class="dev2-box">
                        <span class="dev2-tag">Menunggu Dev 2</span>
                        <h2 class="card-title">Rubrik Berversi, Penugasan &amp; Rekap Nilai Juri</h2>
                        <p class="card-subtitle">Penugasan spesifik karya per juri, penyimpanan draft nilai kriteria, rekap bobot skor, dan penguncian hasil akan disediakan oleh Developer 2.</p>
                    </div>
                </section>

            {{-- ================================================================ --}}
            {{-- PORTAL PENGUNJUNG                                                --}}
            {{-- ================================================================ --}}
            @else
                <section class="panel-section {{ $activeTab === 'ringkasan' ? 'active' : '' }}" data-panel="ringkasan">
                    <div class="two-col-grid">
                        <div class="card">
                            <div class="card-header">
                                <div>
                                    <h2 class="card-title">Selamat Datang di Portal Pengunjung</h2>
                                    <p class="card-subtitle">Jelajahi katalog karya inovasi terpublikasi atau daftar sebagai peserta lomba apabila Anda merupakan mahasiswa atau siswa pengusul.</p>
                                </div>
                            </div>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                <button type="button" class="btn btn-primary" onclick="switchPortalTab('showcase')">Jelajahi Showcase ({{ $publishedWorks->count() }})</button>
                                <button type="button" class="btn btn-outline" onclick="switchPortalTab('daftar_peserta')">Daftar Sebagai Peserta Lomba</button>
                            </div>
                        </div>

                        <div class="dev2-box">
                            <span class="dev2-tag">Menunggu Dev 2</span>
                            <h2 class="card-title">Tiket QR, Check-in &amp; Voting Pengunjung</h2>
                            <p class="card-subtitle">Fitur pemesanan tiket kunjungan QR, agenda sesi acara, voting karya favorit, feedback langsung, dan survei kepuasan akan tersedia melalui modul Pengunjung (Dev 2).</p>
                        </div>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'showcase' ? 'active' : '' }}" data-panel="showcase">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Showcase Karya Inovasi Terbaru</h2>
                                <p class="card-subtitle">Menampilkan karya-karya terbaik yang sudah mendapatkan persetujuan publikasi resmi.</p>
                            </div>
                        </div>
                        @if ($publishedWorks->isEmpty())
                            <div class="alert alert-info">Belum ada karya terpublikasi di showcase pada periode ini.</div>
                        @else
                            <div class="three-col-grid">
                                @foreach ($publishedWorks as $pub)
                                    <div class="work-box">
                                        <strong>{{ $pub->judul_publik }}</strong>
                                        <div class="field-hint">{{ $pub->karya?->submitter?->institusi ?? '-' }} &bull; {{ $pub->karya?->kategori }}</div>
                                        <p class="field-hint">{{ \Illuminate\Support\Str::limit($pub->ringkasan_publik, 120) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'tiket_aktivitas' ? 'active' : '' }}" data-panel="tiket_aktivitas">
                    <div class="dev2-box">
                        <span class="dev2-tag">Menunggu Dev 2</span>
                        <h2 class="card-title">Tiket QR, Pertemuan, Voting &amp; Survei</h2>
                        <p class="card-subtitle">Pengambilan kode QR kunjungan venue, riwayat aktivitas stan pameran, pemberian voting &amp; feedback karya, serta pengisian survei acara disiapkan pada modul Developer 2.</p>
                    </div>
                </section>

                <section class="panel-section {{ $activeTab === 'daftar_peserta' ? 'active' : '' }}" data-panel="daftar_peserta">
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <h2 class="card-title">Lengkapi Data Peserta Lomba</h2>
                                <p class="card-subtitle">Khusus pengunjung yang ingin beralih peran menjadi peserta pengusul karya kompetisi FUTURiSE.</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('participant.register') }}">
                            @csrf
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="jenis_peserta_vis">Kategori Peserta <span class="req">*</span></label>
                                    <select name="jenis_peserta" id="jenis_peserta_vis" required>
                                        <option value="mahasiswa" {{ old('jenis_peserta') === 'mahasiswa' ? 'selected' : '' }}>Mahasiswa Perguruan Tinggi</option>
                                        <option value="siswa_smk" {{ old('jenis_peserta') === 'siswa_smk' ? 'selected' : '' }}>Siswa SMK / Sederajat</option>
                                        <option value="umum" {{ old('jenis_peserta') === 'umum' ? 'selected' : '' }}>Inovator Umum</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nomor_identitas_vis">NIM / NISN / NIK <span class="req">*</span></label>
                                    <input type="text" name="nomor_identitas" id="nomor_identitas_vis" value="{{ old('nomor_identitas') }}" placeholder="Masukkan NIM (Mahasiswa), NISN 10 digit, atau NIK 16 digit" required>
                                </div>
                                <div class="form-group full">
                                    <label for="institusi_input_vis">Asal Kampus / Sekolah / Instansi <span class="req">*</span></label>
                                    <input type="text" name="institusi" id="institusi_input_vis" class="js-institusi-autocomplete" data-list="institusiListVis" value="{{ old('institusi') }}" placeholder="Ketik minimal 2 huruf nama kampus atau sekolah..." autocomplete="off" required>
                                    <div class="autocomplete-list" id="institusiListVis"></div>
                                </div>
                                <div class="form-group full">
                                    <label for="no_telepon_vis">Nomor Telepon / WhatsApp</label>
                                    <input type="text" name="no_telepon" id="no_telepon_vis" value="{{ old('no_telepon', $user->person?->phone) }}" placeholder="Contoh: 081234567890">
                                </div>
                            </div>
                            <div style="margin-top: 18px;">
                                <button type="submit" class="btn btn-primary">Simpan Data Peserta &amp; Gabung Kompetisi</button>
                            </div>
                        </form>
                    </div>
                </section>
            @endif
        </main>
    </div>

    <script>
        function switchPortalTab(tabKey) {
            const buttons = document.querySelectorAll('[data-tab-btn]');
            const panels = document.querySelectorAll('[data-panel]');

            buttons.forEach(function (btn) {
                btn.classList.toggle('active', btn.getAttribute('data-tab-btn') === tabKey);
            });

            panels.forEach(function (panel) {
                panel.classList.toggle('active', panel.getAttribute('data-panel') === tabKey);
            });

            try {
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tabKey);
                window.history.replaceState({}, '', url.toString());
            } catch (e) {}

            const sidebar = document.getElementById('portalSidebar');
            if (sidebar) {
                sidebar.classList.remove('mobile-open');
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('portalSidebar');
            if (sidebar) {
                sidebar.classList.toggle('mobile-open');
            }
        }

        function toggleCollapse(id) {
            const el = document.getElementById(id);
            if (el) {
                el.classList.toggle('open');
            }
        }

        function addTeamRow() {
            const container = document.getElementById('teamRowsContainer');
            if (!container) return;
            const row = document.createElement('div');
            row.className = 'two-col-grid';
            row.style.gridTemplateColumns = '1fr 1fr 1fr';
            row.innerHTML =
                '<input type="text" name="anggota_nama[]" placeholder="Nama lengkap anggota">' +
                '<input type="text" name="anggota_peran[]" placeholder="Peran (mis. ML Engineer)">' +
                '<input type="email" name="anggota_email[]" placeholder="Email anggota">';
            container.appendChild(row);
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-tab-btn]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    switchPortalTab(btn.getAttribute('data-tab-btn'));
                });
            });

            // Autocomplete Institusi Perguruan Tinggi / SMK
            const inputs = document.querySelectorAll('.js-institusi-autocomplete');
            inputs.forEach(function (input) {
                const listId = input.getAttribute('data-list');
                const dropdown = listId ? document.getElementById(listId) : null;
                if (!dropdown) return;

                let debounceTimer = null;

                input.addEventListener('input', function () {
                    const query = input.value.trim();
                    clearTimeout(debounceTimer);

                    if (query.length < 2) {
                        dropdown.innerHTML = '';
                        dropdown.classList.remove('open');
                        return;
                    }

                    debounceTimer = setTimeout(function () {
                        fetch('/institusi/search?q=' + encodeURIComponent(query))
                            .then(function (res) { return res.json(); })
                            .then(function (payload) {
                                const items = Array.isArray(payload.data) ? payload.data : [];
                                if (items.length === 0) {
                                    dropdown.innerHTML = '';
                                    dropdown.classList.remove('open');
                                    return;
                                }

                                dropdown.innerHTML = '';
                                items.forEach(function (item) {
                                    const row = document.createElement('div');
                                    row.className = 'autocomplete-item';
                                    const name = item.nama || '';
                                    const loc = [item.kab_kota, item.provinsi].filter(Boolean).join(', ');

                                    const strong = document.createElement('strong');
                                    strong.textContent = name;
                                    const span = document.createElement('span');
                                    span.textContent = loc || 'Institusi Terdaftar';

                                    row.appendChild(strong);
                                    row.appendChild(span);
                                    row.addEventListener('click', function () {
                                        input.value = name;
                                        dropdown.innerHTML = '';
                                        dropdown.classList.remove('open');
                                    });
                                    dropdown.appendChild(row);
                                });
                                dropdown.classList.add('open');
                            })
                            .catch(function () {
                                dropdown.classList.remove('open');
                            });
                    }, 220);
                });

                document.addEventListener('click', function (evt) {
                    if (!dropdown.contains(evt.target) && evt.target !== input) {
                        dropdown.classList.remove('open');
                    }
                });
            });
        });
    </script>
</body>
</html>
