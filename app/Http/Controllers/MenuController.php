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
        $query = Menu::query()->with(['recipes.ingredient'])->orderBy('nama_menu');

        if ($request->filled('cari')) {
            $keyword = $request->string('cari')->toString();
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_menu', 'like', "%{$keyword}%")
                  ->orWhere('kategori', 'like', "%{$keyword}%");
            });
        }

        $menus = $query->get()->map(function (Menu $menu) {
            $menu->hpp_hitung = $menu->recipes->sum(function ($recipe) {
                return (float) $recipe->takaran
                    * (float) $recipe->ingredient->harga_per_satuan_dasar;
            });
            $menu->margin_hitung = (float) $menu->harga_jual - $menu->hpp_hitung;
            $menu->persen_margin = $menu->harga_jual > 0
                ? ($menu->margin_hitung / (float) $menu->harga_jual) * 100
                : 0;
            return $menu;
        });

        return view('Manajer.Menu', [
            'menus' => $menus,
            'totalMenu' => Menu::count(),
            'rataHarga' => (float) (Menu::avg('harga_jual') ?? 0),
            'pencarian' => $request->query('cari', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:255', 'unique:menus,nama_menu'],
            'kategori' => ['required', 'string', 'max:100'],
            'harga_jual' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);

        Menu::create($validated + ['aktif' => true]);

        return redirect()->route('manajer.menu')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:255', Rule::unique('menus', 'nama_menu')->ignore($menu->id_menu, 'id_menu')],
            'kategori' => ['required', 'string', 'max:100'],
            'harga_jual' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);

        $menu->update($validated);

        return redirect()->route('manajer.menu')->with('success', 'Menu berhasil diperbarui.');
    }

    public function toggleStatus(Menu $menu): RedirectResponse
    {
        $menu->update(['aktif' => !$menu->aktif]);

        return redirect()->route('manajer.menu')->with(
            'success',
            $menu->aktif ? 'Menu berhasil diaktifkan.' : 'Menu berhasil dinonaktifkan.'
        );
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        if ($menu->recipes()->exists()) {
            return redirect()->route('manajer.menu')
                ->with('error', 'Menu tidak dapat dihapus karena masih digunakan dalam resep. Nonaktifkan menu jika tidak digunakan.');
        }

        $menu->delete();

        return redirect()->route('manajer.menu')->with('success', 'Menu berhasil dihapus.');
    }
}
