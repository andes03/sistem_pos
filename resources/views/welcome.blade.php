<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Balcos Compound - Coffee & Eatery</title>
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@500&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; background-color: #f5f5f5; }
        h1, h2 { font-family: 'Oswald', sans-serif; }
        .text-brown-custom { color: #4b3621; }
        .bg-brown-custom { background-color: #4b3621; }
        .hover\:bg-brown-dark:hover { background-color: #3b2a1a; }
        .product-image { width: 100%; height: 100%; object-fit: cover; }
        .aspect-square { aspect-ratio: 1 / 1; }
        .image-loading { transition: opacity 0.3s ease; }
        
        /* CSS UNTUK OVERLAY "MEMUAT..." SUDAH DIHAPUS */

        .filter-btn {
            transition: all 0.2s ease-in-out;
            border: 1px solid #d1d5db;
        }
        .filter-btn:hover {
            border-color: #4b3621;
            background-color: #f9fafb;
        }
        .filter-btn.filter-btn-active {
            background-color: #4b3621;
            color: #ffffff;
            border-color: #4b3621;
            font-weight: 600;
        }
        .header-bg-slideshow {
            position: absolute;
            inset: 0;
            z-index: 1;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            animation: kenburns 30s linear infinite;
            will-change: transform, opacity;
        }
        .header-bg-slideshow:nth-child(1) { background-image: url('{{ asset("gambar4.png") }}'); }
        .header-bg-slideshow:nth-child(2) { background-image: url('{{ asset("gambar5.png") }}'); animation-delay: 10s; }
        .header-bg-slideshow:nth-child(3) { background-image: url('{{ asset("gambar6.png") }}'); animation-delay: 20s; }
        @keyframes kenburns {
            0% { opacity: 0; transform: scale(1.1); }
            10% { opacity: 1; }
            33.3% { opacity: 1; transform: scale(1); }
            43.3% { opacity: 0; }
            100% { opacity: 0; }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animated-section {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

<nav class="bg-white shadow-sm sticky top-0 z-40">
    <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <div class="text-2xl font-bold text-brown-custom tracking-widest uppercase">Sebelas Coffee</div>
        <div>
            <a href="{{ route('login') }}" class="px-4 py-2 bg-brown-custom text-white rounded-lg hover:bg-brown-dark transition-colors">Login</a>
        </div>
    </div>
</nav>

<header class="relative py-24 bg-gray-900 overflow-hidden">
    <div class="header-bg-slideshow"></div>
    <div class="header-bg-slideshow"></div>
    <div class="header-bg-slideshow"></div>
    
    <div class="absolute inset-0 bg-gray-900 opacity-70 z-2"></div>
    
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold text-white mb-4 tracking-wider">Sebelas Coffee</h1>
        <p class="text-xl text-gray-200 mb-8">Coffee & Eatery</p>
        <a href="#menu-section" class="inline-block bg-white text-brown-custom font-bold px-8 py-3 rounded-full uppercase tracking-wider hover:bg-gray-200 transition-all duration-300 transform hover:scale-105 shadow-lg">
            Lihat Menu
        </a>
    </div>
</header>

<main class="py-12 md:py-20">
    <div class="container mx-auto px-4">
        
        <section id="membership-checker" class="mb-16 animated-section">
            <div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
                <h2 class="text-2xl font-bold text-brown-custom mb-4 text-center">Cek Status Membership Anda</h2>
                <form id="membership-form" action="{{ route('check.membership.ajax') }}" method="POST">
                    @csrf
                    <div class="flex">
                        <input type="email" name="email" id="member-email" placeholder="Masukkan email terdaftar..." class="flex-grow px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-brown-custom" required>
                        <button type="submit" class="px-6 py-2 bg-brown-custom text-white rounded-r-lg hover:bg-brown-dark transition-colors font-semibold">Cek</button>
                    </div>
                </form>
                <div id="membership-result-container" class="mt-4"></div>
            </div>
        </section>

        <section class="bg-gray-100 p-8 rounded-lg shadow-md mb-16 animated-section" style="animation-delay: 0.2s;">
            <h2 class="text-3xl font-bold text-brown-custom mb-4 text-center">Gabung Member Sebelas Coffee</h2>
            <p class="text-gray-600 text-center mb-8">Dapatkan diskon eksklusif dengan meningkatkan total transaksimu!</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($memberships as $index => $member)
                    <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200 text-center transform hover:-translate-y-2 transition-transform duration-300">
                        <div class="mb-4">
                            @if($index == 0)
                                <svg class="w-12 h-12 mx-auto text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                            @elseif($index == 1)
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-12 h-12 mx-auto text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @endif
                        </div>
                        <p class="text-xl font-semibold text-brown-custom mb-2">
                             {{ $member->nama }} Member
                        </p>
                        <p class="text-2xl font-bold text-green-600 mb-3">
                             Diskon {{ rtrim(rtrim(number_format($member->diskon, 2), '0'), '.') }}%
                        </p>
                        <p class="text-sm text-gray-500">
                            Dicapai dengan total transaksi <strong>Rp{{ number_format($member->minimal_transaksi, 0, ',', '.') }}</strong>.
                        </p>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500">Info membership belum tersedia.</div>
                @endforelse
            </div>
        </section>

        <section id="menu-section" class="animated-section" style="animation-delay: 0.4s;">
            <h2 class="text-3xl font-bold text-brown-custom mb-8 text-center">Menu Kami</h2>
            
            <div id="product-filters" class="mb-8 p-4 bg-white rounded-lg shadow-md sticky top-[70px] z-30">
                <div class="relative mb-4">
                    <input type="text" name="search" id="search" placeholder="Cari nama kopi, makanan..." class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brown-custom">
                     <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                         <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                     </div>
                </div>

                <div id="kategori-filter-container" class="flex flex-wrap gap-2 justify-center">
                    <button class="filter-btn filter-btn-active px-4 py-1.5 rounded-full text-sm" data-kategori="all">
                        Semua Kategori
                    </button>
                    @foreach($kategori as $cat)
                        <button class="filter-btn px-4 py-1.5 rounded-full text-sm" data-kategori="{{ $cat->id }}">
                            {{ $cat->nama }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div id="product-grid-container" class="relative">
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @forelse($produk as $item)
                        <div class="bg-white rounded-lg shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 product-card transform hover:-translate-y-1">
                            <div class="relative group aspect-square">
                                <div class="image-loading absolute inset-0 bg-gray-200 flex items-center justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-400"></div>
                                </div>
                                <img src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/200x200?text=Balcos' }}"
                                     alt="{{ $item->nama }}" 
                                     class="product-image w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 opacity-0"
                                     onload="handleImageLoad(this)"
                                     onerror="handleImageError(this)">
                                <div class="absolute top-2 right-2 z-10">
                                    <span class="px-2 py-1 {{ $item->stok > 0 ? 'bg-green-500' : 'bg-red-500' }} text-white text-xs font-medium rounded-full shadow-sm">
                                        Stok: {{ number_format($item->stok, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-base font-semibold text-gray-900 mb-1 truncate" title="{{ $item->nama }}">
                                    {{ $item->nama }}
                                </h3>
                                <div class="mb-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-indigo-100 text-indigo-700">
                                        {{ $item->kategori->nama ?? 'Tidak Ada' }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <span class="text-lg font-bold text-brown-custom">
                                        Rp{{ number_format($item->harga, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-8 text-gray-500">
                             <div class="bg-gray-100 rounded-full p-4 mb-3">
                                 <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                             </div>
                            <h3 class="text-base font-medium text-gray-900 mb-1">Produk Tidak Ditemukan</h3>
                            <p class="text-sm text-gray-500 text-center">
                                Coba gunakan kata kunci atau filter yang berbeda.
                            </p>
                        </div>
                    @endforelse
                </div>

                @if($produk->hasPages())
                    <div class="mt-8">
                        <div class="px-4 py-3 bg-white border border-gray-200 rounded-lg">
                            {{ $produk->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</main>


<script>
    function handleImageLoad(img) {
        const loadingDiv = img.parentNode.querySelector('.image-loading');
        if (loadingDiv) {
            loadingDiv.style.opacity = '0';
            setTimeout(() => { loadingDiv.style.display = 'none'; }, 300);
        }
        img.style.opacity = '1';
    }
    function handleImageError(img) {
        const loadingDiv = img.parentNode.querySelector('.image-loading');
        if (loadingDiv) {
            loadingDiv.innerHTML = `<div class="text-gray-400 text-xs text-center p-2">Gambar tidak tersedia</div>`;
        }
        img.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const membershipForm = document.getElementById('membership-form');
        const resultContainer = document.getElementById('membership-result-container');
        const productGridContainer = document.getElementById('product-grid-container');
        const searchInput = document.getElementById('search');
        const kategoriFilterContainer = document.getElementById('kategori-filter-container');
        let debounceTimer;
        
        // ==========================================================
        // FUNGSI INI TELAH DIPERBARUI DENGAN SKELETON LOADER
        // ==========================================================
        function fetchProducts(url) {
            const skeletonCardHTML = `
                <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-100 animate-pulse">
                    <div class="aspect-square bg-gray-200"></div>
                    <div class="p-4">
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-1/2 mb-4"></div>
                        <div class="h-5 bg-gray-200 rounded w-1/3"></div>
                    </div>
                </div>
            `;
            let skeletonGridHTML = '<div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">';
            for (let i = 0; i < 10; i++) {
                skeletonGridHTML += skeletonCardHTML;
            }
            skeletonGridHTML += '</div>';

            productGridContainer.innerHTML = skeletonGridHTML;

            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGridContent = doc.getElementById('product-grid-container').innerHTML;
                productGridContainer.innerHTML = newGridContent;
                document.querySelectorAll('.product-image').forEach(img => {
                    if (img.complete) { handleImageLoad(img); }
                });
            })
            .catch(error => {
                console.error('Error fetching products:', error);
                productGridContainer.innerHTML = '<div class="col-span-full text-center text-red-500">Gagal memuat produk. Silakan coba lagi.</div>';
            });
        }
        // ==========================================================
        // BATAS AKHIR FUNGSI YANG DIPERBARUI
        // ==========================================================

        function applyFilters() {
            const searchValue = searchInput.value;
            const activeKategoriBtn = kategoriFilterContainer.querySelector('.filter-btn-active');
            const kategoriValue = activeKategoriBtn ? activeKategoriBtn.dataset.kategori : 'all';
            const url = new URL('{{ route("produk.filter") }}');
            url.searchParams.set('search', searchValue);
            url.searchParams.set('kategori', kategoriValue);
            window.history.pushState({}, '', url);
            fetchProducts(url.toString());
        }
        searchInput.addEventListener('keyup', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(applyFilters, 500);
        });
        kategoriFilterContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('filter-btn')) {
                kategoriFilterContainer.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('filter-btn-active');
                });
                e.target.classList.add('filter-btn-active');
                applyFilters();
            }
        });
        productGridContainer.addEventListener('click', function(e) {
            let target = e.target;
            while (target != null && target.tagName !== 'A') {
                target = target.parentElement;
            }
            if (target && target.matches('.pagination a')) {
                e.preventDefault();
                const url = target.href;
                fetchProducts(url);
                document.getElementById('menu-section').scrollIntoView({ behavior: 'smooth' });
            }
        });
        membershipForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const button = this.querySelector('button[type="submit"]');
            const originalButtonText = button.textContent;
            button.textContent = 'Mengecek...';
            button.disabled = true;
            resultContainer.innerHTML = `<div class="text-center text-gray-500">Memuat...</div>`;
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.success) {
                    const pelanggan = data.pelanggan;
                    const membership = pelanggan.membership;
                    const diskon = parseFloat(membership.diskon);
                    html = `
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p class="font-bold">Member Ditemukan!</p>
                        <p><strong>Nama:</strong> ${pelanggan.nama}</p>
                        <p><strong>Email:</strong> ${pelanggan.email}</p>
                        <div class="mt-2 pt-2 border-t border-green-300">
                            <p><strong>Level Member:</strong> ${membership.nama}</p>
                            <p><strong>Diskon Anda:</strong> ${Number.isInteger(diskon) ? diskon : diskon.toFixed(2)}%</p>
                            <p><strong>Total Transaksi Anda:</strong> ${data.formattedTotal}</p>
                        </div>
                    </div>`;
                } else {
                    html = `
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p class="font-bold">Tidak Ditemukan</p>
                        <p>${data.message}</p>
                    </div>`;
                }
                resultContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                resultContainer.innerHTML = `
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p class="font-bold">Terjadi Kesalahan</p><p>Gagal terhubung ke server.</p>
                    </div>`;
            })
            .finally(() => {
                button.textContent = originalButtonText;
                button.disabled = false;
            });
        });
    });
</script>

</body>
</html>