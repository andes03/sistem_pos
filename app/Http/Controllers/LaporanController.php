<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Membership;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon; // Tambahkan use statement untuk Carbon

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $membership_id = $request->input('membership_id');
        $metode_pembayaran = $request->input('metode_pembayaran');

        // Menggunakan Transaksi::query() di awal agar objek $query bisa di-clone untuk sum()
        $query = Transaksi::query()->with(['pelanggan', 'detailTransaksi.produk'])
            // Mengurutkan berdasarkan tanggal_transaksi terbaru
            ->orderBy('tanggal_transaksi', 'desc');

        // ==========================================================
        // PERBAIKAN: Menggunakan 'tanggal_transaksi' dan whereDate()
        // ==========================================================
        if ($from_date) {
            $query->whereDate('tanggal_transaksi', '>=', $from_date);
        }
        if ($to_date) {
            $query->whereDate('tanggal_transaksi', '<=', $to_date);
        }
        // ==========================================================

        if ($membership_id) {
            $query->whereHas('pelanggan', function ($q) use ($membership_id) {
                $q->where('membership_id', $membership_id);
            });
        }

        if ($metode_pembayaran) {
            $query->where('metode_pembayaran', $metode_pembayaran);
        }
        
        // Clone query sebelum pagination untuk menghitung total
        $queryForTotal = clone $query;
        
        $transaksis = $query->paginate(8)->withQueryString();
        
        // Menghitung total transaksi dari semua data yang difilter
        $totalTransaksi = $queryForTotal->sum('total');

        $memberships = Membership::orderBy('nama')->get();

        // Daftar metode pembayaran
        $metodePembayaran = [
            'tunai' => 'Tunai',
            'transfer' => 'Transfer',
            'ewallet' => 'E-Wallet'
        ];

        if ($request->ajax()) {
            return view('laporan.partials.table', compact('transaksis', 'totalTransaksi'));
        }

        return view('laporan.index', compact(
            'transaksis', 
            'from_date', 
            'to_date', 
            'memberships', 
            'membership_id', 
            'totalTransaksi',
            'metodePembayaran',
            'metode_pembayaran'
        ));
    }
    
    public function cetakPdf(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $membership_id = $request->input('membership_id');
        $metode_pembayaran = $request->input('metode_pembayaran');
        
        $query = Transaksi::query()->with(['pelanggan', 'detailTransaksi.produk'])
            // Mengurutkan berdasarkan tanggal_transaksi terbaru
            ->orderBy('tanggal_transaksi', 'desc');

        // ==========================================================
        // PERBAIKAN: Menggunakan 'tanggal_transaksi' dan whereDate()
        // ==========================================================
        if ($from_date && $to_date) {
            // whereDate() lebih bersih daripada whereBetween dengan string waktu
            $query->whereDate('tanggal_transaksi', '>=', $from_date)
                  ->whereDate('tanggal_transaksi', '<=', $to_date);
        } elseif ($from_date) {
             $query->whereDate('tanggal_transaksi', '>=', $from_date);
        } elseif ($to_date) {
             $query->whereDate('tanggal_transaksi', '<=', $to_date);
        }
        // ==========================================================

        if ($membership_id) {
            $query->whereHas('pelanggan', function ($q) use ($membership_id) {
                $q->where('membership_id', $membership_id);
            });
        }

        if ($metode_pembayaran) {
            $query->where('metode_pembayaran', $metode_pembayaran);
        }

        $transaksis = $query->get();
        
        $membership_name = $membership_id ? Membership::find($membership_id)->nama : 'Semua Membership';
        
        // Menentukan nama metode pembayaran untuk ditampilkan di PDF
        $metodePembayaranLabel = $metode_pembayaran ? 
            collect([
                'tunai' => 'Tunai',
                'transfer' => 'Transfer',
                'ewallet' => 'E-Wallet'
            ])->get($metode_pembayaran) : 'Semua Metode';
        
        // Menghitung total transaksi dari semua data yang difilter
        $totalTransaksi = $query->sum('total');

        // Data tanggal (from_date dan to_date) yang diambil dari input
        // akan digunakan di laporan PDF (tanggal di laporan)
        $pdf = Pdf::loadView('laporan.pdf_template', compact(
            'transaksis', 
            'from_date', 
            'to_date', 
            'membership_name', 
            'totalTransaksi',
            'metodePembayaranLabel'
        ));

        // Penamaan file PDF (tanggal di laporan pdf)
        $filename = 'laporan-transaksi-';
        if ($from_date && $to_date) {
            // Nama file mencantumkan tanggal filter dari input
            $filename .= $from_date . '_sd_' . $to_date;
        } else {
            $filename .= Carbon::now()->format('Y-m-d'); // Menggunakan Carbon karena sudah di-import
        }
        $filename .= ($membership_id ? '-(' . $membership_id . ')' : '');
        $filename .= ($metode_pembayaran ? '-' . $metode_pembayaran : '');
        $filename .= '.pdf';
        
        return $pdf->stream($filename);
    }
}