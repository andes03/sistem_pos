<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-blue-500">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Nama</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Nomor HP</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Membership</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Alamat</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($pelanggan as $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->email ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->nomor_hp ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ $item->membership ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $item->membership->nama ?? '-' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs truncate" title="{{ $item->alamat }}">{{ $item->alamat }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                        {{-- Tombol Lihat Detail --}}
                        <a href="{{ route('pelanggan.show', $item->id) }}"
                            class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors"
                            title="Lihat Detail">
                            <i data-feather="eye" class="w-4 h-4"></i>
                        </a>
    
                        <button onclick='openEditModal({{ json_encode($item) }})'
                            class="p-2 bg-yellow-400 text-white rounded-full hover:bg-yellow-500 transition-colors"
                            title="Edit Pelanggan">
                            <i data-feather="edit" class="w-4 h-4"></i>
                        </button>
                        
                        <button onclick='openDeleteModal({{ json_encode($item) }})'
                            class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors"
                            title="Hapus Pelanggan">
                            <i data-feather="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i data-feather="users" class="w-12 h-12 text-gray-300 mb-2"></i>
                        <p>Data pelanggan tidak ditemukan.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if($pelanggan->hasPages())
    <div class="px-6 py-4 bg-white border-t">
        {{ $pelanggan->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
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