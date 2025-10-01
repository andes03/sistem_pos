{{-- Container utama --}}
<div class="px-4 md:px-6 lg:px-8 mt-6 mb-6">

    {{-- Grid Layout Membership --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($memberships as $item)
            {{-- Card Membership dengan outline biru --}}
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border-2 border-gray-200">
                
                {{-- Header Card (Nama Membership) --}}
                <div class="bg-blue-600 text-white px-4 py-2">
                    <h3 class="text-sm font-semibold truncate" title="{{ $item->nama }}">
                        {{ $item->nama }}
                    </h3>
                </div>
                
                {{-- Konten Card --}}
                <div class="p-4">
                    {{-- Diskon --}}
                    <div class="mb-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-green-100 text-green-700">
                            Diskon: {{ $item->diskon }}%
                        </span>
                    </div>

                    {{-- Minimal Transaksi --}}
                    <div class="mb-3">
                        <span class="text-sm text-gray-600">Minimal Transaksi:</span><br>
                        <span class="text-base font-bold text-blue-600">
                            Rp {{ number_format($item->minimal_transaksi, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-2 mt-4">
                        {{-- Detail --}}
                        <a href="{{ route('membership.show', $item->id) }}"
                            class="flex-1 px-2 py-1.5 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition-colors flex items-center justify-center"
                            title="Detail">
                            <i data-feather="eye" class="w-3 h-3 mr-1"></i>
                            Detail
                        </a>

                        {{-- Edit --}}
                        <button onclick='openEditModal(@json($item))'
                            class="px-2 py-1.5 bg-yellow-400 text-white text-xs rounded hover:bg-yellow-500 transition-colors flex items-center justify-center"
                            title="Edit">
                            <i data-feather="edit" class="w-3 h-3"></i>
                        </button>

                        {{-- Hapus --}}
                        <button onclick='openDeleteModal(@json($item))'
                            class="px-2 py-1.5 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition-colors flex items-center justify-center"
                            title="Hapus">
                            <i data-feather="trash-2" class="w-3 h-3"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            <div class="col-span-full flex flex-col items-center justify-center py-8 text-gray-500">
                <div class="bg-gray-100 rounded-full p-4 mb-3">
                    <i data-feather="user" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-base font-medium text-gray-900 mb-1">Tidak ada membership</h3>
                <p class="text-sm text-gray-500 text-center">
                    Belum ada membership yang ditambahkan.
                </p>
            </div>
        @endforelse
    </div>
</div>

{{-- Bagian Paginasi --}}
@if($memberships->hasPages())
    <div class="mt-6 px-4 py-3 bg-white border border-gray-200 rounded-lg mx-4 md:mx-6 lg:mx-8">
        {{ $memberships->links('vendor.pagination.tailwind') }}
    </div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
