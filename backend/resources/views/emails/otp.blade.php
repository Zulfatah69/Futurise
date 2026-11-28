<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi OTP - FUTURiSE</title>
    <style>
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            .email-padding {
                padding: 24px 20px !important;
            }
            .otp-digits {
                font-size: 30px !important;
                letter-spacing: 8px !important;
                text-indent: 8px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #F4F7FC; font-family: 'Kumbh Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif; color: #1E293B; -webkit-font-smoothing: antialiased;">

    <!-- Preheader text untuk pratinjau inbox -->
    <div style="display: none; max-height: 0; overflow: hidden; opacity: 0; font-size: 1px; line-height: 1px; color: #F4F7FC;">
        Kode verifikasi OTP FUTURiSE Anda adalah {{ $otp }} (berlaku selama {{ $expiresMinutes }} menit).
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #F4F7FC; width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 32px 14px;">

                <table role="presentation" class="email-container" width="560" cellpadding="0" cellspacing="0" border="0" style="width: 560px; max-width: 560px; background-color: #FFFFFF; border: 1px solid #E2E8F0; border-collapse: collapse;">
                    <!-- Top Accent Bar (Deep Navy + Cyan) -->
                    <tr>
                        <td style="padding: 0; height: 5px; background-color: #2B2B5E;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse: collapse;">
                                <tr>
                                    <td width="82%" style="height: 5px; background-color: #2B2B5E; font-size: 0; line-height: 0;">&nbsp;</td>
                                    <td width="18%" style="height: 5px; background-color: #00CDE5; font-size: 0; line-height: 0;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Header Brand -->
                    <tr>
                        <td class="email-padding" style="padding: 28px 34px 20px; text-align: center; border-bottom: 1px solid #E2E8F0;">
                            <div style="font-size: 26px; font-weight: 800; letter-spacing: -0.6px; color: #2B2B5E; line-height: 1.1;">
                                FUTU<span style="color: #00CDE5;">RiSE</span>
                            </div>
                            <div style="font-size: 12px; font-weight: 500; color: #64748B; margin-top: 6px;">
                                West Java Digital Innovation Festival &bull; APTIKOM Jawa Barat
                            </div>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td class="email-padding" style="padding: 30px 34px;">
                            <div style="font-size: 18px; font-weight: 700; color: #2B2B5E; margin-bottom: 12px;">
                                Halo, {{ $userName }}
                            </div>

                            <p style="margin: 0 0 22px; font-size: 14px; line-height: 1.7; color: #475569;">
                                Gunakan 6-digit kode verifikasi (OTP) berikut untuk mengonfirmasi alamat email dan menyelesaikan verifikasi akun Anda di portal FUTURiSE:
                            </p>

                            <!-- Centered OTP Box -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 22px; border-collapse: collapse;">
                                <tr>
                                    <td align="center" style="background-color: #F8FAFC; border: 1.5px solid #CBD5E1; border-top: 3px solid #017DC7; border-radius: 8px; padding: 24px 16px;">
                                        <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; color: #64748B; margin-bottom: 12px;">
                                            Kode Verifikasi Akun
                                        </div>
                                        <div class="otp-digits" style="font-family: 'Courier New', Courier, monospace; font-size: 38px; font-weight: 800; letter-spacing: 12px; text-indent: 12px; color: #2B2B5E; line-height: 1.1;">
                                            {{ $otp }}
                                        </div>
                                        <div style="margin-top: 14px; display: inline-block; padding: 5px 12px; background-color: #EFF6FF; border-radius: 999px; font-size: 12px; font-weight: 600; color: #017DC7;">
                                            Berlaku {{ $expiresMinutes }} menit &bull; 1 kali penggunaan
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Meta Info Table -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 20px; border-collapse: collapse; background-color: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 12px 16px; font-size: 12.5px; color: #475569; line-height: 1.6;">
                                        <strong style="color: #2B2B5E;">Catatan Keamanan:</strong>
                                        Jangan bagikan kode OTP ini kepada siapa pun termasuk pihak yang mengaku sebagai panitia. Jika Anda tidak merasa melakukan pendaftaran, abaikan email ini.
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 12.5px; color: #64748B; line-height: 1.6;">
                                Anda juga dapat membuka kembali halaman verifikasi pada portal dan memasukkan kode di atas sebelum masa berlaku berakhir.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #F8FAFC; padding: 18px 34px; text-align: center; border-top: 1px solid #E2E8F0; font-size: 11.5px; color: #64748B; line-height: 1.5;">
                            &copy; {{ date('Y') }} FUTURiSE &bull; APTIKOM Jawa Barat.<br>
                            Email ini dikirim otomatis oleh sistem, mohon tidak membalas ke alamat pengirim ini.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>
