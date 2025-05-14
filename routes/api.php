<?php

use App\Http\Controllers\Api\Web\LandingPageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rute untuk menampilkan daftar voucher
Route::get('/home', [LandingPageController::class, 'index']);

Route::get('/detail-voucher/{id}', [LandingPageController::class, 'getVoucher']);

// Rute untuk membuat order
Route::post('/create-order', [LandingPageController::class, 'createOrder']);

// Rute untuk menangani notifikasi Midtrans setelah pembayaran
Route::post('/midtrans-notification', [LandingPageController::class, 'handleNotification']);

Route::get('/download-pdf/{orderNumber}', [LandingPageController::class, 'downloadPDF']);
