<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\DistributorController;

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
    Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
    Route::get('/product/detail/{id}', [ProductController::class, 'detail'])->name('product.detail');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/product/update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::delete('/product/delete/{id}', [ProductController::class, 'delete'])->name('product.delete');

    Route::get('/distributor', [DistributorController::class, 'index'])->name('admin.distributor');
    Route::post('/distributor/import', [DistributorController::class, 'import'])->name('distributor.import');
    Route::get('/distributor/export', [DistributorController::class, 'export'])->name('distributor.export');
    Route::post('/distributor/store', [DistributorController::class, 'store'])->name('distributor.store');
    Route::get('/distributor/create', [DistributorController::class, 'create'])->name('distributor.create');
    Route::get('/distributor/edit/{id}', [DistributorController::class, 'edit'])->name('distributor.edit');
    Route::post('/distributor/update/{id}', [DistributorController::class, 'update'])->name('admin.distributor.update');
    Route::delete('/distributor/delete/{id}', [DistributorController::class, 'delete'])->name('distributor.delete');
});

// User Routes
Route::group(['middleware' => 'auth:web'], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    Route::post('/user-logout', [AuthController::class, 'user_logout'])->name('user.logout');

    // Product Routes
    Route::get('/user/product/detail/{id}', [UserController::class, 'detail_product'])->name('user.detail.product');
    Route::get('/product/purchase/{productId}/{usetId}', [UserController::class, 'purchase']);

});
