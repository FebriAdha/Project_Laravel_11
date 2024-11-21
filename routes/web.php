<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::post('/post-login', [AuthController::class, 'login'])->name('login');
});

// Admin Routes
Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', function () {
        return view('pages.admin.index');
    })->name('admin.dashboard');

    Route::post('/admin-logout', [AuthController::class, 'admin_logout'])->name('admin.logout');
});

// User Routes
Route::group(['middleware' => 'auth:web'], function () { // Menggunakan guard 'web'
    Route::get('/user', function () {
        return view('pages.user.index');
    })->name('user.dashboard');

    Route::post('/user-logout', [AuthController::class, 'user_logout'])->name('user.logout');
});
