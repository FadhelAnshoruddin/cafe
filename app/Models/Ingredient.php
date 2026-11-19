<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $table = 'ingredients';

    protected $primaryKey = 'id_bahan';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'nama_bahan',
        'satuan_dasar',
        'stok',
        'stok_minimum',
        'harga_per_satuan_dasar',
        'harga_diperbarui_pada',
    ];

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'id_bahan', 'id_bahan');
    }

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class, 'id_bahan', 'id_bahan');
    }
}
