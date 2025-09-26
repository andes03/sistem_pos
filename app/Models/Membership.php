<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $table = 'membership';

    protected $fillable = [
        'nama',
        'diskon',
        'minimal_transaksi',
    ];

    /**
     * Relasi ke model Pelanggan.
     */
    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'membership_id');
    }
}