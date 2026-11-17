<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    protected $table = 'recipes';
    protected $primaryKey = 'id_resep';
    public $timestamps = false;
    protected $guarded = [];

    public function ingredient(): BelongsTo
    {
        return $this->belongsTo(Ingredient::class, 'id_bahan', 'id_bahan');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'id_menu', 'id_menu');
    }
}
