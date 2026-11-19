<?php

use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Recipe;
use App\Models\StockIn;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createManagerUser(): User
{
    return User::query()->forceCreate([
        'username' => 'manager-test',
        'password_hash' => 'secret-password',
        'peran' => 'manajer',
        'dibuat_pada' => now(),
    ]);
}

function createIngredient(array $attributes = []): Ingredient
{
    return Ingredient::query()->create(array_merge([
        'nama_bahan' => 'Biji kopi',
        'satuan_dasar' => 'g',
        'stok' => 10,
        'stok_minimum' => 2,
        'harga_per_satuan_dasar' => 1.25,
    ], $attributes));
}

it('creates, updates, and deletes menu recipes while preventing duplicate ingredients', function () {
    $this->actingAs(createManagerUser());
    $menu = Menu::query()->create([
        'nama_menu' => 'Latte',
        'kategori' => 'Minuman',
        'harga_jual' => 25000,
        'aktif' => true,
    ]);
    $ingredient = createIngredient();

    $this->post(route('manajer.resep.store'), [
        'id_menu' => $menu->id_menu,
        'id_bahan' => $ingredient->id_bahan,
        'takaran' => 18,
    ])->assertRedirect(route('manajer.resep'));

    $recipe = Recipe::query()->firstOrFail();
    $this->assertDatabaseHas('recipes', ['id_resep' => $recipe->id_resep, 'takaran' => 18]);

    $this->post(route('manajer.resep.store'), [
        'id_menu' => $menu->id_menu,
        'id_bahan' => $ingredient->id_bahan,
        'takaran' => 20,
    ])->assertSessionHas('error', 'Bahan tersebut sudah ada dalam resep menu ini.');

    $this->put(route('manajer.resep.update', $recipe), [
        'id_menu' => $menu->id_menu,
        'id_bahan' => $ingredient->id_bahan,
        'takaran' => 20,
    ])->assertRedirect(route('manajer.resep'));

    $this->assertDatabaseHas('recipes', ['id_resep' => $recipe->id_resep, 'takaran' => 20]);

    $this->delete(route('manajer.resep.destroy', $recipe))
        ->assertRedirect(route('manajer.resep'));

    $this->assertDatabaseMissing('recipes', ['id_resep' => $recipe->id_resep]);
});

it('allows managers to create, update, activate, and delete menus', function () {
    $this->actingAs(createManagerUser());

    $this->post(route('manajer.menu.store'), [
        'nama_menu' => 'Latte',
        'kategori' => 'Minuman',
        'harga_jual' => 25000,
    ])->assertRedirect(route('manajer.menu'));

    $menu = Menu::query()->firstOrFail();
    $this->assertDatabaseHas('menus', ['id_menu' => $menu->id_menu, 'aktif' => true]);

    $this->put(route('manajer.menu.update', $menu), [
        'nama_menu' => 'Latte Senja',
        'kategori' => 'Signature',
        'harga_jual' => 28000,
    ])->assertRedirect(route('manajer.menu'));

    $this->assertDatabaseHas('menus', [
        'id_menu' => $menu->id_menu,
        'nama_menu' => 'Latte Senja',
        'kategori' => 'Signature',
        'harga_jual' => 28000,
    ]);

    $this->patch(route('manajer.menu.status', $menu))
        ->assertRedirect(route('manajer.menu'));

    $this->assertDatabaseHas('menus', ['id_menu' => $menu->id_menu, 'aktif' => false]);

    $this->delete(route('manajer.menu.destroy', $menu))
        ->assertRedirect(route('manajer.menu'));

    $this->assertDatabaseMissing('menus', ['id_menu' => $menu->id_menu]);
});

