<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\User\UserController;

// Guest Routes
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/post-register', [AuthController::class, 'post_register'])->name('post.register');
    Route::post('/post-login', [AuthController::class, 'login'])->name('login');
});

// Admin Routes
Route::group(['middleware' => 'admin'], function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Product Routes
    Route::get('/product', [ProductController::class, 'index'])->name('admin.product');
    Route::post('/admin-logout', [AuthController::class, 'admin_logout'])->name('admin.logout');
});

// User Routes
Route::group(['middleware' => 'auth:web'], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    Route::post('/user-logout', [AuthController::class, 'user_logout'])->name('user.logout');
});
