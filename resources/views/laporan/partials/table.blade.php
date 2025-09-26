<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membership</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($transaksis as $transaksi)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ ($transaksis->currentPage() - 1) * $transaksis->perPage() + $loop->iteration }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $transaksi->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $transaksi->pelanggan->nama ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $transaksi->pelanggan->membership->nama ?? 'N/A' }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $transaksi->metode_pembayaran == 'tunai' ? 'bg-green-100 text-green-800' : 
                           ($transaksi->metode_pembayaran == 'transfer' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                        {{ ucfirst($transaksi->metode_pembayaran == 'ewallet' ? 'E-Wallet' : $transaksi->metode_pembayaran) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">Rp {{ number_format($transaksi->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500">Tidak ada data transaksi.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
        </tfoot>
    </table>
</div>

@if($transaksis->hasPages())
    <div class="px-6 py-4 bg-white border-t">
        {{ $transaksis->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
    </div>
@endif