<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'orders';

    protected $primaryKey = 'id_pesanan';

    public $timestamps = false;

    protected $fillable = [
        'id_pengguna',
        'sumber',
        'nomor_meja',
        'metode_bayar',
        'status',
        'subtotal',
        'biaya_layanan',
        'pajak',
        'total',
        'dibuat_pada',
        'request_key',
    ];

    protected function casts(): array
    {
        return [
            'nomor_meja' => 'integer',
            'subtotal' => 'decimal:2',
            'biaya_layanan' => 'decimal:2',
            'pajak' => 'decimal:2',
            'total' => 'decimal:2',
            'dibuat_pada' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'id_pesanan', 'id_pesanan');
    }
}
