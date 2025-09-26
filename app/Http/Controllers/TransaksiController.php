<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Membership;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    /**
     * Displays a paginated list of transactions with search and filter functionality.
     */
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'user'])->latest('tanggal_transaksi');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', fn ($subq) => $subq->where('nama', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn ($subq) => $subq->where('nama', 'like', "%{$search}%"));
            });
        }

        if ($paymentMethod = $request->input('metode_pembayaran')) {
            $query->where('metode_pembayaran', $paymentMethod);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('tanggal_transaksi', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('tanggal_transaksi', '<=', $dateTo);
        }

        $transaksi = $query->paginate(8);
        $paymentMethods = ['tunai', 'transfer', 'ewallet'];

        if ($request->ajax()) {
            return view('transaksi.partials.table', compact('transaksi'))->render();
        }

        return view('transaksi.index', compact('transaksi', 'search', 'paymentMethods', 'paymentMethod', 'dateFrom', 'dateTo'));
    }


    /**
     * Displays a single transaction with its details.
     */
    public function show(Transaksi $transaksi)
    {
        $transaksi->load('pelanggan', 'user', 'detailTransaksi.produk');
        return view('transaksi.show', compact('transaksi'));
    }

    public function print(Transaksi $transaksi)
    {
        $transaksi->load('pelanggan.membership', 'user', 'detailTransaksi.produk');
        return view('transaksi.print', compact('transaksi'));
    }

    /**
     * Shows the form for creating a new transaction.
     */
    public function create()
    {
        $pelanggan = Pelanggan::with('membership')->get();
        $produk = Produk::with('kategori')->where('stok', '>', 0)->get();
        $kategori = Kategori::all();

        return view('transaksi.create', compact('pelanggan', 'produk', 'kategori'));
    }

    /**
     * Stores a newly created transaction in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'metode_pembayaran' => 'required|in:tunai,transfer,ewallet',
            'produk' => 'required|json', // Use 'json' rule for better validation
        ]);

        DB::beginTransaction();

        try {
            $pelanggan = Pelanggan::with('membership')->findOrFail($request->pelanggan_id);
            $produkKeranjang = json_decode($request->produk, true);

            if (empty($produkKeranjang)) {
                throw new \Exception("Keranjang belanja tidak boleh kosong.");
            }

            $subtotalKeseluruhan = 0;
            $detailItems = [];
            $produkIds = array_column($produkKeranjang, 'id');
            $produkDb = Produk::whereIn('id', $produkIds)->get()->keyBy('id');

            foreach ($produkKeranjang as $item) {
                $produk = $produkDb->get($item['id']);
                $jumlah = $item['jumlah'];

                if (!$produk || $produk->stok < $jumlah) {
                    throw new \Exception("Stok produk '{$produk->nama}' tidak mencukupi. Stok tersedia: {$produk->stok}");
                }

                $subtotal = $produk->harga * $jumlah;
                $subtotalKeseluruhan += $subtotal;

                $detailItems[] = [
                    'produk' => $produk,
                    'jumlah' => $jumlah,
                    'harga' => $produk->harga,
                    'subtotal' => $subtotal,
                ];
            }

            $diskon = 0;
            $persenDiskon = 0;
            $membershipInfo = 'Tidak ada';

            if ($pelanggan->membership) {
                $membershipInfo = $pelanggan->membership->nama;
                $persenDiskon = $pelanggan->membership->diskon;
                $diskon = ($persenDiskon / 100) * $subtotalKeseluruhan;
            }

            $totalSetelahDiskon = round($subtotalKeseluruhan - $diskon, 2);

            $transaksi = Transaksi::create([
                'pelanggan_id' => $request->pelanggan_id,
                'user_id' => Auth::id(),
                'tanggal_transaksi' => now(),
                'total' => $totalSetelahDiskon,
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            foreach ($detailItems as $detail) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $detail['produk']->id,
                    'jumlah' => $detail['jumlah'],
                    'harga' => $detail['harga'],
                    'subtotal' => $detail['subtotal'],
                ]);
                $detail['produk']->decrement('stok', $detail['jumlah']);
            }

            DB::commit();

            $successMessage = 'Transaksi berhasil dibuat!';
            if ($diskon > 0) {
                $successMessage .= " Pelanggan mendapat diskon {$persenDiskon}% sebesar Rp " . number_format($diskon, 0, ',', '.');
            }

            return redirect()->route('transaksi.show', $transaksi->id)
                ->with('success', $successMessage)
                ->with('info', [
                    'subtotal' => $subtotalKeseluruhan,
                    'diskon' => $diskon,
                    'persen_diskon' => $persenDiskon,
                    'total' => $totalSetelahDiskon,
                    'membership' => $membershipInfo,
                    'hemat' => $diskon > 0,
                ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }


    /**
     * Helper method to get product data via AJAX.
     */
    public function getProduk($id)
    {
        return Produk::with('kategori')->findOrFail($id);
    }

    /**
     * NEW: Helper method to get products by category and search term via AJAX.
     */
    public function getFilteredProduk(Request $request)
    {
        $query = Produk::with('kategori')->where('stok', '>', 0);
        
        if ($request->has('kategori_id') && $request->kategori_id !== 'all') {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->has('search') && $request->search !== null) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    /**
     * Helper method to get customer data via AJAX.
     */
    public function getPelanggan($id)
    {
        return Pelanggan::with('membership')->findOrFail($id);
    }

    /**
     * Helper method to calculate total with discount (for display purposes).
     */
    public function calculateDiscount($subtotal, $membership)
    {
        $diskon = 0;
        if ($membership) {
            $diskon = ($membership->diskon / 100) * $subtotal;
        }
        $total = $subtotal - $diskon;

        return [
            'subtotal' => $subtotal,
            'diskon' => $diskon,
            'persen_diskon' => $membership ? $membership->diskon : 0,
            'total' => $total,
        ];
    }
}
