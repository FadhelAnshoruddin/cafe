<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}