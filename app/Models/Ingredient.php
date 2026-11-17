<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ingredient extends Model
{
    protected $table = 'ingredients';
    protected $primaryKey = 'id_bahan';
    public $timestamps = false;
    protected $guarded = [];

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class, 'id_bahan', 'id_bahan');
    }
}
