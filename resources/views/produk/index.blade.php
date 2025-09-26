@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Produk</h1>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-200 text-green-800 rounded shadow">
        {{ session('success') }}
    </div>
    @endif

    {{-- Container untuk Pencarian, Filter, dan Tombol Tambah --}}
    <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
        <div class="flex-1 w-full flex flex-col sm:flex-row gap-4">
            {{-- Form Pencarian --}}
            <div class="relative w-full sm:w-1/2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i data-feather="search" class="w-5 h-5"></i>
                </div>
                <input type="text"
                    id="searchInput"
                    name="search"
                    placeholder="Cari nama produk..."
                    value="{{ $search ?? '' }}"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                    autocomplete="off"/>
            </div>

            {{-- Filter Kategori --}}
            <div class="relative w-full sm:w-1/2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i data-feather="grid" class="w-5 h-5"></i>
                </div>
                <select id="kategoriFilter" name="kategori"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @if($kategoriFilter == $kategori->id) selected @endif>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <button onclick="openAddModal()"
                class="w-full md:w-auto px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 flex items-center justify-center gap-1 transition-colors shadow-md">
            <i data-feather="plus" class="w-5 h-5"></i> Tambah Produk
        </button>
    </div>

    {{-- Container untuk tabel dinamis yang akan di-update oleh AJAX --}}
    <div class="overflow-x-auto bg-white shadow-md rounded-lg" id="table-container">
        @include('produk.partials.table', ['produk' => $produk])
    </div>
</div>

{{-- ================================================= --}}
{{-- ============== MODALS SECTION =================== --}}
{{-- ================================================= --}}

