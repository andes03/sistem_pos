<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-blue-500">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Nama Kategori</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Deskripsi</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Dibuat Pada</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">Aksi</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($kategoris as $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->nama }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 max-w-xs overflow-hidden truncate">{{ $item->deskripsi ?? '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->created_at->format('d M Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                        <button
                            onclick="openDetailModal('{{ route('kategori.show', $item->id) }}')"
                            class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors"
                            title="Lihat Detail Kategori">
                            <i data-feather="eye" class="w-4 h-4"></i>
                        </button>
                        
                        <button
                            onclick="openEditModal({{ json_encode($item) }})"
                            class="p-2 bg-yellow-400 text-white rounded-full hover:bg-yellow-500 transition-colors"
                            title="Edit Kategori">
                            <i data-feather="edit" class="w-4 h-4"></i>
                        </button>
                        
                        <button
                            onclick="openDeleteModal('{{ route('kategori.destroy', $item->id) }}', '{{ $item->nama }}')"
                            class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-colors"
                            title="Hapus Kategori">
                            <i data-feather="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i data-feather="tag" class="w-12 h-12 text-gray-300 mb-2"></i>
                        <p>Tidak ada kategori yang ditemukan.</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Bagian Paginasi --}}
@if($kategoris->hasPages())
    <div class="px-6 py-4 bg-white border-t">
        {{ $kategoris->links('vendor.pagination.tailwind') }}
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