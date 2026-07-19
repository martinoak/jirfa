<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificatesController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublishController;
use App\Http\Controllers\ReferencesController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomepageController::class, 'index'])->name('homepage');
Route::post('email', [HomepageController::class, 'email'])->name('email');

Route::view('login', 'auth.login')->name('login');
Route::post('authenticate', [AuthController::class, 'authenticate'])->name('authenticate');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');

    Route::resource('reference', ReferencesController::class)->except('show');
    Route::delete('reference/{reference}/image/{image}', [ReferencesController::class, 'destroyImage'])
        ->name('reference.image.destroy');

    Route::resource('certificate', CertificatesController::class)->except('show');

    Route::post('publish', PublishController::class)->name('publish');
});