it('allows managers to create, update, and delete ingredients', function () {
    $this->actingAs(createManagerUser());

    $this->post(route('manajer.bahan.store'), [
        'nama_bahan' => 'Susu UHT',
        'satuan_dasar' => 'ml',
        'stok' => 1200,
        'stok_minimum' => 200,
        'harga_per_satuan_dasar' => 18,
    ])->assertRedirect(route('manajer.bahan.index'));

    $ingredient = Ingredient::query()->firstOrFail();
    $this->assertDatabaseHas('ingredients', ['id_bahan' => $ingredient->id_bahan, 'stok' => 1200]);

    $this->put(route('manajer.bahan.update', $ingredient), [
        'nama_bahan' => 'Susu Segar',
        'satuan_dasar' => 'ml',
        'stok' => 1000,
        'stok_minimum' => 150,
        'harga_per_satuan_dasar' => 20,
    ])->assertRedirect(route('manajer.bahan.index'));

    $this->assertDatabaseHas('ingredients', [
        'id_bahan' => $ingredient->id_bahan,
        'nama_bahan' => 'Susu Segar',
        'stok' => 1000,
        'stok_minimum' => 150,
        'harga_per_satuan_dasar' => 20,
    ]);

    $this->delete(route('manajer.bahan.destroy', $ingredient))
        ->assertRedirect(route('manajer.bahan.index'));

    $this->assertDatabaseMissing('ingredients', ['id_bahan' => $ingredient->id_bahan]);
});

it('keeps ingredient stock in sync when a stock-in record is created, edited, and deleted', function () {
    $this->actingAs(createManagerUser());
    $ingredient = createIngredient();
    $otherIngredient = createIngredient([
        'nama_bahan' => 'Susu UHT',
        'satuan_dasar' => 'ml',
        'stok' => 2,
    ]);

    $this->post(route('manajer.stok-masuk.store'), [
        'id_bahan' => $ingredient->id_bahan,
        'tanggal' => '2026-11-19',
        'jumlah' => 5,
        'harga_per_satuan_dasar' => 2.5,
        'supplier' => 'Supplier Kopi',
    ])->assertRedirect(route('manajer.stok-masuk'));

    $stockIn = StockIn::query()->firstOrFail();
    $this->assertDatabaseHas('ingredients', ['id_bahan' => $ingredient->id_bahan, 'stok' => 15]);
    $this->assertDatabaseHas('stok_in', ['id_stok_masuk' => $stockIn->id_stok_masuk, 'supplier' => 'Supplier Kopi']);

    $this->put(route('manajer.stok-masuk.update', $stockIn), [
        'id_bahan' => $otherIngredient->id_bahan,
        'tanggal' => '2026-11-20',
        'jumlah' => 4,
        'harga_per_satuan_dasar' => 3,
        'supplier' => 'Supplier Baru',
    ])->assertRedirect(route('manajer.stok-masuk'));

    $this->assertDatabaseHas('ingredients', ['id_bahan' => $ingredient->id_bahan, 'stok' => 10]);
    $this->assertDatabaseHas('ingredients', [
        'id_bahan' => $otherIngredient->id_bahan,
        'stok' => 6,
        'harga_per_satuan_dasar' => 3,
    ]);

    $this->delete(route('manajer.stok-masuk.destroy', $stockIn))
        ->assertRedirect(route('manajer.stok-masuk'));

    $this->assertDatabaseHas('ingredients', ['id_bahan' => $otherIngredient->id_bahan, 'stok' => 2]);
    $this->assertDatabaseMissing('stok_in', ['id_stok_masuk' => $stockIn->id_stok_masuk]);
});

