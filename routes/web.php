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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Payments & PPTK Access (Admin & Superadmin)
    Route::middleware('role:superadmin,admin')->group(function () {
        Route::resource('payments', PaymentController::class);
        Route::get('payments/{payment}/print', [PaymentController::class, 'print'])->name('payments.print');
        Route::resource('pptk', PptkController::class);
    });

    // Import Access (Superadmin Only)
    Route::middleware('role:superadmin')->group(function () {
        Route::get('import', [ImportController::class, 'index'])->name('import.index');
        Route::post('import/process', [ImportController::class, 'process'])->name('import.process');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
