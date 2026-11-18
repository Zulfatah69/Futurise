<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUTURiSE - Future Innovation and Creativity | APTIKOM</title>

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
            background-color: var(--color-canvas);
            color: #1A1A36;
        }

        /* TOP NAVIGATION */
        .navbar {
            width: 100%;
            background: var(--color-primary);
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--color-success);
        }

        .brand {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.8px;
            color: var(--color-canvas);
            text-decoration: none;
        }

        .brand span {
            color: var(--color-success);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-nav-outline {
            color: var(--color-canvas);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 9px 20px;
            border-radius: var(--radius-btn);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-nav-outline:hover {
            background: var(--color-canvas);
            color: var(--color-primary);
            border-color: var(--color-canvas);
        }

        .btn-nav-solid {
            background: var(--color-success);
            color: var(--color-primary);
            border: 1px solid var(--color-success);
            padding: 9px 22px;
            border-radius: var(--radius-btn);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-nav-solid:hover {
            background: #00B4C8;
            border-color: #00B4C8;
        }

        /* HERO SECTION */
        .hero {
            background: var(--color-primary);
            color: var(--color-canvas);
            padding: 85px 7% 95px;
            position: relative;
            border-bottom: 1px solid var(--color-hairline);
        }

        .hero-badge {
            display: inline-block;
            background: rgba(0, 205, 229, 0.15);
            color: var(--color-success);
            border: 1px solid var(--color-success);
            padding: 6px 14px;
            border-radius: var(--radius-btn);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 22px;
        }

        .hero h1 {
            font-size: 46px;
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -1.2px;
            max-width: 820px;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: var(--color-success);
        }

        .hero p {
            font-size: 18px;
            line-height: 1.8;
            color: #C0D3E5;
            max-width: 720px;
            margin-bottom: 36px;
        }

        .hero-cta-group {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            background: var(--color-success);
            color: var(--color-primary);
            padding: 16px 36px;
            border-radius: var(--radius-btn);
            font-size: 16px;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(0, 205, 229, 0.25);
        }

        .btn-hero-primary:hover {
            background: #00B4C8;
        }

        .btn-hero-secondary {
            background: transparent;
            color: var(--color-canvas);
            border: 2px solid rgba(255, 255, 255, 0.6);
            padding: 14px 32px;
            border-radius: var(--radius-btn);
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-hero-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--color-canvas);
        }

        /* HIGHLIGHT CARDS (SHARP 0px RADIUS) */
        .features-section {
            padding: 70px 7%;
            background: var(--color-surface);
        }

        .section-header {
            text-align: center;
            max-width: 680px;
            margin: 0 auto 50px;
        }

        .section-header h2 {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.6px;
            color: var(--color-primary);
            margin-bottom: 12px;
        }

        .section-header p {
            color: var(--color-ink);
            font-size: 15px;
            line-height: 1.8;
        }

        .grid-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: var(--color-canvas);
            padding: 34px 30px;
            border-radius: var(--radius-card);
            border: 1px solid var(--color-hairline);
            border-top: 4px solid var(--color-primary);
            box-shadow: 0 4px 12px rgba(43, 43, 94, 0.04);
            transition: transform 0.2s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            border-top-color: var(--color-success);
        }

        .card-icon {
            font-size: 30px;
            margin-bottom: 18px;
            color: var(--color-accent);
        }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.4px;
            color: var(--color-primary);
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 14px;
            color: var(--color-ink);
            line-height: 1.8;
        }

        /* FOOTER */
        .footer {
            background: var(--color-primary);
            color: #C0D3E5;
            padding: 30px 7%;
            text-align: center;
            font-size: 13px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 850px) {
            .hero h1 {
                font-size: 34px;
            }
            .grid-cards {
                grid-template-columns: 1fr;
            }
            .hero {
                padding: 60px 6%;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="/" class="brand">FUTURI<span>SE</span></a>

        <div class="nav-actions">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-nav-solid">
                    Buka Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-nav-outline">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="btn-nav-solid">
                    Daftar Akun
                </a>
            @endauth
        </div>
    </nav>

    <!-- HERO -->
    <header class="hero">
        <div class="hero-badge">Festival & Kompetisi Nasional APTIKOM</div>
        <h1>Inovasi Teknologi & Kreativitas Digital <span>Masa Depan</span></h1>
        <p>
            Platform resmi kompetisi karya teknologi, pameran inovasi mahasiswa, dan kolaborasi industri pendidikan komputer dan informatika di seluruh Indonesia.
        </p>

        <div class="hero-cta-group">
            @auth
                <a href="{{ route('dashboard') }}" class="btn-hero-primary">
                    Akses Dashboard Kepesertaan →
                </a>
            @else
                <a href="{{ route('register') }}" class="btn-hero-primary">
                    Daftar Akun Sekarang
                </a>
                <a href="{{ route('login') }}" class="btn-hero-secondary">
                    Masuk ke Portal
                </a>
            @endauth
        </div>
    </header>

    <!-- HIGHLIGHT SECTION -->
    <main class="features-section">
        <div class="section-header">
            <h2>Pilar Utama FUTURiSE</h2>
            <p>
                Menghubungkan akademisi, mahasiswa berprestasi, dan mitra industri untuk memajukan riset dan penerapan teknologi komputer di Indonesia.
            </p>
        </div>

        <div class="grid-cards">
            <div class="feature-card">
                <div class="card-icon">🏆</div>
                <h3>Kompetisi & Festival</h3>
                <p>
                    Ajang pembuktian karya inovatif mahasiswa dan siswa SMK di bidang teknologi informasi, kecerdasan buatan, IoT, dan aplikasi digital.
                </p>
            </div>

            <div class="feature-card">
                <div class="card-icon">🏛️</div>
                <h3>18.000+ Institusi Nasional</h3>
                <p>
                    Terhubung langsung dengan basis data resmi perguruan tinggi dan sekolah vokasi di seluruh Indonesia melalui jaringan APTIKOM.
                </p>
            </div>

            <div class="feature-card">
                <div class="card-icon">🤝</div>
                <h3>Kolaborasi Industri</h3>
                <p>
                    Peluang hilirisasi riset kampus dengan mitra industri, sponsor teknologi, dan investor untuk mengakselerasi produk digital siap pakai.
                </p>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        &copy; {{ date('Y') }} FUTURiSE Indonesia &bull; Asosiasi Pendidikan Tinggi Informatika dan Komputer (APTIKOM).
    </footer>

</body>
</html>
