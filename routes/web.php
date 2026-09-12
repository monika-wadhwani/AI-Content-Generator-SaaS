<?php

use App\Http\Controllers\BrandProfileController;
use App\Http\Controllers\ContentGenerationController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth','verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/brand-profiles', [BrandProfileController::class, 'index'])->name('brand-profiles.index');
    Route::get('/brand-profiles/create', [BrandProfileController::class, 'create'])->name('brand-profiles.create');
    Route::post('/brand-profiles', [BrandProfileController::class, 'store'])->name('brand-profiles.store');

    Route::get('/brand-profiles/{brandProfile}/generate', [ContentGenerationController::class, 'create'])
        ->name('content-generations.create');
    Route::post('/brand-profiles/{brandProfile}/generate', [ContentGenerationController::class, 'store'])
        ->name('content-generations.store');
    Route::get('/generations/{generation}/check', [ContentGenerationController::class, 'check'])
        ->name('content-generations.check');

    Route::post('/generations/{generation}/retry', [ContentGenerationController::class, 'retry'])->name('content-generations.retry');
});

require __DIR__.'/auth.php';
