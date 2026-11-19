<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockIn extends Model
{
    protected $table = 'stok_in';

    protected $primaryKey = 'id_stok_masuk';

    public $timestamps = false;

    protected $fillable = [
        'id_bahan',
        'id_pengguna',
        'tanggal',
        'jumlah',
        'harga_per_satuan_dasar',
        'supplier',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'decimal:3',
            'harga_per_satuan_dasar' => 'decimal:4',
        ];
    }

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'id_bahan', 'id_bahan');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }
}
