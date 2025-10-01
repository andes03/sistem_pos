@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Pelanggan</h1>

    @if(session('success'))
        <div id="success-message" class="hidden">{{ session('success') }}</div>
    @endif

    <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
        <div class="flex-1 w-full flex flex-col sm:flex-row gap-4">
            <div class="relative w-full sm:w-1/2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i data-feather="search" class="w-5 h-5"></i>
                </div>
                <input type="text"
                       id="searchInput"
                       name="search"
                       placeholder="Cari nama atau email..."
                       value="{{ $search ?? '' }}"
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                       autocomplete="off"/>
            </div>

            <div class="relative w-full sm:w-1/2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i data-feather="users" class="w-5 h-5"></i>
                </div>
                <select id="filterMembership" name="membership"
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    <option value="">Semua Membership</option>
                    @foreach($memberships as $membership)
                        <option value="{{ $membership->id }}" {{ ($membershipFilter == $membership->id) ? 'selected' : '' }}>
                            {{ $membership->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <button onclick="openAddModal()"
                class="w-full md:w-auto px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 flex items-center justify-center gap-1 transition-colors shadow-md">
            <i data-feather="plus" class="w-5 h-5"></i> Tambah Pelanggan
        </button>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg" id="table-container">
        @include('pelanggan.partials.table', ['pelanggan' => $pelanggan])
    </div>
</div>

{{-- Modal Tambah Pelanggan --}}
<div id="addModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50"
      data-has-errors="{{ $errors->any() && session('_form_type') === 'add' ? 'true' : 'false' }}">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Tambah Pelanggan Baru</h2>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('pelanggan.store') }}" id="addCustomerForm">
            @csrf
            <input type="hidden" name="_form_type" value="add">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                <input type="text" name="nama" required value="{{ old('nama') }}"
                       class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nama') border-red-500 @enderror"
                       placeholder="Masukkan nama pelanggan">
                @error('nama') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                       class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('email') border-red-500 @enderror"
                       placeholder="Masukkan alamat email">
                @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP *</label>
                <input type="text" name="nomor_hp" required value="{{ old('nomor_hp') }}"
                       class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nomor_hp') border-red-500 @enderror"
                       placeholder="Masukkan nomor telepon">
                @error('nomor_hp') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
                <textarea name="alamat" required rows="3"
                          class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('alamat') border-red-500 @enderror"
                          placeholder="Masukkan alamat pelanggan">{{ old('alamat') }}</textarea>
                @error('alamat') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                <p class="text-sm text-blue-800 flex items-start gap-2">
                    <i data-feather="info" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                    <span>Membership akan otomatis diberikan berdasarkan total transaksi pelanggan.</span>
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('addModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition-colors flex items-center gap-2">
                    <i data-feather="plus" class="w-4 h-4"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Pelanggan --}}
<div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50"
      data-has-errors-edit="{{ $errors->any() && session('_form_type') === 'edit' ? session('pelanggan_id_edit') : '' }}">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Data Pelanggan</h2>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form_type" value="edit">
            <input type="hidden" name="pelanggan_id_edit" id="pelanggan_id_edit">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama *</label>
                <input type="text" name="nama" id="editNama" required
                       value="{{ old('_form_type') === 'edit' ? old('nama') : '' }}"
                       class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nama') border-red-500 @enderror">
                @error('nama') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                <input type="email" name="email" id="editEmail" required
                       value="{{ old('_form_type') === 'edit' ? old('email') : '' }}"
                       class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('email') border-red-500 @enderror">
                @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP *</label>
                <input type="text" name="nomor_hp" id="editNomorHp" required
                       value="{{ old('_form_type') === 'edit' ? old('nomor_hp') : '' }}"
                       class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nomor_hp') border-red-500 @enderror">
                @error('nomor_hp') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
                <textarea name="alamat" id="editAlamat" required rows="3"
                          class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('alamat') border-red-500 @enderror">{{ old('_form_type') === 'edit' ? old('alamat') : '' }}</textarea>
                @error('alamat') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-4">
                <p class="text-sm text-blue-800 flex items-start gap-2">
                    <i data-feather="info" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                    <span>Membership dikelola otomatis oleh sistem berdasarkan total transaksi dan tidak dapat diubah secara manual.</span>
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Batal</button>
                <button type="submit" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 flex items-center gap-2">
                    <i data-feather="save" class="w-4 h-4"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Hapus Pelanggan --}}
<div id="deleteModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 mx-4">
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i data-feather="alert-triangle" class="text-red-600 w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Hapus Pelanggan</h2>
        </div>

        <div class="text-center mb-8">
            <p class="text-gray-700 text-lg">
                Apakah Anda yakin ingin menghapus pelanggan:
            </p>
            <p class="text-xl font-bold text-red-600 mt-2 bg-red-50 py-2 px-4 rounded border">
                <span id="deletePelangganNama"></span>
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

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
<script src="{{ asset('js/pelanggan.js') }}"></script>
@endsection