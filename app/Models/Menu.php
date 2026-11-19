<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $table = 'menus';

    protected $primaryKey = 'id_menu';

    public $timestamps = false;

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

    public function getRouteKeyName(): string
    {
        return 'id_menu';
    }

    public function recipe(): HasMany
    {
        return $this->hasMany(Recipe::class, 'id_menu', 'id_menu');
    }

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'id_menu', 'id_menu');
    }
}
