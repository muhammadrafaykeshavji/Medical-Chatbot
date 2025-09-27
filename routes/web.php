<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HealthMetricsController;
use App\Http\Controllers\SymptomCheckerController;
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Chat routes
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/new', [ChatController::class, 'newConversation'])->name('new');
        Route::post('/message', [ChatController::class, 'store'])->name('store');
        Route::get('/{conversation}', [ChatController::class, 'show'])->name('show');
    });
    
    // Health Metrics routes
    Route::prefix('health-metrics')->name('health-metrics.')->group(function () {
        Route::get('/', [HealthMetricsController::class, 'index'])->name('index');
        Route::get('/create', [HealthMetricsController::class, 'create'])->name('create');
        Route::post('/', [HealthMetricsController::class, 'store'])->name('store');
        Route::get('/{healthMetric}', [HealthMetricsController::class, 'show'])->name('show');
        Route::get('/{healthMetric}/edit', [HealthMetricsController::class, 'edit'])->name('edit');
        Route::patch('/{healthMetric}', [HealthMetricsController::class, 'update'])->name('update');
        Route::delete('/{healthMetric}', [HealthMetricsController::class, 'destroy'])->name('destroy');
    });
    
    // Symptom Checker routes
    Route::prefix('symptom-checker')->name('symptom-checker.')->group(function () {
        Route::get('/', [SymptomCheckerController::class, 'index'])->name('index');
        Route::get('/create', [SymptomCheckerController::class, 'create'])->name('create');
        Route::post('/', [SymptomCheckerController::class, 'store'])->name('store');
        Route::get('/{symptomCheck}', [SymptomCheckerController::class, 'show'])->name('show');
    });
});

// Social Login Routes
Route::get('/auth/{provider}', [SocialLoginController::class, 'redirectToProvider'])->name('social.login');
Route::get('/auth/{provider}/callback', [SocialLoginController::class, 'handleProviderCallback'])->name('social.callback');

require __DIR__.'/auth.php';
