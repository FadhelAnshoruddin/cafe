<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function menuListing(): EloquentCollection
    {
        $menus = Menu::query()
            ->with('recipes.ingredient')
            ->orderBy('kategori')
            ->orderBy('nama_menu')
            ->get();

        foreach ($menus as $menu) {
            $available = $menu->aktif && $menu->recipes->isNotEmpty();

            foreach ($menu->recipes as $recipe) {
                if (
                    $recipe->ingredient === null
                    || self::decimalToUnits((string) $recipe->ingredient->stok, 3)
                        < self::decimalToUnits((string) $recipe->takaran, 3)
                ) {
                    $available = false;
                    break;
                }
            }

            $menu->setAttribute('tersedia', $available);
            $menu->setAttribute(
                'status_ketersediaan',
                ! $menu->aktif ? 'Nonaktif' : ($available ? 'Tersedia' : 'Habis')
            );
        }

        return $menus;
    }

    /**
     * @param  array{nomor_meja: int, metode_bayar: string, request_key: string, items: list<array{id_menu: int, jumlah: int}>}  $data
     */
    public function create(array $data, User $user, string $source = 'kasir'): Order
    {
        $existingOrder = Order::query()
            ->where('request_key', $data['request_key'])
            ->where('id_pengguna', $user->getAuthIdentifier())
            ->first();

        if ($existingOrder !== null) {
            return $existingOrder->load('details');
        }

        try {
            return DB::transaction(function () use ($data, $user, $source): Order {
                if ($source === 'apk' && Order::query()
                    ->where('id_pengguna', $user->getAuthIdentifier())
                    ->where('status', 'baru')
                    ->exists()) {
                    throw ValidationException::withMessages([
                        'items' => 'Anda masih memiliki pesanan berstatus Baru.',
                    ]);
                }

                $quantitiesByMenu = collect($data['items'])
                    ->groupBy('id_menu')
                    ->map(fn (Collection $items): int => $items->sum('jumlah'));
                $menuIds = $quantitiesByMenu->keys()->map(fn ($id): int => (int) $id)->sort()->values();

                $menus = Menu::query()
                    ->whereIn('id_menu', $menuIds)
                    ->orderBy('id_menu')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id_menu');

                if ($menus->count() !== $menuIds->count()) {
                    throw ValidationException::withMessages([
                        'items' => 'Salah satu menu yang dipilih tidak lagi tersedia.',
                    ]);
                }

                foreach ($menus as $menu) {
                    if (! $menu->aktif) {
                        throw ValidationException::withMessages([
                            'items' => "Menu {$menu->nama_menu} sedang nonaktif.",
                        ]);
                    }
                }

                $recipes = Recipe::query()
                    ->whereIn('id_menu', $menuIds)
                    ->orderBy('id_bahan')
                    ->orderBy('id_menu')
                    ->lockForUpdate()
                    ->get()
                    ->groupBy('id_menu');

                foreach ($menuIds as $menuId) {
                    if ($recipes->get($menuId, collect())->isEmpty()) {
                        throw ValidationException::withMessages([
                            'items' => "Menu {$menus->get($menuId)->nama_menu} belum memiliki resep.",
                        ]);
                    }
                }

                $ingredientIds = $recipes->flatten()->pluck('id_bahan')->unique()->sort()->values();
                $ingredients = Ingredient::query()
                    ->whereIn('id_bahan', $ingredientIds)
                    ->orderBy('id_bahan')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id_bahan');

                $requiredUnits = [];
                foreach ($quantitiesByMenu as $menuId => $quantity) {
                    foreach ($recipes->get($menuId) as $recipe) {
                        $perServing = self::decimalToUnits((string) $recipe->takaran, 3);

                        if ($quantity > intdiv(PHP_INT_MAX, $perServing)) {
                            throw ValidationException::withMessages([
                                'items' => 'Jumlah pesanan melebihi batas yang dapat diproses.',
                            ]);
                        }

                        $requiredUnits[$recipe->id_bahan] = ($requiredUnits[$recipe->id_bahan] ?? 0)
                            + ($perServing * $quantity);
                    }
                }

                $shortages = [];
                foreach ($requiredUnits as $ingredientId => $required) {
                    $ingredient = $ingredients->get($ingredientId);
                    if ($ingredient === null) {
                        throw ValidationException::withMessages([
                            'items' => 'Data bahan resep tidak ditemukan.',
                        ]);
                    }

                    $available = self::decimalToUnits((string) $ingredient->stok, 3);
                    if ($available < $required) {
                        $shortages[] = sprintf(
                            '%s (kurang %s %s)',
                            $ingredient->nama_bahan,
                            self::unitsToDecimal($required - $available, 3),
                            $ingredient->satuan_dasar
                        );
                    }
                }

                if ($shortages !== []) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok bahan tidak cukup: '.implode(', ', $shortages).'.',
                    ]);
                }

                $subtotalCents = 0;
                $orderLines = [];
                foreach ($quantitiesByMenu as $menuId => $quantity) {
                    $menu = $menus->get($menuId);
                    $unitPriceCents = self::decimalToUnits((string) $menu->harga_jual, 2);
                    $lineTotal = $unitPriceCents * $quantity;

                    if ($lineTotal > PHP_INT_MAX - $subtotalCents) {
                        throw ValidationException::withMessages([
                            'items' => 'Total pesanan melebihi batas yang dapat diproses.',
                        ]);
                    }

                    $subtotalCents += $lineTotal;
                    $orderLines[] = [
                        'menu' => $menu,
                        'jumlah' => $quantity,
                        'harga_satuan' => self::unitsToDecimal($unitPriceCents, 2),
                        'subtotal_item' => self::unitsToDecimal($lineTotal, 2),
                        'resep_snapshot' => $recipes->get($menuId)
                            ->mapWithKeys(fn (Recipe $recipe): array => [
                                (string) $recipe->id_bahan => self::decimalToUnits((string) $recipe->takaran, 3),
                            ])
                            ->all(),
                    ];
                }

                $serviceCents = self::percentage($subtotalCents, 5);
                $taxCents = self::percentage($subtotalCents + $serviceCents, 10);

                $order = Order::query()->create([
                    'id_pengguna' => $user->getAuthIdentifier(),
                    'sumber' => $source,
                    'nomor_meja' => $data['nomor_meja'],
                    'metode_bayar' => $data['metode_bayar'],
                    'status' => 'baru',
                    'subtotal' => self::unitsToDecimal($subtotalCents, 2),
                    'biaya_layanan' => self::unitsToDecimal($serviceCents, 2),
                    'pajak' => self::unitsToDecimal($taxCents, 2),
                    'total' => self::unitsToDecimal($subtotalCents + $serviceCents + $taxCents, 2),
                    'dibuat_pada' => now(),
                    'request_key' => $data['request_key'],
                ]);

                foreach ($orderLines as $line) {
                    /** @var Menu $menu */
                    $menu = $line['menu'];
                    OrderDetail::query()->create([
                        'id_pesanan' => $order->id_pesanan,
                        'id_menu' => $menu->id_menu,
                        'nama_menu_snapshot' => $menu->nama_menu,
                        'jumlah' => $line['jumlah'],
                        'harga_satuan' => $line['harga_satuan'],
                        'subtotal_item' => $line['subtotal_item'],
                        'resep_snapshot' => $line['resep_snapshot'],
                    ]);
                }

                foreach ($requiredUnits as $ingredientId => $required) {
                    $ingredient = $ingredients->get($ingredientId);
                    $ingredient->stok = self::unitsToDecimal(
                        self::decimalToUnits((string) $ingredient->stok, 3) - $required,
                        3
                    );
                    $ingredient->save();
                }

                return $order->load('details');
            }, 3);
        } catch (QueryException $exception) {
            $duplicate = Order::query()
                ->where('request_key', $data['request_key'])
                ->where('id_pengguna', $user->getAuthIdentifier())
                ->first();

            if ($duplicate !== null) {
                return $duplicate->load('details');
            }

            throw $exception;
        }
    }

    public function updateStatus(Order $order, string $nextStatus): Order
    {
        return DB::transaction(function () use ($order, $nextStatus): Order {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id_pesanan);
            $allowedNextStatus = match ($lockedOrder->status) {
                'baru' => 'diproses',
                'diproses' => 'selesai',
                default => null,
            };

            if ($nextStatus !== $allowedNextStatus) {
                throw ValidationException::withMessages([
                    'status' => 'Perubahan status harus mengikuti urutan Baru, Diproses, lalu Selesai.',
                ]);
            }

            $lockedOrder->update(['status' => $nextStatus]);

            return $lockedOrder;
        }, 3);
    }

    public function cancel(Order $order): Order
    {
        return DB::transaction(function () use ($order): Order {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->with('details')
                ->findOrFail($order->id_pesanan);

            if ($lockedOrder->status !== 'baru') {
                throw ValidationException::withMessages([
                    'status' => 'Pesanan hanya dapat dibatalkan saat berstatus Baru.',
                ]);
            }

            $requiredUnits = [];
            foreach ($lockedOrder->details as $detail) {
                foreach ($detail->resep_snapshot ?? [] as $ingredientId => $perServingUnits) {
                    $requiredUnits[$ingredientId] = ($requiredUnits[$ingredientId] ?? 0)
                        + ((int) $perServingUnits * $detail->jumlah);
                }
            }

            $ingredients = Ingredient::query()
                ->whereIn('id_bahan', array_keys($requiredUnits))
                ->orderBy('id_bahan')
                ->lockForUpdate()
                ->get()
                ->keyBy('id_bahan');

            foreach ($requiredUnits as $ingredientId => $required) {
                $ingredient = $ingredients->get($ingredientId);

                if ($ingredient === null) {
                    throw ValidationException::withMessages([
                        'status' => 'Pengembalian stok gagal karena bahan pesanan sudah tidak tersedia.',
                    ]);
                }

                $ingredient->stok = self::unitsToDecimal(
                    self::decimalToUnits((string) $ingredient->stok, 3) + $required,
                    3
                );
                $ingredient->save();
            }

            $lockedOrder->update(['status' => 'dibatalkan']);

            return $lockedOrder;
        }, 3);
    }

    private static function percentage(int $amountInCents, int $percent): int
    {
        return intdiv(($amountInCents * $percent) + 50, 100);
    }

    private static function decimalToUnits(string $value, int $scale): int
    {
        [$whole, $fraction] = array_pad(explode('.', $value, 2), 2, '');
        $fraction = substr(str_pad($fraction, $scale, '0'), 0, $scale);

        return ((int) $whole * (10 ** $scale)) + (int) $fraction;
    }

    private static function unitsToDecimal(int $units, int $scale): string
    {
        $base = 10 ** $scale;

        return intdiv($units, $base).'.'.str_pad((string) ($units % $base), $scale, '0', STR_PAD_LEFT);
    }
}
