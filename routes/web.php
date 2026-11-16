<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// HALAMAN UTAMA
Route::get('/', function () {
    return redirect()->route('login');
});

// AUTENTIKASI
Route::middleware('guest')->group(function () {

    Route::get('/auth', [AuthController::class, 'index'])
        ->name('login');

    Route::redirect('/login', '/auth');

    Route::post('/auth', [AuthController::class, 'login'])
        ->name('login.process');

});

// HALAMAN MANAJER
Route::middleware(['auth', 'role:manajer'])->group(function () {

    Route::get('/manajer/dashboard', function () {
        return view('Manajer.DashboardManajer');
    })->name('manajer.dashboard');

    Route::get('/manajer/menu', function () {
        return view('Manajer.Menu');
    })->name('manajer.menu');

    Route::get('/manajer/resep', function () {
        return view('Manajer.Resep');
    })->name('manajer.resep');

    Route::get('/manajer/bahan', function () {
        return view('Manajer.Bahan');
    })->name('manajer.bahan');

    Route::get('/manajer/stok-masuk', function () {
        return view('Manajer.StokMasuk');
    })->name('manajer.stok-masuk');

});

// HALAMAN KASIR
Route::middleware(['auth', 'role:kasir'])->group(function () {

    Route::get('/kasir/dashboard', function () {
        return view('Kasir.DashboardKasir');
    })->name('kasir.dashboard');

});

// LOGOUT
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});