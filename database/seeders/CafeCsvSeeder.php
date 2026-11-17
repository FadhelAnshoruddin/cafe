<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class CafeCsvSeeder extends Seeder
{
    private array $report = [];
    private int $imported = 0;
    private int $skipped = 0;

    public function run(): void
    {
        $base = database_path('data_csv/Varian_C_Kafe');

        // Urutan wajib: Data_Awal dahulu, master dan data demo sesudahnya.
        $this->importIngredients($base . '/Data_Awal/bahan.csv');
        $this->importMenus($base . '/Data_Awal/menu.csv');
        $this->importRecipes($base . '/Data_Awal/resep.csv');

        $this->seedMasterCategories($base . '/Data_Seeder_CSV/master_kategori_menu.csv');
        $this->seedDemoStock($base . '/Data_Seeder_CSV/seed_stok_demo.csv');

        $this->writeReport();

        $this->command?->info("Impor CSV Varian C selesai: {$this->imported} baris masuk, {$this->skipped} baris dilewati.");
        $this->command?->info('Laporan: storage/app/import-reports/varian-c-import-report.csv');
    }

    private function rows(string $path): array
    {
        if (!File::exists($path)) {
            throw new \RuntimeException("File CSV tidak ditemukan: {$path}");
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \RuntimeException("File CSV tidak dapat dibuka: {$path}");
        }

        $headers = fgetcsv($handle);
        if (!$headers) {
            fclose($handle);
            return [];
        }

        $headers = array_map(fn ($value) => trim(preg_replace('/^\xEF\xBB\xBF/', '', (string) $value)), $headers);
        $rows = [];
        $line = 1;

        while (($values = fgetcsv($handle)) !== false) {
            $line++;
            if ($values === [null] || (count($values) === 1 && trim((string) $values[0]) === '')) {
                continue;
            }

            $values = array_pad($values, count($headers), null);
            $rows[] = ['line' => $line, 'data' => array_combine($headers, array_slice($values, 0, count($headers)))];
        }

        fclose($handle);
        return $rows;
    }

    private function importIngredients(string $path): void
    {
        foreach ($this->rows($path) as $row) {
            $d = $row['data'];
            try {
                $kode = trim((string) ($d['kode'] ?? ''));
                $nama = trim((string) ($d['nama_bahan'] ?? ''));
                $satuan = trim((string) ($d['satuan'] ?? ''));
                $jumlah = $this->number($d['stok_awal'] ?? null);
                $harga = $this->number($d['harga_per_satuan'] ?? null);

                if ($kode === '' || $nama === '' || $jumlah === null || $harga === null || $jumlah < 0 || $harga < 0) {
                    $this->skip($path, $row['line'], 'Data bahan wajib tidak valid.');
                    continue;
                }
                if (DB::table('ingredients')->where('kode_bahan', $kode)->exists()
                    || DB::table('ingredients')->where('nama_bahan', $nama)->exists()) {
                    $this->skip($path, $row['line'], 'Duplikat kode atau nama bahan.');
                    continue;
                }

                [$baseUnit, $stockFactor] = match ($satuan) {
                    'kg' => ['g', 1000],
                    'liter' => ['ml', 1000],
                    'g', 'ml', 'pcs' => [$satuan, 1],
                    default => [null, null],
                };

                if ($baseUnit === null) {
                    $this->skip($path, $row['line'], "Satuan bahan tidak dikenal: {$satuan}.");
                    continue;
                }

                DB::table('ingredients')->insert([
                    'kode_bahan' => $kode,
                    'nama_bahan' => $nama,
                    'satuan_dasar' => $baseUnit,
                    'stok' => $jumlah * $stockFactor,
                    'stok_minimum' => 0,
                    'harga_per_satuan_dasar' => $harga / $stockFactor,
                    'harga_diperbarui_pada' => now(),
                ]);
                $this->success($path, $row['line']);
            } catch (Throwable $e) {
                $this->skip($path, $row['line'], 'Gagal menyimpan bahan: ' . $e->getMessage());
            }
        }
    }

    private function importMenus(string $path): void
    {
        foreach ($this->rows($path) as $row) {
            $d = $row['data'];
            try {
                $kode = trim((string) ($d['kode'] ?? ''));
                $nama = trim((string) ($d['nama_menu'] ?? ''));
                $kategori = trim((string) ($d['kategori'] ?? ''));
                $harga = $this->number($d['harga_jual'] ?? null);

                if ($kode === '' || $nama === '' || $kategori === '' || $harga === null || $harga < 0) {
                    $this->skip($path, $row['line'], 'Data menu wajib tidak valid.');
                    continue;
                }
                if (DB::table('menus')->where('kode_menu', $kode)->exists()
                    || DB::table('menus')->where('nama_menu', $nama)->exists()) {
                    $this->skip($path, $row['line'], 'Duplikat kode atau nama menu.');
                    continue;
                }

                DB::table('menus')->insert([
                    'kode_menu' => $kode,
                    'nama_menu' => $nama,
                    'kategori' => $kategori,
                    'harga_jual' => $harga,
                    'aktif' => true,
                ]);
                $this->success($path, $row['line']);
            } catch (Throwable $e) {
                $this->skip($path, $row['line'], 'Gagal menyimpan menu: ' . $e->getMessage());
            }
        }
    }

    private function importRecipes(string $path): void
    {
        foreach ($this->rows($path) as $row) {
            $d = $row['data'];
            try {
                $kodeMenu = trim((string) ($d['kode_menu'] ?? ''));
                $namaBahan = trim((string) ($d['nama_bahan'] ?? ''));
                $takaran = $this->number($d['takaran'] ?? null);
                $satuan = trim((string) ($d['satuan'] ?? ''));

                if ($kodeMenu === '' || $namaBahan === '' || $takaran === null || $takaran <= 0 || !in_array($satuan, ['g', 'ml', 'pcs'], true)) {
                    $this->skip($path, $row['line'], 'Data resep wajib tidak valid.');
                    continue;
                }

                $menu = DB::table('menus')->where('kode_menu', $kodeMenu)->first();
                $ingredient = DB::table('ingredients')->whereRaw('LOWER(nama_bahan) = ?', [mb_strtolower($namaBahan)])->first();

                if (!$menu) {
                    $this->skip($path, $row['line'], "Kode menu {$kodeMenu} tidak ditemukan.");
                    continue;
                }
                if (!$ingredient) {
                    $this->skip($path, $row['line'], "Bahan '{$namaBahan}' tidak ditemukan (periksa ejaan/nama bahan).");
                    continue;
                }
                if ($ingredient->satuan_dasar !== $satuan) {
                    $this->skip($path, $row['line'], "Satuan resep {$satuan} tidak sesuai satuan dasar bahan {$ingredient->satuan_dasar}.");
                    continue;
                }
                if (DB::table('recipes')->where('id_menu', $menu->id_menu)->where('id_bahan', $ingredient->id_bahan)->exists()) {
                    $this->skip($path, $row['line'], 'Komposisi resep duplikat untuk menu dan bahan tersebut.');
                    continue;
                }

                DB::table('recipes')->insert([
                    'id_menu' => $menu->id_menu,
                    'id_bahan' => $ingredient->id_bahan,
                    'takaran' => $takaran,
                ]);
                $this->success($path, $row['line']);
            } catch (Throwable $e) {
                $this->skip($path, $row['line'], 'Gagal menyimpan resep: ' . $e->getMessage());
            }
        }
    }

    private function seedMasterCategories(string $path): void
    {
        foreach ($this->rows($path) as $row) {
            $d = $row['data'];
            $kode = trim((string) ($d['kode_kategori'] ?? ''));
            $nama = trim((string) ($d['nama_kategori'] ?? ''));
            if ($kode === '' || $nama === '') {
                $this->skip($path, $row['line'], 'Master kategori tidak valid.');
                continue;
            }

            // UpdateOrInsert menjaga satu baris per kode dan memastikan master tersedia.
            DB::table('kategori_menus')->updateOrInsert(
                ['kode_kategori' => $kode],
                ['nama_kategori' => $nama]
            );
            $this->success($path, $row['line']);
        }
    }

    private function seedDemoStock(string $path): void
    {
        foreach ($this->rows($path) as $row) {
            $d = $row['data'];
            $nama = trim((string) ($d['nama_bahan'] ?? ''));
            $jumlah = $this->number($d['jumlah'] ?? null);
            $satuan = trim((string) ($d['satuan'] ?? ''));

            if ($nama !== 'Susu UHT' || $jumlah === null || $jumlah < 0 || $satuan !== 'liter') {
                $this->skip($path, $row['line'], 'Baris stok demo tidak sesuai spesifikasi Varian C.');
                continue;
            }

            $ingredient = DB::table('ingredients')->where('nama_bahan', $nama)->first();
            if (!$ingredient) {
                $this->skip($path, $row['line'], 'Susu UHT belum tersedia; jalankan impor Data_Awal terlebih dahulu.');
                continue;
            }
            if ($ingredient->satuan_dasar !== 'ml') {
                $this->skip($path, $row['line'], 'Satuan dasar Susu UHT harus ml.');
                continue;
            }

            // Nilai stok demo adalah kondisi akhir, sehingga selalu tepat 0,3 liter = 300 ml.
            DB::table('ingredients')->where('id_bahan', $ingredient->id_bahan)->update(['stok' => $jumlah * 1000]);
            $this->success($path, $row['line']);
        }
    }

    private function number(mixed $value): ?float
    {
        if ($value === null || trim((string) $value) === '' || !is_numeric($value)) {
            return null;
        }
        return (float) $value;
    }

    private function success(string $path, int $line): void
    {
        $this->imported++;
        $this->report[] = [$path, $line, 'IMPORTED', ''];
    }

    private function skip(string $path, int $line, string $reason): void
    {
        $this->skipped++;
        $this->report[] = [$path, $line, 'SKIPPED', $reason];
    }

    private function writeReport(): void
    {
        $directory = storage_path('app/import-reports');
        File::ensureDirectoryExists($directory);
        $handle = fopen($directory . '/varian-c-import-report.csv', 'w');
        fputcsv($handle, ['file', 'row', 'status', 'reason']);
        foreach ($this->report as $record) {
            fputcsv($handle, $record);
        }
        fclose($handle);
    }
}
