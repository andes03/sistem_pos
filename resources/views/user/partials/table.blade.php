<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-blue-500">
        <tr>
            {{-- Kolom ID Dihapus --}}
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Pengguna</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Username</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">No. HP</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Role</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Dibuat Pada</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                {{-- Kolom ID Dihapus --}}
                
                {{-- Kolom Pengguna (Foto, Nama, Email) yang Digabungkan --}}
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        {{-- Bagian Foto/Inisial --}}
                        <div class="flex-shrink-0 h-10 w-10 relative">
                            @if($user->image)
                                <img src="{{ asset('storage/' . $user->image) }}"
                                    alt="{{ $user->nama }}" 
                                    class="h-10 w-10 object-cover rounded-full"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm absolute top-0 left-0" style="display: none;">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}{{ strtoupper(substr(strstr($user->nama, ' ') ?: $user->nama, 1, 1)) }}
                                </div>
                            @else
                                <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}{{ strtoupper(substr(strstr($user->nama, ' ') ?: $user->nama, 1, 1)) }}
                                </div>
                            @endif
                        </div>
                        {{-- Bagian Nama dan Email --}}
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $user->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                {{-- Kolom Lainnya --}}
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->username }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->nomor_hp }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                        <button
                            onclick="openDetailModal('{{ route('users.show', $user->id) }}')"
                            class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors"
                            title="Lihat Detail User">
                            <i data-feather="eye" class="w-4 h-4"></i>
                        </button>
                        <button
                            onclick="openEditModal({{ json_encode($user) }}, '{{ $user->image ? asset('storage/' . $user->image) : '' }}')"
                            class="p-2 bg-yellow-400 text-white rounded-full hover:bg-yellow-500 transition-colors"
                            title="Edit User">
                            <i data-feather="edit" class="w-4 h-4"></i>
                        </button>
                        <button
                            onclick="openDeleteModal('{{ route('users.destroy', $user->id) }}', '{{ $user->nama }}')"
                            class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors"
                            title="Hapus User">
                            <i data-feather="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                {{-- colspan diubah dari 7 menjadi 6 --}}
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i data-feather="users" class="w-12 h-12 text-gray-300 mb-2"></i>
                        <p>Tidak ada pengguna yang ditemukan.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Bagian Paginasi --}}
@if ($users->hasPages())
    <div class="px-6 py-4 bg-white border-t">
        {{ $users->links() }}
    </div>
@endif

<script>
    document.querySelectorAll('#table-container .pagination a').forEach(link => {
        link.classList.add('pagination-link');
    });

    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>