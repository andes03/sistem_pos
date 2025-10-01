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
            ['nama' => 'Dessert', 'deskripsi' => 'Makanan penutup dan kue', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Produk (15 produk)
        DB::table('produk')->insert([
            // Kategori Kopi
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
                'nama' => 'Americano',
                'image' => 'products/americano.jpg',
                'deskripsi' => 'Espresso dengan air panas',
                'harga' => 18000,
                'stok' => 100,
                'kategori_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Cappuccino',
                'image' => 'products/cappuccino.jpg',
                'deskripsi' => 'Espresso dengan susu foam',
                'harga' => 22000,
                'stok' => 80,
                'kategori_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Latte',
                'image' => 'products/latte.jpg',
                'deskripsi' => 'Espresso dengan susu steamed',
                'harga' => 23000,
                'stok' => 80,
                'kategori_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Mocha',
                'image' => 'products/mocha.jpg',
                'deskripsi' => 'Kopi dengan coklat dan susu',
                'harga' => 26000,
                'stok' => 70,
                'kategori_id' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Kategori Non-Kopi
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
                'nama' => 'Chocolate',
                'image' => 'products/chocolate.jpg',
                'deskripsi' => 'Minuman coklat hangat',
                'harga' => 20000,
                'stok' => 60,
                'kategori_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Thai Tea',
                'image' => 'products/thaitea.jpg',
                'deskripsi' => 'Teh susu khas Thailand',
                'harga' => 18000,
                'stok' => 55,
                'kategori_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Lemon Tea',
                'image' => 'products/lemontea.jpg',
                'deskripsi' => 'Teh lemon segar',
                'harga' => 15000,
                'stok' => 70,
                'kategori_id' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Kategori Makanan
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
            [
                'nama' => 'French Fries',
                'image' => 'products/fries.jpg',
                'deskripsi' => 'Kentang goreng crispy',
                'harga' => 18000,
                'stok' => 40,
                'kategori_id' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Sandwich',
                'image' => 'products/sandwich.jpg',
                'deskripsi' => 'Sandwich ayam dan sayuran',
                'harga' => 25000,
                'stok' => 25,
                'kategori_id' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            // Kategori Dessert
            [
                'nama' => 'Brownies',
                'image' => 'products/brownies.jpg',
                'deskripsi' => 'Brownies coklat lembut',
                'harga' => 22000,
                'stok' => 35,
                'kategori_id' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Cheesecake',
                'image' => 'products/cheesecake.jpg',
                'deskripsi' => 'Cheesecake creamy',
                'harga' => 28000,
                'stok' => 20,
                'kategori_id' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Tiramisu',
                'image' => 'products/tiramisu.jpg',
                'deskripsi' => 'Tiramisu khas Italia',
                'harga' => 30000,
                'stok' => 15,
                'kategori_id' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Membership (4 level)
        DB::table('membership')->insert([
            ['nama' => 'Bronze', 'diskon' => 5, 'minimal_transaksi' => 100000, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Silver', 'diskon' => 10, 'minimal_transaksi' => 500000, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Gold', 'diskon' => 15, 'minimal_transaksi' => 1000000, 'created_at' => $now, 'updated_at' => $now],
            ['nama' => 'Platinum', 'diskon' => 20, 'minimal_transaksi' => 5000000, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Pelanggan (10 pelanggan, termasuk yang tanpa membership)
        DB::table('pelanggan')->insert([
            [
                'nama' => 'Andi Setiawan',
                'email' => 'andi@example.com',
                'nomor_hp' => '08123456789',
                'alamat' => 'Jl. Merpati No.10, Jakarta Selatan',
                'membership_id' => 1, // Bronze
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'nomor_hp' => '08129876543',
                'alamat' => 'Jl. Kenanga No.5, Jakarta Pusat',
                'membership_id' => 2, // Silver
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Citra Dewi',
                'email' => 'citra@example.com',
                'nomor_hp' => '08134567890',
                'alamat' => 'Jl. Melati No.15, Jakarta Barat',
                'membership_id' => 3, // Gold
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Doni Prasetyo',
                'email' => 'doni@example.com',
                'nomor_hp' => '08145678901',
                'alamat' => 'Jl. Anggrek No.20, Jakarta Timur',
                'membership_id' => 4, // Platinum
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Eka Putri',
                'email' => 'eka@example.com',
                'nomor_hp' => '08156789012',
                'alamat' => 'Jl. Mawar No.25, Jakarta Utara',
                'membership_id' => 1, // Bronze
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Fajar Nugroho',
                'email' => 'fajar@example.com',
                'nomor_hp' => '08167890123',
                'alamat' => 'Jl. Dahlia No.30, Tangerang',
                'membership_id' => 2, // Silver
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Gita Sari',
                'email' => 'gita@example.com',
                'nomor_hp' => '08178901234',
                'alamat' => 'Jl. Tulip No.35, Bekasi',
                'membership_id' => null, // Tidak punya membership
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Hadi Wijaya',
                'email' => 'hadi@example.com',
                'nomor_hp' => '08189012345',
                'alamat' => 'Jl. Sakura No.40, Depok',
                'membership_id' => null, // Tidak punya membership
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Intan Permata',
                'email' => 'intan@example.com',
                'nomor_hp' => '08190123456',
                'alamat' => 'Jl. Lily No.45, Bogor',
                'membership_id' => null, // Tidak punya membership
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'nama' => 'Joko Susilo',
                'email' => 'joko@example.com',
                'nomor_hp' => '08101234567',
                'alamat' => 'Jl. Orchid No.50, Cikarang',
                'membership_id' => 1, // Bronze
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

        // Transaksi tanpa diskon (total = subtotal)
        // Pelanggan 1 (Andi) - Bronze - Total transaksi: 150.000
        DB::table('transaksi')->insert([
            'pelanggan_id' => 1,
            'user_id' => 2,
            'tanggal_transaksi' => $now->copy()->subDays(10),
            'total' => 150000,
            'metode_pembayaran' => 'tunai',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('detail_transaksi')->insert([
            [
                'transaksi_id' => 1,
                'produk_id' => 1, // Espresso
                'jumlah' => 2,
                'harga' => 15000,
                'subtotal' => 30000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 1,
                'produk_id' => 10, // Roti Bakar
                'jumlah' => 3,
                'harga' => 20000,
                'subtotal' => 60000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 1,
                'produk_id' => 3, // Cappuccino
                'jumlah' => 2,
                'harga' => 22000,
                'subtotal' => 44000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 1,
                'produk_id' => 9, // Lemon Tea
                'jumlah' => 1,
                'harga' => 15000,
                'subtotal' => 15000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Pelanggan 2 (Budi) - Silver - Total transaksi: 600.000
        DB::table('transaksi')->insert([
            'pelanggan_id' => 2,
            'user_id' => 2,
            'tanggal_transaksi' => $now->copy()->subDays(8),
            'total' => 600000,
            'metode_pembayaran' => 'transfer',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('detail_transaksi')->insert([
            [
                'transaksi_id' => 2,
                'produk_id' => 4, // Latte
                'jumlah' => 5,
                'harga' => 23000,
                'subtotal' => 115000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 2,
                'produk_id' => 6, // Matcha Latte
                'jumlah' => 4,
                'harga' => 25000,
                'subtotal' => 100000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 2,
                'produk_id' => 14, // Cheesecake
                'jumlah' => 5,
                'harga' => 28000,
                'subtotal' => 140000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 2,
                'produk_id' => 15, // Tiramisu
                'jumlah' => 8,
                'harga' => 30000,
                'subtotal' => 240000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Pelanggan 3 (Citra) - Gold - Total transaksi: 1.200.000
        DB::table('transaksi')->insert([
            'pelanggan_id' => 3,
            'user_id' => 1,
            'tanggal_transaksi' => $now->copy()->subDays(5),
            'total' => 1200000,
            'metode_pembayaran' => 'ewallet',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('detail_transaksi')->insert([
            [
                'transaksi_id' => 3,
                'produk_id' => 5, // Mocha
                'jumlah' => 10,
                'harga' => 26000,
                'subtotal' => 260000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 3,
                'produk_id' => 12, // Sandwich
                'jumlah' => 12,
                'harga' => 25000,
                'subtotal' => 300000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 3,
                'produk_id' => 13, // Brownies
                'jumlah' => 10,
                'harga' => 22000,
                'subtotal' => 220000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 3,
                'produk_id' => 10, // Roti Bakar
                'jumlah' => 10,
                'harga' => 20000,
                'subtotal' => 200000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 3,
                'produk_id' => 11, // French Fries
                'jumlah' => 12,
                'harga' => 18000,
                'subtotal' => 216000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Pelanggan 4 (Doni) - Platinum - Total transaksi: 5.500.000
        DB::table('transaksi')->insert([
            'pelanggan_id' => 4,
            'user_id' => 2,
            'tanggal_transaksi' => $now->copy()->subDays(3),
            'total' => 5500000,
            'metode_pembayaran' => 'transfer',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('detail_transaksi')->insert([
            [
                'transaksi_id' => 4,
                'produk_id' => 15, // Tiramisu
                'jumlah' => 50,
                'harga' => 30000,
                'subtotal' => 1500000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 4,
                'produk_id' => 14, // Cheesecake
                'jumlah' => 50,
                'harga' => 28000,
                'subtotal' => 1400000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 4,
                'produk_id' => 5, // Mocha
                'jumlah' => 40,
                'harga' => 26000,
                'subtotal' => 1040000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 4,
                'produk_id' => 12, // Sandwich
                'jumlah' => 30,
                'harga' => 25000,
                'subtotal' => 750000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 4,
                'produk_id' => 4, // Latte
                'jumlah' => 35,
                'harga' => 23000,
                'subtotal' => 805000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Pelanggan 5 (Eka) - Bronze - Total transaksi: 120.000
        DB::table('transaksi')->insert([
            'pelanggan_id' => 5,
            'user_id' => 2,
            'tanggal_transaksi' => $now->copy()->subDays(7),
            'total' => 120000,
            'metode_pembayaran' => 'ewallet',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('detail_transaksi')->insert([
            [
                'transaksi_id' => 5,
                'produk_id' => 2, // Americano
                'jumlah' => 3,
                'harga' => 18000,
                'subtotal' => 54000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 5,
                'produk_id' => 3, // Cappuccino
                'jumlah' => 3,
                'harga' => 22000,
                'subtotal' => 66000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Pelanggan 6 (Fajar) - Silver - Total transaksi: 550.000
        DB::table('transaksi')->insert([
            'pelanggan_id' => 6,
            'user_id' => 1,
            'tanggal_transaksi' => $now->copy()->subDays(6),
            'total' => 550000,
            'metode_pembayaran' => 'transfer',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('detail_transaksi')->insert([
            [
                'transaksi_id' => 6,
                'produk_id' => 6, // Matcha Latte
                'jumlah' => 10,
                'harga' => 25000,
                'subtotal' => 250000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 6,
                'produk_id' => 12, // Sandwich
                'jumlah' => 12,
                'harga' => 25000,
                'subtotal' => 300000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        // Pelanggan 7 (Gita) - Tidak punya membership - Total: 50.000
        DB::table('transaksi')->insert([
            'pelanggan_id' => 7,
            'user_id' => 2,
            'tanggal_transaksi' => $now->copy()->subDays(1),
            'total' => 50000,
            'metode_pembayaran' => 'tunai',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('detail_transaksi')->insert([
            [
                'transaksi_id' => 7,
                'produk_id' => 2, // Americano
                'jumlah' => 1,
                'harga' => 18000,
                'subtotal' => 18000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 7,
                'produk_id' => 9, // Lemon Tea
                'jumlah' => 1,
                'harga' => 15000,
                'subtotal' => 15000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'transaksi_id' => 7,
                'produk_id' => 10, // Roti Bakar
                'jumlah' => 1,
                'harga' => 20000,
                'subtotal' => 20000,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}