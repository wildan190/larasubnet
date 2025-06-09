<?php

use App\Http\Controllers\Api\DashboardController as ApiDashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VoucherController;
use App\Http\Controllers\Api\Web\GetVoucherSettlementController;
use App\Http\Controllers\Api\Web\HistoryController;
use App\Http\Controllers\Api\Web\LandingPageController;
use App\Http\Controllers\Auth\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rute untuk dashboard (membutuhkan autentikasi)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [ApiDashboardController::class, 'index']);
    Route::prefix('vouchers')->group(function () {
        Route::get('/', [VoucherController::class, 'index']);
        Route::post('/store-multiple', [VoucherController::class, 'storeMultiple']);
        Route::get('/{id}', [VoucherController::class, 'show']);
        Route::put('/{id}', [VoucherController::class, 'update']);
        Route::delete('/{id}', [VoucherController::class, 'destroy']);
        Route::get('/group', [VoucherController::class, 'groupData']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'edit']);
    Route::put('/profile', [UserController::class, 'update']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
});

// Rute untuk menampilkan daftar voucher
Route::get('/home', [LandingPageController::class, 'index']);

Route::get('/detail-voucher/{id}', [LandingPageController::class, 'getVoucher']);

// Rute untuk membuat order
Route::post('/create-order', [LandingPageController::class, 'createOrder']);

// Rute untuk menangani notifikasi Midtrans setelah pembayaran
Route::post('/midtrans-notification', [LandingPageController::class, 'handleNotification']);

Route::get('/download-pdf/{orderNumber}', [LandingPageController::class, 'downloadPDF']);

Route::get('/vouchers/settlement', GetVoucherSettlementController::class);

Route::get('/web/history', [HistoryController::class, 'index']);
