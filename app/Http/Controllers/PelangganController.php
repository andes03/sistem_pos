<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Membership;
use Illuminate\Validation\ValidationException;

class PelangganController extends Controller
{
    /**
     * Menampilkan daftar pelanggan dengan fitur pencarian dan paginasi, serta filter membership.
     */
    public function index(Request $request)
    {
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
     * Menyimpan data pelanggan baru ke database.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:pelanggan,email',
                'nomor_hp' => 'required|string|max:20',
                'alamat' => 'required|string',
                'membership_id' => 'required|exists:membership,id',
            ], [
                // Pesan validasi kustom
                'nama.required' => 'Kolom nama wajib diisi.',
                'email.required' => 'Kolom email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Alamat email ini sudah digunakan. Silakan gunakan email lain.',
                'nomor_hp.required' => 'Kolom nomor HP wajib diisi.',
                'alamat.required' => 'Kolom alamat wajib diisi.',
                'membership_id.required' => 'Pilihan membership wajib diisi.',
            ]);

            Pelanggan::create($validated);

            return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('_form_type', 'add');
        }
    }

    /**
     * Memperbarui data pelanggan yang ada.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:pelanggan,email,' . $pelanggan->id,
                'nomor_hp' => 'required|string|max:20',
                'alamat' => 'required|string',
                'membership_id' => 'required|exists:membership,id',
            ], [
                // Pesan validasi kustom
                'nama.required' => 'Kolom nama wajib diisi.',
                'email.required' => 'Kolom email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Alamat email ini sudah digunakan. Silakan gunakan email lain.',
                'nomor_hp.required' => 'Kolom nomor HP wajib diisi.',
                'alamat.required' => 'Kolom alamat wajib diisi.',
                'membership_id.required' => 'Pilihan membership wajib diisi.',
            ]);

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
     */
    public function show(Pelanggan $pelanggan)
    {
        // Memuat relasi membership dan transaksi
        $pelanggan->load(['membership', 'transaksi.detailTransaksi.produk']);

        // Menghitung total transaksi
        $jumlahTransaksi = $pelanggan->transaksi->count();

        // Mengurutkan riwayat transaksi dari yang terbaru
        $riwayatTransaksi = $pelanggan->transaksi->sortByDesc('tanggal_transaksi');
        
        return view('pelanggan.show', compact('pelanggan', 'jumlahTransaksi', 'riwayatTransaksi'));
    }
}