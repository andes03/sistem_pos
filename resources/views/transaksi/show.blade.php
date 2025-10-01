@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Transaksi</h1>
                <p class="text-sm text-gray-500 mt-1">
                    ID Transaksi: #{{ str_pad($transaksi->id, 6, '0', STR_PAD_LEFT) }} | 
                    <span class="font-medium">
                        {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->locale('id')->isoFormat('H:mm:ss') }} WIB 
                    </span>
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('transaksi.print', $transaksi->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                    <i data-feather="printer" class="w-4 h-4 mr-2"></i>
                    Cetak
                </a>
                <a href="{{ route('transaksi.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                    <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        @php
            // Perhitungan diskon
            $subtotalKeseluruhan = $transaksi->detailTransaksi->sum('subtotal');
            $totalSetelahDiskon = $transaksi->total;
            $jumlahDiskon = $subtotalKeseluruhan - $totalSetelahDiskon;
            $persenDiskon = $subtotalKeseluruhan > 0 ? ($jumlahDiskon / $subtotalKeseluruhan) * 100 : 0;
            $membership = $transaksi->pelanggan->membership ?? null;
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
            
            {{-- Card Informasi Utama --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-white">Informasi Transaksi</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i data-feather="check-circle" class="w-3 h-3 mr-1"></i>
                            Selesai
                        </span>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Info Transaksi --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tanggal</p>
                                <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                            </div>
                            
                            {{-- BARU: Jam Transaksi --}}
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Waktu</p>
                                <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->locale('id')->isoFormat('HH:mm:ss') }} WIB</p>
                            </div>

                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Metode Pembayaran</p>
                                <div class="mt-1 flex items-center">
                                    @if($transaksi->metode_pembayaran == 'tunai')
                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i data-feather="dollar-sign" class="w-3 h-3 mr-1"></i>
                                            Tunai
                                        </div>
                                    @elseif($transaksi->metode_pembayaran == 'transfer')
                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i data-feather="credit-card" class="w-3 h-3 mr-1"></i>
                                            Transfer
                                        </div>
                                    @else
                                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i data-feather="smartphone" class="w-3 h-3 mr-1"></i>
                                            E-Wallet
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Info Pelanggan --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pelanggan</p>
                                <p class="mt-1 text-sm font-medium text-gray-900">{{ $transaksi->pelanggan->nama ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $transaksi->pelanggan->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                        
                        {{-- Info Kasir --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Dilayani Oleh</p>
                                <p class="mt-1 text-sm font-medium text-gray-900">{{ $transaksi->user->nama ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $transaksi->user->jabatan ?? 'Kasir' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Detail Produk dengan Ringkasan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Produk & Pembayaran</h3>
                    <p class="text-sm text-gray-500">{{ $transaksi->detailTransaksi->count() }} item yang dibeli</p>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transaksi->detailTransaksi as $detail)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $detail->produk->nama ?? 'Produk Dihapus' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp{{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $detail->jumlah }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-8 text-gray-500">Tidak ada produk dalam transaksi ini.</td>
                                </tr>
                            @endforelse
                            
                            {{-- Ringkasan Pembayaran dalam tabel --}}
                            <tr class="bg-gray-50 border-t-2 border-gray-300">
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Subtotal:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">Rp{{ number_format($subtotalKeseluruhan, 0, ',', '.') }}</td>
                            </tr>
                            
                            @if($jumlahDiskon > 0)
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-red-600">Diskon Mebership ({{ number_format($persenDiskon, 1) }}%):</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600">-Rp{{ number_format($jumlahDiskon, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            
                            <tr class="bg-green-50 border-t-2 border-green-300">
                                <td colspan="3" class="px-6 py-4 text-right text-base font-bold text-gray-900">Total Bayar:</td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-green-600">Rp{{ number_format($totalSetelahDiskon, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    feather.replace();
</script>
@endsection