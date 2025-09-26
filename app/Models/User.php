<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';

    protected $fillable = [
        'username',
        'nama',
        'email',
        'password',
        'nomor_hp',
        'alamat',
        'jabatan',
        'role',
        'image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relasi dengan transaksi
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}