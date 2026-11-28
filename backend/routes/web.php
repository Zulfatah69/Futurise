<?php

use App\Http\Controllers\Api\v1\InstitutionController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\WorkWebController;
use App\Models\Festival;
use App\Models\Karya;
use App\Models\PublikasiKarya;
use App\Models\User;
use App\Services\KaryaService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// ── OTP Verification (harus login tapi belum verified) ───────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/verify-otp', [OtpController::class, 'show'])->name('otp.show');
    // Alias untuk middleware bawaan Laravel (EnsureEmailIsVerified)
    Route::get('/email/verify', [OtpController::class, 'show'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', fn () => redirect()->route('otp.show'))->name('verification.verify');
    Route::post('/verify-otp', [OtpController::class, 'verify'])->name('otp.verify');
    Route::post('/verify-otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
});

// ── Protected Routes (harus login + email verified + akun active) ────────────
Route::middleware(['auth', 'verified:otp.show', 'account.active'])->group(function () {

    Route::get('/account/pending', function () {
        $user = auth()->user();

        if (
            $user->account_type !== 'pic' ||
            $user->account_status === 'active'
        ) {
            return redirect()->route('dashboard');
        }

        return view('account.pending');
    })->name('account.pending');

    Route::get('/dashboard', function () {
        $user = auth()->user()->load([
            'person',
            'roles',
            'peserta',
        ]);

        if (
            $user->account_type === 'pic' &&
            $user->account_status !== 'active'
        ) {
            return redirect()->route('account.pending');
        }

        $activeFestival = Festival::aktif()->with(['kategoris', 'periodes'])->first();

        $isPicKampus = $user->hasRole('pic-kampus');
        $isTimMahasiswa = $user->hasRole('tim-mahasiswa');
        $isPicSekolah = $user->hasRole('pic-sekolah');
        $isPicIndustri = $user->hasRole('pic-industri');
        $isPicSponsor = $user->hasRole('pic-sponsor');
        $isJuri = $user->hasRole('juri');
        $isAdminRole = $user->hasAnyRole(User::ADMIN_ROLES);

        $managedWorks = collect();
        $institutionActiveWorksCount = 0;
        $maxWorksQuota = KaryaService::MAX_WORKS_PER_INSTITUTION;

        if ($user->peserta) {
            $peserta = $user->peserta;
            $normalizedInst = mb_strtolower(trim((string) $peserta->institusi));

            $workRelations = [
                'festival',
                'submitter:id,user_id,nama_lengkap,institusi,jenis_peserta',
                'pesertas:id,nama_lengkap,institusi',
                'anggotaTims',
                'files',
                'versis.updater:id,name',
                'publikasiWeb.logs.updater:id,name',
            ];

            if ($isPicKampus && $normalizedInst !== '') {
                $managedWorks = Karya::with($workRelations)
                    ->where(function ($q) use ($peserta, $normalizedInst) {
                        $q->where('peserta_id', $peserta->id)
                            ->orWhereHas('submitter', function ($sq) use ($normalizedInst) {
                                $sq->whereRaw('LOWER(TRIM(institusi)) = ?', [$normalizedInst]);
                            });
                    })
                    ->latest()
                    ->get();
            } else {
                $managedWorks = Karya::with($workRelations)
                    ->where(function ($q) use ($peserta) {
                        $q->where('peserta_id', $peserta->id)
                            ->orWhereHas('pesertas', function ($pq) use ($peserta) {
                                $pq->where('peserta.id', $peserta->id);
                            });
                    })
                    ->latest()
                    ->get();
            }

            if ($activeFestival) {
                $quotaQuery = Karya::query()
                    ->where('festival_id', $activeFestival->id)
                    ->where('status', '!=', 'tidak_lolos');

                if ($normalizedInst !== '' && in_array($peserta->jenis_peserta, ['mahasiswa', 'siswa', 'siswa_smk', 'pic'], true)) {
                    $quotaQuery->whereHas('submitter', function ($sq) use ($normalizedInst) {
                        $sq->whereRaw('LOWER(TRIM(institusi)) = ?', [$normalizedInst]);
                    });
                } else {
                    $quotaQuery->where('peserta_id', $peserta->id);
                }

                $institutionActiveWorksCount = $quotaQuery->count();
            }
        }

        $curatedWorks = $isJuri
            ? Karya::with(['submitter:id,nama_lengkap,institusi', 'files'])
                ->where('status', 'lolos')
                ->when($activeFestival, fn ($q) => $q->where('festival_id', $activeFestival->id))
                ->latest()
                ->get()
            : collect();

        $publishedWorks = ($isPicIndustri || $isPicSponsor || $user->hasRole('pengunjung'))
            ? PublikasiKarya::published()
                ->with(['karya.submitter:id,nama_lengkap,institusi'])
                ->latest('tanggal_terbit')
                ->limit(6)
                ->get()
            : collect();

        return view('dashboard', compact(
            'user',
            'activeFestival',
            'isPicKampus',
            'isTimMahasiswa',
            'isPicSekolah',
            'isPicIndustri',
            'isPicSponsor',
            'isJuri',
            'isAdminRole',
            'managedWorks',
            'institutionActiveWorksCount',
            'maxWorksQuota',
            'curatedWorks',
            'publishedWorks'
        ));
    })->name('dashboard');

    // Registrasi & Pembaruan Profil Peserta / PIC Kampus (Dev 1)
    Route::post('/peserta/daftar', [ParticipantController::class, 'register'])->name('participant.register');
    Route::post('/peserta/profil', [ParticipantController::class, 'updateProfile'])->name('participant.profile.update');

    // Submisi, Edit, Tim, Dokumen & Ajuan Publikasi Karya (Web Dashboard Dev 1)
    Route::post('/karya/simpan', [WorkWebController::class, 'store'])->name('karya.store');
    Route::post('/karya/{id}/update', [WorkWebController::class, 'update'])->name('karya.update');
    Route::post('/karya/{id}/submit', [WorkWebController::class, 'submit'])->name('karya.submit');
    Route::post('/karya/{id}/upload', [WorkWebController::class, 'uploadFile'])->name('karya.upload');
    Route::post('/karya/{id}/anggota', [WorkWebController::class, 'addMember'])->name('karya.members.store');
    Route::post('/karya/{id}/anggota/{memberId}/hapus', [WorkWebController::class, 'removeMember'])->name('karya.members.destroy');
    Route::post('/karya/{id}/publikasi', [WorkWebController::class, 'requestPublication'])->name('karya.publication.request');

    // Pencarian Institusi (Autocomplete)
    Route::get('/institusi/search', [InstitutionController::class, 'search'])->name('institusi.search');

});
