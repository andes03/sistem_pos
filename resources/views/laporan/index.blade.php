@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Laporan Transaksi</h1>

    <div class="flex flex-col md:flex-row gap-6">

        {{-- Kolom Kiri: Wadah untuk Tabel --}}
        <div class="flex-grow md:w-3/4">
            <div class="overflow-x-auto bg-white shadow-md rounded-lg" id="table-container">
                @include('laporan.partials.table', ['transaksis' => $transaksis])
            </div>
        </div>

        {{-- Kolom Kanan: Filter dan Cetak --}}
        <div class="md:w-1/4">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Filter Laporan</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="fromDate" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                        <input type="date" 
                               id="fromDate" 
                               name="from_date" 
                               value="{{ $from_date ?? '' }}"
                               class="w-full pl-4 pr-4 py-2 border border-gray-400 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    </div>

                    <div>
                        <label for="toDate" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                        <input type="date" 
                               id="toDate" 
                               name="to_date" 
                               value="{{ $to_date ?? '' }}"
                               class="w-full pl-4 pr-4 py-2 border border-gray-400 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                    </div>
                    
                    <div>
                        <label for="membershipId" class="block text-sm font-medium text-gray-700 mb-1">Membership</label>
                        <select id="membershipId" name="membership_id"
                                class="w-full pl-4 pr-10 py-2 border border-gray-400 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                            <option value="">Semua Membership</option>
                            @foreach($memberships as $membership)
                                <option value="{{ $membership->id }}" @if(($membership_id ?? '') == $membership->id) selected @endif>
                                    {{ $membership->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="metodePembayaranId" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select id="metodePembayaranId" name="metode_pembayaran"
                                class="w-full pl-4 pr-10 py-2 border border-gray-400 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                            <option value="">Semua Metode</option>
                            @foreach($metodePembayaran as $key => $label)
                                <option value="{{ $key }}" @if(($metode_pembayaran ?? '') == $key) selected @endif>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-2 mt-6">
                    <button id="resetFilterBtn" 
                            class="w-full py-2.5 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                        Reset Filter
                    </button>
                    <a href="{{ route('laporan.cetak-pdf', ['from_date' => $from_date, 'to_date' => $to_date, 'membership_id' => $membership_id, 'metode_pembayaran' => $metode_pembayaran]) }}" 
                       class="w-full py-2 bg-red-500 text-white text-center rounded-md hover:bg-red-600 transition-colors flex items-center justify-center gap-2"
                       id="cetakPdfBtn">
                        <i data-feather="printer" class="w-5 h-5"></i> Cetak PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
<script src="{{ asset('js/laporan.js') }}"></script>
@endsection