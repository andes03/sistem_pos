@extends('layouts.app') {{-- Pastikan ini sesuai dengan layout utama Anda --}}

@section('content')
<div class="container mx-auto py-8">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('pelanggan.index') }}" class="text-gray-600 hover:text-gray-800 transition-colors">
            <i data-feather="arrow-left" class="w-6 h-6"></i>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Detail Pelanggan</h1>
    </div>

    {{-- Kartu Detail Pelanggan --}}
    <div class="bg-white shadow-lg rounded-lg p-8 mb-8">
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h2 class="text-2xl font-semibold text-gray-700">{{ $pelanggan->nama }}</h2>
            <div class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                {{ $pelanggan->membership->nama }}
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600"><strong class="font-medium text-gray-800">Email:</strong> {{ $pelanggan->email }}</p>
                <p class="text-gray-600 mt-2"><strong class="font-medium text-gray-800">Nomor HP:</strong> {{ $pelanggan->nomor_hp }}</p>
                <p class="text-gray-600 mt-2"><strong class="font-medium text-gray-800">Alamat:</strong> {{ $pelanggan->alamat }}</p>
            </div>
            <div>
                <p class="text-gray-600"><strong class="font-medium text-gray-800">Total Transaksi:</strong> <span class="text-blue-600 font-bold">{{ $jumlahTransaksi }}</span></p>
                {{-- Anda bisa menambahkan detail lain seperti total belanja, diskon yang didapat, dll. --}}
            </div>
        </div>
    </div>
    
    {{-- Riwayat Transaksi --}}
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Riwayat Transaksi</h2>

    @if($riwayatTransaksi->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500">
            <i data-feather="info" class="w-10 h-10 mx-auto mb-3 text-gray-400"></i>
            <p>Pelanggan ini belum memiliki riwayat transaksi.</p>
        </div>
    @else
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ID Transaksi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pembayaran
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Membership Saat Transaksi
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Detail Produk
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($riwayatTransaksi as $transaksi)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $transaksi->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->isoFormat('D MMMM YYYY') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                Rp{{ number_format($transaksi->total, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaksi->metode_pembayaran }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $transaksi->pelanggan->membership->nama ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-sm overflow-hidden text-ellipsis">
                                <ul class="list-disc list-inside">
                                    @foreach($transaksi->detailTransaksi as $detail)
                                        <li>{{ $detail->produk->nama }} (x{{ $detail->jumlah }})</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        feather.replace();
    });
</script>
@endsection