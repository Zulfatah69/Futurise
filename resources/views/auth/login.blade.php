<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - FUTURiSE</title>

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
            padding: 24px 20px;
            background-color: var(--color-surface);
            background-image: 
                radial-gradient(at 0% 0%, rgba(0, 205, 229, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(43, 43, 94, 0.12) 0px, transparent 50%);
        }

        /* SHARP CARD (APTIKOM STANDARD: 0px RADIUS) */
        .login-card {
            width: 100%;
            max-width: 440px;
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
            margin-bottom: 28px;
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
            letter-spacing: 0.5px;
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
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 26px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: var(--color-primary);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0px;
        }

        input[type="email"],
        input[type="password"] {
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

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
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
            width: 19px;
            height: 19px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
        }

        .remember-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13px;
        }

        .remember-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--color-ink);
            cursor: pointer;
            font-weight: 500;
        }

        .remember-label input {
            accent-color: var(--color-primary);
            width: 16px;
            height: 16px;
        }

        .error-box {
            color: #991B1B;
            background: #FEF2F2;
            border-left: 4px solid #EF4444;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .status-box {
            color: #065F46;
            background: #ECFDF5;
            border-left: 4px solid var(--color-success);
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 20px;
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
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: 0px;
        }

        .btn-submit:hover {
            background: #1E1E45;
            box-shadow: 0 4px 12px rgba(43, 43, 94, 0.2);
        }

        .register-link {
            text-align: center;
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid var(--color-hairline);
            color: var(--color-ink);
            font-size: 13px;
        }

        .register-link a {
            color: var(--color-accent);
            font-weight: 700;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <main class="login-card">
        <div class="brand-stripe"></div>

        <!-- BRAND -->
        <div class="brand">
            <h1>FUTURI<span>SE</span></h1>
            <p>Future Innovation and Creativity</p>
        </div>

        <!-- HEADING -->
        <h2 class="heading">Selamat Datang Kembali</h2>
        <p class="subtitle">
            Masuk dengan akun yang telah terdaftar untuk mengakses dashboard.
        </p>

        <!-- FLASH STATUS -->
        @if (session('status'))
            <div class="status-box">
                {{ session('status') }}
            </div>
        @endif

        <!-- ERROR MESSAGE -->
        @if ($errors->any())
            <div class="error-box">
                <strong>Gagal masuk:</strong>
                <ul style="padding-left: 18px; margin-top: 4px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- LOGIN FORM -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="nama@email.com"
                    required
                    autofocus
                    autocomplete="email"
                >
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan kata sandi"
                        required
                        autocomplete="current-password"
                    >
                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword()"
                        aria-label="Tampilkan kata sandi"
                    >
                        <svg id="eye-icon" viewBox="0 0 24 24">
                            <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- REMEMBER ME -->
            <div class="remember-row">
                <label class="remember-label">
                    <input type="checkbox" name="remember">
                    <span>Ingat saya</span>
                </label>
            </div>

            <!-- SUBMIT -->
            <button type="submit" class="btn-submit">
                Masuk ke Akun
            </button>
        </form>

        <!-- REGISTER LINK -->
        <div class="register-link">
            Belum memiliki akun?
            <a href="{{ route('register') }}">
                Daftar sekarang
            </a>
        </div>
    </main>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            document.getElementById('eye-icon').innerHTML = isHidden
                ? `<path d="M3 3l18 18"/><path d="M10.6 10.6a2 2 0 002.8 2.8"/><path d="M9.9 5.2A10.8 10.8 0 0112 5c6.4 0 10 7 10 7a14.7 14.7 0 01-3.1 3.9"/><path d="M6.2 6.2C3.5 8 2 12 2 12s3.6 7 10 7c1.2 0 2.3-.2 3.3-.6"/>`
                : `<path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>`;
        }
    </script>

</body>
</html>