it('preserves stock when a stock-in record cannot be reversed', function () {
    $this->actingAs(createManagerUser());
    $ingredient = createIngredient(['stok' => 2]);
    $stockIn = StockIn::query()->create([
        'id_bahan' => $ingredient->id_bahan,
        'id_pengguna' => auth()->id(),
        'tanggal' => '2026-11-19',
        'jumlah' => 5,
        'harga_per_satuan_dasar' => 1.25,
    ]);

    $this->put(route('manajer.stok-masuk.update', $stockIn), [
        'id_bahan' => $ingredient->id_bahan,
        'tanggal' => '2026-11-20',
        'jumlah' => 1,
        'harga_per_satuan_dasar' => 1.25,
    ])->assertSessionHas('error', 'Stok tidak dapat dikurangi karena jumlahnya sudah terpakai.');

    $this->delete(route('manajer.stok-masuk.destroy', $stockIn))
        ->assertSessionHas('error', 'Catatan tidak dapat dihapus karena sebagian stoknya sudah terpakai.');

    $this->assertDatabaseHas('ingredients', ['id_bahan' => $ingredient->id_bahan, 'stok' => 2]);
    $this->assertDatabaseHas('stok_in', ['id_stok_masuk' => $stockIn->id_stok_masuk]);
});

it('prevents deleting ingredients or menus used by recipes', function () {
    $this->actingAs(createManagerUser());
    $menu = Menu::query()->create([
        'nama_menu' => 'Latte',
        'kategori' => 'Minuman',
        'harga_jual' => 25000,
        'aktif' => true,
    ]);
    $ingredient = createIngredient();
    $recipe = Recipe::query()->create([
        'id_menu' => $menu->id_menu,
        'id_bahan' => $ingredient->id_bahan,
        'takaran' => 18,
    ]);

    $this->delete(route('manajer.menu.destroy', $menu))
        ->assertSessionHas('error', 'Menu tidak dapat dihapus karena masih digunakan dalam resep atau pesanan.');

    $this->delete(route('manajer.bahan.destroy', $ingredient))
        ->assertSessionHas('error', 'Bahan tidak dapat dihapus karena masih digunakan dalam resep atau riwayat stok masuk.');

    $this->assertDatabaseHas('menus', ['id_menu' => $menu->id_menu]);
    $this->assertDatabaseHas('ingredients', ['id_bahan' => $ingredient->id_bahan]);

    $recipe->delete();

    $this->delete(route('manajer.menu.destroy', $menu))
        ->assertRedirect(route('manajer.menu'));

    $this->delete(route('manajer.bahan.destroy', $ingredient))
        ->assertRedirect(route('manajer.bahan.index'));

    $this->assertDatabaseMissing('menus', ['id_menu' => $menu->id_menu]);
    $this->assertDatabaseMissing('ingredients', ['id_bahan' => $ingredient->id_bahan]);
});

it('shows live manager dashboard totals and working navigation links', function () {
    $this->actingAs(createManagerUser());
    $menu = Menu::query()->create([
        'nama_menu' => 'Latte',
        'kategori' => 'Minuman',
        'harga_jual' => 25000,
        'aktif' => true,
    ]);
    $ingredient = createIngredient(['stok' => 1]);
    Recipe::query()->create([
        'id_menu' => $menu->id_menu,
        'id_bahan' => $ingredient->id_bahan,
        'takaran' => 18,
    ]);

    $this->get(route('manajer.dashboard'))
        ->assertOk()
        ->assertSee('Manajemen Bahan')
        ->assertSee(route('manajer.bahan.index'))
        ->assertViewHas('totalMenu', 1)
        ->assertViewHas('totalResep', 1)
        ->assertViewHas('stokMenipis', 1)
        ->assertViewHas('totalBahan', 1);
});

it('renders manager CRUD screens when their tables are empty', function () {
    $this->actingAs(createManagerUser());

    $this->get(route('manajer.menu'))
        ->assertOk()
        ->assertSee('Daftar menu');

    $this->get(route('manajer.bahan.index'))
        ->assertOk()
        ->assertSee('Daftar bahan');

    $this->get(route('manajer.resep'))
        ->assertOk()
        ->assertSee('Belum ada komposisi resep');

    $this->get(route('manajer.stok-masuk'))
        ->assertOk()
        ->assertSee('Riwayat stok masuk');
});
