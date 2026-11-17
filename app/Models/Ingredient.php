<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $table = 'ingredients';

    protected $primaryKey = 'id_bahan';

    protected $fillable = [
        'nama_bahan',
        'satuan_dasar',
        'harga_per_satuan_dasar',
        'stok',
    ];

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'id_bahan', 'id_bahan');
    }
}