<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Models\StockIn;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StockInController extends Controller
{
    public function index(): View
    {
        return view('Manajer.StokMasuk', [
            'stockIns' => StockIn::with('ingredient')
                ->orderByDesc('tanggal')
                ->orderByDesc('id_stok_masuk')
                ->get(),
            'ingredients' => Ingredient::orderBy('nama_bahan')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        DB::transaction(function () use ($data, $request): void {
            $ingredient = Ingredient::query()
                ->lockForUpdate()
                ->findOrFail($data['id_bahan']);

            $ingredient->increment('stok', $data['jumlah']);
            $ingredient->update([
                'harga_per_satuan_dasar' => $data['harga_per_satuan_dasar'],
                'harga_diperbarui_pada' => now(),
            ]);

            StockIn::create($data + [
                'id_pengguna' => $request->user()->getAuthIdentifier(),
            ]);
        });

        return redirect()->route('manajer.stok-masuk')->with('success', 'Stok masuk berhasil dicatat.');
    }

    public function update(Request $request, StockIn $stockIn): RedirectResponse
    {
        $data = $this->validatedData($request);

        $updated = DB::transaction(function () use ($data, $stockIn): bool {
            $lockedStockIn = StockIn::query()
                ->lockForUpdate()
                ->findOrFail($stockIn->getKey());

            $ingredientIds = collect([$lockedStockIn->id_bahan, $data['id_bahan']])
                ->unique()
                ->sort()
                ->values();

            $ingredients = Ingredient::query()
                ->whereIn('id_bahan', $ingredientIds)
                ->orderBy('id_bahan')
                ->lockForUpdate()
                ->get()
                ->keyBy('id_bahan');

            $previousIngredient = $ingredients->get($lockedStockIn->id_bahan);
            $newIngredient = $ingredients->get($data['id_bahan']);

            if ($previousIngredient === null || $newIngredient === null) {
                abort(404);
            }

            if ($previousIngredient->is($newIngredient)) {
                $difference = (float) $data['jumlah'] - (float) $lockedStockIn->jumlah;
                $updatedStock = round((float) $previousIngredient->stok + $difference, 3);

                if ($updatedStock < 0) {
                    return false;
                }

                if ($difference > 0) {
                    $previousIngredient->increment('stok', $difference);
                } elseif ($difference < 0) {
                    $previousIngredient->decrement('stok', abs($difference));
                }
            } else {
                $remainingStock = round((float) $previousIngredient->stok - (float) $lockedStockIn->jumlah, 3);

                if ($remainingStock < 0) {
                    return false;
                }

                $previousIngredient->decrement('stok', (float) $lockedStockIn->jumlah);
                $newIngredient->increment('stok', $data['jumlah']);
            }

            $newIngredient->update([
                'harga_per_satuan_dasar' => $data['harga_per_satuan_dasar'],
                'harga_diperbarui_pada' => now(),
            ]);

            $lockedStockIn->update($data);

            return true;
        });

        if (! $updated) {
            return back()
                ->withInput()
                ->with('error', 'Stok tidak dapat dikurangi karena jumlahnya sudah terpakai.');
        }

        return redirect()->route('manajer.stok-masuk')->with('success', 'Catatan stok masuk berhasil diperbarui.');
    }

    public function destroy(StockIn $stockIn): RedirectResponse
    {
        $deleted = DB::transaction(function () use ($stockIn): bool {
            $lockedStockIn = StockIn::query()
                ->lockForUpdate()
                ->findOrFail($stockIn->getKey());

            $ingredient = Ingredient::query()
                ->lockForUpdate()
                ->findOrFail($lockedStockIn->id_bahan);

            $remainingStock = round((float) $ingredient->stok - (float) $lockedStockIn->jumlah, 3);

            if ($remainingStock < 0) {
                return false;
            }

            $ingredient->decrement('stok', (float) $lockedStockIn->jumlah);
            $lockedStockIn->delete();

            return true;
        });

        if (! $deleted) {
            return back()->with('error', 'Catatan tidak dapat dihapus karena sebagian stoknya sudah terpakai.');
        }

        return redirect()->route('manajer.stok-masuk')->with('success', 'Catatan stok masuk berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'id_bahan' => ['required', 'integer', 'exists:ingredients,id_bahan'],
            'tanggal' => ['required', 'date'],
            'jumlah' => ['required', 'numeric', 'gt:0'],
            'harga_per_satuan_dasar' => ['required', 'numeric', 'min:0'],
            'supplier' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
