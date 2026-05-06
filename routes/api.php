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
    Route::get('documents',   [PublicController::class, 'documents']);
    Route::post('contact',    [PublicController::class, 'storeContact']);
});

// ─── AUTH (public) ───────────────────────────────────────────────────────────
Route::post('login',  [AuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ─── VIEWER+ : ອ່ານໄດ້ທຸກ role ──────────────────────────────────────────────
Route::middleware(['auth:sanctum', 'admin.role'])->group(function () {

    Route::get('user', [AuthController::class, 'user']);

    // Dashboard
    Route::get('dashboard',       [DashboardController::class, 'index']);
    Route::get('dashboard/chart', [DashboardController::class, 'chart']);

    // Read-only resources
    Route::apiResource('news',          NewsController::class)->only(['index', 'show']);
    Route::apiResource('events',        EventController::class)->only(['index', 'show']);
    Route::apiResource('partners',      PartnerController::class)->only(['index', 'show']);
    Route::apiResource('mou',           MouController::class)->only(['index', 'show']);
    Route::apiResource('committee',     CommitteeController::class)->only(['index', 'show']);
    Route::apiResource('monk-programs', MonkProgramController::class)->only(['index', 'show']);
    Route::apiResource('aid-projects',  AidProjectController::class)->only(['index', 'show']);

    // Reference data
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('tags',       [TagController::class, 'index']);

    // Contacts read + mark-read
    Route::get('contacts',                  [ContactController::class, 'index']);
    Route::get('contacts/{contact}',        [ContactController::class, 'show']);
    Route::patch('contacts/{contact}/read', [ContactController::class, 'markRead']);

    // Statistics / Charts
    Route::get('statistics', [StatisticsController::class, 'index']);

    // PDF Export
    Route::get('pdf/news',      [PdfController::class, 'news']);
    Route::get('pdf/events',    [PdfController::class, 'events']);
    Route::get('pdf/partners',  [PdfController::class, 'partners']);
    Route::get('pdf/mou',       [PdfController::class, 'mou']);
    Route::get('pdf/committee', [PdfController::class, 'committee']);
});

// ─── EDITOR+ : ຂຽນ/ແກ້ໄຂ/ລຶບ content ────────────────────────────────────────
Route::middleware(['auth:sanctum', 'admin.role:editor'])->group(function () {

    // News
    Route::apiResource('news', NewsController::class)->except(['index', 'show']);
    Route::patch('news/{news}/status', [NewsController::class, 'updateStatus']);

    // Events
    Route::apiResource('events', EventController::class)->except(['index', 'show']);
    Route::patch('events/{event}/status', [EventController::class, 'updateStatus']);

    // Partners
    Route::apiResource('partners',      PartnerController::class)->except(['index', 'show']);
    Route::apiResource('mou',           MouController::class)->except(['index', 'show']);
    Route::apiResource('monk-programs', MonkProgramController::class)->except(['index', 'show']);
    Route::apiResource('aid-projects',  AidProjectController::class)->except(['index', 'show']);

    // Contacts delete
    Route::delete('contacts/{contact}', [ContactController::class, 'destroy']);
});

// ─── ADMIN+ : ຂຽນ/ແກ້ໄຂ/ລຶບ org config (ກົງກັບ web.php) ─────────────────────
Route::middleware(['auth:sanctum', 'admin.role:admin'])->group(function () {
    Route::apiResource('committee', CommitteeController::class)->except(['index', 'show']);
});
