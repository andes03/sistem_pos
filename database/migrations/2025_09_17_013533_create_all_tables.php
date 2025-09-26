<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Produk
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('image')->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2);
            $table->integer('stok')->default(0);
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->timestamps();
        });

        // Membership
        Schema::create('membership', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->decimal('diskon', 5, 2)->default(0);
            $table->decimal('minimal_transaksi', 12, 2)->default(0);
            $table->timestamps();
        });

        // Pelanggan
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('nomor_hp');
            $table->string('alamat')->nullable();
            $table->foreignId('membership_id')->nullable()->constrained('membership')->onDelete('set null');
            $table->timestamps();
        });

        // User
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('nama');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('nomor_hp');
            $table->string('alamat')->nullable();
            $table->string('jabatan')->nullable();
            $table->enum('role', ['user', 'admin'])->default('user');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Transaksi
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->constrained('pelanggan')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->dateTime('tanggal_transaksi');
            $table->decimal('total', 12, 2);
            $table->enum('metode_pembayaran', ['tunai', 'transfer', 'ewallet'])->default('tunai');
            // Kolom status telah dihapus
            $table->timestamps();
        });

        // Detail Transaksi
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
        Schema::dropIfExists('transaksi');
        Schema::dropIfExists('user');
        Schema::dropIfExists('pelanggan');
        Schema::dropIfExists('membership');
        Schema::dropIfExists('produk');
        Schema::dropIfExists('kategori');
    }
};