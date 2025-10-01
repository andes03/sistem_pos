<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-blue-500">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Tanggal</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Pelanggan</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Kasir</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider">Total</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-100 uppercase tracking-wider">Metode Pembayaran</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-100 uppercase tracking-wider"></th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($transaksi as $item)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->isoFormat('D MMMM YYYY') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->pelanggan->nama ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->user->nama ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-right">Rp{{ number_format($item->total, 0, ',', '.') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ strtolower($item->metode_pembayaran) == 'tunai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucwords($item->metode_pembayaran ?? 'N/A') }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('transaksi.show', $item->id) }}"
                           class="p-2 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors"
                           title="Lihat Detail">
                            <i data-feather="eye" class="w-4 h-4"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i data-feather="shopping-bag" class="w-12 h-12 text-gray-300 mb-2"></i>
                        <p>Data transaksi tidak ditemukan</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@if($transaksi->hasPages())
    <div class="px-6 py-4 bg-white border-t">
        {{ $transaksi->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
    </div>
@endif

<script>
    // Menambahkan kelas 'pagination-link' ke semua tautan paginasi agar bisa dideteksi oleh JS
    document.querySelectorAll('#table-container .pagination a').forEach(link => {
        link.classList.add('pagination-link');
    });

    // Inisialisasi ulang ikon Feather setelah konten dimuat atau diubah
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>