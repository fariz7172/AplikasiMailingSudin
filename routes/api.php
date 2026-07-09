<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KegiatanController as ApiKegiatanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Cascade dropdown: ambil kegiatan berdasarkan program_id
Route::get('/kegiatans', [ApiKegiatanController::class, 'byProgram'])->name('api.kegiatans');

// Cascade dropdown: ambil sub kegiatan berdasarkan kegiatan_id
Route::get('/sub-kegiatans', [ApiKegiatanController::class, 'subByKegiatan'])->name('api.sub-kegiatans');

// Tambah Kegiatan baru via Modal (API)
Route::post('/kegiatans', [ApiKegiatanController::class, 'storeKegiatan'])->name('api.kegiatans.store');

// Tambah Sub Kegiatan baru via Modal (API)
Route::post('/sub-kegiatans', [ApiKegiatanController::class, 'storeSubKegiatan'])->name('api.sub-kegiatans.store');

// API Pembayaran
use App\Http\Controllers\Api\PaymentController as ApiPaymentController;

Route::get('/payments', [ApiPaymentController::class, 'index'])->name('api.payments.index');
Route::get('/payments/{id}', [ApiPaymentController::class, 'show'])->name('api.payments.show');
