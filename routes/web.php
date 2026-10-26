<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Admin\ActivityController as AdminActivityController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DkmMemberController as AdminDkmMemberController;
use App\Http\Controllers\Admin\FridayScheduleController as AdminFridayScheduleController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\FridayScheduleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PrayerScheduleController;
use App\Http\Controllers\ProfileController;
use App\Models\MasjidSetting;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Public Routes (Tahap 1)
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');
Route::get('/jadwal-sholat', [PrayerScheduleController::class, 'index'])->name('prayer.schedule');
Route::get('/kegiatan', [ActivityController::class, 'index'])->name('activities.index');
Route::get('/kegiatan/{slug}', [ActivityController::class, 'show'])->name('activities.show');
Route::get('/khutbah-jumat', [FridayScheduleController::class, 'index'])->name('friday.schedule');
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/profil', ProfileController::class)->name('profile');

// Donasi preview for Tahap 1
Route::get('/donasi', function () {
    $setting = MasjidSetting::current();

    return view('public.donation', compact('setting'));
})->name('donation');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::redirect('/login', '/admin/login')->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    /*
    | Protected Admin Routes
    */
    Route::middleware(['auth', 'admin.role:super_admin,admin_konten,bendahara'])->group(function () {
        Route::get('/', AdminDashboardController::class)->name('admin.dashboard');

        // Content Admin routes
        Route::middleware(['admin.role:super_admin,admin_konten'])->group(function () {
            Route::resource('news', AdminNewsController::class, ['as' => 'admin'])->except(['show']);
            Route::resource('activities', AdminActivityController::class, ['as' => 'admin'])->except(['show']);
            Route::resource('friday-schedules', AdminFridayScheduleController::class, ['as' => 'admin'])->except(['show']);
            Route::resource('dkm-members', AdminDkmMemberController::class, ['as' => 'admin'])->except(['show']);

            Route::get('/settings', [AdminSettingController::class, 'edit'])->name('admin.settings.edit');
            Route::put('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
        });
    });
});
