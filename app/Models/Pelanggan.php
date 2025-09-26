<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama',
        'email',
        'nomor_hp',
        'alamat',
        'membership_id',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model Membership.
     * Setiap pelanggan memiliki satu membership.
     */
    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
    
    /**
     * Mendefinisikan relasi "hasMany" ke model Transaksi.
     * Setiap pelanggan bisa memiliki banyak transaksi.
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}