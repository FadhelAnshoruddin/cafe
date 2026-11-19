<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecipeController extends Controller
{
    public function index(): View
    {
        return view('Manajer.Resep', [
            'recipes' => Recipe::with(['menu', 'ingredient'])
                ->orderBy('id_menu')
                ->orderBy('id_resep')
                ->get(),
            'menus' => Menu::orderBy('nama_menu')->get(),
            'ingredients' => Ingredient::orderBy('nama_bahan')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id_menu' => ['required', 'exists:menus,id_menu'],
            'id_bahan' => ['required', 'exists:ingredients,id_bahan'],
            'takaran' => ['required', 'numeric', 'gt:0'],
        ]);

        if ($this->compositionExists($validated['id_menu'], $validated['id_bahan'])) {
            return back()
                ->withInput()
                ->with('error', 'Bahan tersebut sudah ada dalam resep menu ini.');
        }

        Recipe::create($validated);

        return redirect()->route('manajer.resep')->with('success', 'Resep berhasil ditambahkan.');
    }

    public function update(Request $request, Recipe $recipe): RedirectResponse
    {
        $validated = $request->validate([
            'id_menu' => ['required', 'exists:menus,id_menu'],
            'id_bahan' => ['required', 'exists:ingredients,id_bahan'],
            'takaran' => ['required', 'numeric', 'gt:0'],
        ]);

        if ($this->compositionExists($validated['id_menu'], $validated['id_bahan'], $recipe)) {
            return back()
                ->withInput()
                ->with('error', 'Bahan tersebut sudah ada dalam resep menu ini.');
        }

        $recipe->update($validated);

        return redirect()->route('manajer.resep')->with('success', 'Resep berhasil diperbarui.');
    }

    public function destroy(Recipe $recipe): RedirectResponse
    {
        $recipe->delete();

        return redirect()->route('manajer.resep')->with('success', 'Resep berhasil dihapus.');
    }

    private function compositionExists(int|string $menuId, int|string $ingredientId, ?Recipe $except = null): bool
    {
        return Recipe::query()
            ->where('id_menu', $menuId)
            ->where('id_bahan', $ingredientId)
            ->when($except, fn ($query) => $query->where('id_resep', '!=', $except->id_resep))
            ->exists();
    }
}
