<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WelcomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes - Rute yang bisa diakses tanpa harus login
// Mengarahkan root URL ('/') ke view 'welcome'
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
// Auth Routes - Rute untuk login dan logout
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes - Rute yang memerlukan autentikasi
// Semua rute di dalam grup ini akan memeriksa apakah pengguna sudah login
Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Admin Only Routes
    Route::middleware('check.role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    
    // Routes accessible by both admin and user
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak-pdf', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak-pdf');
    Route::get('/transaksi/produk/filter', [TransaksiController::class, 'getFilteredProduk'])->name('transaksi.produk.filter');

    Route::resource('transaksi', TransaksiController::class);
    Route::resource('transaksi', TransaksiController::class)->only(['index', 'show', 'create', 'store']);

    Route::resource('membership', MembershipController::class)->except(['create', 'edit']);
    Route::resource('transaksi', TransaksiController::class)->only(['index', 'show', 'create', 'store']);
    Route::post('/get-pelanggan-info', [TransaksiController::class, 'getPelangganInfo'])->name('pelanggan.info');
    Route::resource('kategori', KategoriController::class)->except(['create', 'edit']);
    Route::get('/kategori/{kategori}/detail', [KategoriController::class, 'show'])->name('kategori.show');

    Route::resource('produk', ProdukController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::get('pelanggan/{pelanggan}', [PelangganController::class, 'show'])->name('pelanggan.show');
    Route::get('transaksi/{transaksi}/print', [TransaksiController::class, 'print'])->name('transaksi.print');
});

Route::get('/filter-produk', [WelcomeController::class, 'filterProducts'])->name('produk.filter');
Route::post('/check-membership-ajax', [WelcomeController::class, 'ajaxCheckMembership'])->name('check.membership.ajax');