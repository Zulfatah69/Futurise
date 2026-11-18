<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - FUTURISE</title>

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
            background-color: var(--color-surface);
            color: #1A1A36;
        }

        /* NAVBAR (APTIKOM DEEP NAVY ANCHOR) */
        .navbar {
            width: 100%;
            background: var(--color-primary);
            padding: 16px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--color-success);
        }

        .brand {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--color-canvas);
        }

        .brand span {
            color: var(--color-success);
        }

        .logout-btn {
            background: transparent;
            color: var(--color-canvas);
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 8px 20px;
            border-radius: var(--radius-btn);
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            font-family: var(--font-family);
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: var(--color-canvas);
            color: var(--color-primary);
            border-color: var(--color-canvas);
        }

        .container {
            max-width: 1024px;
            margin: 40px auto 60px;
            padding: 0 20px;
        }

        /* SHARP CARDS (APTIKOM STANDARD: 0px RADIUS) */
        .card {
            background: var(--color-canvas);
            padding: 36px 40px;
            border-radius: var(--radius-card);
            border: 1px solid var(--color-hairline);
            box-shadow: 0 4px 16px rgba(43, 43, 94, 0.04);
            margin-bottom: 24px;
        }

        .card-header-stripe {
            border-top: 4px solid var(--color-primary);
        }

        .subtitle {
            color: var(--color-accent);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        h1 {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.8px;
            color: var(--color-primary);
            margin-bottom: 8px;
        }

        .description {
            color: var(--color-ink);
            line-height: 1.8;
            margin-bottom: 24px;
            font-size: 15px;
        }

        .alert-success {
            background: #ECFDF5;
            color: #065F46;
            padding: 14px 18px;
            border-radius: 4px;
            border-left: 4px solid var(--color-success);
            margin-bottom: 24px;
            font-size: 14px;
            line-height: 1.6;
        }

        .alert-error {
            background: #FEF2F2;
            color: #991B1B;
            padding: 14px 18px;
            border-radius: 4px;
            border-left: 4px solid #EF4444;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-top: 18px;
        }

        .info-box {
            background: var(--color-surface);
            padding: 20px 22px;
            border-radius: var(--radius-card);
            border: 1px solid var(--color-hairline);
        }

        .info-box small {
            display: block;
            color: var(--color-ink);
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-box strong {
            color: var(--color-primary);
            font-size: 16px;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: var(--radius-btn);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: rgba(0, 205, 229, 0.15);
            color: var(--color-primary);
            border: 1px solid var(--color-success);
        }

        .badge-warning {
            background: #FEF3C7;
            color: #92400E;
            border: 1px solid #F59E0B;
        }

        /* PESERTA REGISTRATION FORM SECTION */
        .register-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--color-hairline);
        }

        .register-section-header h2 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.4px;
            color: var(--color-primary);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        label {
            display: block;
            color: var(--color-primary);
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid var(--color-hairline);
            border-radius: var(--radius-btn);
            outline: none;
            font-size: 14px;
            font-family: var(--font-family);
            background: var(--color-canvas);
            color: #1A1A36;
            transition: all 0.2s ease;
        }

        input:focus, select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(43, 43, 94, 0.12);
        }

        /* BUTTON (APTIKOM STANDARD: 8px RADIUS, PRIMARY NAVY) */
        .btn-primary {
            background: var(--color-primary);
            color: var(--color-canvas);
            border: none;
            padding: 14px 32px;
            border-radius: var(--radius-btn);
            font-size: 15px;
            font-weight: 700;
            font-family: var(--font-family);
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary:hover {
            background: #1E1E45;
            box-shadow: 0 4px 12px rgba(43, 43, 94, 0.2);
        }

        .empty-peserta-banner {
            background: rgba(0, 205, 229, 0.08);
            border: 1px dashed var(--color-accent);
            border-radius: var(--radius-card);
            padding: 26px;
            text-align: center;
            margin-bottom: 26px;
        }

        .empty-peserta-banner h3 {
            color: var(--color-primary);
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.4px;
            margin-bottom: 8px;
        }

        .empty-peserta-banner p {
            color: var(--color-ink);
            font-size: 14px;
            max-width: 640px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* AUTOCOMPLETE DROPDOWN */
        .suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--color-canvas);
            border: 1px solid var(--color-hairline);
            border-radius: var(--radius-card);
            margin-top: 4px;
            max-height: 280px;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 0 8px 24px rgba(43, 43, 94, 0.12);
        }

        .suggestion-item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid var(--color-surface);
            transition: all 0.15s ease;
            text-align: left;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .suggestion-item:hover {
            background: rgba(0, 205, 229, 0.08);
        }

        .suggestion-name {
            font-weight: 700;
            color: var(--color-primary);
            font-size: 13px;
        }

        .suggestion-meta {
            font-size: 11px;
            color: var(--color-ink);
            margin-top: 3px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .suggestion-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-pt {
            background: rgba(43, 43, 94, 0.1);
            color: var(--color-primary);
        }

        .badge-smk {
            background: rgba(0, 205, 229, 0.15);
            color: #007A8A;
        }

        .footer {
            text-align: center;
            color: var(--color-ink);
            font-size: 13px;
            margin: 40px 0;
        }

        @media (max-width: 700px) {
            .card {
                padding: 26px 20px;
            }

            .info-grid, .form-row {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 16px 20px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="brand">FUTURI<span>SE</span></div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                Keluar
            </button>
        </form>
    </nav>

    <main class="container">

        <!-- FLASH NOTIFICATION -->
        @if (session('status'))
            <div class="alert-success">
                ✅ {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Terjadi kesalahan:</strong>
                <ul style="margin-top: 6px; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- WELCOME CARD -->
        <section class="card card-header-stripe">
            <div class="subtitle">PORTAL AKUN PENGGUNA</div>
            <h1>Halo, {{ $user->name }}!</h1>
            <p class="description">
                Selamat datang di Dashboard FUTURiSE. Akun Anda telah terverifikasi dan siap digunakan.
            </p>

            <div class="info-grid">
                <div class="info-box">
                    <small>Nama Lengkap</small>
                    <strong>{{ $user->name }}</strong>
                </div>

                <div class="info-box">
                    <small>Email Terverifikasi</small>
                    <strong>{{ $user->email }}</strong>
                </div>

                <div class="info-box">
                    <small>Nomor Telepon</small>
                    <strong>{{ $user->person?->phone ?? '-' }}</strong>
                </div>

                <div class="info-box">
                    <small>Status Akun</small>
                    <span class="badge badge-success">
                        AKTIF
                    </span>
                </div>
            </div>
        </section>

        <!-- KEPESERTAAN LOMBA -->
        @if ($user->peserta)
            <!-- SUDAH TERDAFTAR SEBAGAI PESERTA -->
            <section class="card">
                <div class="register-section-header">
                    <h2>🎖️ Profil Kepesertaan Lomba</h2>
                    <span class="badge badge-success">TERDAFTAR RESMI</span>
                </div>

                <div class="info-grid">
                    <div class="info-box">
                        <small>Asal Kampus / Institusi</small>
                        <strong>{{ $user->peserta->institusi }}</strong>
                    </div>

                    <div class="info-box">
                        <small>Kategori Peserta</small>
                        <strong>{{ strtoupper($user->peserta->jenis_peserta) }}</strong>
                    </div>

                    <div class="info-box">
                        <small>Nomor Identitas (NIM / NISN / KTP)</small>
                        <strong>{{ $user->peserta->nomor_identitas ?? '-' }}</strong>
                    </div>

                    <div class="info-box">
                        <small>No. Kontak Peserta</small>
                        <strong>{{ $user->peserta->no_telepon ?? '-' }}</strong>
                    </div>
                </div>

                <div style="margin-top: 24px; padding: 16px; background: var(--color-surface); border: 1px solid var(--color-hairline); font-size: 13px; color: var(--color-ink); line-height: 1.6;">
                    ℹ️ Profil kepesertaan Anda sudah aktif. Anda dapat mengikuti kompetisi dan mengunggah karya saat submisi dibuka.
                </div>
            </section>
        @else
            <!-- BELUM TERDAFTAR SEBAGAI PESERTA: TAMPILKAN FORM TAHAP 2 -->
            <section class="card">
                <div class="empty-peserta-banner">
                    <h3>🏆 Ingin Mengikuti Lomba FUTURiSE?</h3>
                    <p>
                        Lengkapi form pendaftaran peserta di bawah ini untuk mendaftarkan institusi dan identitas kamu sebagai peserta lomba resmi.
                    </p>
                </div>

                <div class="register-section-header">
                    <h2>Formulir Pendaftaran Peserta Lomba</h2>
                    <span class="badge badge-warning">BELUM TERDAFTAR</span>
                </div>

                <form method="POST" action="{{ route('participant.register') }}">
                    @csrf

                    <div class="form-row">
                        <!-- AUTOCOMPLETE INSTITUSI -->
                        <div class="form-group">
                            <label for="institusi">Asal Kampus / Sekolah / Institusi <span style="color: #DC2626;">*</span></label>
                            <div style="position: relative;">
                                <input
                                    type="text"
                                    id="institusi"
                                    name="institusi"
                                    value="{{ old('institusi') }}"
                                    placeholder="Ketik nama kampus (misal: ITENAS) atau SMK..."
                                    autocomplete="off"
                                    required
                                >
                                <div id="institusi-spinner" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); display: none; font-size: 12px; color: var(--color-ink);">
                                    ⏳
                                </div>
                            </div>
                            <div id="institusi-suggestions" class="suggestions-dropdown" style="display: none;"></div>
                            <p style="font-size: 11px; color: var(--color-ink); margin-top: 5px;">
                                💡 <em>Saran institusi akan otomatis muncul dari database (4.300+ PT & 14.000+ SMK).</em>
                            </p>
                        </div>

                        <!-- KATEGORI PESERTA -->
                        <div class="form-group">
                            <label for="jenis_peserta">Kategori Peserta <span style="color: #DC2626;">*</span></label>
                            <select id="jenis_peserta" name="jenis_peserta" required>
                                <option value="mahasiswa" @selected(old('jenis_peserta') === 'mahasiswa')>Mahasiswa (Perguruan Tinggi)</option>
                                <option value="siswa" @selected(old('jenis_peserta') === 'siswa')>Siswa (SMA / SMK / Sederajat)</option>
                                <option value="umum" @selected(old('jenis_peserta') === 'umum')>Umum</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="nomor_identitas">Nomor Identitas (NIM / NISN / KTP) <span style="color: #DC2626;">*</span></label>
                            <input
                                type="text"
                                id="nomor_identitas"
                                name="nomor_identitas"
                                value="{{ old('nomor_identitas') }}"
                                placeholder="Contoh: 152024183"
                                required
                            >
                        </div>

                        <div class="form-group">
                            <label for="no_telepon">Nomor WhatsApp / Telepon Aktif</label>
                            <input
                                type="tel"
                                id="no_telepon"
                                name="no_telepon"
                                value="{{ old('no_telepon', $user->person?->phone) }}"
                                placeholder="Contoh: 081234567890"
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">
                        Simpan & Daftarkan Sebagai Peserta
                    </button>
                </form>
            </section>
        @endif

    </main>

    <footer class="footer">
        © {{ date('Y') }} FUTURISE. All Rights Reserved.
    </footer>

    <!-- AUTOCOMPLETE SCRIPT -->
    <script>
        const institusiInput = document.getElementById('institusi');
        const suggestionsBox = document.getElementById('institusi-suggestions');
        const spinner = document.getElementById('institusi-spinner');
        const jenisPesertaSelect = document.getElementById('jenis_peserta');

        let debounceTimer = null;

        if (institusiInput && suggestionsBox) {
            institusiInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(debounceTimer);

                if (query.length < 2) {
                    suggestionsBox.style.display = 'none';
                    suggestionsBox.innerHTML = '';
                    return;
                }

                if (spinner) spinner.style.display = 'block';

                debounceTimer = setTimeout(() => {
                    const jenis = jenisPesertaSelect ? jenisPesertaSelect.value : '';
                    let kategori = '';
                    if (jenis === 'mahasiswa') kategori = 'perguruan_tinggi';
                    else if (jenis === 'siswa') kategori = 'smk';

                    fetch(`/api/v1/institusi/search?q=${encodeURIComponent(query)}&kategori=${encodeURIComponent(kategori)}`)
                        .then(res => res.json())
                        .then(res => {
                            if (spinner) spinner.style.display = 'none';
                            if (res.success && res.data && res.data.length > 0) {
                                suggestionsBox.innerHTML = res.data.map(item => {
                                    const badgeClass = item.kategori === 'perguruan_tinggi' ? 'badge-pt' : 'badge-smk';
                                    const badgeLabel = item.kategori === 'perguruan_tinggi' ? 'Perguruan Tinggi' : 'SMK';
                                    const location = [item.kab_kota, item.provinsi].filter(Boolean).join(', ');
                                    return `
                                        <div class="suggestion-item" data-name="${item.nama.replace(/"/g, '&quot;')}">
                                            <div class="suggestion-name">${item.nama}</div>
                                            <div class="suggestion-meta">
                                                <span class="suggestion-badge ${badgeClass}">${badgeLabel}</span>
                                                ${location ? `<span>📍 ${location}</span>` : ''}
                                            </div>
                                        </div>
                                    `;
                                }).join('');
                                suggestionsBox.style.display = 'block';

                                suggestionsBox.querySelectorAll('.suggestion-item').forEach(el => {
                                    el.addEventListener('click', function() {
                                        institusiInput.value = this.dataset.name;
                                        suggestionsBox.style.display = 'none';
                                    });
                                });
                            } else {
                                suggestionsBox.innerHTML = `
                                    <div style="padding: 12px; font-size: 12px; color: var(--color-ink); text-align: center;">
                                        Tidak ditemukan di daftar. Anda tetap dapat mengetik nama institusi Anda secara manual.
                                    </div>
                                `;
                                suggestionsBox.style.display = 'block';
                            }
                        })
                        .catch(() => {
                            if (spinner) spinner.style.display = 'none';
                        });
                }, 200);
            });

            document.addEventListener('click', function(e) {
                if (!institusiInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.style.display = 'none';
                }
            });
        }
    </script>

</body>
</html>
