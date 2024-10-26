<?php

use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Socialite routes
Route::get('auth/{driver}/redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
Route::get('auth/google/login', [SocialiteController::class, 'google_callback'])->name('socialite.callback');














