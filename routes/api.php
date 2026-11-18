<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\InstitutionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // ── Autentikasi Publik (Tahap 1: Registrasi Akun) ─────────────────────────
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // ── Referensi Data Institusi (Autocomplete Pencarian PT & SMK) ───────────
    Route::get('/institusi/search', [InstitutionController::class, 'search']);

    // ── Protected Routes (Sanctum Bearer Token) ──────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Tahap 2: Registrasi Peserta Lomba setelah login
        Route::post('/participants/register', [AuthController::class, 'registerParticipant']);
    });
});
