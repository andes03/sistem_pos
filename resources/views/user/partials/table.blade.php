<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Foto</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($users as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    <div class="relative h-16 w-16">
                        @if($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}"
                                alt="{{ $user->nama }}" 
                                class="h-16 w-16 object-cover rounded-full"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="h-16 w-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg absolute top-0 left-0" style="display: none;">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}{{ strtoupper(substr(strstr($user->nama, ' '), 1, 1)) }}
                            </div>
                        @else
                            <div class="h-16 w-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                {{ strtoupper(substr($user->nama, 0, 1)) }}{{ strtoupper(substr(strstr($user->nama, ' '), 1, 1)) }}
                            </div>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->username }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->nama }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
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
                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
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