<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Membership;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Atur locale ke Bahasa Indonesia untuk format tanggal dan bulan
        Carbon::setLocale('id');

        // 1. Ringkasan Bisnis
        $totalPendapatan = Transaksi::sum('total');
        $totalTransaksi = Transaksi::count();
        $totalPelanggan = Pelanggan::count();
        $totalProduk = Produk::count();

        // 2. Data untuk Diagram Batang Pendapatan (12 bulan penuh)
        // Ambil daftar tahun yang tersedia dari database
        $availableYears = Transaksi::select(DB::raw('YEAR(tanggal_transaksi) as year'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // Tentukan tahun yang dipilih, default ke tahun saat ini
        $selectedYear = $request->input('tahun', Carbon::now()->year);

        $bulanLabels = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulanLabels[] = Carbon::create(null, $i)->isoFormat('MMMM');
        }
        $pendapatanPerBulan = Transaksi::select(
            DB::raw('MONTH(tanggal_transaksi) as bulan'),
            DB::raw('SUM(total) as total')
        )
        ->whereYear('tanggal_transaksi', $selectedYear)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();
        $pendapatanValues = array_fill(0, 12, 0);
        foreach ($pendapatanPerBulan as $data) {
            $pendapatanValues[$data->bulan - 1] = $data->total;
        }
        $pendapatanLabels = $bulanLabels;

        // 3. Data untuk Produk Terlaris (5 produk teratas)
        $produkTerlarisQuery = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->select('produk.nama', DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'))
            ->groupBy('produk.nama')
            ->orderByDesc('total_terjual')
            ->limit(5);

        // Tambahkan filter jika ada kategori_id dalam request
        if ($request->has('kategori_id') && $request->input('kategori_id') != '') {
            $produkTerlarisQuery->where('produk.kategori_id', $request->input('kategori_id'));
        }

        $produkTerlaris = $produkTerlarisQuery->get();
            
        $produkTerlarisLabels = $produkTerlaris->pluck('nama');
        $produkTerlarisValues = $produkTerlaris->pluck('total_terjual');

        // 4. Data untuk Diagram Lingkaran Membership
        $membershipData = Pelanggan::select(
            DB::raw('CASE WHEN membership_id IS NULL THEN 0 ELSE membership_id END as membership_id'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('membership_id')
        ->get();

        $membershipLabels = $membershipData->map(function($item) {
            if ($item->membership_id == 0) {
                return 'Tidak ada Membership';
            }
            $membership = Membership::find($item->membership_id);
            return $membership ? $membership->nama : 'Tidak diketahui';
        });
        $membershipValues = $membershipData->pluck('total');

        // 5. Data untuk Distribusi Produk per Kategori
        $kategoriData = Kategori::withCount('produk')->get();
        $kategoriLabels = $kategoriData->pluck('nama');
        $kategoriValues = $kategoriData->pluck('produk_count');

        // Tambahkan daftar kategori untuk filter dropdown
        $kategoriList = Kategori::all();

        // 6. Data untuk Chart Transaksi per Metode Pembayaran
        // Tentukan bulan yang dipilih, default ke bulan saat ini
        $selectedMonth = $request->input('bulan', Carbon::now()->month);
        $selectedYear = $request->input('tahun', Carbon::now()->year);
        
        // Ambil data transaksi dan total uang per metode pembayaran untuk bulan yang dipilih
        $metodePembayaranData = Transaksi::select(
            'metode_pembayaran',
            DB::raw('COUNT(*) as jumlah_transaksi'),
            DB::raw('SUM(total) as total_uang')
        )
        ->whereYear('tanggal_transaksi', $selectedYear)
        ->whereMonth('tanggal_transaksi', $selectedMonth)
        ->groupBy('metode_pembayaran')
        ->get();

        // Prepare data untuk chart
        $metodePembayaranLabels = ['Tunai', 'Transfer', 'E-Wallet'];
        $metodePembayaranJumlahTransaksi = [0, 0, 0];
        $metodePembayaranTotalUang = [0, 0, 0];

        foreach ($metodePembayaranData as $data) {
            $index = 0;
            switch ($data->metode_pembayaran) {
                case 'tunai':
                    $index = 0;
                    break;
                case 'transfer':
                    $index = 1;
                    break;
                case 'ewallet':
                    $index = 2;
                    break;
            }
            $metodePembayaranJumlahTransaksi[$index] = $data->jumlah_transaksi;
            $metodePembayaranTotalUang[$index] = $data->total_uang;
        }

        // Generate bulan options untuk filter
        $bulanOptions = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulanOptions[] = [
                'value' => $i,
                'label' => Carbon::create(null, $i)->isoFormat('MMMM'),
            ];
        }

        // 7. Produk Stok Menipis (contoh: stok di bawah 5)
        $stokMenipis = Produk::with('kategori')->where('stok', '<', 5)->get();

        // 8. Pelanggan Terbaru (5 pelanggan terakhir)
        $pelangganTerbaru = Pelanggan::orderBy('created_at', 'desc')->limit(5)->get();

        // 9. Produk Terbaru (5 produk terakhir)
        $produkTerbaru = Produk::orderBy('created_at', 'desc')->limit(5)->get();

        // 10. Transaksi Terbaru (5 transaksi terakhir)
        $transaksiTerbaru = Transaksi::with('pelanggan')->orderBy('tanggal_transaksi', 'desc')->limit(5)->get();

        return view('dashboard.dashboard', compact(
            'totalPendapatan',
            'totalTransaksi',
            'totalPelanggan',
            'totalProduk',
            'pendapatanLabels',
            'pendapatanValues',
            'produkTerlarisLabels',
            'produkTerlarisValues',
            'membershipLabels',
            'membershipValues',
            'kategoriLabels',
            'kategoriValues',
            'metodePembayaranLabels',
            'metodePembayaranJumlahTransaksi',
            'metodePembayaranTotalUang',
            'bulanOptions',
            'selectedMonth',
            'stokMenipis',
            'pelangganTerbaru',
            'produkTerbaru',
            'kategoriList',
            'transaksiTerbaru',
            'availableYears',
            'selectedYear'
        ));
    }

    // Method AJAX untuk filter Pendapatan per Tahun
    public function getPendapatan(Request $request)
    {
        Carbon::setLocale('id');
        
        $tahun = $request->input('tahun', Carbon::now()->year);

        $bulanLabels = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulanLabels[] = Carbon::create(null, $i)->isoFormat('MMMM');
        }

        $pendapatanPerBulan = Transaksi::select(
            DB::raw('MONTH(tanggal_transaksi) as bulan'),
            DB::raw('SUM(total) as total')
        )
        ->whereYear('tanggal_transaksi', $tahun)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

        $pendapatanValues = array_fill(0, 12, 0);
        foreach ($pendapatanPerBulan as $data) {
            $pendapatanValues[$data->bulan - 1] = $data->total;
        }

        return response()->json([
            'labels' => $bulanLabels,
            'values' => $pendapatanValues
        ]);
    }

    // Method AJAX untuk filter Produk Terlaris per Kategori
    public function getProdukTerlaris(Request $request)
    {
        $produkTerlarisQuery = DB::table('detail_transaksi')
            ->join('produk', 'detail_transaksi.produk_id', '=', 'produk.id')
            ->select('produk.nama', DB::raw('SUM(detail_transaksi.jumlah) as total_terjual'))
            ->groupBy('produk.nama')
            ->orderByDesc('total_terjual')
            ->limit(5);

        if ($request->has('kategori_id') && $request->input('kategori_id') != '') {
            $produkTerlarisQuery->where('produk.kategori_id', $request->input('kategori_id'));
        }

        $produkTerlaris = $produkTerlarisQuery->get();
        
        return response()->json([
            'labels' => $produkTerlaris->pluck('nama'),
            'values' => $produkTerlaris->pluck('total_terjual')
        ]);
    }

    // Method AJAX untuk filter Metode Pembayaran per Bulan
    public function getMetodePembayaran(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        $metodePembayaranData = Transaksi::select(
            'metode_pembayaran',
            DB::raw('COUNT(*) as jumlah_transaksi'),
            DB::raw('SUM(total) as total_uang')
        )
        ->whereYear('tanggal_transaksi', $tahun)
        ->whereMonth('tanggal_transaksi', $bulan)
        ->groupBy('metode_pembayaran')
        ->get();

        $metodePembayaranLabels = ['Tunai', 'Transfer', 'E-Wallet'];
        $metodePembayaranJumlahTransaksi = [0, 0, 0];
        $metodePembayaranTotalUang = [0, 0, 0];

        foreach ($metodePembayaranData as $data) {
            $index = 0;
            switch ($data->metode_pembayaran) {
                case 'tunai':
                    $index = 0;
                    break;
                case 'transfer':
                    $index = 1;
                    break;
                case 'ewallet':
                    $index = 2;
                    break;
            }
            $metodePembayaranJumlahTransaksi[$index] = $data->jumlah_transaksi;
            $metodePembayaranTotalUang[$index] = $data->total_uang;
        }

        return response()->json([
            'labels' => $metodePembayaranLabels,
            'jumlahTransaksi' => $metodePembayaranJumlahTransaksi,
            'totalUang' => $metodePembayaranTotalUang
        ]);
    }
}