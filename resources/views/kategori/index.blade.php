@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Kategori</h1>

    @if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-200 text-green-800 rounded-lg shadow">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="mb-4 px-4 py-3 bg-red-200 text-red-800 rounded-lg shadow">
        {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
        <div class="relative flex-1 w-full md:w-auto">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i data-feather="search" class="w-5 h-5"></i>
            </div>
            <input type="text"
                   id="searchInput"
                   name="search"
                   placeholder="Cari kategori..."
                   value="{{ $search ?? '' }}"
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                   autocomplete="off"/>
        </div>
        <button onclick="openAddModal()"
                class="w-full md:w-auto px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 flex items-center justify-center gap-1 transition-colors shadow-md">
            <i data-feather="plus" class="w-5 h-5"></i> Tambah Kategori
        </button>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg" id="table-container">
        @include('kategori.partials.table', ['kategoris' => $kategoris])
    </div>
</div>

{{-- ================================================= --}}
{{-- ============== MODALS SECTION =================== --}}
{{-- ================================================= --}}

<div id="addModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50"
     data-has-errors="{{ $errors->any() && session('_form_type') === 'add' ? 'true' : 'false' }}">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Tambah Kategori Baru</h2>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('kategori.store') }}" id="addKategoriForm">
            @csrf
            <input type="hidden" name="_form_type" value="add">
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
                <input type="text" name="nama" id="nama" required value="{{ old('nama') }}"
                       class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nama') border-red-500 @enderror"
                       placeholder="Masukkan nama kategori">
                @error('nama') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3"
                          class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('deskripsi') border-red-500 @enderror"
                          placeholder="Deskripsi singkat tentang kategori">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors flex items-center gap-2">
                    <i data-feather="plus" class="w-4 h-4"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50"
     data-has-errors-edit="{{ $errors->any() && session('_form_type') === 'edit' ? session('kategori_id_edit') : '' }}"
     data-kategori-data="{{ $errors->any() && session('_form_type') === 'edit' ? json_encode(old()) : '{}' }}">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Kategori</h2>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form_type" value="edit">
            <input type="hidden" name="kategori_id_edit" id="kategori_id_edit">
            <div class="mb-4">
                <label for="editNama" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
                <input type="text" name="nama" id="editNama" required
                       value="{{ old('_form_type') === 'edit' ? old('nama') : '' }}"
                       class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nama') border-red-500 @enderror"
                       placeholder="Masukkan nama kategori">
                @error('nama') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label for="editDeskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="editDeskripsi" rows="3"
                          class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('deskripsi') border-red-500 @enderror"
                          placeholder="Deskripsi singkat tentang kategori">{{ old('_form_type') === 'edit' ? old('deskripsi') : '' }}</textarea>
                @error('deskripsi') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 transition-colors flex items-center gap-2">
                    <i data-feather="save" class="w-4 h-4"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 mx-4">
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i data-feather="alert-triangle" class="text-red-600 w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Hapus Kategori</h2>
        </div>
        <div class="text-center mb-8">
            <p class="text-gray-700 text-lg">
                Apakah Anda yakin ingin menghapus kategori:
            </p>
            <p class="text-xl font-bold text-red-600 mt-2 bg-red-50 py-2 px-4 rounded border">
                <span id="deleteKategoriNama"></span>
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

{{-- Modal Detail Kategori --}}
<div id="detailModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-[90vh] p-6 mx-4 flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800" id="detailKategoriNama"></h2>
            <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="overflow-y-auto pr-2">
            <div class="mb-6">
                <p class="text-gray-700 font-semibold text-lg">Deskripsi:</p>
                <p id="detailKategoriDeskripsi" class="text-gray-600 mt-1"></p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 font-semibold text-lg">Produk dalam Kategori Ini:</p>
                <div id="produkList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-2">
                    {{-- Konten produk akan dimuat di sini oleh JavaScript --}}
                </div>
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button type="button" onclick="closeModal('detailModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Tutup</button>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
<script src="{{ asset('js/kategori.js') }}"></script>
@endsection