@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-4xl font-extrabold text-gray-900 mb-8 tracking-wide">Dashboard Analitik Bisnis</h1>

    {{-- Kartu Ringkasan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Card 1: Total Pendapatan --}}
        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col justify-between transform transition-transform duration-300 hover:scale-[1.03] hover:shadow-xl">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 rounded-full p-2.5 mr-3 flex-shrink-0">
                    <i data-feather="dollar-sign" class="w-5 h-5 text-blue-500"></i>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold uppercase text-xs tracking-wider">Total Pendapatan</p>
                    <p class="text-2xl font-bold mt-1 text-gray-900">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
            </div>
            <a href="{{ route('laporan.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center transition-colors duration-200 mt-2 group">
                Lihat Laporan
                <i data-feather="arrow-right" class="w-4 h-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1"></i>
            </a>
        </div>
        
        {{-- Card 2: Total Transaksi --}}
        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col justify-between transform transition-transform duration-300 hover:scale-[1.03] hover:shadow-xl">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 rounded-full p-2.5 mr-3 flex-shrink-0">
                    <i data-feather="shopping-bag" class="w-5 h-5 text-green-500"></i>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold uppercase text-xs tracking-wider">Total Transaksi</p>
                    <p class="text-2xl font-bold mt-1 text-gray-900">{{ $totalTransaksi }}</p>
                </div>
            </div>
            <a href="{{ route('transaksi.index') }}" class="text-green-600 hover:text-green-800 text-sm font-semibold flex items-center transition-colors duration-200 mt-2 group">
                Lihat Transaksi
                <i data-feather="arrow-right" class="w-4 h-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1"></i>
            </a>
        </div>
        
        {{-- Card 3: Total Pelanggan --}}
        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col justify-between transform transition-transform duration-300 hover:scale-[1.03] hover:shadow-xl">
            <div class="flex items-center mb-4">
                <div class="bg-purple-100 rounded-full p-2.5 mr-3 flex-shrink-0">
                    <i data-feather="users" class="w-5 h-5 text-purple-500"></i>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold uppercase text-xs tracking-wider">Total Pelanggan</p>
                    <p class="text-2xl font-bold mt-1 text-gray-900">{{ $totalPelanggan }}</p>
                </div>
            </div>
            <a href="{{ route('pelanggan.index') }}" class="text-purple-600 hover:text-purple-800 text-sm font-semibold flex items-center transition-colors duration-200 mt-2 group">
                Lihat Pelanggan
                <i data-feather="arrow-right" class="w-4 h-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1"></i>
            </a>
        </div>
        
        {{-- Card 4: Total Produk --}}
        <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col justify-between transform transition-transform duration-300 hover:scale-[1.03] hover:shadow-xl">
            <div class="flex items-center mb-4">
                <div class="bg-pink-100 rounded-full p-2.5 mr-3 flex-shrink-0">
                    <i data-feather="tag" class="w-5 h-5 text-pink-500"></i>
                </div>
                <div>
                    <p class="text-gray-500 font-semibold uppercase text-xs tracking-wider">Total Produk</p>
                    <p class="text-2xl font-bold mt-1 text-gray-900">{{ $totalProduk }}</p>
                </div>
            </div>
            <a href="{{ route('produk.index') }}" class="text-pink-600 hover:text-pink-800 text-sm font-semibold flex items-center transition-colors duration-200 mt-2 group">
                Lihat Produk
                <i data-feather="arrow-right" class="w-4 h-4 ml-1 transform transition-transform duration-200 group-hover:translate-x-1"></i>
            </a>
        </div>
    </div>

    {{-- Main Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Large Revenue Chart (2 columns) --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 lg:col-span-2 h-[500px]">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 sm:mb-0">Pendapatan Bulanan Tahun {{ $selectedYear }}</h2>
                <form id="year-filter-form" action="{{ route('dashboard') }}" method="GET">
                    <select name="tahun" id="tahunFilter" class="border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($availableYears as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="h-[380px]">
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>

        {{-- Stacked Side Charts (1 column) --}}
        <div class="lg:col-span-1 space-y-6 h-[500px]">
            {{-- Produk Terlaris (Top) --}}
            <div class="bg-white rounded-2xl shadow-xl p-4 h-[235px]">
                <div class="flex flex-col justify-between items-start mb-3">
                    <h2 class="text-lg font-bold text-gray-800 mb-2">Produk Terlaris</h2>
                    <form id="category-filter-form" action="{{ route('dashboard') }}" method="GET" class="w-full">
                        <select name="kategori_id" id="kategoriFilter" class="border-gray-300 rounded-md shadow-sm text-xs focus:ring-blue-500 focus:border-blue-500 w-full">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="h-[150px]">
                    <canvas id="produkTerlarisChart"></canvas>
                </div>
            </div>

            {{-- Metode Pembayaran (Bottom) --}}
            <div class="bg-white rounded-2xl shadow-xl p-4 h-[235px]">
                <div class="flex flex-col justify-between items-start mb-3">
                    <h2 class="text-lg font-bold text-gray-800 mb-2">Metode Pembayaran</h2>
                    <div class="flex items-center gap-2 w-full">
                        <select name="bulan" id="bulanFilter" class="border-gray-300 rounded-md shadow-sm text-xs focus:ring-blue-500 focus:border-blue-500 flex-1">
                            @foreach ($bulanOptions as $bulan)
                                <option value="{{ $bulan['value'] }}" {{ $selectedMonth == $bulan['value'] ? 'selected' : '' }}>
                                    {{ $bulan['label'] }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-xs text-gray-500 whitespace-nowrap">
                            <i data-feather="bar-chart-2" class="w-3 h-3 inline"></i>
                        </div>
                    </div>
                </div>
                <div class="h-[150px]">
                    <canvas id="metodePembayaranChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Diagram Lingkaran & Tabel Kecil (4 Kolom) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        
        {{-- Diagram Lingkaran Membership --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col items-center">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Distribusi Membership</h2>
            <div class="w-full max-w-xs mx-auto">
                <canvas id="membershipChart"></canvas>
            </div>
        </div>

        {{-- Distribusi Produk per Kategori --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 flex flex-col items-center">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Produk per Kategori</h2>
            <div class="w-full max-w-xs mx-auto">
                <canvas id="kategoriChart"></canvas>
            </div>
        </div>
        
        {{-- Produk Stok Menipis --}}
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Stok Menipis</h2>
                <a href="{{ route('produk.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    {{-- DIUBAH: Menggunakan loop @for untuk memastikan selalu ada 5 baris --}}
                    <tbody class="bg-white divide-y divide-gray-200">
                        @for ($i = 0; $i < 5; $i++)
                            <tr class="odd:bg-white even:bg-gray-50">
                                @if (isset($stokMenipis[$i]))
                                    @php $produk = $stokMenipis[$i]; @endphp
                                    {{-- Baris dengan data produk --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-gray-900" title="{{ $produk->nama }}">{{ \Illuminate\Support\Str::limit($produk->nama, 20) }}</span>
                                                <span class="text-xs text-gray-500 mt-1">{{ $produk->kategori->nama }}</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-bold text-red-600">{{ $produk->stok }}</span>
                                                <span class="text-xs text-red-500 block">Sisa</span>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    {{-- Baris kosong sebagai placeholder --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col">
                                                <span class="text-sm text-gray-400">-</span>
                                                <span class="text-xs text-gray-400 mt-1">-</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-bold text-gray-400">-</span>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="bg-white rounded-2xl shadow-xl p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Transaksi Terakhir</h2>
                <a href="{{ route('transaksi.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200">Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transaksiTerbaru as $transaksi)
                            <tr class="odd:bg-white even:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-between w-full">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-semibold text-gray-900" title="{{ $transaksi->pelanggan->nama ?? 'Pelanggan Umum' }}">{{ \Illuminate\Support\Str::limit($transaksi->pelanggan->nama ?? 'Umum', 15) }}</span>
                                            <span class="text-xs text-gray-600 mt-1">{{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('transaksi.show', $transaksi->id) }}" class="inline-flex items-center justify-center text-blue-600 hover:text-blue-900">
                                                <i data-feather="eye" class="w-4 h-4"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            @for ($i = 0; $i < 5; $i++)
                                <tr class="odd:bg-white even:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <span class="text-sm text-gray-400">-</span>
                                    </td>
                                </tr>
                            @endfor
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<script>
    const chartData = {
        pendapatanLabels: @json($pendapatanLabels),
        pendapatanValues: @json($pendapatanValues),
        produkTerlarisLabels: @json($produkTerlarisLabels),
        produkTerlarisValues: @json($produkTerlarisValues),
        membershipLabels: @json($membershipLabels),
        membershipValues: @json($membershipValues),
        kategoriLabels: @json($kategoriLabels),
        kategoriValues: @json($kategoriValues),
        metodePembayaranLabels: @json($metodePembayaranLabels),
        metodePembayaranJumlahTransaksi: @json($metodePembayaranJumlahTransaksi),
        metodePembayaranTotalUang: @json($metodePembayaranTotalUang),
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>
    feather.replace();
</script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection