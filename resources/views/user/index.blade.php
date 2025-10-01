@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Pengguna</h1>

     {{-- Pesan sukses - disembunyikan dan akan ditampilkan sebagai toast --}}
    @if(session('success'))
        <div id="success-message" class="hidden">{{ session('success') }}</div>
    @endif

    {{-- Pesan error - disembunyikan dan akan ditampilkan sebagai toast --}}
    @if(session('error'))
        <div id="error-message" class="hidden">{{ session('error') }}</div>
    @endif

    <input type="hidden" id="base-url" value="{{ route('users.index') }}">

    <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
        <div class="flex-1 w-full flex flex-col sm:flex-row gap-4">
            <div class="relative w-full sm:w-1/2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i data-feather="search" class="w-5 h-5"></i>
                </div>
                <form id="filterForm">
                    <input type="text"
                           id="search"
                           name="search"
                           placeholder="Cari nama atau username..."
                           value="{{ $search ?? '' }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                           autocomplete="off"/>
                    <button type="submit" class="hidden">Cari</button>
                </form>
            </div>

            <div class="relative w-full sm:w-1/2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                    <i data-feather="users" class="w-5 h-5"></i>
                </div>
                <form id="roleFilterForm">
                    <select id="roleFilter" name="role"
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        <option value="">Semua Role</option>
                        <option value="admin" @if($roleFilter == 'admin') selected @endif>Admin</option>
                        <option value="user" @if($roleFilter == 'user') selected @endif>User</option>
                    </select>
                </form>
            </div>
        </div>

        <button onclick="openAddModal()"
                class="w-full md:w-auto px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 flex items-center justify-center gap-1 transition-colors shadow-md">
            <i data-feather="plus" class="w-5 h-5"></i> Tambah User
        </button>
    </div>

    <div id="table-container" class="overflow-x-auto bg-white shadow-md rounded-lg">
        @include('user.partials.table', ['users' => $users, 'search' => $search, 'roleFilter' => $roleFilter])
    </div>
</div>

{{-- ================================================= --}}
{{-- ============== MODALS SECTION =================== --}}
{{-- ================================================= --}}

