<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\RentalReturnController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TransactionReportController;
use App\Http\Controllers\IncomeReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\EmployeeBonusController;
use App\Http\Controllers\BonusReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackupController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | ADMIN + STAFF
    |--------------------------------------------------------------------------
    */

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('role:admin,staff,kurir')
        ->name('dashboard');


    // Customer
    Route::resource('customers', CustomerController::class)
        ->only([
            'index',
            'store',
            'update',
            'destroy'
        ])
        ->middleware('role:admin,staff,kurir');


    // Rental
    Route::resource('rentals', RentalController::class)
        ->only([
            'index',
            'store',
            'update',
            'destroy'
        ])
        ->middleware('role:admin,staff,kurir');


    // Pengembalian
    Route::resource('rental-returns', RentalReturnController::class)
        ->only([
            'index',
            'store'
        ])
        ->middleware('role:admin,staff,kurir');


    // Payment
    Route::resource('payments', PaymentController::class)
        ->only([
            'index',
            'store',
            'destroy'
        ])
        ->middleware('role:admin,staff,kurir');


    // Invoice
    Route::get(
        '/invoices/{rental}',
        [InvoiceController::class, 'show']
    )
        ->middleware('role:admin,staff,kurir')
        ->name('invoices.show');


    /*
    |--------------------------------------------------------------------------
    | ADMIN ONLY
    |--------------------------------------------------------------------------
    */


    // Category
    Route::resource('categories', CategoryController::class)
        ->except([
            'create',
            'edit',
            'show'
        ])
        ->middleware('role:admin');


    // Product
    Route::resource('products', ProductController::class)
        ->only([
            'index',
            'store',
            'update',
            'destroy'
        ])
        ->middleware('role:admin');


    // Employee
    // Hanya Admin yang boleh mengelola akun karyawan
    Route::resource('employees', EmployeeController::class)
        ->only([
            'index',
            'store',
            'update',
            'destroy'
        ])
        ->middleware('role:admin');


    // Laporan Transaksi
    Route::get(
        '/reports/transactions',
        [TransactionReportController::class, 'index']
    )
        ->middleware('role:admin')
        ->name('reports.transactions');


    // Laporan Pendapatan
    Route::get(
        '/reports/income',
        [IncomeReportController::class, 'index']
    )
        ->middleware('role:admin')
        ->name('reports.income');


    // Bonus Karyawan
    Route::get(
        '/bonuses',
        [EmployeeBonusController::class, 'index']
    )
        ->middleware('role:admin')
        ->name('bonuses.index');


    Route::post(
        '/bonuses',
        [EmployeeBonusController::class, 'store']
    )
        ->middleware('role:admin')
        ->name('bonuses.store');


    Route::put(
        '/bonuses/{employeeBonusSetting}',
        [EmployeeBonusController::class, 'update']
    )
        ->middleware('role:admin')
        ->name('bonuses.update');


    Route::delete(
        '/bonuses/{employeeBonusSetting}',
        [EmployeeBonusController::class, 'destroy']
    )
        ->middleware('role:admin')
        ->name('bonuses.destroy');


    // Rekap Bonus
    Route::get(
        '/bonuses/report',
        [BonusReportController::class, 'index']
    )
        ->middleware('role:admin')
        ->name('bonuses.report');


//back up

        Route::middleware('role:admin')->group(function () {
    Route::get('/backups', [BackupController::class, 'index'])
        ->name('backups.index');

    Route::post('/backups', [BackupController::class, 'store'])
        ->name('backups.store');

    Route::get('/backups/{filename}/download', [BackupController::class, 'download'])
        ->name('backups.download');
});

});


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');


    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';