<?php

use App\Http\Controllers\Api\PublicController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\MouController;
use App\Http\Controllers\Api\CommitteeController;
use App\Http\Controllers\Api\MonkProgramController;
use App\Http\Controllers\Api\AidProjectController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\PdfController;
use Illuminate\Support\Facades\Route;

// ─── PUBLIC (no auth) ────────────────────────────────────────────────────────
Route::prefix('public')->group(function () {
    Route::get('home',     [PublicController::class, 'home']);
    Route::get('slides',   [PublicController::class, 'slides']);
    Route::get('stats',    [PublicController::class, 'stats']);
    Route::get('news',          [PublicController::class, 'news']);
    Route::get('news/{slug}',   [PublicController::class, 'newsDetail']);
    Route::get('events',   [PublicController::class, 'events']);
    Route::get('partners',  [PublicController::class, 'partners']);
    Route::get('settings',    [PublicController::class, 'settings']);
    Route::get('menu',        [PublicController::class, 'menu']);
    Route::get('pages/{slug}',[PublicController::class, 'page']);
    Route::post('contact',    [PublicController::class, 'storeContact']);
});

// ─── AUTH (public) ───────────────────────────────────────────────────────────
Route::post('login',  [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ─── PROTECTED API ───────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::get('user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('dashboard/chart', [DashboardController::class, 'chart']);

    // News
    Route::apiResource('news', NewsController::class);
    Route::patch('news/{news}/status', [NewsController::class, 'updateStatus']);

    // Events
    Route::apiResource('events', EventController::class);
    Route::patch('events/{event}/status', [EventController::class, 'updateStatus']);

    // Partners
    Route::apiResource('partners', PartnerController::class);

    // MOU Agreements
    Route::apiResource('mou', MouController::class);

    // Committee Members
    Route::apiResource('committee', CommitteeController::class);

    // Monk Exchange Programs
    Route::apiResource('monk-programs', MonkProgramController::class);

    // Aid Projects
    Route::apiResource('aid-projects', AidProjectController::class);

    // Categories & Tags (reference data)
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('tags',       [TagController::class, 'index']);

    // Contacts (read-only from admin side)
    Route::get('contacts',              [ContactController::class, 'index']);
    Route::get('contacts/{contact}',    [ContactController::class, 'show']);
    Route::patch('contacts/{contact}/read', [ContactController::class, 'markRead']);
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy']);

    // Statistics / Charts
    Route::get('statistics', [StatisticsController::class, 'index']);

    // PDF Export
    Route::get('pdf/news',      [PdfController::class, 'news']);
    Route::get('pdf/events',    [PdfController::class, 'events']);
    Route::get('pdf/partners',  [PdfController::class, 'partners']);
    Route::get('pdf/mou',       [PdfController::class, 'mou']);
    Route::get('pdf/committee', [PdfController::class, 'committee']);
});
