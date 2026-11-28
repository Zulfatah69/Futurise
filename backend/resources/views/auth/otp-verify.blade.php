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
            --color-primary-dark: #1F1F45;
            --color-accent: #017DC7;
            --color-cyan: #00CDE5;
            --color-canvas: #FFFFFF;
            --color-ink: #1E293B;
            --color-muted: #64748B;
            --color-hairline: #E2E8F0;
            --color-surface: #F8FAFC;
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
            padding: 24px 16px;
            background-color: var(--color-surface);
            background-image:
                radial-gradient(at 0% 0%, rgba(0, 205, 229, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(43, 43, 94, 0.10) 0px, transparent 50%);
            color: var(--color-ink);
            -webkit-font-smoothing: antialiased;
        }

        .otp-card {
            width: 100%;
            max-width: 460px;
            background: var(--color-canvas);
            padding: 40px 36px 34px;
            border-radius: var(--radius-card);
            border: 1px solid var(--color-hairline);
            border-top: 5px solid var(--color-primary);
            box-shadow: 0 10px 30px rgba(43, 43, 94, 0.07);
            text-align: center;
            position: relative;
        }

        .brand-stripe {
            position: absolute;
            top: -5px;
            right: 0;
            width: 92px;
            height: 5px;
            background: var(--color-cyan);
        }

        .brand {
            margin-bottom: 22px;
        }

        .brand h1 {
            color: var(--color-primary);
            font-size: 30px;
            font-weight: 800;
            letter-spacing: -0.8px;
            line-height: 1.1;
        }

        .brand h1 span {
            color: var(--color-cyan);
        }

        .brand p {
            color: var(--color-muted);
            font-size: 12.5px;
            font-weight: 500;
            margin-top: 5px;
        }

        .icon-circle {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: rgba(1, 125, 199, 0.09);
            border: 1px solid rgba(0, 205, 229, 0.35);
            color: var(--color-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .icon-circle svg {
            width: 24px;
            height: 24px;
            stroke-width: 2;
        }

        .heading {
            color: var(--color-primary);
            font-size: 21px;
            font-weight: 700;
            letter-spacing: -0.3px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: var(--color-muted);
            font-size: 13.5px;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .email-chip {
            display: inline-block;
            margin-top: 6px;
            padding: 5px 12px;
            border-radius: 999px;
            background: var(--color-surface);
            border: 1px solid var(--color-hairline);
            color: var(--color-primary);
            font-size: 13px;
            font-weight: 700;
            word-break: break-all;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 6px;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 18px;
            text-align: left;
        }

        .alert svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .alert-success {
            color: #065F46;
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            border-left: 4px solid var(--color-cyan);
        }

        .alert-error {
            color: #991B1B;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            border-left: 4px solid #EF4444;
        }

        .otp-field-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-muted);
            margin-bottom: 10px;
        }

        .otp-digits-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 10px;
        }

        .otp-digit {
            width: 100%;
            height: 58px;
            border: 1.5px solid #CBD5E1;
            border-radius: var(--radius-btn);
            background: var(--color-surface);
            font-size: 24px;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            text-align: center;
            color: var(--color-primary);
            outline: none;
            transition: border-color 0.16s ease, box-shadow 0.16s ease, background-color 0.16s ease;
        }

        .otp-digit:focus {
            border-color: var(--color-accent);
            background: var(--color-canvas);
            box-shadow: 0 0 0 3px rgba(1, 125, 199, 0.15);
        }

        .otp-digit.is-filled {
            border-color: var(--color-primary);
            background: var(--color-canvas);
        }

        .otp-digit.has-error {
            border-color: #EF4444;
            background: #FEF2F2;
        }

        .otp-helper {
            font-size: 12px;
            color: var(--color-muted);
            margin-bottom: 22px;
        }

        .btn-submit {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: var(--radius-btn);
            background: var(--color-primary);
            color: var(--color-canvas);
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.18s ease, box-shadow 0.18s ease, opacity 0.18s ease;
        }

        .btn-submit:hover:not(:disabled) {
            background: var(--color-primary-dark);
            box-shadow: 0 6px 14px rgba(43, 43, 94, 0.22);
        }

        .btn-submit:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .resend-section {
            margin-top: 22px;
            padding-top: 18px;
            border-top: 1px solid var(--color-hairline);
            font-size: 13px;
            color: var(--color-muted);
            line-height: 1.6;
        }

        .cooldown-badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 999px;
            background: #EFF6FF;
            color: var(--color-accent);
            font-weight: 700;
            font-variant-numeric: tabular-nums;
        }

        .btn-link {
            background: none;
            border: none;
            color: var(--color-accent);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
        }

        .btn-link:hover {
            color: var(--color-primary);
        }

        .logout-link {
            margin-top: 14px;
        }

        .logout-link button {
            background: none;
            border: none;
            color: var(--color-muted);
            font-size: 12.5px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: underline;
        }

        .logout-link button:hover {
            color: var(--color-primary);
        }

        .hidden {
            display: none !important;
        }

        @media (max-width: 480px) {
            .otp-card {
                padding: 32px 20px 28px;
            }
            .otp-digits-grid {
                gap: 6px;
            }
            .otp-digit {
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <main class="otp-card">
        <div class="brand-stripe" aria-hidden="true"></div>

        <div class="brand">
            <h1>FUTU<span>RiSE</span></h1>
            <p>West Java Digital Innovation Festival &bull; APTIKOM</p>
        </div>

        <div class="icon-circle" aria-hidden="true">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h2 class="heading">Verifikasi Email Anda</h2>
        <p class="subtitle">
            Masukkan 6-digit kode OTP yang telah dikirim ke:<br>
            <span class="email-chip">{{ auth()->user()?->email }}</span>
        </p>

        @if (session('status'))
            <div class="alert alert-success" role="status">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->has('otp'))
            <div class="alert alert-error" role="alert">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $errors->first('otp') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}" id="otpForm" novalidate>
            @csrf
            <input type="hidden" id="otp" name="otp" value="{{ preg_replace('/\D+/', '', (string) old('otp', '')) }}">

            <label class="otp-field-label" for="otp-digit-0">Kode Verifikasi 6 Digit</label>

            <div class="otp-digits-grid" role="group" aria-label="Input 6 digit kode OTP">
                @for ($i = 0; $i < 6; $i++)
                    <input
                        type="text"
                        id="otp-digit-{{ $i }}"
                        class="otp-digit {{ $errors->has('otp') ? 'has-error' : '' }}"
                        inputmode="numeric"
                        maxlength="1"
                        autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                        aria-label="Digit {{ $i + 1 }} dari 6"
                        {{ $i === 0 ? 'autofocus' : '' }}
                    >
                @endfor
            </div>

            <div class="otp-helper">Anda dapat menempel (paste) langsung 6 digit kode dari email.</div>

            <button type="submit" class="btn-submit" id="submitOtpBtn">
                Verifikasi &amp; Masuk Portal
            </button>
        </form>

        <div class="resend-section">
            <div id="cooldownWrapper" class="{{ $cooldown > 0 ? '' : 'hidden' }}">
                Kirim ulang kode tersedia dalam
                <span class="cooldown-badge" id="countdown">{{ sprintf('%02d:%02d', intdiv($cooldown, 60), $cooldown % 60) }}</span>
            </div>

            <div id="resendWrapper" class="{{ $cooldown > 0 ? 'hidden' : '' }}">
                <span>Belum menerima kode OTP? </span>
                <form method="POST" action="{{ route('otp.resend') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-link">Kirim Ulang OTP</button>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="logout-link">
            @csrf
            <button type="submit">Gunakan akun lain (Keluar)</button>
        </form>
    </main>

    <script>
        (function () {
            const digits = Array.from(document.querySelectorAll('.otp-digit'));
            const hiddenOtp = document.getElementById('otp');
            const otpForm = document.getElementById('otpForm');
            const submitBtn = document.getElementById('submitOtpBtn');

            function syncOtpValue() {
                const value = digits.map((input) => input.value.replace(/\D/g, '')).join('');
                hiddenOtp.value = value;
                digits.forEach((input) => {
                    input.classList.toggle('is-filled', input.value !== '');
                });
                submitBtn.disabled = value.length !== 6;
                return value;
            }

            // Isi ulang bila ada nilai lama (old input)
            const initialDigits = (hiddenOtp.value || '').replace(/\D/g, '').slice(0, 6);
            initialDigits.split('').forEach((char, idx) => {
                if (digits[idx]) {
                    digits[idx].value = char;
                }
            });
            syncOtpValue();

            digits.forEach((input, idx) => {
                input.addEventListener('input', (event) => {
                    const cleaned = event.target.value.replace(/\D/g, '');
                    if (cleaned.length > 1) {
                        cleaned.slice(0, 6).split('').forEach((ch, offset) => {
                            if (digits[idx + offset]) {
                                digits[idx + offset].value = ch;
                            }
                        });
                        const nextFocus = Math.min(idx + cleaned.length, 5);
                        digits[nextFocus].focus();
                    } else {
                        event.target.value = cleaned;
                        if (cleaned !== '' && idx < 5) {
                            digits[idx + 1].focus();
                        }
                    }
                    syncOtpValue();
                });

                input.addEventListener('keydown', (event) => {
                    if (event.key === 'Backspace' && input.value === '' && idx > 0) {
                        digits[idx - 1].value = '';
                        digits[idx - 1].focus();
                        syncOtpValue();
                    } else if (event.key === 'ArrowLeft' && idx > 0) {
                        event.preventDefault();
                        digits[idx - 1].focus();
                    } else if (event.key === 'ArrowRight' && idx < 5) {
                        event.preventDefault();
                        digits[idx + 1].focus();
                    }
                });

                input.addEventListener('paste', (event) => {
                    event.preventDefault();
                    const pasted = (event.clipboardData || window.clipboardData)
                        .getData('text')
                        .replace(/\D/g, '')
                        .slice(0, 6);
                    if (!pasted) return;
                    digits.forEach((box, boxIdx) => {
                        box.value = pasted[boxIdx] || '';
                    });
                    digits[Math.min(pasted.length, 5)].focus();
                    syncOtpValue();
                });

                input.addEventListener('focus', () => {
                    input.select();
                });
            });

            otpForm.addEventListener('submit', () => {
                if (syncOtpValue().length === 6) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Memverifikasi...';
                }
            });

            let seconds = {{ (int) $cooldown }};
            const countdownEl = document.getElementById('countdown');
            const cooldownWrapper = document.getElementById('cooldownWrapper');
            const resendWrapper = document.getElementById('resendWrapper');

            if (seconds > 0 && countdownEl && cooldownWrapper && resendWrapper) {
                const formatTime = (total) => {
                    const mins = String(Math.floor(total / 60)).padStart(2, '0');
                    const secs = String(total % 60).padStart(2, '0');
                    return mins + ':' + secs;
                };

                countdownEl.textContent = formatTime(seconds);

                const timer = setInterval(() => {
                    seconds--;
                    if (seconds <= 0) {
                        clearInterval(timer);
                        cooldownWrapper.classList.add('hidden');
                        resendWrapper.classList.remove('hidden');
                    } else {
                        countdownEl.textContent = formatTime(seconds);
                    }
                }, 1000);
            }
        })();
    </script>
</body>
</html>
