<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Membership;
use App\Models\Transaksi;

class WelcomeController extends Controller
{
    /**
     * Tampilkan halaman welcome awal.
     */
    public function index()
    {
        $kategori = Kategori::all();
        $memberships = Membership::all();
        $produk = Produk::paginate(100);

        return view('welcome', compact('produk', 'kategori', 'memberships'));
    }

    /**
     * [AJAX] Filter produk dan kembalikan SELURUH VIEW welcome.
     * JavaScript akan mengekstrak grid produk dari response ini.
     */
    public function filterProducts(Request $request)
    {
        $query = Produk::query();
        
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->input('search') . '%');
        }
        
        if ($request->filled('kategori') && $request->input('kategori') != 'all') {
            $query->where('kategori_id', $request->input('kategori'));
        }
        
        $produk = $query->paginate(100)->withQueryString();

        // Kebutuhan data lain untuk view welcome
        $kategori = Kategori::all();
        $memberships = Membership::all();

        // Mengembalikan seluruh view dengan data yang sudah difilter
        return view('welcome', compact('produk', 'kategori', 'memberships'));
    }

    /**
     * [AJAX] Periksa status membership dan kembalikan data JSON. (Tidak ada perubahan)
     */
    public function ajaxCheckMembership(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $pelanggan = Pelanggan::with('membership')->where('email', $request->email)->first();

        if ($pelanggan) {
            $totalTransaksi = Transaksi::where('pelanggan_id', $pelanggan->id)->sum('total');
            return response()->json([
                'success' => true,
                'pelanggan' => $pelanggan,
                'totalTransaksi' => $totalTransaksi,
                'formattedTotal' => 'Rp' . number_format($totalTransaksi, 0, ',', '.'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Maaf, email tersebut tidak terdaftar sebagai member.',
        ]);
    }
}