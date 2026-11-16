<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;



Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware('guest')->group(function () {

    // Menampilkan halaman autentikasi
    Route::get('/auth', [AuthController::class, 'index'])
        ->name('login');

    // URL lama diarahkan ke /auth
    Route::redirect('/login', '/auth');

    // Memproses autentikasi
    Route::post('/auth', [AuthController::class, 'login'])
        ->name('login.process');

});



Route::middleware(['auth', 'role:manajer'])->group(function () {

    Route::get('/manajer/dashboard', function () {
        return view('Manajer.DashboardManajer');
    })->name('manajer.dashboard');

});


Route::middleware(['auth', 'role:kasir'])->group(function () {

    Route::get('/kasir/dashboard', function () {
        return view('Kasir.DashboardKasir');
    })->name('kasir.dashboard');

});




Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});