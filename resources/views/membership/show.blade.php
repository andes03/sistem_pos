@extends('layouts.app') {{-- Sesuaikan dengan layout Anda --}}

@section('content')
<div class="container mx-auto py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detail Membership</h1>
        <a href="{{ route('membership.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors flex items-center gap-1">
            <i data-feather="arrow-left" class="w-5 h-5"></i> Kembali
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Informasi Membership</h2>
                <div class="space-y-2">
                    <p class="text-gray-700"><span class="font-medium">Nama:</span> <span class="font-bold text-lg">{{ $membership->nama }}</span></p>
                    <p class="text-gray-700"><span class="font-medium">Diskon:</span> {{ $membership->diskon }}%</p>
                    <p class="text-gray-700"><span class="font-medium">Minimal Transaksi:</span> Rp {{ number_format($membership->minimal_transaksi, 0, ',', '.') }}</p>
                </div>
            </div>
            
            <div>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Pelanggan dengan Membership Ini</h2>
                @if($pelanggan->isEmpty())
                    <p class="text-gray-500 italic">Tidak ada pelanggan dengan membership ini.</p>
                @else
                    <ul class="divide-y divide-gray-200">
                        @foreach($pelanggan as $p)
                        <li class="py-2 flex items-center justify-between">
                            <div class="flex-1">
                                <a href="{{ route('pelanggan.show', $p->id) }}" class="text-blue-600 hover:underline font-medium">{{ $p->nama }}</a>
                                <p class="text-sm text-gray-500">{{ $p->email }}</p>
                            </div>
                            <div>
                                <a href="{{ route('pelanggan.show', $p->id) }}" class="text-blue-500 hover:text-blue-700 transition-colors">
                                    <i data-feather="external-link" class="w-4 h-4 inline-block"></i>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        feather.replace();
    });
</script>
@endsection