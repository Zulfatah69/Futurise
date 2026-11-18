<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\FestivalController;
use App\Http\Controllers\Api\v1\InstitutionController;
use App\Http\Controllers\Api\v1\WorkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // ── Autentikasi Publik (Tahap 1: Registrasi Akun) ─────────────────────────
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // ── Referensi Data Institusi (Autocomplete Pencarian PT & SMK) ───────────
    Route::get('/institusi/search', [InstitutionController::class, 'search']);

    // ── Festival Publik ───────────────────────────────────────────────────────
    Route::get('/festivals', [FestivalController::class, 'index']);
    Route::get('/festivals/active', [FestivalController::class, 'active']);
    Route::get('/festivals/{idOrSlug}', [FestivalController::class, 'show']);
    Route::get('/festivals/{id}/periods', [FestivalController::class, 'getPeriods']);
    Route::get('/festivals/{id}/categories', [FestivalController::class, 'getCategories']);

    // ── Protected Routes (Sanctum Bearer Token) ──────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Tahap 2: Registrasi Peserta Lomba setelah login
        Route::post('/participants/register', [AuthController::class, 'registerParticipant']);

        // ── Festival Management (Admin/Panitia) ───────────────────────────────
        Route::post('/festivals', [FestivalController::class, 'store']);
        Route::match(['put', 'patch'], '/festivals/{id}', [FestivalController::class, 'update']);
        Route::delete('/festivals/{id}', [FestivalController::class, 'destroy']);

        Route::post('/festivals/{id}/periods', [FestivalController::class, 'storePeriod']);
        Route::match(['put', 'patch'], '/festivals/{id}/periods/{periodId}', [FestivalController::class, 'updatePeriod']);
        Route::delete('/festivals/{id}/periods/{periodId}', [FestivalController::class, 'destroyPeriod']);

        Route::post('/festivals/{id}/categories', [FestivalController::class, 'storeCategory']);
        Route::match(['put', 'patch'], '/festivals/{id}/categories/{categoryId}', [FestivalController::class, 'updateCategory']);
        Route::delete('/festivals/{id}/categories/{categoryId}', [FestivalController::class, 'destroyCategory']);

        // ── Pengajuan Karya Lomba & Berkas ─────────────────────────────────────
        Route::get('/works', [WorkController::class, 'index']);
        Route::post('/works', [WorkController::class, 'store']);
        Route::get('/works/{id}', [WorkController::class, 'show']);
        Route::match(['put', 'patch'], '/works/{id}', [WorkController::class, 'update']);
        Route::post('/works/{id}/submit', [WorkController::class, 'submit']);
        Route::post('/works/{id}/files', [WorkController::class, 'uploadFile']);
        Route::get('/works/{id}/files', [WorkController::class, 'getFiles']);
        Route::post('/works/{id}/team-members', [WorkController::class, 'addTeamMember']);
        Route::delete('/works/{id}/team-members/{memberId}', [WorkController::class, 'removeTeamMember']);
    });
});
