<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class IngredientController extends Controller
{
    public function index(): View
    {
        $ingredients = Ingredient::orderBy('id_bahan', 'desc')->get();

        return view('Manajer.Bahan', compact('ingredients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bahan' => ['required', 'string', 'max:255', 'unique:ingredients,nama_bahan'],
            'satuan_dasar' => ['required', 'in:g,ml,pcs'],
            'stok' => ['required', 'numeric', 'min:0'],
            'stok_minimum' => ['required', 'numeric', 'min:0'],
            'harga_per_satuan_dasar' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['harga_diperbarui_pada'] = now();

        Ingredient::create($validated);

        return redirect()
            ->route('manajer.bahan.index')
            ->with('success', 'Bahan berhasil ditambahkan!');
    }

    public function update(Request $request, Ingredient $ingredient): RedirectResponse
    {
        $validated = $request->validate([
            'nama_bahan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ingredients', 'nama_bahan')
                    ->ignore($ingredient->id_bahan, 'id_bahan'),
            ],
            'satuan_dasar' => ['required', 'in:g,ml,pcs'],
            'stok' => ['required', 'numeric', 'min:0'],
            'stok_minimum' => ['required', 'numeric', 'min:0'],
            'harga_per_satuan_dasar' => ['required', 'numeric', 'min:0'],
        ]);

        $validated['harga_diperbarui_pada'] = now();

        $ingredient->update($validated);

        return redirect()
            ->route('manajer.bahan.index')
            ->with('success', 'Data bahan berhasil diperbarui!');
    }

    public function destroy(Ingredient $ingredient): RedirectResponse
    {
        if ($ingredient->recipes()->exists() || $ingredient->stockIns()->exists()) {
            return back()->with('error', 'Bahan tidak dapat dihapus karena masih digunakan dalam resep atau riwayat stok masuk.');
        }

        $ingredient->delete();

        return redirect()
            ->route('manajer.bahan.index')
            ->with('success', 'Bahan berhasil dihapus!');
    }
}
