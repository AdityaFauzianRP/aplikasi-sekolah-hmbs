<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PaymentController;

Route::get('/bayar', [PembayaranController::class, 'bayar']);

Route::post('/payment/ppdb/success', [PaymentController::class, 'store'])->name('ppdb.payment.store');

Route::post('/pembayaran/awal/success', [PembayaranController::class, 'updateStatusPembayaran']);

Route::get('/', function () {
    return redirect('/lms');
});

