<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Membership;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $membership_id = $request->input('membership_id');
        $metode_pembayaran = $request->input('metode_pembayaran');

        $query = Transaksi::with(['pelanggan', 'detailTransaksi.produk'])
            ->orderBy('created_at', 'desc');

        if ($from_date) {
            $query->whereDate('created_at', '>=', $from_date);
        }
        if ($to_date) {
            $query->whereDate('created_at', '<=', $to_date);
        }

        if ($membership_id) {
            $query->whereHas('pelanggan', function ($q) use ($membership_id) {
                $q->where('membership_id', $membership_id);
            });
        }

        if ($metode_pembayaran) {
            $query->where('metode_pembayaran', $metode_pembayaran);
        }
        
        $transaksis = $query->paginate(8)->withQueryString();
        
        // Menghitung total transaksi dari semua data yang difilter
        $totalTransaksi = $query->sum('total');

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
        
        $query = Transaksi::with(['pelanggan', 'detailTransaksi.produk'])
            ->orderBy('created_at', 'desc');

        if ($from_date && $to_date) {
            $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }

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

        $pdf = Pdf::loadView('laporan.pdf_template', compact(
            'transaksis', 
            'from_date', 
            'to_date', 
            'membership_name', 
            'totalTransaksi',
            'metodePembayaranLabel'
        ));

        $filename = 'laporan-transaksi-';
        if ($from_date && $to_date) {
            $filename .= $from_date . '_sd_' . $to_date;
        } else {
            $filename .= now()->format('Y-m-d');
        }
        $filename .= ($membership_id ? '-(' . $membership_id . ')' : '');
        $filename .= ($metode_pembayaran ? '-' . $metode_pembayaran : '');
        $filename .= '.pdf';
        
        return $pdf->stream($filename);
    }
}