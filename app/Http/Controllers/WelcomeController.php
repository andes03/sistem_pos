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
     * [AJAX] Periksa status membership dan kembalikan data JSON.
     */
    public function ajaxCheckMembership(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $pelanggan = Pelanggan::with('membership')->where('email', $request->email)->first();

        if ($pelanggan) {
            $totalTransaksi = Transaksi::where('pelanggan_id', $pelanggan->id)->sum('total');
            
            // Cek apakah pelanggan belum punya membership
            if (!$pelanggan->membership || $pelanggan->membership_id === null) {
                // Ambil membership dengan minimal_transaksi terendah
                $membershipTerendah = Membership::orderBy('minimal_transaksi', 'asc')->first();
                
                if ($membershipTerendah) {
                    $selisihTransaksi = $membershipTerendah->minimal_transaksi - $totalTransaksi;
                    $diskonFormatted = rtrim(rtrim(number_format($membershipTerendah->diskon, 2), '0'), '.');
                    
                    return response()->json([
                        'success' => true,
                        'has_membership' => false,
                        'pelanggan' => [
                            'id' => $pelanggan->id,
                            'nama' => $pelanggan->nama,
                            'email' => $pelanggan->email,
                            'membership' => null,
                        ],
                        'totalTransaksi' => $totalTransaksi,
                        'formattedTotal' => 'Rp' . number_format($totalTransaksi, 0, ',', '.'),
                        'target_membership' => $membershipTerendah->nama,
                        'target_diskon' => $diskonFormatted,
                        'selisih_transaksi' => $selisihTransaksi,
                        'formatted_selisih' => 'Rp' . number_format($selisihTransaksi, 0, ',', '.'),
                        'minimal_transaksi' => $membershipTerendah->minimal_transaksi,
                        'formatted_minimal' => 'Rp' . number_format($membershipTerendah->minimal_transaksi, 0, ',', '.'),
                    ]);
                }
            }
            
            // Pelanggan sudah punya membership
            return response()->json([
                'success' => true,
                'has_membership' => true,
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