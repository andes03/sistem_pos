<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk dengan fitur pencarian dan filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $kategoriFilter = $request->input('kategori');
        $kategoris = Kategori::orderBy('nama')->get();

        $query = Produk::query()->with('kategori');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }
        
        if ($kategoriFilter) {
            $query->where('kategori_id', $kategoriFilter);
        }

        // Paginasi dengan mempertahankan query string
        $produk = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Jika permintaan adalah AJAX, kembalikan hanya partial view tabel
        if ($request->ajax()) {
            return view('produk.partials.table', compact('produk', 'kategoris', 'kategoriFilter'));
        }

        // Jika bukan AJAX, kembalikan tampilan utama
        return view('produk.index', compact('produk', 'search', 'kategoris', 'kategoriFilter'));
    }

    /**
     * Menyimpan produk baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:produk,nama',
                'deskripsi' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
                'kategori_id' => 'required|exists:kategori,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'nama.required' => 'Nama produk wajib diisi.',
                'nama.unique' => 'Nama produk ini sudah ada. Silakan gunakan nama lain.',
                'harga.required' => 'Harga wajib diisi.',
                'harga.numeric' => 'Harga harus berupa angka.',
                'stok.required' => 'Stok wajib diisi.',
                'stok.integer' => 'Stok harus berupa angka bulat.',
                'kategori_id.required' => 'Kategori wajib diisi.',
                'image.required' => 'Gambar produk wajib diupload.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar yang didukung adalah jpeg, png, jpg, gif, svg.',
                'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            ]);

            $imagePath = $request->file('image')->store('produk', 'public');

            Produk::create([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'],
                'harga' => $validated['harga'],
                'stok' => $validated['stok'],
                'kategori_id' => $validated['kategori_id'],
                'image' => $imagePath,
            ]);

            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with('_form_type', 'add');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan produk. Coba lagi nanti.')->withInput();
        }
    }

    /**
     * Memperbarui produk yang sudah ada.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Produk $produk)
    {
        try {
            // Jika produk tidak memiliki gambar sebelumnya, maka gambar wajib diisi
            $imageRules = $produk->image ? 'nullable' : 'required';
            
            $validated = $request->validate([
                'nama' => 'required|string|max:255|unique:produk,nama,' . $produk->id,
                'deskripsi' => 'nullable|string',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
                'kategori_id' => 'required|exists:kategori,id',
                'image' => $imageRules . '|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ], [
                'nama.required' => 'Nama produk wajib diisi.',
                'nama.unique' => 'Nama produk ini sudah ada. Silakan gunakan nama lain.',
                'harga.required' => 'Harga wajib diisi.',
                'harga.numeric' => 'Harga harus berupa angka.',
                'stok.required' => 'Stok wajib diisi.',
                'stok.integer' => 'Stok harus berupa angka bulat.',
                'kategori_id.required' => 'Kategori wajib diisi.',
                'image.required' => 'Gambar produk wajib diupload.',
                'image.image' => 'File harus berupa gambar.',
                'image.mimes' => 'Format gambar yang didukung adalah jpeg, png, jpg, gif, svg.',
                'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            ]);

            $imagePath = $produk->image;
            if ($request->hasFile('image')) {
                if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('produk', 'public');
            }

            $produk->update([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'],
                'harga' => $validated['harga'],
                'stok' => $validated['stok'],
                'kategori_id' => $validated['kategori_id'],
                'image' => $imagePath,
            ]);

            return redirect()->route('produk.index')->with('success', 'Data produk berhasil diperbarui.');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput()->with([
                '_form_type' => 'edit',
                'produk_id_edit' => $produk->id,
            ]);
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui produk. Coba lagi nanti.')->withInput();
        }
    }

    /**
     * Menghapus produk dari database.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Produk $produk)
    {
        try {
            if ($produk->image && Storage::disk('public')->exists($produk->image)) {
                Storage::disk('public')->delete($produk->image);
            }

            $produk->delete();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');

        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal menghapus produk. Produk mungkin terkait dengan data lain.');
        }
    }

    /**
     * Menampilkan detail produk.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Produk $produk)
    {
        // Muat (load) relasi kategori
        $produk->load('kategori');

        // Hitung total terjual menggunakan metode yang baru ditambahkan di model
        $totalTerjual = $produk->totalTerjual();

        // Mengembalikan data dalam format JSON
        return response()->json([
            'id' => $produk->id,
            'nama' => $produk->nama,
            'deskripsi' => $produk->deskripsi ?? 'Tidak ada deskripsi',
            'harga' => number_format($produk->harga, 0, ',', '.'),
            'stok' => $produk->stok,
            'kategori' => $produk->kategori->nama ?? 'Tidak Ada',
            'image' => $produk->image ? asset('storage/' . $produk->image) : 'https://via.placeholder.com/200',
            'total_terjual' => number_format($totalTerjual, 0, ',', '.'),
            'dibuat_pada' => $produk->created_at->format('d M Y H:i'),
            'diperbarui_pada' => $produk->updated_at->format('d M Y H:i'),
        ]);
    }
}