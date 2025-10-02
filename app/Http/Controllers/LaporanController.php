<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Membership;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth; // Penting: Untuk mengambil data user yang login

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $membership_id = $request->input('membership_id');
        $metode_pembayaran = $request->input('metode_pembayaran');

        $query = Transaksi::query()->with(['pelanggan', 'detailTransaksi.produk'])
            ->orderBy('tanggal_transaksi', 'desc');

        if ($from_date) {
            $query->whereDate('tanggal_transaksi', '>=', $from_date);
        }
        if ($to_date) {
            $query->whereDate('tanggal_transaksi', '<=', $to_date);
        }

        if ($membership_id) {
            $query->whereHas('pelanggan', function ($q) use ($membership_id) {
                $q->where('membership_id', $membership_id);
            });
        }

        if ($metode_pembayaran) {
            $query->where('metode_pembayaran', $metode_pembayaran);
        }
        
        $queryForTotal = clone $query;
        
        $transaksis = $query->paginate(8)->withQueryString();
        
        $totalTransaksi = $queryForTotal->sum('total');

        $memberships = Membership::orderBy('nama')->get();

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
            ->orderBy('tanggal_transaksi', 'desc');

        if ($from_date && $to_date) {
            $query->whereDate('tanggal_transaksi', '>=', $from_date)
                  ->whereDate('tanggal_transaksi', '<=', $to_date);
        } elseif ($from_date) {
             $query->whereDate('tanggal_transaksi', '>=', $from_date);
        } elseif ($to_date) {
             $query->whereDate('tanggal_transaksi', '<=', $to_date);
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
        
        $metodePembayaranLabel = $metode_pembayaran ? 
            collect([
                'tunai' => 'Tunai',
                'transfer' => 'Transfer',
                'ewallet' => 'E-Wallet'
            ])->get($metode_pembayaran) : 'Semua Metode';
        
        $totalTransaksi = $query->sum('total');

        // PENGAMBILAN NAMA USER SESUAI REFERENSI: Auth::user()->nama
        $user_name = Auth::check() ? Auth::user()->nama : 'Sistem'; 
        
        $pdf = Pdf::loadView('laporan.pdf_template', compact(
            'transaksis', 
            'from_date', 
            'to_date', 
            'membership_name', 
            'totalTransaksi',
            'metodePembayaranLabel',
            'user_name' // Variabel diteruskan ke view
        ));

        // Penamaan file PDF
        $filename = 'laporan-transaksi-';
        if ($from_date && $to_date) {
            $filename .= $from_date . '_sd_' . $to_date;
        } else {
            $filename .= Carbon::now()->format('Y-m-d');
        }
        $filename .= ($membership_id ? '-(' . $membership_id . ')' : '');
        $filename .= ($metode_pembayaran ? '-' . $metode_pembayaran : '');
        $filename .= '.pdf';
        
        return $pdf->stream($filename);
    }
}