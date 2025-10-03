<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sebelas Coffee - Coffee & Eatery</title>
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { 
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
            background-color: #ffffff;
            color: #1a1a1a;
            line-height: 1.6;
        }
        
        .text-primary { color: #3b82f6; }
        .text-secondary { color: #64748b; }
        .bg-dark { background-color: #3b82f6; }
        .bg-light { background-color: #f8fafc; }
        .border-minimal { border-color: #e2e8f0; }
        
        h1, h2, h3 { 
            font-weight: 600; 
            letter-spacing: -0.02em;
        }
        
        nav {
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid #e5e5e5;
        }
        
        .hero {
            min-height: 60vh;
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><rect width="1" height="1" x="50" y="50" fill="white" opacity="0.1"/></svg>');
            opacity: 0.1;
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
        
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            padding: 0.875rem 2rem;
            border-radius: 2px;
            font-weight: 500;
            font-size: 0.875rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
        }
        
        .btn-outline {
            background-color: transparent;
            color: #3b82f6;
            border: 1px solid #3b82f6;
        }
        
        .btn-outline:hover {
            background-color: #3b82f6;
            color: white;
        }
        
        .card {
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            border-color: #3b82f6;
            transform: translateY(-2px);
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: grayscale(10%);
            transition: all 0.4s ease;
        }
        
        .card:hover .product-image {
            filter: grayscale(0%);
            transform: scale(1.05);
        }
        
        .aspect-square { 
            aspect-ratio: 1 / 1; 
            overflow: hidden;
        }
        
        .image-loading { transition: opacity 0.3s ease; }
        
        .filter-btn {
            padding: 0.5rem 1.25rem;
            background: white;
            border: 1px solid #e5e5e5;
            border-radius: 2px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #666;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .filter-btn:hover {
            border-color: #3b82f6;
            color: #3b82f6;
        }
        
        .filter-btn-active {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }
        
        input[type="text"],
        input[type="email"] {
            border: 1px solid #e5e5e5;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border-radius: 2px;
            transition: all 0.2s ease;
        }
        
        input[type="text"]:focus,
        input[type="email"]:focus {
            outline: none;
            border-color: #3b82f6;
        }
        
        .membership-card {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 2.5rem 2rem;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease-in-out;
            display: flex;
            flex-direction: column;
        }
        .membership-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.07);
        }
        
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s ease-in-out infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .section {
            padding: 4rem 0;
        }
        
        @media (min-width: 768px) {
            .section {
                padding: 6rem 0;
            }
        }
        
        .stock-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            padding: 0.25rem 0.625rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid #e5e5e5;
            border-radius: 2px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .price {
            font-size: 1.125rem;
            font-weight: 600;
            color: #3b82f6;
        }
    </style>
</head>
<body>

<nav class="sticky top-0 z-50">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center max-w-7xl">
        <div class="text-xl font-semibold tracking-tight">Sistem Loyalitas Pelanggan Sebelas Coffee</div>
        <a href="{{ route('login') }}" class="btn-primary">Login</a>
    </div>
</nav>

<header class="hero relative">
    <div class="header-bg-slideshow"></div>
    <div class="header-bg-slideshow"></div>
    <div class="header-bg-slideshow"></div>
    
    <div class="absolute inset-0 bg-gray-900 opacity-70" style="z-index: 2;"></div>
    
    <div class="container mx-auto px-6 py-24 relative max-w-7xl" style="z-index: 10;">
        <div class="max-w-3xl">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 tracking-tight">
                Sebelas Coffee
            </h1>
            <p class="text-xl text-gray-300 mb-8 font-light">
                Coffee & Eatery
            </p>
            <a href="#menu-section" class="btn-primary inline-block">
                Lihat Menu
            </a>
        </div>
    </div>
</header>

<main>
    <section class="section bg-light">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl font-semibold mb-8 text-center">Cek Status Membership</h2>
                <form id="membership-form" action="{{ route('check.membership.ajax') }}" method="POST">
                    @csrf
                    <div class="flex gap-2">
                        <input 
                            type="email"
                            name="email"
                            id="member-email" 
                            placeholder="Masukkan email terdaftar..." 
                            class="flex-1"
                            required
                        >
                        <button type="submit" class="btn-primary">Cek</button>
                    </div>
                </form>
                <div id="membership-result-container" class="mt-6"></div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container mx-auto px-6 max-w-7xl">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-semibold mb-4">Gabung Member Sebelas Coffee</h2>
                <p class="text-secondary max-w-2xl mx-auto">
                    Dapatkan diskon eksklusif dengan meningkatkan total transaksimu
                </p>
            </div>
            
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-8">
                @forelse($memberships as $index => $member)
                    <div class="membership-card">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $member->nama }}</h3>
                        
                        <div class="my-6">
                            <span class="text-5xl font-bold text-gray-900">{{ rtrim(rtrim(number_format($member->diskon, 2), '0'), '.') }}<span class="text-3xl text-gray-400">%</span></span>
                            <p class="mt-1 text-sm text-secondary tracking-wide uppercase">Diskon</p>
                        </div>
                        
                        <div class="flex-grow text-sm text-secondary">
                            <p>Minimal akumulasi transaksi</p>
                            <p class="text-base font-semibold text-gray-700 mt-1">Rp{{ number_format($member->minimal_transaksi, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-secondary">Info membership belum tersedia</div>
                @endforelse
            </div>
        </div>
    </section>
    <section id="menu-section" class="section bg-light">
        <div class="container mx-auto px-6 max-w-7xl">
            <h2 class="text-3xl md:text-4xl font-semibold mb-12 text-center">Menu Kami</h2>
            
            <div id="product-filters" class="mb-12 sticky top-20 z-40 bg-white border border-minimal rounded-md p-4">
                <div class="mb-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input 
                        type="text"
                        name="search"
                        id="search" 
                        placeholder="Cari nama kopi, makanan..." 
                        style="padding-left: 2.75rem;"
                        class="w-full"
                    >
                </div>
                
                <div id="kategori-filter-container" class="flex flex-wrap gap-2 justify-center">
                    <button class="filter-btn filter-btn-active" data-kategori="all">
                        Semua Kategori
                    </button>
                    @foreach($kategori as $cat)
                        <button class="filter-btn" data-kategori="{{ $cat->id }}">
                            {{ $cat->nama }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div id="product-grid-container">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @forelse($produk as $item)
                        <div class="card product-card">
                            <div class="aspect-square relative group">
                                <div class="image-loading absolute inset-0 bg-gray-200 flex items-center justify-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-gray-400"></div>
                                </div>
                                <img 
                                    src="{{ $item->image ? asset('storage/' . $item->image) : 'https://via.placeholder.com/200x200?text=Sebelas' }}"
                                    alt="{{ $item->nama }}" 
                                    class="product-image opacity-0"
                                    onload="handleImageLoad(this)"
                                    onerror="handleImageError(this)"
                                >
                                <div class="stock-badge">
                                    Stok: {{ number_format($item->stok, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold mb-1 text-sm truncate" title="{{ $item->nama }}">
                                    {{ $item->nama }}
                                </h3>
                                <p class="text-xs text-secondary mb-3">{{ $item->kategori->nama ?? 'Tidak Ada' }}</p>
                                <p class="price">Rp{{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full flex flex-col items-center justify-center py-8 text-secondary">
                            <div class="bg-gray-100 rounded-full p-4 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                            </div>
                            <h3 class="text-base font-medium text-gray-900 mb-1">Produk Tidak Ditemukan</h3>
                            <p class="text-sm text-secondary text-center">
                                Coba gunakan kata kunci atau filter yang berbeda
                            </p>
                        </div>
                    @endforelse
                </div>

                @if($produk->hasPages())
                    <div class="mt-8">
                        <div class="px-4 py-3 bg-white border border-minimal rounded-md">
                            {{ $produk->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</main>

<footer class="bg-dark text-white py-12">
    <div class="container mx-auto px-6 max-w-7xl text-center">
        <p class="text-gray-100 text-sm">© 2025 Sebelas Coffee. All rights reserved.</p>
    </div>
</footer>

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
        
        function fetchProducts(url) {
            const skeletonCardHTML = `
                <div class="card animate-pulse">
                    <div class="aspect-square skeleton"></div>
                    <div class="p-4">
                        <div class="h-4 skeleton rounded w-3/4 mb-2"></div>
                        <div class="h-3 skeleton rounded w-1/2 mb-4"></div>
                        <div class="h-5 skeleton rounded w-1/3"></div>
                    </div>
                </div>
            `;
            let skeletonGridHTML = '<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">';
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
            resultContainer.innerHTML = `<div class="text-center text-secondary">Memuat...</div>`;
            
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                let html = '';
                if (data.success) {
                    const pelanggan = data.pelanggan;
                    
                    if (data.has_membership && pelanggan.membership) {
                        const membership = pelanggan.membership;
                        const diskon = parseFloat(membership.diskon);
                        html = `
                        <div class="p-4 border border-minimal rounded-md bg-white">
                            <p class="font-semibold mb-2">Member Ditemukan!</p>
                            <p class="text-sm text-secondary mb-1"><strong>Nama:</strong> ${pelanggan.nama}</p>
                            <p class="text-sm text-secondary mb-1"><strong>Email:</strong> ${pelanggan.email}</p>
                            <div class="mt-2 pt-2 border-t border-minimal">
                                <p class="text-sm text-secondary mb-1"><strong>Level Member:</strong> ${membership.nama}</p>
                                <p class="text-sm text-secondary mb-1"><strong>Diskon Anda:</strong> ${Number.isInteger(diskon) ? diskon : diskon.toFixed(2)}%</p>
                                <p class="text-sm text-secondary"><strong>Total Transaksi:</strong> ${data.formattedTotal}</p>
                            </div>
                        </div>`;
                    } else {
                        html = `
<div class="p-4 border border-minimal rounded-md bg-white">
    <p class="font-semibold mb-2">Member Ditemukan!</p>
    <p class="text-sm text-secondary mb-1"><strong>Nama:</strong> ${pelanggan.nama}</p>
    <p class="text-sm text-secondary mb-1"><strong>Email:</strong> ${pelanggan.email}</p>
    <div class="mt-2 pt-2 border-t border-minimal">
        <p class="text-sm text-secondary mb-1"><strong>Level Member:</strong> <span class="text-gray-500">Belum Ada</span></p>
        <p class="text-sm text-secondary mb-1"><strong>Total Transaksi:</strong> ${data.formattedTotal}</p>
    </div>
    <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-md">
        <p class="text-sm font-medium text-blue-900 mb-1">Target Membership</p>
        <p class="text-sm text-blue-800">
            Transaksi lagi <strong>${data.formatted_selisih}</strong> untuk mendapatkan membership <strong>${data.target_membership}</strong> (Diskon ${data.target_diskon}%)
        </p>
        <p class="text-xs text-blue-700 mt-1">
            (Minimal transaksi: ${data.formatted_minimal})
        </p>
    </div>
</div>`;
                    }
                } else {
                    html = `
                    <div class="p-4 border border-gray-300 rounded-md bg-gray-50">
                        <p class="font-semibold mb-2">Tidak Ditemukan</p>
                        <p class="text-sm text-secondary">${data.message}</p>
                    </div>`;
                }
                resultContainer.innerHTML = html;
            })
            .catch(error => {
                console.error('Fetch error:', error);
                resultContainer.innerHTML = `
                    <div class="p-4 border border-gray-300 rounded-md bg-gray-50">
                        <p class="font-semibold mb-2">Terjadi Kesalahan</p>
                        <p class="text-sm text-secondary">Gagal terhubung ke server. ${error.message}</p>
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