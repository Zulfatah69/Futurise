<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun - FUTURISE</title>

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
            padding: 24px 20px;
            background-color: var(--color-surface);
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 205, 229, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(43, 43, 94, 0.12) 0px, transparent 50%);
        }

        .verify-card {
            width: 100%;
            max-width: 440px;
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
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(0, 205, 229, 0.12);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-circle svg {
            width: 30px;
            height: 30px;
            stroke-width: 2;
        }

        h2 {
            color: var(--color-primary);
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 10px;
        }

        p {
            color: var(--color-ink);
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .btn-submit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 52px;
            border: none;
            border-radius: var(--radius-btn);
            background: var(--color-primary);
            color: var(--color-canvas);
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-submit:hover {
            background: #1E1E45;
            box-shadow: 0 4px 12px rgba(43, 43, 94, 0.2);
        }
    </style>
</head>

<body>

    <main class="verify-card">
        <div class="brand-stripe"></div>

        <div class="brand">
            <h1>FUTURI<span>SE</span></h1>
            <p>Future Innovation and Creativity</p>
        </div>

        <div class="icon-circle">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h2>Verifikasi Akun OTP</h2>
        <p>
            Akun Anda memerlukan verifikasi menggunakan kode 6-digit OTP yang telah kami kirimkan ke email Anda.
        </p>

        <a href="{{ route('otp.show') }}" class="btn-submit">
            Menuju Halaman Input OTP →
        </a>
    </main>

</body>
</html>
