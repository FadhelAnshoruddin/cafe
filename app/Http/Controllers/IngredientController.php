<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class IngredientController extends Controller
{
    // READ
    public function index()
    {
        $ingredients = Ingredient::latest('id_bahan')->get();

        return view('Manajer.Bahan', compact('ingredients'));
    }

    // CREATE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan_dasar' => 'required|string|max:50',
            'harga_per_satuan_dasar' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
        ]);

        Ingredient::create($validated);

        return redirect()
            ->route('manajer.bahan')
            ->with('success', 'Bahan berhasil ditambahkan.');
    }

    // UPDATE
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan_dasar' => 'required|string|max:50',
            'harga_per_satuan_dasar' => 'required|numeric|min:0',
            'stok' => 'required|numeric|min:0',
        ]);

        $ingredient->update($validated);

        return redirect()
            ->route('manajer.bahan')
            ->with('success', 'Bahan berhasil diperbarui.');
    }

    // DELETE
    public function destroy(Ingredient $ingredient)
    {
        if ($ingredient->recipes()->exists()) {
            return back()->with(
                'error',
                'Bahan tidak dapat dihapus karena masih digunakan dalam resep.'
            );
        }

        $ingredient->delete();

        return redirect()
            ->route('manajer.bahan')
            ->with('success', 'Bahan berhasil dihapus.');
    }
}