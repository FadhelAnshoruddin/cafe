<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'nomor_hp', 'password_hash', 'peran', 'wajib_ganti_password'])]
#[Hidden(['password_hash'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_pengguna';

    public $timestamps = false;

    /**
     * Kolom password yang digunakan oleh Laravel Auth.
     */
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    /**
     * Accessor agar middleware bisa menggunakan $user->role.
     */
    public function getRoleAttribute(): string
    {
        return $this->peran;
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'id_pengguna', 'id_pengguna');
    }

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password_hash' => 'hashed',
            'wajib_ganti_password' => 'boolean',
            'dibuat_pada' => 'datetime',
        ];
    }
}
