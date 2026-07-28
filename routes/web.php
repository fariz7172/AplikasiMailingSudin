<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PptkController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\SubKegiatanController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Export Access (All Authenticated Roles)
    Route::get('export', [ImportController::class, 'export'])->name('export.data');

    // Payments, PPTK, Program, Kegiatan Access (Admin & Superadmin)
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::resource('payments', PaymentController::class);
        Route::get('payments/{payment}/print', [PaymentController::class, 'print'])->name('payments.print');
        Route::post('payments/{payment}/save-print', [PaymentController::class, 'savePrintData'])->name('payments.save-print');
        Route::resource('pptk', PptkController::class);
        Route::resource('programs', ProgramController::class);
        Route::resource('kegiatans', KegiatanController::class);
        Route::resource('sub-kegiatans', SubKegiatanController::class);
        
        // Cetak Slip Gaji (Berdasarkan PPTK)
        Route::get('slip-gaji', [App\Http\Controllers\SlipGajiController::class, 'index'])->name('slip-gaji.index');
        Route::post('slip-gaji/print', [App\Http\Controllers\SlipGajiController::class, 'print'])->name('slip-gaji.print');
    });

    // Import Access (Superadmin Only)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('import', [ImportController::class, 'index'])->name('import.index');
        Route::post('import/process', [ImportController::class, 'process'])->name('import.process');
        Route::get('import/template', [ImportController::class, 'downloadTemplate'])->name('import.template');
        Route::resource('perusahaans', App\Http\Controllers\PerusahaanController::class)->except(['show']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
