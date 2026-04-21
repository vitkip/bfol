<?php

use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNews;
use App\Http\Controllers\Admin\EventController as AdminEvent;
use App\Http\Controllers\Admin\PageController as AdminPage;
use App\Http\Controllers\Admin\MediaController as AdminMedia;
use App\Http\Controllers\Admin\DocumentController as AdminDocument;
use App\Http\Controllers\Admin\PartnerController as AdminPartner;
use App\Http\Controllers\Admin\MouController as AdminMou;
use App\Http\Controllers\Admin\MonkProgramController as AdminMonkProgram;
use App\Http\Controllers\Admin\AidProjectController as AdminAidProject;
use App\Http\Controllers\Admin\CommitteeController as AdminCommittee;
use App\Http\Controllers\Admin\SlideController as AdminSlide;
use App\Http\Controllers\Admin\BannerController as AdminBanner;
use App\Http\Controllers\Admin\ContactController as AdminContact;
use App\Http\Controllers\Admin\TagController as AdminTag;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\NewsController;
use App\Http\Controllers\Front\EventController;
use App\Http\Controllers\Front\MediaController;
use App\Http\Controllers\Front\PageController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\SearchController;
use Illuminate\Support\Facades\Route;

// ─── ADMIN AUTH (no locale prefix) ───────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login',  [AdminAuth::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuth::class, 'login'])->name('login.post');
    Route::post('logout',[AdminAuth::class, 'logout'])->name('logout');
});

// ─── ADMIN PANEL (protected) ──────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin.role'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('news',          AdminNews::class);
    Route::resource('events',        AdminEvent::class);
    Route::resource('pages',         AdminPage::class);
    Route::resource('media',         AdminMedia::class);
    Route::resource('documents',     AdminDocument::class);
    Route::resource('partners',      AdminPartner::class);
    Route::resource('mou',           AdminMou::class);
    Route::resource('monk-programs', AdminMonkProgram::class);
    Route::resource('aid-projects',  AdminAidProject::class);
    Route::resource('committee',     AdminCommittee::class);
    Route::resource('slides',        AdminSlide::class);
    Route::resource('banners',       AdminBanner::class);
    Route::resource('contacts',      AdminContact::class)->only(['index','show','destroy']);
    Route::patch('contacts/{contact}/read', [AdminContact::class, 'markRead'])->name('contacts.read');
    Route::resource('tags',          \App\Http\Controllers\Admin\TagController::class);
        Route::resource('categories',    \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('users',         AdminUser::class);
    Route::resource('settings',      AdminSetting::class)->only(['index','store']);
});

// ─── FRONTEND (locale prefix: lo / en / zh) ───────────────────────────────────
foreach (['lo', 'en', 'zh'] as $locale) {
    Route::prefix($locale)->name("{$locale}.")->group(function () use ($locale) {
        Route::get('/',         [HomeController::class,    'index'])->name('home');
        Route::get('news',      [NewsController::class,    'index'])->name('news.index');
        Route::get('news/{slug}',[NewsController::class,   'show'])->name('news.show');
        Route::get('events',    [EventController::class,   'index'])->name('events.index');
        Route::get('events/{slug}',[EventController::class,'show'])->name('events.show');
        Route::get('media',     [MediaController::class,   'index'])->name('media.index');
        Route::get('page/{slug}',[PageController::class,   'show'])->name('page.show');
        Route::get('contact',   [ContactController::class, 'show'])->name('contact');
        Route::post('contact',  [ContactController::class, 'submit'])->name('contact.submit');
        Route::get('search',    [SearchController::class,  'index'])->name('search');
    });
}

// ─── ROOT REDIRECT → default locale ──────────────────────────────────────────
Route::get('/', fn() => redirect('/' . config('app.locale', 'lo')))->name('home');
Route::get('/news/{slug}',    fn($slug) => redirect('/' . config('app.locale', 'lo') . '/news/' . $slug));
Route::get('/events/{slug}',  fn($slug) => redirect('/' . config('app.locale', 'lo') . '/events/' . $slug));
Route::get('/media',          fn() => redirect('/' . config('app.locale', 'lo') . '/media'));
Route::get('/contact',        fn() => redirect('/' . config('app.locale', 'lo') . '/contact'));
Route::get('/search',         fn() => redirect('/' . config('app.locale', 'lo') . '/search'));
