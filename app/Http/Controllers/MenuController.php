<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $query = Menu::query()->with('recipes.ingredient')->orderBy('nama_menu');

        if ($request->filled('cari')) {
            $keyword = trim((string) $request->query('cari'));
            $query->where(fn ($q) => $q->where('nama_menu', 'like', "%{$keyword}%")
                ->orWhere('kategori', 'like', "%{$keyword}%"));
        }

        $menus = $query->get();

        foreach ($menus as $menu) {
            $menu->hpp_hitung = $menu->recipes->sum(fn ($recipe) => $recipe->ingredient
                    ? (float) $recipe->takaran * (float) $recipe->ingredient->harga_per_satuan_dasar
                    : 0
            );
            $menu->margin_hitung = (float) $menu->harga_jual - $menu->hpp_hitung;
            $menu->persen_margin = (float) $menu->harga_jual > 0
                ? ($menu->margin_hitung / (float) $menu->harga_jual) * 100 : 0;
        }

        return view('Manajer.Menu', [
            'menus' => $menus,
            'totalMenu' => Menu::count(),
            'rataHarga' => (float) (Menu::avg('harga_jual') ?? 0),
            'pencarian' => (string) $request->query('cari', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_menu' => ['required', 'string', 'max:255', 'unique:menus,nama_menu'],
            'kategori' => ['required', 'string', 'max:100'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
        ]);

        Menu::create($data + ['aktif' => true]);

        return redirect()->route('manajer.menu')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $data = $request->validate([
            'nama_menu' => ['required', 'string', 'max:255', Rule::unique('menus', 'nama_menu')->ignore($menu->id_menu, 'id_menu')],
            'kategori' => ['required', 'string', 'max:100'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
        ]);

        $menu->update($data);

        return redirect()->route('manajer.menu')->with('success', 'Menu berhasil diperbarui.');
    }

    public function toggleStatus(Menu $menu): RedirectResponse
    {
        $menu->update(['aktif' => ! $menu->aktif]);

        return redirect()->route('manajer.menu')->with(
            'success',
            $menu->aktif ? 'Menu berhasil diaktifkan.' : 'Menu berhasil dinonaktifkan.'
        );
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $hasOrderDetails = DB::table('orders_details')
            ->where('id_menu', $menu->id_menu)
            ->exists();

        if ($menu->recipes()->exists() || $hasOrderDetails) {
            return back()->with('error', 'Menu tidak dapat dihapus karena masih digunakan dalam resep atau pesanan.');
        }

        $menu->delete();

        return redirect()->route('manajer.menu')->with('success', 'Menu berhasil dihapus.');
    }
}
