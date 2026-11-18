<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\CommitteeController;
use App\Http\Controllers\Api\v1\CommitteeTaskController;
use App\Http\Controllers\Api\v1\DivisionController;
use App\Http\Controllers\Api\v1\FestivalController;
use App\Http\Controllers\Api\v1\InstitutionController;
use App\Http\Controllers\Api\v1\PublicationController;
use App\Http\Controllers\Api\v1\WorkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // ── Autentikasi Publik (Tahap 1: Registrasi Akun) ─────────────────────────
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // ── Referensi Data Institusi (Autocomplete Pencarian PT & SMK) ───────────
    Route::get('/institusi/search', [InstitutionController::class, 'search']);

    // ── Etalase Galeri Karya Publik (Showcase & Expo) ─────────────────────────
    Route::get('/showcase', [PublicationController::class, 'publicShowcase']);
    Route::get('/showcase/{slug}', [PublicationController::class, 'publicShowcaseDetail']);
    Route::post('/showcase/{slug}/like', [PublicationController::class, 'like'])->middleware('throttle:30,1');

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

        // ── Manajemen Publikasi Karya (Kurasi & Showcase) ─────────────────────
        Route::get('/work-publications', [PublicationController::class, 'index']);
        Route::post('/work-publications', [PublicationController::class, 'store']);
        Route::get('/work-publications/{id}', [PublicationController::class, 'show']);
        Route::match(['put', 'patch'], '/work-publications/{id}', [PublicationController::class, 'update']);
        Route::post('/work-publications/{id}/submit', [PublicationController::class, 'submitForApproval']);
        Route::post('/work-publications/{id}/approve', [PublicationController::class, 'approve']);
        Route::post('/work-publications/{id}/publish', [PublicationController::class, 'publish']);
        Route::post('/work-publications/{id}/withdraw', [PublicationController::class, 'withdraw']);
        Route::get('/work-publications/{id}/logs', [PublicationController::class, 'logs']);

        // ── Kepanitiaan, Divisi & Shift Penugasan ─────────────────────────────
        Route::get('/committee', [CommitteeController::class, 'index']);
        Route::post('/committee', [CommitteeController::class, 'store']);
        Route::get('/committee/{committee}', [CommitteeController::class, 'show']);
        Route::match(['put', 'patch'], '/committee/{committee}', [CommitteeController::class, 'update']);
        Route::delete('/committee/{committee}', [CommitteeController::class, 'destroy']);
        Route::post('/festivals/{festival}/committee/{committee}/assign', [CommitteeController::class, 'assignToFestival']);
        Route::match(['put', 'patch'], '/committee-assignments/{assignment}', [CommitteeController::class, 'updateAssignment']);

        // Divisi Panitia Festival
        Route::get('/festivals/{festival}/divisions', [DivisionController::class, 'index']);
        Route::post('/festivals/{festival}/divisions', [DivisionController::class, 'store']);
        Route::get('/festivals/{festival}/divisions/{division}', [DivisionController::class, 'show']);
        Route::match(['put', 'patch'], '/festivals/{festival}/divisions/{division}', [DivisionController::class, 'update']);
        Route::delete('/festivals/{festival}/divisions/{division}', [DivisionController::class, 'destroy']);

        // Shift Penugasan Tugas Panitia
        Route::get('/committee-tasks', [CommitteeTaskController::class, 'index']);
        Route::post('/committee-tasks', [CommitteeTaskController::class, 'store']);
        Route::get('/committee-tasks/{task}', [CommitteeTaskController::class, 'show']);
        Route::match(['put', 'patch'], '/committee-tasks/{task}', [CommitteeTaskController::class, 'update']);
        Route::delete('/committee-tasks/{task}', [CommitteeTaskController::class, 'destroy']);
        Route::post('/committee-tasks/{task}/check-in', [CommitteeTaskController::class, 'checkIn']);
        Route::post('/committee-tasks/{task}/complete', [CommitteeTaskController::class, 'complete']);
    });
});
