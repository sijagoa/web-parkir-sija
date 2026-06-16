<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\TransactionController;

Route::get('/', function () {
    return redirect()->route('locations.index');
});

Route::resource('location', LocationController::class)->names('locations');
Route::resource('vehicle-type', VehicleTypeController::class)->names('vehicle-types');

// Transaction custom routes (harus sebelum resource agar tidak tertimpa)
Route::post('transaction/enter', [TransactionController::class, 'enterVehicle'])->name('transactions.enter');
Route::post('transaction/exit', [TransactionController::class, 'exitVehicle'])->name('transactions.exit');
Route::get('transaction/all-data', [TransactionController::class, 'allTransactions'])->name('transactions.all');
Route::get('transaction/pdf/{noTiket}', [TransactionController::class, 'generatePdf'])->name('transactions.pdf');
Route::get('transaction/ticket-info/{noTiket}', [TransactionController::class, 'getTicketInfo'])->name('transactions.ticket-info');

// Transaction resource (index only, other methods handled above)
Route::get('transaction', [TransactionController::class, 'index'])->name('transactions.index');
