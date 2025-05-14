<?php

use App\Http\Controllers\VoucherController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('admin.vouchers.index');
    Route::get('/vouchers/create', [VoucherController::class, 'create'])->name('admin.vouchers.create');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('admin.vouchers.store');
    Route::get('/vouchers/{id}', [VoucherController::class, 'show'])->name('admin.vouchers.show');
    Route::get('/vouchers/{id}/edit', [VoucherController::class, 'edit'])->name('admin.vouchers.edit');
    Route::put('/vouchers/{id}', [VoucherController::class, 'update'])->name('admin.vouchers.update');
    Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
    // Anda bisa menambahkan rute lainnya di dalam grup ini jika diperlukan
});