<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Kategori
        DB::table('kategori')->insert([
            ['nama' => 'Kopi', 'deskripsi' => 'Aneka kopi seduh dan espresso', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Non-Kopi', 'deskripsi' => 'Minuman selain kopi', 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Makanan', 'deskripsi' => 'Snack dan makanan ringan', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Produk
        DB::table('produk')->insert([
            [
                'nama' => 'Espresso',
                'image' => 'products/espresso.jpg',
                'deskripsi' => 'Kopi espresso single shot',
                'harga' => 15000,
                'stok' => 100,
                'kategori_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Matcha Latte',
                'image' => 'products/matcha.jpg',
                'deskripsi' => 'Minuman matcha dengan susu',
                'harga' => 25000,
                'stok' => 50,
                'kategori_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Roti Bakar',
                'image' => 'products/roti.jpg',
                'deskripsi' => 'Roti bakar coklat keju',
                'harga' => 20000,
                'stok' => 30,
                'kategori_id' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Membership
        DB::table('membership')->insert([
            ['nama' => 'Silver', 'diskon' => 5, 'minimal_transaksi' => 100000, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Gold', 'diskon' => 10, 'minimal_transaksi' => 500000, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Platinum', 'diskon' => 15, 'minimal_transaksi' => 1000000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Pelanggan
        DB::table('pelanggan')->insert([
            [
                'nama' => 'Andi Setiawan',
                'email' => 'andi@example.com',
                'nomor_hp' => '08123456789',
                'alamat' => 'Jl. Merpati No.10',
                'membership_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'nomor_hp' => '08129876543',
                'alamat' => 'Jl. Kenanga No.5',
                'membership_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // User
        DB::table('user')->insert([
            [
                'username' => 'admin',
                'nama' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'nomor_hp' => '081122334455',
                'alamat' => 'Kantor Pusat',
                'jabatan' => 'Manager',
                'role' => 'admin',
                'image' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'username' => 'kasir1',
                'nama' => 'Kasir Satu',
                'email' => 'kasir1@example.com',
                'password' => Hash::make('password'),
                'nomor_hp' => '081155667788',
                'alamat' => 'Outlet Utama',
                'jabatan' => 'Kasir',
                'role' => 'user',
                'image' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Transaksi
        DB::table('transaksi')->insert([
            [
                'pelanggan_id' => 1,
                'user_id' => 2, // kasir1
                'tanggal_transaksi' => $now,
                'total' => 35000,
                'metode_pembayaran' => 'tunai',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Detail Transaksi
        DB::table('detail_transaksi')->insert([
            [
                'transaksi_id' => 1,
                'produk_id' => 1, // Espresso
                'jumlah' => 1,
                'harga' => 15000,
                'subtotal' => 15000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 1,
                'produk_id' => 3, // Roti Bakar
                'jumlah' => 1,
                'harga' => 20000,
                'subtotal' => 20000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}