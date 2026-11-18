<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Akun - FUTURiSE</title>
    <style>
        body {
            font-family: 'Kumbh Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
            background-color: #F7F7F7;
            margin: 0;
            padding: 30px 15px;
            color: #1A1A36;
        }
        .container {
            max-width: 520px;
            margin: 0 auto;
            background: #FFFFFF;
            border: 1px solid #E2DFEB;
            border-top: 5px solid #2B2B5E;
            box-shadow: 0 4px 16px rgba(43, 43, 94, 0.05);
        }
        .header {
            padding: 28px 30px 20px;
            text-align: center;
            border-bottom: 1px solid #F1F1F6;
        }
        .brand-name {
            color: #2B2B5E;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0;
        }
        .brand-name span {
            color: #00CDE5;
        }
        .brand-sub {
            color: #82828A;
            font-size: 12px;
            font-weight: 500;
            margin-top: 4px;
        }
        .body {
            padding: 32px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #2B2B5E;
            margin-bottom: 12px;
        }
        .message {
            font-size: 14px;
            line-height: 1.8;
            color: #4A4A5A;
            margin-bottom: 24px;
        }
        .otp-container {
            background: #F7F7F7;
            border: 2px dashed #00CDE5;
            padding: 24px;
            text-align: center;
            margin: 24px 0;
        }
        .otp-code {
            font-size: 38px;
            font-weight: 800;
            letter-spacing: 12px;
            color: #2B2B5E;
            line-height: 1;
            margin-left: 12px; /* balance letter-spacing */
        }
        .expiry-note {
            color: #D97706;
            font-size: 12px;
            font-weight: 600;
            margin-top: 12px;
        }
        .security-note {
            font-size: 12px;
            color: #82828A;
            line-height: 1.6;
            margin-top: 20px;
            border-top: 1px solid #E2DFEB;
            padding-top: 16px;
        }
        .footer {
            background: #F7F7F7;
            padding: 18px 30px;
            text-align: center;
            border-top: 1px solid #E2DFEB;
            font-size: 11px;
            color: #82828A;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="brand-name">FUTURI<span>SE</span></h1>
            <div class="brand-sub">Future Innovation and Creativity &bull; APTIKOM</div>
        </div>

        <div class="body">
            <div class="greeting">Halo, {{ $userName }}!</div>
            <p class="message">
                Terima kasih telah mendaftar di portal FUTURiSE. Untuk memverifikasi alamat email dan mengaktifkan akun Anda, masukkan 6-digit kode OTP resmi berikut:
            </p>

            <div class="otp-container">
                <div class="otp-code">{{ $otp }}</div>
                <div class="expiry-note">⏱ Berlaku selama {{ $expiresMinutes }} menit &bull; 1 kali penggunaan</div>
            </div>

            <div class="security-note">
                <strong>Catatan Keamanan:</strong> Jangan berikan kode ini kepada siapa pun. Tim FUTURiSE tidak akan pernah meminta kode verifikasi Anda. Jika Anda tidak merasa mendaftar, abaikan email ini.
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} FUTURiSE Indonesia. Seluruh hak cipta dilindungi undang-undang.
        </div>
    </div>
</body>
</html>
