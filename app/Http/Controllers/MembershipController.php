<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Membership;
use Illuminate\Validation\ValidationException;

class MembershipController extends Controller
{
    /**
     * Menampilkan daftar membership dengan pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Membership::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        $memberships = $query->orderBy('created_at', 'desc')->paginate(6);

        if ($request->ajax()) {
            return view('membership.partials.table', compact('memberships'))->render();
        }

        return view('membership.index', compact('memberships', 'search'));
    }

    /**
     * Menyimpan data membership baru.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:membership,nama',
                'diskon' => 'required|numeric|min:0|max:100',
                'minimal_transaksi' => 'required|numeric|min:0',
            ], [
                'nama.required' => 'Kolom nama wajib diisi.',
                'nama.unique' => 'Nama membership ini sudah ada.',
                'diskon.required' => 'Kolom diskon wajib diisi.',
                'diskon.numeric' => 'Diskon harus berupa angka.',
                'diskon.min' => 'Diskon tidak boleh negatif.',
                'minimal_transaksi.required' => 'Kolom minimal transaksi wajib diisi.',
                'minimal_transaksi.numeric' => 'Minimal transaksi harus berupa angka.',
                'minimal_transaksi.min' => 'Minimal transaksi tidak boleh negatif.',
            ]);

            Membership::create($validated);

            return redirect()->route('membership.index')->with('success', 'Membership berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('_form_type', 'add');
        }
    }

    /**
     * Memperbarui data membership.
     */
    public function update(Request $request, Membership $membership)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:membership,nama,' . $membership->id,
                'diskon' => 'required|numeric|min:0|max:100',
                'minimal_transaksi' => 'required|numeric|min:0',
            ], [
                'nama.required' => 'Kolom nama wajib diisi.',
                'nama.unique' => 'Nama membership ini sudah ada.',
                'diskon.required' => 'Kolom diskon wajib diisi.',
                'diskon.numeric' => 'Diskon harus berupa angka.',
                'diskon.min' => 'Diskon tidak boleh negatif.',
                'minimal_transaksi.required' => 'Kolom minimal transaksi wajib diisi.',
                'minimal_transaksi.numeric' => 'Minimal transaksi harus berupa angka.',
                'minimal_transaksi.min' => 'Minimal transaksi tidak boleh negatif.',
            ]);

            $membership->update($validated);

            return redirect()->route('membership.index')->with('success', 'Data membership berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with([
                '_form_type' => 'edit',
                'membership_id_edit' => $membership->id,
            ]);
        }
    }

    /**
     * Menghapus data membership.
     */
    public function destroy(Membership $membership)
    {
        $membership->delete();
        return redirect()->route('membership.index')->with('success', 'Membership berhasil dihapus.');
    }

    /**
     * Menampilkan detail membership termasuk daftar pelanggannya.
     */
    public function show(Membership $membership)
    {
        // Memuat relasi 'pelanggan' untuk membership yang dipilih
        $membership->load('pelanggan');

        // Mengurutkan pelanggan berdasarkan nama
        $pelanggan = $membership->pelanggan->sortBy('nama');

        return view('membership.show', compact('membership', 'pelanggan'));
    }
}