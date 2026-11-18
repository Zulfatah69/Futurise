<?php

use App\Http\Controllers\Api\v1\InstitutionController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\WorkWebController;
use App\Models\Festival;
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
            'peserta.karyasAsLeader.festival',
            'peserta.karyasAsLeader.anggotaTims',
            'peserta.karyasAsLeader.files',
        ]);

        if (
            $user->account_type === 'pic' &&
            $user->account_status !== 'active'
        ) {
            return redirect()->route('account.pending');
        }

        $activeFestival = Festival::aktif()->with('kategoris')->first();

        return view('dashboard', compact('user', 'activeFestival'));
    })->name('dashboard');

    // Registrasi Profil Peserta Lomba (Tahap 2)
    Route::post('/peserta/daftar', [ParticipantController::class, 'register'])->name('participant.register');

    // Submisi Karya & Upload Berkas (Web Dashboard)
    Route::post('/karya/simpan', [WorkWebController::class, 'store'])->name('karya.store');
    Route::post('/karya/{id}/submit', [WorkWebController::class, 'submit'])->name('karya.submit');
    Route::post('/karya/{id}/upload', [WorkWebController::class, 'uploadFile'])->name('karya.upload');

    // Pencarian Institusi (Autocomplete)
    Route::get('/institusi/search', [InstitutionController::class, 'search'])->name('institusi.search');

});
