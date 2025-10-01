{{-- Container utama dengan padding horizontal dan margin vertikal (atas & bawah) --}}
<div class="px-4 md:px-6 lg:px-8 mt-6 mb-6">

    {{-- Grid Layout untuk Produk --}}
    {{-- Menggunakan layout responsif: 2 kolom di layar kecil, hingga 5 kolom di layar besar --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @forelse($produk as $item)
            {{-- Kartu Produk --}}
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden border border-gray-200 product-card">
                
                {{-- Gambar Produk --}}
                <div class="relative group aspect-square">
                    {{-- Loading Placeholder --}}
                    <div class="image-loading absolute inset-0 bg-gray-200 flex items-center justify-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-400"></div>
                    </div>
                    
                    <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/200x200' }}"
                        alt="{{ $item->nama }}" 
                        class="product-image w-full h-full object-cover group-hover:scale-105 transition-transform duration-200 opacity-0"
                        onload="handleImageLoad(this)"
                        onerror="handleImageError(this)">
                    
                    {{-- Badge Stok di kanan atas --}}
                    <div class="absolute top-1 right-1 z-10">
                        <span class="px-1.5 py-0.5 {{ $item->stok > 0 ? 'bg-green-500' : 'bg-red-500' }} text-white text-xs font-medium rounded shadow-sm">
                            Stok: {{ number_format($item->stok, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                
                {{-- Konten Card --}}
                <div class="p-3">
                    {{-- Nama Produk --}}
                    <h3 class="text-sm font-medium text-gray-900 mb-1 truncate" title="{{ $item->nama }}">
                        {{ $item->nama }}
                    </h3>
                    
                    {{-- Kategori --}}
                    <div class="mb-2">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-indigo-100 text-indigo-700">
                            {{ $item->kategori->nama ?? 'Tidak Ada' }}
                        </span>
                    </div>
                    
                    {{-- Harga --}}
                    <div class="mb-3">
                        <span class="text-lg font-bold text-green-600">
                            Rp{{ number_format($item->harga, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-1">
                        <button
                            onclick="openDetailModal('{{ route('produk.show', $item->id) }}')"
                            class="flex-1 px-2 py-1.5 bg-blue-500 text-white text-xs rounded hover:bg-blue-600 transition-colors flex items-center justify-center"
                            title="Detail">
                            <i data-feather="eye" class="w-3 h-3 mr-1"></i>
                            Detail
                        </button>
                        
                        <button
                            onclick="openEditModal({{ json_encode($item) }}, '{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/200x200' }}')"
                            class="px-2 py-1.5 bg-yellow-400 text-white text-xs rounded hover:bg-yellow-500 transition-colors"
                            title="Edit">
                            <i data-feather="edit" class="w-3 h-3"></i>
                        </button>
                        
                        <button
                            onclick="openDeleteModal('{{ route('produk.destroy', $item->id) }}', '{{ $item->nama }}')"
                            class="px-2 py-1.5 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition-colors"
                            title="Hapus">
                            <i data-feather="trash-2" class="w-3 h-3"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            {{-- Empty State --}}
            {{-- Menggunakan col-span-full untuk memastikan pesan di tengah --}}
            <div class="col-span-full flex flex-col items-center justify-center py-8 text-gray-500">
                <div class="bg-gray-100 rounded-full p-4 mb-3">
                    <i data-feather="box" class="w-8 h-8 text-gray-400"></i>
                </div>
                <h3 class="text-base font-medium text-gray-900 mb-1">Tidak ada produk</h3>
                <p class="text-sm text-gray-500 text-center">
                    Belum ada produk yang ditambahkan.
                </p>
            </div>
        @endforelse
    </div>

</div>

{{-- Bagian Paginasi --}}
@if($produk->hasPages())
    {{-- Menambahkan padding horizontal yang sama seperti container grid --}}
    <div class="mt-6 px-4 py-3 bg-white border border-gray-200 rounded-lg mx-4 md:mx-6 lg:mx-8">
        {{ $produk->links('vendor.pagination.tailwind') }}
    </div>
@endif


{{-- Script untuk Feather Icons --}}
<script>
    // Fungsi untuk menangani ketika gambar berhasil dimuat
    function handleImageLoad(img) {
        const loadingDiv = img.parentNode.querySelector('.image-loading');
        if (loadingDiv) {
            loadingDiv.style.opacity = '0';
            setTimeout(() => {
                loadingDiv.style.display = 'none';
            }, 300);
        }
        img.style.opacity = '1';
        img.style.transition = 'opacity 0.3s ease';
    }
    
    // Fungsi untuk menangani ketika gambar gagal dimuat
    function handleImageError(img) {
        const loadingDiv = img.parentNode.querySelector('.image-loading');
        if (loadingDiv) {
            loadingDiv.innerHTML = '<div class="text-gray-400 text-xs text-center">Gambar<br>tidak tersedia</div>';
            loadingDiv.classList.remove('animate-spin');
        }
        img.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Cek gambar yang sudah dimuat sebelum script ini dijalankan
        document.querySelectorAll('.product-image').forEach(img => {
            if (img.complete && img.naturalHeight !== 0) {
                handleImageLoad(img);
            }
        });
    });
</script>

<style>
    /* Memastikan aspect ratio 1:1 untuk container gambar */
    .aspect-square {
        aspect-ratio: 1 / 1;
    }
    
    /* Fallback untuk browser yang tidak mendukung aspect-ratio */
    .aspect-square::before {
        content: '';
        display: block;
        padding-top: 100%;
    }
    
    .aspect-square > * {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    /* Custom hover effects */
    .group:hover .group-hover\:scale-105 {
        transform: scale(1.05);
    }
    
    /* Loading animation */
    .image-loading {
        transition: opacity 0.3s ease;
    }
</style>