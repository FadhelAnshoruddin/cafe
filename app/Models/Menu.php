<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menus';
    protected $primaryKey = 'id_menu';
    public $timestamps = false;

    public function getRouteKeyName(): string
    {
        return 'id_menu';
    }

    protected $fillable = [
        'nama_menu',
        'harga_jual',
        'kategori',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'harga_jual' => 'decimal:2',
            'aktif' => 'boolean',
        ];
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'id_menu', 'id_menu');
    }
}
