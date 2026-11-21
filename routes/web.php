<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\StockInController;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Recipe;
use Illuminate\Support\Facades\Route;

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
        return view('Manajer.DashboardManajer', [
            'totalMenu' => Menu::count(),
            'totalResep' => Recipe::distinct('id_menu')->count('id_menu'),
            'stokMenipis' => Ingredient::whereColumn('stok', '<=', 'stok_minimum')->count(),
            'totalBahan' => Ingredient::count(),
        ]);
    })->name('manajer.dashboard');

    // CRUD MENU
    Route::get('/manajer/menu', [MenuController::class, 'index'])
        ->name('manajer.menu');

    Route::post('/manajer/menu', [MenuController::class, 'store'])
        ->name('manajer.menu.store');

    Route::put('/manajer/menu/{menu}', [MenuController::class, 'update'])
        ->name('manajer.menu.update');

    Route::delete('/manajer/menu/{menu}', [MenuController::class, 'destroy'])
        ->name('manajer.menu.destroy');

    Route::patch('/manajer/menu/{menu}/status', [MenuController::class, 'toggleStatus'])
        ->name('manajer.menu.status');

    // CRUD BAHAN
    Route::get('/manajer/bahan', [IngredientController::class, 'index'])
        ->name('manajer.bahan.index');

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
    Route::get('/manajer/stok-masuk', [StockInController::class, 'index'])
        ->name('manajer.stok-masuk');

    Route::post('/manajer/stok-masuk', [StockInController::class, 'store'])
        ->name('manajer.stok-masuk.store');

    Route::put('/manajer/stok-masuk/{stockIn}', [StockInController::class, 'update'])
        ->name('manajer.stok-masuk.update');

    Route::delete('/manajer/stok-masuk/{stockIn}', [StockInController::class, 'destroy'])
        ->name('manajer.stok-masuk.destroy');

});

// HALAMAN KASIR
Route::middleware(['auth', 'role:kasir'])->group(function () {

    Route::get('/kasir/dashboard', [OrderController::class, 'index'])
        ->name('kasir.dashboard');

    Route::get('/kasir/pesanan/buat', [OrderController::class, 'create'])
        ->name('kasir.orders.create');

    Route::post('/kasir/pesanan', [OrderController::class, 'store'])
        ->name('kasir.orders.store');

    Route::get('/kasir/pesanan/{order}', [OrderController::class, 'show'])
        ->name('kasir.orders.show');

    Route::get('/kasir/pesanan/{order}/struk', [OrderController::class, 'receipt'])
        ->name('kasir.orders.receipt');

    Route::patch('/kasir/pesanan/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('kasir.orders.status');

    Route::delete('/kasir/pesanan/{order}', [OrderController::class, 'destroy'])
        ->name('kasir.orders.destroy');

});

// LOGOUT
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});
