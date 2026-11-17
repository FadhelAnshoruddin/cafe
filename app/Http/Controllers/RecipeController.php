<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Menu;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class RecipeController extends Controller{
    
    // READ: Menampilkan semua data resep
    public function index()
    {
        $recipes = Recipe::with(['menu', 'ingredient'])
            ->orderBy('id_resep')
            ->get();

        $menus = Menu::orderBy('nama_menu')->get();

        $ingredients = Ingredient::orderBy('nama_bahan')->get();

        return view('Manajer.Resep', compact(
            'recipes',
            'menus',
            'ingredients'
        ));
    }


    // CREATE: Menambahkan resep baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_menu' => 'required|exists:menus,id_menu',
            'id_bahan' => 'required|exists:ingredients,id_bahan',
            'takaran' => 'required|numeric|gt:0',
        ]);

        // Memeriksa apakah bahan sudah ada di resep menu tersebut
        $duplicate = Recipe::where('id_menu', $validated['id_menu'])
            ->where('id_bahan', $validated['id_bahan'])
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->with('error', 'Bahan tersebut sudah ada dalam resep menu ini.');
        }

        Recipe::create($validated);

        return redirect()
            ->route('manajer.resep')
            ->with('success', 'Resep berhasil ditambahkan.');
    }


    // UPDATE: Mengubah data resep
    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'id_menu' => 'required|exists:menus,id_menu',
            'id_bahan' => 'required|exists:ingredients,id_bahan',
            'takaran' => 'required|numeric|gt:0',
        ]);

        // Memeriksa duplikasi, kecuali data yang sedang diedit
        $duplicate = Recipe::where('id_menu', $validated['id_menu'])
            ->where('id_bahan', $validated['id_bahan'])
            ->where('id_resep', '!=', $recipe->id_resep)
            ->exists();

        if ($duplicate) {
            return back()
                ->withInput()
                ->with('error', 'Bahan tersebut sudah ada dalam resep menu ini.');
        }

        $recipe->update($validated);

        return redirect()
            ->route('manajer.resep')
            ->with('success', 'Resep berhasil diperbarui.');
    }


    // DELETE: Menghapus data resep
    public function destroy(Recipe $recipe)
    {
        $recipe->delete();

        return redirect()
            ->route('manajer.resep')
            ->with('success', 'Resep berhasil dihapus.');
    }
    
}