<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [AuthController::class, 'index']);
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/customers/ajax-store', [CustomerController::class, 'ajaxStore'])->name('customers.ajax_store');
});

// Administrator Only
Route::middleware(['auth', 'role:Administrator'])->group(function () {
    Route::resource('/users', UserController::class);
    Route::resource('/customers', CustomerController::class);
    Route::resource('/services', ServiceController::class);  
});

// Administrator & Operator (Transaksi & Pickup)
Route::middleware(['auth', 'role:Administrator,Operator'])->group(function () {
    Route::post('/transaksi/{id}/status', [TransaksiController::class, 'updateStatus'])->name('transaksi.update_status');
    Route::get('/transaksi/{id}/receipt', [TransaksiController::class, 'receipt'])->name('transaksi.receipt');
    Route::resource('/transaksi', TransaksiController::class);
    Route::resource('/pickup', PickupController::class);
});

// Operator (Specific if any, current empty)
// Route::middleware(['auth', 'role:Operator'])->group(function () {
// });

// Pimpinan
Route::middleware(['auth', 'role:Pimpinan'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

