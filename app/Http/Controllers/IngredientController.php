<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IngredientController extends Controller
{
    // Menampilkan seluruh data bahan
    public function index()
    {
        $ingredients = Ingredient::orderBy('id_bahan', 'desc')->get();

        return view('Manajer.Bahan', compact('ingredients'));
    }

    // Menambahkan bahan baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bahan' => 'required|string|max:255|unique:ingredients,nama_bahan',
            'satuan_dasar' => 'required|in:g,ml,pcs',
            'stok' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_per_satuan_dasar' => 'required|numeric|min:0',
        ]);

        $validated['harga_diperbarui_pada'] = now();

        Ingredient::create($validated);

        return redirect()
            ->route('manajer.bahan.index')
            ->with('success', 'Bahan berhasil ditambahkan!');
    }

    // Memperbarui data bahan
    public function update(Request $request, $id_bahan)
    {
        $ingredient = Ingredient::findOrFail($id_bahan);

        $validated = $request->validate([
            'nama_bahan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ingredients', 'nama_bahan')
                    ->ignore($ingredient->id_bahan, 'id_bahan'),
            ],
            'satuan_dasar' => 'required|in:g,ml,pcs',
            'stok' => 'required|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
            'harga_per_satuan_dasar' => 'required|numeric|min:0',
        ]);

        $validated['harga_diperbarui_pada'] = now();

        $ingredient->update($validated);

        return redirect()
            ->route('manajer.bahan.index')
            ->with('success', 'Data bahan berhasil diperbarui!');
    }

    // Menghapus data bahan
    public function destroy($id_bahan)
    {
        $ingredient = Ingredient::findOrFail($id_bahan);

        $ingredient->delete();

        return redirect()
            ->route('manajer.bahan.index')
            ->with('success', 'Bahan berhasil dihapus!');
    }
}