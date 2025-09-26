<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar kategori dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Kategori::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        $kategoris = $query->orderBy('nama')->paginate(8)->withQueryString();

        if ($request->ajax()) {
            return view('kategori.partials.table', compact('kategoris'));
        }

        return view('kategori.index', compact('kategoris', 'search'));
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:kategori,nama',
                'deskripsi' => 'nullable|string',
            ], [
                'nama.required' => 'Nama kategori wajib diisi.',
                'nama.unique' => 'Nama kategori ini sudah ada. Silakan gunakan nama lain.',
            ]);

            Kategori::create($validated);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('_form_type', 'add');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan kategori. Coba lagi nanti.')->withInput();
        }
    }

    /**
     * Memperbarui kategori yang sudah ada.
     */
    public function update(Request $request, Kategori $kategori)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:kategori,nama,' . $kategori->id,
                'deskripsi' => 'nullable|string',
            ], [
                'nama.required' => 'Nama kategori wajib diisi.',
                'nama.unique' => 'Nama kategori ini sudah ada. Silakan gunakan nama lain.',
            ]);

            $kategori->update($validated);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with([
                '_form_type' => 'edit',
                'kategori_id_edit' => $kategori->id,
            ]);
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui kategori. Coba lagi nanti.')->withInput();
        }
    }

    /**
     * Menghapus kategori dari database.
     */
    public function destroy(Kategori $kategori)
    {
        try {
            if ($kategori->produk()->count() > 0) {
                return redirect()->back()->with('error', 'Gagal menghapus kategori. Kategori ini masih memiliki produk terkait.');
            }
            
            $kategori->delete();
            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');

        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal menghapus kategori. Terjadi kesalahan pada database.');
        }
    }

    /**
     * Menampilkan detail kategori beserta produk di dalamnya.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Kategori $kategori)
    {
        // Muat (load) relasi produk
        $kategori->load('produk');

        // Mengembalikan data dalam format JSON
        return response()->json([
            'id' => $kategori->id,
            'nama' => $kategori->nama,
            'deskripsi' => $kategori->deskripsi ?? 'Tidak ada deskripsi',
            'produk' => $kategori->produk->map(function ($produk) {
                return [
                    'id' => $produk->id,
                    'nama' => $produk->nama,
                    'harga' => number_format($produk->harga, 0, ',', '.'),
                    'stok' => number_format($produk->stok, 0, ',', '.'),
                    'image' => $produk->image ? asset('storage/' . $produk->image) : 'https://via.placeholder.com/64',
                ];
            }),
            'dibuat_pada' => $kategori->created_at->format('d M Y H:i'),
            'diperbarui_pada' => $kategori->updated_at->format('d M Y H:i'),
        ]);
    }
}