{{-- Modal Tambah Produk --}}
<div id="addModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50"
     data-has-errors="{{ $errors->any() && session('_form_type') === 'add' ? 'true' : 'false' }}">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-auto max-h-[90vh] p-6 mx-4 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Tambah Produk Baru</h2>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data" id="addProductForm">
            @csrf
            <input type="hidden" name="_form_type" value="add">

            {{-- Grid untuk tata letak 2 kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                        <input type="text" name="nama" required value="{{ old('nama') }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nama') border-red-500 @enderror"
                               placeholder="Masukkan nama produk">
                        @error('nama') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
                        <input type="number" name="harga" required value="{{ old('harga') }}" step="0.01"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('harga') border-red-500 @enderror"
                               placeholder="Masukkan harga produk">
                        @error('harga') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                        <input type="number" name="stok" required value="{{ old('stok') }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('stok') border-red-500 @enderror"
                               placeholder="Masukkan jumlah stok">
                        @error('stok') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                        <select name="kategori_id" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('kategori_id') border-red-500 @enderror">
                            <option value="" disabled selected>Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                {{-- Kolom Kanan --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" rows="4"
                                    class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('deskripsi') border-red-500 @enderror"
                                    placeholder="Masukkan deskripsi produk">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar *</label>
                        <input type="file" name="image" required accept="image/*"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('image') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Format yang didukung: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB.</p>
                        @error('image') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div> {{-- Akhir dari grid --}}

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors flex items-center gap-2">
                    <i data-feather="plus" class="w-4 h-4"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Produk --}}
<div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50"
     data-has-errors-edit="{{ $errors->any() && session('_form_type') === 'edit' ? session('produk_id_edit') : '' }}"
     data-produk-data="{{ $errors->any() && session('_form_type') === 'edit' ? json_encode(old()) : '{}' }}">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-auto max-h-[90vh] p-6 mx-4 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Data Produk</h2>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600"><i data-feather="x" class="w-5 h-5"></i></button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form_type" value="edit">
            <input type="hidden" name="produk_id_edit" id="produk_id_edit">

            {{-- Grid untuk tata letak 2 kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                        <input type="text" name="nama" id="editNama" required
                               value="{{ old('_form_type') === 'edit' ? old('nama') : '' }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nama') border-red-500 @enderror">
                        @error('nama') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga *</label>
                        <input type="number" name="harga" id="editHarga" required step="0.01"
                               value="{{ old('_form_type') === 'edit' ? old('harga') : '' }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('harga') border-red-500 @enderror">
                        @error('harga') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok *</label>
                        <input type="number" name="stok" id="editStok" required
                               value="{{ old('_form_type') === 'edit' ? old('stok') : '' }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('stok') border-red-500 @enderror">
                        @error('stok') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                        <select name="kategori_id" id="editKategoriId" required
                                 class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('kategori_id') border-red-500 @enderror">
                            <option value="" disabled>Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                        {{ (old('_form_type') === 'edit' && old('kategori_id') == $kategori->id) ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="editDeskripsi" rows="4"
                                    class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('deskripsi') border-red-500 @enderror"
                                    placeholder="Masukkan deskripsi produk">{{ old('_form_type') === 'edit' ? old('deskripsi') : '' }}</textarea>
                        @error('deskripsi') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
                        {{-- Preview gambar saat ini --}}
                        <div class="mb-2">
                            <p class="text-sm text-gray-600 mb-1">Gambar saat ini:</p>
                            <img id="image-preview" src="https://via.placeholder.com/100" alt="Preview" class="w-24 h-24 object-cover rounded border">
                        </div>
                        <input type="file" name="image" id="editImage" accept="image/*"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('image') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar. Format yang didukung: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB.</p>
                        <input type="hidden" name="current_image_path" id="current-image-path">
                        @error('image') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div> {{-- Akhir dari grid --}}

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 flex items-center gap-2">
                    <i data-feather="save" class="w-4 h-4"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus Produk --}}
<div id="deleteModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 mx-4">
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i data-feather="alert-triangle" class="text-red-600 w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Hapus Produk</h2>
        </div>

        <div class="text-center mb-8">
            <p class="text-gray-700 text-lg">
                Apakah Anda yakin ingin menghapus produk:
            </p>
            <p class="text-xl font-bold text-red-600 mt-2 bg-red-50 py-2 px-4 rounded border">
                <span id="deleteProdukNama"></span>
            </p>
        </div>

        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex flex-col-reverse sm:flex-row justify-center gap-3">
                <button type="button" onclick="closeModal('deleteModal')" class="flex-1 px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-medium">Batal</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium flex items-center justify-center gap-2">
                    <i data-feather="trash-2" class="w-4 h-4"></i> Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Detail Produk --}}
<div id="detailModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Detail Produk</h2>
            <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-1">
                <img id="detail-image" src="" alt="Gambar Produk" class="w-full h-auto object-cover rounded-lg shadow-md">
            </div>
            <div class="md:col-span-1">
                <h3 id="detail-nama" class="text-2xl font-bold text-gray-900 mb-2"></h3>
                <div class="mb-4">
                    <span id="detail-kategori" class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full"></span>
                </div>
                <div class="mb-4">
                    <p class="text-gray-700 text-sm font-semibold">Deskripsi:</p>
                    <p id="detail-deskripsi" class="text-gray-600 mt-1"></p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <i data-feather="dollar-sign" class="w-5 h-5 text-green-500 mr-2"></i>
                        <p class="text-gray-700 font-medium">Harga: <span id="detail-harga" class="font-bold text-lg text-green-600"></span></p>
                    </div>
                    <div class="flex items-center">
                        <i data-feather="package" class="w-5 h-5 text-blue-500 mr-2"></i>
                        <p class="text-gray-700 font-medium">Stok: <span id="detail-stok" class="font-semibold"></span></p>
                    </div>
                    <div class="flex items-center">
                        <i data-feather="trending-up" class="w-5 h-5 text-yellow-500 mr-2"></i>
                        <p class="text-gray-700 font-medium">Total Terjual: <span id="detail-total-terjual" class="font-semibold text-yellow-600"></span></p>
                    </div>
                    <div class="flex items-center text-gray-500 text-sm">
                        <i data-feather="calendar" class="w-4 h-4 mr-2"></i>
                        <p>Dibuat: <span id="detail-created"></span></p>
                    </div>
                    <div class="flex items-center text-gray-500 text-sm">
                        <i data-feather="edit-3" class="w-4 h-4 mr-2"></i>
                        <p>Diperbarui: <span id="detail-updated"></span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <button type="button" onclick="closeModal('detailModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Tutup</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
<script src="{{ asset('js/produk.js') }}"></script>
@endsection