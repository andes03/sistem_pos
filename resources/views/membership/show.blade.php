@extends('layouts.app') {{-- Sesuaikan dengan layout Anda --}}

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Membership</h1>
                <p class="text-sm text-gray-500 mt-1">
                </p>
            </div>
            <a href="{{ route('membership.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm">
                <i data-feather="arrow-left" class="w-4 h-4 mr-2"></i>
                Kembali
            </a>
        </div>

        {{-- Grid Utama: Informasi Membership & Daftar Pelanggan --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Card Informasi Membership (Kolom 1/3) --}}
            <div class="lg:col-span-1 bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden h-fit">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-600">
                    <h2 class="text-xl font-semibold text-white flex items-center gap-2">
                        <i data-feather="award" class="w-5 h-5"></i> Detail Tingkatan
                    </h2>
                </div>
                
                <div class="p-6 space-y-5">
                    
                    {{-- Nama Membership --}}
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Nama Tingkatan</p>
                        <p class="mt-1 text-2xl font-extrabold text-gray-900">{{ $membership->nama }}</p>
                    </div>

                    {{-- Benefit Diskon --}}
                    <div class="flex items-center justify-between border-t pt-4">
                        <p class="text-sm font-medium text-gray-700 flex items-center gap-1">
                            <i data-feather="percent" class="w-4 h-4 text-amber-500"></i> Persentase Diskon
                        </p>
                        <span class="text-xl font-bold text-green-600">{{ $membership->diskon }}%</span>
                    </div>

                    {{-- Minimal Transaksi --}}
                    <div class="flex items-center justify-between border-t pt-4">
                        <p class="text-sm font-medium text-gray-700 flex items-center gap-1">
                            <i data-feather="trending-up" class="w-4 h-4 text-blue-500"></i> Minimal Transaksi
                        </p>
                        <span class="text-lg font-bold text-gray-900">Rp {{ number_format($membership->minimal_transaksi, 0, ',', '.') }}</span>
                    </div>
                    
                    {{-- PENJELASAN BARU DITAMBAHKAN DI BAWAH INI --}}
                    <div class="pt-2">
                        <p class="text-xs text-gray-500 italic">
                            Akumulasi transaksaksi pelanggan  harus mencapai 
                            <span class="font-semibold text-gray-700">Rp {{ number_format($membership->minimal_transaksi, 0, ',', '.') }}</span> 
                            untuk mencapai membership 
                            <span class="font-semibold text-blue-600">{{ $membership->nama }}</span>.
                        </p>
                    </div>
                    {{-- AKHIR PENJELASAN BARU --}}

                </div>
            </div>

            {{-- Card Daftar Pelanggan (Kolom 2/3) --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i data-feather="users" class="w-5 h-5 text-gray-500"></i> Pelanggan ({{ $pelanggan->count() }})
                    </h2>
                    <p class="text-sm text-gray-500">Daftar pelanggan yang memiliki tingkat membership ini.</p>
                </div>

                <div class="p-4">
                    @if($pelanggan->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            <i data-feather="inbox" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                            <p class="text-lg font-medium">Tidak ada pelanggan</p>
                            <p class="text-sm">Belum ada pelanggan yang mencapai tingkat membership ini.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($pelanggan as $p)
                            <li class="py-3 px-2 hover:bg-gray-50 transition-colors flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-500 text-white rounded-full w-10 h-10 flex items-center justify-center text-lg font-bold flex-shrink-0">
                                        {{ strtoupper(substr($p->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('pelanggan.show', $p->id) }}" class="text-gray-900 hover:text-blue-600 font-medium transition-colors">{{ $p->nama }}</a>
                                        <p class="text-sm text-gray-500">{{ $p->email ?? $p->nomor_hp ?? 'No contact info' }}</p>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('pelanggan.show', $p->id) }}" title="Lihat Detail" 
                                       class="text-blue-500 hover:text-blue-700 transition-colors p-2 rounded-full hover:bg-blue-50">
                                        <i data-feather="arrow-right" class="w-4 h-4 inline-block"></i>
                                    </a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        feather.replace();
    });
</script>
@endsection