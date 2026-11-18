<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - FUTURiSE</title>

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
        .otp-card {
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
            width: 58px;
            height: 58px;
            border-radius: 50%;
            background: rgba(0, 205, 229, 0.12);
            color: var(--color-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
        }

        .icon-circle svg {
            width: 28px;
            height: 28px;
            stroke-width: 2;
        }

        .heading {
            color: var(--color-primary);
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: var(--color-ink);
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .subtitle strong {
            color: var(--color-primary);
            font-weight: 700;
        }

        .status-box {
            color: #065F46;
            background: #ECFDF5;
            border-left: 4px solid var(--color-success);
            padding: 12px 14px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 18px;
            text-align: left;
        }

        .error-box {
            color: #991B1B;
            background: #FEF2F2;
            border-left: 4px solid #EF4444;
            padding: 12px 14px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 18px;
            text-align: left;
        }

        .otp-input-group {
            margin-bottom: 22px;
        }

        .otp-input {
            width: 100%;
            padding: 14px 10px;
            border: 2px solid var(--color-hairline);
            border-radius: var(--radius-btn);
            font-size: 28px;
            font-weight: 800;
            font-family: var(--font-family);
            text-align: center;
            letter-spacing: 12px;
            color: var(--color-primary);
            background: var(--color-surface);
            outline: none;
            transition: all 0.2s ease;
        }

        .otp-input:focus {
            border-color: var(--color-primary);
            background: var(--color-canvas);
            box-shadow: 0 0 0 3px rgba(43, 43, 94, 0.12);
        }

        .otp-input::placeholder {
            color: var(--color-body);
            letter-spacing: 6px;
            font-size: 20px;
            font-weight: normal;
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
            letter-spacing: 0px;
        }

        .btn-submit:hover {
            background: #1E1E45;
            box-shadow: 0 4px 12px rgba(43, 43, 94, 0.2);
        }

        .resend-section {
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid var(--color-hairline);
            font-size: 13px;
            color: var(--color-ink);
        }

        .btn-link {
            background: none;
            border: none;
            color: var(--color-accent);
            font-size: 13px;
            font-weight: 700;
            font-family: var(--font-family);
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
        }

        .btn-link:hover {
            color: var(--color-primary);
        }
    </style>
</head>

<body>

    <main class="otp-card">
        <div class="brand-stripe"></div>

        <!-- BRAND -->
        <div class="brand">
            <h1>FUTURI<span>SE</span></h1>
            <p>Future Innovation and Creativity</p>
        </div>

        <div class="icon-circle">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h2 class="heading">Verifikasi Email Anda</h2>
        <p class="subtitle">
            Kode 6-digit OTP telah dikirim ke alamat email:<br>
            <strong>{{ auth()->user()?->email }}</strong>
        </p>

        <!-- STATUS -->
        @if (session('status'))
            <div class="status-box">
                {{ session('status') }}
            </div>
        @endif

        <!-- ERROR -->
        @if ($errors->has('otp'))
            <div class="error-box">
                {{ $errors->first('otp') }}
            </div>
        @endif

        <!-- FORM VERIFIKASI -->
        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <div class="otp-input-group">
                <input
                    type="text"
                    id="otp"
                    name="otp"
                    class="otp-input"
                    inputmode="numeric"
                    pattern="\d{6}"
                    maxlength="6"
                    placeholder="— — — — — —"
                    autocomplete="one-time-code"
                    required
                    autofocus
                >
            </div>

            <button type="submit" class="btn-submit">
                Verifikasi & Masuk Dashboard
            </button>
        </form>

        <!-- RESEND OTP -->
        <div class="resend-section">
            <span id="resend-container">
                @if ($cooldown > 0)
                    <span>Kirim ulang kode dalam <strong id="countdown" style="color: var(--color-primary);">{{ $cooldown }}</strong> detik</span>
                @else
                    <span>Tidak menerima kode? </span>
                    <form method="POST" action="{{ route('otp.resend') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-link">Kirim Ulang OTP</button>
                    </form>
                @endif
            </span>
        </div>

        <form method="POST" action="{{ route('logout') }}" style="margin-top: 16px;">
            @csrf
            <button type="submit" style="background: none; border: none; color: var(--color-ink); font-size: 12px; font-family: var(--font-family); cursor: pointer; text-decoration: underline;">
                Gunakan akun lain (Keluar)
            </button>
        </form>
    </main>

    @if ($cooldown > 0)
    <script>
        let seconds = {{ $cooldown }};
        const el = document.getElementById('countdown');
        const container = document.getElementById('resend-container');

        const timer = setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                clearInterval(timer);
                container.innerHTML = '<span>Tidak menerima kode? </span><form method="POST" action="{{ route('otp.resend') }}" style="display: inline;">@csrf<button type="submit" class="btn-link">Kirim Ulang OTP</button></form>';
            } else if (el) {
                el.textContent = seconds;
            }
        }, 1000);
    </script>
    @endif

</body>
</html>
