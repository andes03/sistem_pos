@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header dengan tombol kembali --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pelanggan </h1>
               
            </div>
            <a href="{{ route('pelanggan.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i>
                Kembali
            </a>
        </div>

        {{-- Card Informasi Pelanggan dan Statistik --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            {{-- Card Informasi Dasar Pelanggan (2/3 lebar) --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white">Kartu Pelanggan</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white 
                            {{ $pelanggan->membership ? 'bg-green-500 hover:bg-green-600' : 'bg-gray-400 hover:bg-gray-500' }} transition-colors">
                            <i data-feather="award" class="w-3 h-3 mr-1"></i>
                            {{ $pelanggan->membership->nama ?? 'Non-Member' }}
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nama & Email --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nama Lengkap</p>
                                <p class="mt-1 text-lg font-bold text-gray-900">{{ $pelanggan->nama }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Email</p>
                                <p class="mt-1 text-sm text-gray-700">{{ $pelanggan->email ?? '-' }}</p>
                            </div>
                        </div>
                        
                        {{-- No HP & Alamat --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nomor HP</p>
                                <p class="mt-1 text-sm text-gray-700">{{ $pelanggan->nomor_hp ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Alamat</p>
                                <p class="mt-1 text-sm text-gray-700">{{ $pelanggan->alamat ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Statistik (1/3 lebar) --}}
            <div class="lg:col-span-1 space-y-4">
                {{-- Total Transaksi --}}
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                            <p class="text-3xl font-extrabold text-blue-600 mt-1">{{ $jumlahTransaksi }}</p>
                            <p class="text-xs text-gray-500 mt-1">kali transaksi</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                            <i data-feather="shopping-cart" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>

                {{-- Total Belanja --}}
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Akumulasi Belanja</p>
                            <p class="text-2xl font-extrabold text-green-600 mt-1">Rp {{ number_format($totalAkumulasiTransaksi ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-1">sepanjang waktu</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full text-green-600">
                            <i data-feather="dollar-sign" class="w-6 h-6"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Card Riwayat Transaksi --}}
        <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-bold text-gray-900">Riwayat Transaksi Terakhir</h2>
                <p class="text-sm text-gray-500">{{ $jumlahTransaksi }} total transaksi tercatat</p>
            </div>
            
            @if($riwayatTransaksi->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Transaksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail Produk</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($riwayatTransaksi as $transaksi)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                        #{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-700">
                                        Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @php
                                            $method = strtolower($transaksi->metode_pembayaran);
                                            $class = '';
                                            $icon = '';
                                            if ($method === 'tunai') {
                                                $class = 'bg-green-100 text-green-800';
                                                $icon = 'dollar-sign';
                                            } elseif ($method === 'transfer') {
                                                $class = 'bg-blue-100 text-blue-800';
                                                $icon = 'credit-card';
                                            } else {
                                                $class = 'bg-purple-100 text-purple-800';
                                                $icon = 'smartphone';
                                            }
                                        @endphp
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }} items-center gap-1">
                                            <i data-feather="{{ $icon }}" class="w-3 h-3"></i>
                                            {{ ucfirst($transaksi->metode_pembayaran) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <ul class="list-disc list-inside space-y-0.5">
                                            @foreach($transaksi->detailTransaksi as $detail)
                                                <li class="text-xs text-gray-600">
                                                    {{ $detail->produk->nama ?? 'Produk tidak tersedia' }} <span class="font-medium">({{ $detail->jumlah }}x)</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i data-feather="inbox" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                    <p class="text-lg font-medium">Belum ada riwayat transaksi</p>
                    <p class="text-sm">Pelanggan ini belum melakukan pembelian apapun.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Memuat Feather Icons --}}
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        feather.replace();
    });
</script>
@endsection