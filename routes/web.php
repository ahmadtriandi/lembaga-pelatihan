<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

// ---------- Website publik ----------
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/artikel', [SiteController::class, 'posts'])->name('posts');
Route::get('/artikel/{post}', [SiteController::class, 'post'])->name('post');
Route::post('/daftar', [SiteController::class, 'register'])->middleware('throttle:5,1')->name('register');

// ---------- Login admin ----------
Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'show'])->name('login');
    Route::post('/admin/login', [LoginController::class, 'login'])->middleware('throttle:10,1');
});
Route::post('/admin/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

// ---------- Dashboard ----------
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');

    Route::get('pengaturan', [Admin\SettingController::class, 'edit'])->name('settings');
    Route::put('pengaturan', [Admin\SettingController::class, 'update'])->name('settings.update');

    Route::resource('bidang', Admin\CategoryController::class)->except('show')
        ->parameters(['bidang' => 'category'])->names('categories');
    Route::resource('program', Admin\ProgramController::class)->except('show')
        ->parameters(['program' => 'program'])->names('programs');
    Route::resource('artikel', Admin\PostController::class)->except('show')
        ->parameters(['artikel' => 'post'])->names('posts');
    Route::resource('testimoni', Admin\TestimonialController::class)->except('show')
        ->parameters(['testimoni' => 'testimonial'])->names('testimonials');

    Route::resource('galeri', Admin\GalleryController::class)->only(['index', 'store', 'destroy'])
        ->parameters(['galeri' => 'gallery'])->names('galleries');
    Route::resource('fasilitas', Admin\FacilityController::class)->only(['index', 'store', 'destroy'])
        ->parameters(['fasilitas' => 'facility'])->names('facilities');
    Route::resource('klien', Admin\ClientController::class)->only(['index', 'store', 'destroy'])
        ->parameters(['klien' => 'client'])->names('clients');

    Route::get('pendaftar/export', [Admin\RegistrationController::class, 'export'])->name('registrations.export');
    Route::resource('pendaftar', Admin\RegistrationController::class)->only(['index', 'show', 'update', 'destroy'])
        ->parameters(['pendaftar' => 'registration'])->names('registrations');
});