{{-- Modal Tambah User --}}
<div id="addModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50"
     data-has-errors="{{ $errors->any() && session('_form_type') === 'add' ? 'true' : 'false' }}">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-auto max-h-[90vh] p-6 mx-4 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Tambah User Baru</h2>
            <button onclick="closeModal('addModal')" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" id="addUserForm">
            @csrf
            <input type="hidden" name="_form_type" value="add">

            {{-- Grid untuk tata letak 2 kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                        <input type="text" name="username" required value="{{ old('username') }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('username') border-red-500 @enderror"
                               placeholder="Masukkan username">
                        @error('username') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="nama" required value="{{ old('nama') }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nama') border-red-500 @enderror"
                               placeholder="Masukkan nama lengkap">
                        @error('nama') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('email') border-red-500 @enderror"
                               placeholder="Masukkan email">
                        @error('email') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password (minimal 8 karakter) *</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                   value="{{ old('_form_type') === 'add' ? old('password') : '' }}"
                                   class="w-full px-3 py-2 pr-10 border rounded-md focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror"
                                   placeholder="Masukkan password">
                            <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i data-feather="eye" id="togglePasswordIcon" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @error('password')
                            @unless(str_contains($message, 'Konfirmasi') || str_contains($message, 'konfirmasi') || str_contains($message, 'confirmation'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @endunless
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password *</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   value="{{ old('_form_type') === 'add' ? old('password_confirmation') : '' }}"
                                   class="w-full px-3 py-2 pr-10 border rounded-md focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror"
                                   placeholder="Konfirmasi password">
                            <button type="button" onclick="togglePassword('password_confirmation', 'togglePasswordConfirmationIcon')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i data-feather="eye" id="togglePasswordConfirmationIcon" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @error('password')
                            @if(str_contains($message, 'Konfirmasi') || str_contains($message, 'konfirmasi') || str_contains($message, 'confirmation'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @endif
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP *</label>
                        <input type="text" name="nomor_hp" required value="{{ old('nomor_hp') }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nomor_hp') border-red-500 @enderror"
                               placeholder="Masukkan nomor HP">
                        @error('nomor_hp') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                {{-- Kolom Kanan --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
                        <textarea name="alamat" rows="3" required
                                         class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('alamat') border-red-500 @enderror"
                                         placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                        @error('alamat') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan *</label>
                        <input type="text" name="jabatan" required value="{{ old('jabatan') }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('jabatan') border-red-500 @enderror"
                               placeholder="Masukkan jabatan">
                        @error('jabatan') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select name="role" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('role') border-red-500 @enderror">
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil *</label>
                        <div class="mb-3">
                            <img id="image-preview" src="https://via.placeholder.com/100" alt="Preview" 
                                 class="w-24 h-24 object-cover rounded border hidden">
                        </div>
                        <input type="file" name="image" id="imageInput" accept="image/*" required
                               onchange="previewImage(this, 'image-preview')"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('image') border-red-500 @enderror">
                        <div id="image-info">
                            <p class="text-xs text-gray-500 mt-1">Format yang didukung: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB.</p>
                        </div>
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

{{-- Modal Edit User --}}
<div id="editModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50"
     data-has-errors-edit="{{ $errors->any() && session('_form_type') === 'edit' ? session('user_id_edit') : '' }}"
     data-user-data="{{ $errors->any() && session('_form_type') === 'edit' ? json_encode(old()) : '{}' }}">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl h-auto max-h-[90vh] p-6 mx-4 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Edit Data User</h2>
            <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-gray-600"><i data-feather="x" class="w-5 h-5"></i></button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="_form_type" value="edit">
            <input type="hidden" name="user_id_edit" id="user_id_edit">

            {{-- Grid untuk tata letak 2 kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Kolom Kiri --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                        <input type="text" name="username" id="editUsername" required
                               value="{{ old('_form_type') === 'edit' ? old('username') : '' }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('username') border-red-500 @enderror">
                        @error('username') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password (minimal 8 karakter) <small class="text-gray-500">(kosongkan jika tidak diubah)</small></label>
                        <div class="relative">
                            <input type="password" name="password" id="editPassword"
                                   value="{{ old('_form_type') === 'edit' ? old('password') : '' }}"
                                   class="w-full px-3 py-2 pr-10 border rounded-md focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror"
                                   placeholder="Masukkan password baru">
                            <button type="button" onclick="togglePassword('editPassword', 'toggleEditPasswordIcon')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i data-feather="eye" id="toggleEditPasswordIcon" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @error('password')
                            @unless(str_contains($message, 'Konfirmasi') || str_contains($message, 'konfirmasi') || str_contains($message, 'confirmation'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @endunless
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="editPasswordConfirmation"
                                   value="{{ old('_form_type') === 'edit' ? old('password_confirmation') : '' }}"
                                   class="w-full px-3 py-2 pr-10 border rounded-md focus:ring-2 focus:ring-blue-400 @error('password') border-red-500 @enderror"
                                   placeholder="Konfirmasi password baru">
                            <button type="button" onclick="togglePassword('editPasswordConfirmation', 'toggleEditPasswordConfirmationIcon')" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <i data-feather="eye" id="toggleEditPasswordConfirmationIcon" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @error('password')
                            @if(str_contains($message, 'Konfirmasi') || str_contains($message, 'konfirmasi') || str_contains($message, 'confirmation'))
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @endif
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP *</label>
                        <input type="text" name="nomor_hp" id="editNomorHp" required
                               value="{{ old('_form_type') === 'edit' ? old('nomor_hp') : '' }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('nomor_hp') border-red-500 @enderror">
                        @error('nomor_hp') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat *</label>
                        <textarea name="alamat" id="editAlamat" rows="3" required
                                         class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('alamat') border-red-500 @enderror"
                                         placeholder="Masukkan alamat lengkap">{{ old('_form_type') === 'edit' ? old('alamat') : '' }}</textarea>
                        @error('alamat') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan *</label>
                        <input type="text" name="jabatan" id="editJabatan" required
                               value="{{ old('_form_type') === 'edit' ? old('jabatan') : '' }}"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('jabatan') border-red-500 @enderror"
                               placeholder="Masukkan jabatan">
                        @error('jabatan') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                        <select name="role" id="editRole" required
                                     class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('role') border-red-500 @enderror">
                            <option value="" disabled>Pilih Role</option>
                            <option value="user"
                                         {{ (old('_form_type') === 'edit' && old('role') == 'user') ? 'selected' : '' }}>User</option>
                            <option value="admin"
                                         {{ (old('_form_type') === 'edit' && old('role') == 'admin') ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                        <img id="edit-image-preview" src="https://via.placeholder.com/100" alt="Preview" class="w-24 h-24 object-cover rounded mb-2">
                        <input type="file" name="image" id="editImage" accept="image/*"
                               onchange="previewImage(this, 'edit-image-preview')"
                               class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-blue-400 @error('image') border-red-500 @enderror">
                        <input type="hidden" name="current_image_path" id="current-image-path">
                        <div id="editImageInput-info">
                            <p class="text-xs text-gray-500 mt-1">Format yang didukung: JPEG, PNG, JPG, GIF, SVG. Maksimal 2MB.</p>
                        </div>
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

{{-- Modal Hapus User --}}
<div id="deleteModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 mx-4">
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i data-feather="alert-triangle" class="text-red-600 w-8 h-8"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-900">Hapus User</h2>
        </div>

        <div class="text-center mb-8">
            <p class="text-gray-700 text-lg">
                Apakah Anda yakin ingin menghapus user:
            </p>
            <p class="text-xl font-bold text-red-600 mt-2 bg-red-50 py-2 px-4 rounded border">
                <span id="deleteUserNama"></span>
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

{{-- Modal Detail User --}}
<div id="detailModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 mx-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Detail User</h2>
            <button onclick="closeModal('detailModal')" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-1 flex justify-center">
                <div class="relative w-full max-w-xs">
                    <img id="detail-image" src="" alt="Foto User" 
                         class="w-full h-auto object-cover rounded-lg shadow-md"
                         style="display: none;"
                         onload="this.style.display='block'; this.nextElementSibling.style.display='none';"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    
                    <div id="detail-avatar" 
                         class="w-full aspect-square bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg shadow-md flex items-center justify-center text-white"
                         style="display: flex;">
                        <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="md:col-span-1">
                <h3 id="detail-nama" class="text-2xl font-bold text-gray-900 mb-2"></h3>
                <div class="mb-4">
                    <span id="detail-role" class="px-3 py-1 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full"></span>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center">
                        <i data-feather="user" class="w-5 h-5 text-blue-500 mr-2"></i>
                        <p class="text-gray-700 font-medium">Username: <span id="detail-username" class="font-semibold"></span></p>
                    </div>
                    <div class="flex items-center">
                        <i data-feather="mail" class="w-5 h-5 text-green-500 mr-2"></i>
                        <p class="text-gray-700 font-medium">Email: <span id="detail-email" class="font-semibold"></span></p>
                    </div>
                    <div class="flex items-center">
                        <i data-feather="phone" class="w-5 h-5 text-yellow-500 mr-2"></i>
                        <p class="text-gray-700 font-medium">No. HP: <span id="detail-nomor-hp" class="font-semibold"></span></p>
                    </div>
                    <div class="flex items-start">
                        <i data-feather="map-pin" class="w-5 h-5 text-red-500 mr-2 mt-0.5"></i>
                        <p class="text-gray-700 font-medium">Alamat: <span id="detail-alamat" class="font-semibold"></span></p>
                    </div>
                    <div class="flex items-center">
                        <i data-feather="briefcase" class="w-5 h-5 text-purple-500 mr-2"></i>
                        <p class="text-gray-700 font-medium">Jabatan: <span id="detail-jabatan" class="font-semibold"></span></p>
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
<script src="{{ asset('js/user.js') }}"></script>
@endsection