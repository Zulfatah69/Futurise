<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - FUTURiSE</title>

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
            padding: 35px 20px;
            background-color: var(--color-surface);
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 205, 229, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(43, 43, 94, 0.12) 0px, transparent 50%);
        }

        /* SHARP CARD (APTIKOM STANDARD: 0px RADIUS) */
        .register-card {
            width: 100%;
            max-width: 480px;
            background: var(--color-canvas);
            padding: 44px 38px;
            border-radius: var(--radius-card);
            border: 1px solid var(--color-hairline);
            border-top: 5px solid var(--color-primary);
            box-shadow: 0 8px 24px rgba(43, 43, 94, 0.06);
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
            text-align: center;
            margin-bottom: 26px;
        }

        .brand h1 {
            color: var(--color-primary);
            font-size: 32px;
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

        .heading {
            color: var(--color-primary);
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 6px;
        }

        .subtitle {
            color: var(--color-ink);
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            color: var(--color-primary);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 7px;
        }

        input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid var(--color-hairline);
            border-radius: var(--radius-btn);
            outline: none;
            font-size: 14px;
            font-family: var(--font-family);
            color: #1A1A36;
            background: var(--color-canvas);
            transition: all 0.2s ease;
        }

        input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(43, 43, 94, 0.12);
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 46px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            border: none;
            background: transparent;
            color: var(--color-ink);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: var(--color-primary);
        }

        .toggle-password svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
        }

        .error-box {
            color: #991B1B;
            background: #FEF2F2;
            border-left: 4px solid #EF4444;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .error-box ul {
            padding-left: 18px;
            margin-top: 4px;
        }

        .notice-box {
            margin-top: 18px;
            padding: 12px 14px;
            background: var(--color-surface);
            border: 1px solid var(--color-hairline);
            border-radius: 4px;
            font-size: 12px;
            color: var(--color-ink);
            text-align: center;
            line-height: 1.5;
        }

        /* BUTTON (APTIKOM STANDARD: 8px RADIUS, PRIMARY NAVY) */
        .btn-submit {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: var(--radius-btn);
            background: var(--color-primary);
            color: var(--color-canvas);
            font-size: 16px;
            font-weight: 700;
            font-family: var(--font-family);
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
            letter-spacing: 0px;
        }

        .btn-submit:hover {
            background: #1E1E45;
            box-shadow: 0 4px 12px rgba(43, 43, 94, 0.2);
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--color-hairline);
            color: var(--color-ink);
            font-size: 13px;
        }

        .login-link a {
            color: var(--color-accent);
            font-weight: 700;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <main class="register-card">
        <div class="brand-stripe"></div>

        <!-- BRAND -->
        <div class="brand">
            <h1>FUTURI<span>SE</span></h1>
            <p>Future Innovation and Creativity</p>
        </div>

        <!-- HEADING -->
        <h2 class="heading">Daftar Akun Baru</h2>
        <p class="subtitle">
            Buat akun untuk masuk ke portal FUTURiSE. Pendaftaran kompetisi/lomba dapat dilengkapi setelah akun aktif di dashboard.
        </p>

        <!-- VALIDATION ERROR -->
        @if ($errors->any())
            <div class="error-box">
                <strong>Registrasi belum berhasil:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- REGISTER FORM -->
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- NAMA LENGKAP -->
            <div class="form-group">
                <label for="name">Nama Lengkap <span style="color: #DC2626;">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Muhammad Zulfatah"
                    required
                    autofocus
                    autocomplete="name"
                >
            </div>

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email">Alamat Email <span style="color: #DC2626;">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="nama@email.com"
                    required
                    autocomplete="email"
                >
            </div>

            <!-- NOMOR HP / WHATSAPP -->
            <div class="form-group">
                <label for="phone">Nomor HP / WhatsApp <span style="color: var(--color-ink); font-weight: 400;">(Opsional)</span></label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="Contoh: 081234567890"
                    autocomplete="tel"
                >
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label for="password">Kata Sandi <span style="color: #DC2626;">*</span></label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Minimal 8 karakter"
                        required
                        autocomplete="new-password"
                    >
                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword('password', this)"
                        aria-label="Tampilkan kata sandi"
                    >
                        <svg viewBox="0 0 24 24">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- KONFIRMASI PASSWORD -->
            <div class="form-group">
                <label for="password_confirmation">Ulangi Kata Sandi <span style="color: #DC2626;">*</span></label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Ulangi kata sandi"
                        required
                        autocomplete="new-password"
                    >
                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword('password_confirmation', this)"
                        aria-label="Tampilkan kata sandi"
                    >
                        <svg viewBox="0 0 24 24">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- SUBMIT BUTTON -->
            <button type="submit" class="btn-submit">
                Daftar & Kirim Kode OTP
            </button>

            <div class="notice-box">
                🛡️ Kode OTP verifikasi 6-digit akan dikirim langsung ke alamat email Anda.
            </div>
        </form>

        <!-- LOGIN LINK -->
        <div class="login-link">
            Sudah memiliki akun?
            <a href="{{ route('login') }}">
                Masuk sekarang
            </a>
        </div>
    </main>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            button.innerHTML = isHidden
                ? `<svg viewBox="0 0 24 24"><path d="M3 3l18 18"/><path d="M10.6 10.6a2 2 0 002.8 2.8"/><path d="M9.9 5.2A10.8 10.8 0 0112 5c6.4 0 10 7 10 7a14.7 14.7 0 01-3.1 3.9"/><path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7c1.2 0 2.3-.2 3.3-.6"/></svg>`
                : `<svg viewBox="0 0 24 24"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>`;
        }
    </script>

</body>
</html>
