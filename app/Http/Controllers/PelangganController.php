<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Membership;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class PelangganController extends Controller
{
    /**
     * Menampilkan daftar pelanggan dengan fitur pencarian dan paginasi, serta filter membership.
     * Auto-upgrade membership berdasarkan total transaksi.
     */
    public function index(Request $request)
    {
        // Auto-upgrade membership untuk semua pelanggan
        $this->autoUpgradeMemberships();

        $search = $request->input('search');
        $membershipFilter = $request->input('membership');
        $memberships = Membership::orderBy('nama')->get();
        $query = Pelanggan::query()->with('membership');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }
        
        if ($membershipFilter) {
            $query->where('membership_id', $membershipFilter);
        }

        $pelanggan = $query->orderBy('created_at', 'desc')->paginate(8);

        if ($request->ajax()) {
            return view('pelanggan.partials.table', compact('pelanggan'))->render();
        }

        return view('pelanggan.index', compact('pelanggan', 'search', 'memberships', 'membershipFilter'));
    }

    /**
     * Auto-upgrade/downgrade membership berdasarkan total transaksi pelanggan
     */
    private function autoUpgradeMemberships()
    {
        // Ambil semua pelanggan dengan total transaksi mereka
        $pelangganList = Pelanggan::with('transaksi')->get();

        // Ambil semua membership diurutkan dari minimal_transaksi tertinggi ke terendah
        $memberships = Membership::orderBy('minimal_transaksi', 'desc')->get();

        foreach ($pelangganList as $pelanggan) {
            // Hitung total transaksi pelanggan
            $totalTransaksi = $pelanggan->transaksi->sum('total');

            // Cari membership yang sesuai berdasarkan total transaksi
            $membershipYangSesuai = null;
            foreach ($memberships as $membership) {
                if ($totalTransaksi >= $membership->minimal_transaksi) {
                    $membershipYangSesuai = $membership;
                    break;
                }
            }

            // Update membership (bisa naik, turun, atau null) jika berbeda dengan yang sekarang
            $membershipIdBaru = $membershipYangSesuai ? $membershipYangSesuai->id : null;
            
            if ($pelanggan->membership_id !== $membershipIdBaru) {
                $pelanggan->update(['membership_id' => $membershipIdBaru]);
            }
        }
    }

    /**
     * Menyimpan data pelanggan baru ke database.
     * Membership akan otomatis null karena belum ada transaksi.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:pelanggan,email',
                'nomor_hp' => [
                    'required',
                    'string',
                    'regex:/^[0-9]{10,15}$/',
                    'unique:pelanggan,nomor_hp'
                ],
                'alamat' => 'required|string',
            ], [
                'nama.required' => 'Kolom nama wajib diisi.',
                'email.required' => 'Kolom email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Alamat email ini sudah digunakan. Silakan gunakan email lain.',
                'nomor_hp.required' => 'Kolom nomor HP wajib diisi.',
                'nomor_hp.regex' => 'Nomor HP harus berupa angka dan berjumlah 10-15 digit.',
                'nomor_hp.unique' => 'Nomor HP ini sudah terdaftar. Silakan gunakan nomor lain.',
                'alamat.required' => 'Kolom alamat wajib diisi.',
            ]);

            // Set membership_id ke null untuk pelanggan baru
            $validated['membership_id'] = null;

            Pelanggan::create($validated);

            return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('_form_type', 'add');
        }
    }

    /**
     * Memperbarui data pelanggan yang ada.
     * Membership tidak bisa diubah karena otomatis dikelola sistem.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:pelanggan,email,' . $pelanggan->id,
                'nomor_hp' => [
                    'required',
                    'string',
                    'regex:/^[0-9]{10,15}$/',
                    'unique:pelanggan,nomor_hp,' . $pelanggan->id
                ],
                'alamat' => 'required|string',
            ], [
                'nama.required' => 'Kolom nama wajib diisi.',
                'email.required' => 'Kolom email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Alamat email ini sudah digunakan. Silakan gunakan email lain.',
                'nomor_hp.required' => 'Kolom nomor HP wajib diisi.',
                'nomor_hp.regex' => 'Nomor HP harus berupa angka dan berjumlah 10-15 digit.',
                'nomor_hp.unique' => 'Nomor HP ini sudah terdaftar. Silakan gunakan nomor lain.',
                'alamat.required' => 'Kolom alamat wajib diisi.',
            ]);

            // Update tanpa mengubah membership_id
            $pelanggan->update($validated);

            return redirect()->route('pelanggan.index')->with('success', 'Data pelanggan berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with([
                '_form_type' => 'edit',
                'pelanggan_id_edit' => $pelanggan->id,
            ]);
        }
    }

    /**
     * Menghapus data pelanggan dari database.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    /**
     * Menampilkan detail pelanggan termasuk riwayat transaksi.
     * Auto-upgrade membership sebelum menampilkan detail.
     */
    public function show(Pelanggan $pelanggan)
    {
        // Auto-upgrade membership untuk pelanggan ini
        $this->upgradeSingleCustomerMembership($pelanggan);

        // Memuat relasi membership dan transaksi
        $pelanggan->load(['membership', 'transaksi.detailTransaksi.produk']);

        // Menghitung total transaksi
        $jumlahTransaksi = $pelanggan->transaksi->count();
        $totalAkumulasiTransaksi = $pelanggan->transaksi->sum('total');

        // Mengurutkan riwayat transaksi dari yang terbaru
        $riwayatTransaksi = $pelanggan->transaksi->sortByDesc('tanggal_transaksi');
        
        return view('pelanggan.show', compact('pelanggan', 'jumlahTransaksi', 'totalAkumulasiTransaksi', 'riwayatTransaksi'));
    }

    /**
     * Upgrade/downgrade membership untuk satu pelanggan spesifik
     */
    private function upgradeSingleCustomerMembership(Pelanggan $pelanggan)
    {
        // Hitung total transaksi pelanggan
        $totalTransaksi = $pelanggan->transaksi->sum('total');

        // Ambil semua membership diurutkan dari minimal_transaksi tertinggi ke terendah
        $memberships = Membership::orderBy('minimal_transaksi', 'desc')->get();

        // Cari membership yang sesuai
        $membershipYangSesuai = null;
        foreach ($memberships as $membership) {
            if ($totalTransaksi >= $membership->minimal_transaksi) {
                $membershipYangSesuai = $membership;
                break;
            }
        }

        // Update membership (bisa naik, turun, atau null) jika berbeda
        $membershipIdBaru = $membershipYangSesuai ? $membershipYangSesuai->id : null;
        
        if ($pelanggan->membership_id !== $membershipIdBaru) {
            $pelanggan->update(['membership_id' => $membershipIdBaru]);
            // Refresh data pelanggan
            $pelanggan->refresh();
        }
    }
}