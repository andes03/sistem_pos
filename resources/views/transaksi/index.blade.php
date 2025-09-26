@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    
    {{-- JUDUL Halaman (Dipastikan di Kiri) --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Manajemen Transaksi</h1>

    @if(session('success'))
        <div class="mb-4 px-4 py-3 bg-green-200 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search Bar dan Tombol Tambah Transaksi (Sejajar di bawah judul) --}}
    <div class="mb-6 flex items-center gap-4">
        {{-- Search Bar (Takes most of the width) --}}
        <div class="relative flex-grow">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                <i data-feather="search" class="w-5 h-5"></i>
            </div>
            <input type="text"
                id="searchInput"
                name="search"
                placeholder="Cari pelanggan atau kasir..."
                value="{{ $search ?? '' }}"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                autocomplete="off"/>
        </div>

        {{-- Tombol untuk menambah transaksi baru --}}
        <a href="{{ route('transaksi.create') }}" class="flex-shrink-0 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition-colors shadow">
            + Tambah Transaksi
        </a>
    </div>
    
    <div class="flex flex-col md:flex-row gap-6">

        {{-- Kolom Kiri: Tabel --}}
        <div class="flex-grow md:w-3/4">
            <div class="overflow-x-auto bg-white shadow-md rounded-lg" id="table-container">
                @include('transaksi.partials.table', ['transaksi' => $transaksi])
            </div>
        </div>

        {{-- Kolom Kanan: Filter dan Reset --}}
        <div class="md:w-1/4">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Filter Transaksi</h3>
                
                <div class="space-y-4">
                    {{-- Filter Metode Pembayaran --}}
                    <div>
                        <label for="paymentMethodFilter" class="block text-sm font-medium text-gray-700 mb-1">Metode Pembayaran</label>
                        <select id="paymentMethodFilter" name="metode_pembayaran"
                                class="w-full pl-4 pr-10 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                            <option value="">Semua Metode</option>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method }}" @if(($paymentMethodFilter ?? '') == $method) selected @endif>
                                    {{ ucwords($method) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Tanggal --}}
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                        <div>
                            <label for="dateFrom" class="block text-xs font-medium text-gray-500 mb-1">Tgl. Mulai</label>
                            <input type="date" 
                                id="dateFrom" 
                                name="date_from" 
                                value="{{ $dateFrom ?? '' }}"
                                class="w-full pl-4 pr-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>
                        <div>
                            <label for="dateTo" class="block text-xs font-medium text-gray-500 mb-1">Tgl. Selesai</label>
                            <input type="date" 
                                id="dateTo" 
                                name="date_to" 
                                value="{{ $dateTo ?? '' }}"
                                class="w-full pl-4 pr-4 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>
                    </div>
                </div>

                {{-- Tombol Reset Filter --}}
                <div class="mt-6">
                    <button id="resetFilterBtn" 
                            class="w-full px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">
                        Reset Filter
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.0/dist/feather.min.js"></script>
<script src="{{ asset('js/transaksi.js') }}"></script>
@endsection