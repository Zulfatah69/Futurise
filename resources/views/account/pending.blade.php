<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Persetujuan - FUTURISE</title>

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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
            background-color: var(--color-surface);
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 205, 229, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(43, 43, 94, 0.12) 0px, transparent 50%);
        }

        /* SHARP CARD (APTIKOM STANDARD: 0px RADIUS) */
        .pending-card {
            width: 100%;
            max-width: 480px;
            background: var(--color-canvas);
            padding: 44px 38px;
            border-radius: var(--radius-card);
            border: 1px solid var(--color-hairline);
            border-top: 5px solid var(--color-primary);
            box-shadow: 0 8px 24px rgba(43, 43, 94, 0.06);
            text-align: center;
            position: relative;
        }

        .brand-stripe {
            position: absolute;
            top: -5px;
            right: 0;
            width: 80px;
            height: 5px;
            background: var(--color-success);
        }

        .brand {
            margin-bottom: 24px;
        }

        .brand h1 {
            color: var(--color-primary);
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -1px;
            line-height: 1.1;
        }

        .brand h1 span {
            color: var(--color-accent);
        }

        .brand p {
            color: var(--color-ink);
            font-size: 13px;
            font-weight: 500;
            margin-top: 6px;
            text-transform: capitalize;
        }

        .icon-circle {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: #FFFBEB;
            color: #D97706;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            border: 1px solid #FDE68A;
        }

        .icon-circle svg {
            width: 32px;
            height: 32px;
            stroke-width: 2;
        }

        h2 {
            color: var(--color-primary);
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 10px;
        }

        .description {
            color: var(--color-ink);
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .info-box {
            background: var(--color-surface);
            border: 1px solid var(--color-hairline);
            padding: 16px 20px;
            border-radius: var(--radius-card);
            margin-bottom: 16px;
            text-align: left;
        }

        .info-box small {
            display: block;
            color: var(--color-ink);
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-box strong {
            color: var(--color-primary);
            font-size: 14px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .status-badge {
            display: inline-block;
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #F59E0B;
            padding: 6px 14px;
            border-radius: var(--radius-btn);
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 22px;
            letter-spacing: 0.5px;
        }

        .notice {
            color: var(--color-ink);
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        /* BUTTON (APTIKOM STANDARD: 8px RADIUS, PRIMARY NAVY) */
        .btn-submit {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: var(--radius-btn);
            background: var(--color-primary);
            color: var(--color-canvas);
            font-size: 15px;
            font-weight: 700;
            font-family: var(--font-family);
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-submit:hover {
            background: #1E1E45;
            box-shadow: 0 4px 12px rgba(43, 43, 94, 0.2);
        }

        .footer {
            color: var(--color-ink);
            font-size: 12px;
            margin-top: 24px;
        }
    </style>
</head>

<body>

    <main class="pending-card">
        <div class="brand-stripe"></div>

        <!-- BRAND -->
        <div class="brand">
            <h1>FUTURI<span>SE</span></h1>
            <p>Future Innovation and Creativity</p>
        </div>

        <div class="icon-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="12" cy="12" r="9"/>
                <path d="M12 7v5l3 2"/>
            </svg>
        </div>

        <h2>Akun Menunggu Persetujuan</h2>

        <p class="description">
            Alamat email Anda telah berhasil diverifikasi. Akun PIC Anda saat ini sedang dalam proses verifikasi oleh panitia administrator FUTURiSE.
        </p>

        <div class="info-box">
            <small>Nama Lengkap</small>
            <strong>{{ auth()->user()->name }}</strong>
        </div>

        <div class="info-box">
            <small>Alamat Email</small>
            <strong>{{ auth()->user()->email }}</strong>
        </div>

        <div class="info-box">
            <small>Kategori PIC</small>
            <strong>{{ strtoupper(auth()->user()->pic_category ?? '-') }}</strong>
        </div>

        <div class="status-badge">
            STATUS: MENUNGGU PERSETUJUAN ADMIN
        </div>

        <p class="notice">
            Anda akan dapat mengakses seluruh fitur pengelolaan karya dan booth setelah disetujui. Silakan cek berkala.
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-submit">
                Keluar dari Akun
            </button>
        </form>

        <div class="footer">
            © {{ date('Y') }} FUTURISE. All Rights Reserved.
        </div>
    </main>

</body>
</html>
