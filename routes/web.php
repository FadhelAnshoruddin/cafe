<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;


// HALAMAN LOGIN
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/auth', [AuthController::class, 'index'])->name('login');
    Route::redirect('/login', '/auth');
    Route::post('/auth', [AuthController::class, 'login'])->name('login.process');
});


// HALAMAN MANAJER
Route::middleware(['auth', 'role:manajer'])->group(function () {

    // DASHBOARD
    Route::get('/manajer/dashboard', function () {
        return view('Manajer.DashboardManajer');
    })->name('manajer.dashboard');


    // CRUD MENU
    Route::get('/manajer/menu', [MenuController::class, 'index'])
        ->name('manajer.menu');

    Route::post('/manajer/menu', [MenuController::class, 'store'])
        ->name('manajer.menu.store');

    Route::put('/manajer/menu/{menu}', [MenuController::class, 'update'])
        ->name('manajer.menu.update');

    Route::patch('/manajer/menu/{menu}/status', [MenuController::class, 'toggleStatus'])
        ->name('manajer.menu.status');


    // CRUD BAHAN
    Route::get('/manajer/bahan', [IngredientController::class, 'index'])
        ->name('manajer.bahan');

    Route::post('/manajer/bahan', [IngredientController::class, 'store'])
        ->name('manajer.bahan.store');

    Route::put('/manajer/bahan/{ingredient}', [IngredientController::class, 'update'])
        ->name('manajer.bahan.update');

    Route::delete('/manajer/bahan/{ingredient}', [IngredientController::class, 'destroy'])
        ->name('manajer.bahan.destroy');


    // CRUD RESEP
    Route::get('/manajer/resep', [RecipeController::class, 'index'])
        ->name('manajer.resep');

    Route::post('/manajer/resep', [RecipeController::class, 'store'])
        ->name('manajer.resep.store');

    Route::put('/manajer/resep/{recipe}', [RecipeController::class, 'update'])
        ->name('manajer.resep.update');

    Route::delete('/manajer/resep/{recipe}', [RecipeController::class, 'destroy'])
        ->name('manajer.resep.destroy');


    // STOK MASUK
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