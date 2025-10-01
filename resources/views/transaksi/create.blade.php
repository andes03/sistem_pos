@extends('layouts.app')

@section('content')
<style>
    .product-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: #fff;
        cursor: pointer;
    }
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        border-color: #3b82f6;
    }
    .product-card.out-of-stock {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .product-card.out-of-stock:hover {
        transform: none;
        box-shadow: none;
        border-color: #e5e7eb;
    }
    .product-card .image-container {
        position: relative;
        width: 100%;
        padding-top: 75%;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    }
    .product-card img, .image-loading {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .image-loading {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        transition: opacity 0.3s ease;
    }
    .image-spinner {
        border: 2px solid rgba(59, 130, 246, 0.1);
        border-top: 2px solid #3b82f6;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .stock-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stock-badge.in-stock {
        background: #dcfce7;
        color: #166534;
    }
    .stock-badge.low-stock {
        background: #fef3c7;
        color: #92400e;
    }
    .stock-badge.out-of-stock {
        background: #fecaca;
        color: #991b1b;
    }
    .cart-item {
        border-bottom: 1px solid #eee;
        padding: 10px 0;
    }
    .cart-item:last-child {
        border-bottom: none;
    }
    .payment-option:has(input:checked) {
        background-color: #16a34a;
        border-color: #16a34a;
    }
    .payment-option:has(input:checked) .text-gray-700 {
        color: white !important;
    }
    .payment-option:has(input:checked) .text-gray-500 {
        color: #dcfce7 !important;
    }
    
    .category-option {
        transition: all 0.2s ease;
        border: 2px solid #e5e7eb;
    }
    .category-option:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
        color: #1d4ed8;
    }
    .category-option.active {
        background-color: #3b82f6;
        border-color: #3b82f6;
        color: white;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    .category-option.active:hover {
        background-color: #2563eb;
        border-color: #2563eb;
        color: white;
    }
</style>

<div class="container mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Tambah Transaksi</h1>
        <a href="{{ route('transaksi.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium flex items-center gap-2 transition-colors">
            <i data-feather="arrow-left" class="w-5 h-5"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Daftar Produk (2/3 lebar) -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-lg rounded-lg">
                <div class="bg-blue-600 text-white px-6 py-4 rounded-t-lg">
                    <h4 class="text-xl font-semibold flex items-center">
                        <i data-feather="box" class="w-5 h-5 mr-2"></i>
                        Daftar Produk
                    </h4>
                </div>
                <div class="p-6">
                    <!-- Filter dan Pencarian -->
                    <div class="mb-6">
                        <!-- Filter Kategori dengan Button Options -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Filter Kategori</label>
                            <div class="flex flex-wrap gap-2" id="kategoriOptions">
                                <button type="button" 
                                        class="category-option px-4 py-2 rounded-lg font-medium text-sm active" 
                                        data-kategori="all">
                                    Semua Kategori
                                </button>
                                @foreach($kategori as $cat)
                                    <button type="button" 
                                            class="category-option px-4 py-2 rounded-lg font-medium text-sm" 
                                            data-kategori="{{ $cat->id }}">
                                        {{ $cat->nama }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Search Bar -->
                        <div>
                            <label for="produkSearch" class="block text-sm font-medium text-gray-700 mb-2">Cari Produk</label>
                            <div class="relative">
                                <input type="text" 
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       id="produkSearch" 
                                       placeholder="Cari berdasarkan nama produk...">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i data-feather="search" class="w-4 h-4 text-gray-400"></i>
                                </div>
                                <button type="button" 
                                        id="clearSearch"
                                        class="absolute inset-y-0 right-0 pr-3 items-center text-gray-400 hover:text-gray-600 hidden">
                                    <i data-feather="x" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="produkGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <!-- Produk akan dimuat di sini oleh JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Keranjang dan Form Transaksi (1/3 lebar) -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-lg rounded-lg sticky top-6">
                <div class="bg-green-600 text-white px-4 py-3 rounded-t-lg">
                    <h4 class="text-lg font-semibold flex items-center">
                        <i data-feather="shopping-cart" class="w-4 h-4 mr-2"></i>
                        Keranjang
                    </h4>
                </div>
                <div class="p-4">
                    <form id="transaksiForm" method="POST" action="{{ route('transaksi.store') }}">
                        @csrf
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="pelanggan_search" class="block text-xs font-medium text-gray-700 mb-1">Pelanggan</label>
                            <div class="relative">
                                <input type="text" 
                                       id="pelanggan_search" 
                                       class="w-full px-2 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                                       placeholder="Cari nama atau email..."
                                       autocomplete="off">
                                <div id="pelanggan_dropdown" class="absolute z-50 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-48 overflow-y-auto hidden shadow-lg">
                                    <!-- Dropdown items akan ditambahkan di sini -->
                                </div>
                                <input type="hidden" name="pelanggan_id" id="pelanggan_id" required>
                            </div>
                        </div>

                        <div id="membershipInfo" class="bg-blue-50 border border-blue-200 text-blue-700 px-3 py-2 rounded-lg mb-3 hidden">
                            <div class="text-xs font-semibold">Info Membership:</div>
                            <div id="membershipDetail" class="text-xs"></div>
                            <div id="membershipStatus" class="mt-1 text-xs"></div>
                        </div>

                        <div id="cartItems" class="mb-3">
                            <h6 class="text-xs font-medium text-gray-700 mb-2">Produk di Keranjang:</h6>
                            <div id="emptyCartMessage" class="text-gray-500 text-center py-6 text-xs border border-gray-200 rounded-lg">
                                <i data-feather="shopping-cart" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                                <p>Keranjang masih kosong</p>
                            </div>
                            <div id="cartTable" class="hidden">
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <table class="w-full text-xs">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-2 py-2 text-left font-medium text-gray-700">Produk</th>
                                                <th class="px-1 py-2 text-center font-medium text-gray-700 w-16">Qty</th>
                                                <th class="px-2 py-2 text-right font-medium text-gray-700">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cartTableBody" class="divide-y divide-gray-200">
                                            <!-- Item keranjang akan ditambahkan di sini -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">
                        
                        <div class="mb-3">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                            <div class="flex space-x-2">
                                <label class="flex-1 text-center p-2 border border-gray-200 rounded-lg cursor-pointer hover:border-green-300 transition-colors duration-200 payment-option">
                                    <input type="radio" name="metode_pembayaran" value="tunai" class="hidden" checked required>
                                    <div class="text-xs font-medium text-gray-700">Tunai</div>
                                    <div class="text-xs text-gray-500 mt-0.5">Cash</div>
                                </label>
                                
                                <label class="flex-1 text-center p-2 border border-gray-200 rounded-lg cursor-pointer hover:border-green-300 transition-colors duration-200 payment-option">
                                    <input type="radio" name="metode_pembayaran" value="transfer" class="hidden" required>
                                    <div class="text-xs font-medium text-gray-700">Transfer</div>
                                    <div class="text-xs text-gray-500 mt-0.5">Bank</div>
                                </label>
                                
                                <label class="flex-1 text-center p-2 border border-gray-200 rounded-lg cursor-pointer hover:border-green-300 transition-colors duration-200 payment-option">
                                    <input type="radio" name="metode_pembayaran" value="ewallet" class="hidden" required>
                                    <div class="text-xs font-medium text-gray-700">E-Wallet</div>
                                    <div class="text-xs text-gray-500 mt-0.5">QRIS</div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-3 rounded-lg mb-3">
                            <div class="space-y-1.5">
                                <div class="flex justify-between text-xs">
                                    <span class="font-medium">Subtotal:</span>
                                    <span id="subtotalDisplay">Rp 0</span>
                                </div>
                                <div id="diskonRow" class="justify-between text-green-600 hidden text-xs">
                                    <span class="font-medium">Diskon (<span id="diskonPersen">0</span>%):</span>
                                    <span id="diskonDisplay">- Rp 0</span>
                                </div>
                                <hr>
                                <div class="flex justify-between font-bold text-sm">
                                    <span>Total Bayar:</span>
                                    <span id="totalDisplay">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="produk" id="produkInput">

                        <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 text-sm rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center justify-center" id="simpanBtn">
                            <i data-feather="save" class="w-4 h-4 mr-1"></i>
                            Simpan Transaksi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // PENTING: Pisahkan data produk untuk filter dan keranjang
    let produkData = @json($produk);           // Data produk yang ter-filter (untuk display)
    let allProdukData = @json($produk);        // Semua data produk (untuk keranjang)
    let pelangganData = @json($pelanggan);
    let cart = {};
    let currentMembership = null;
    let currentKategori = 'all';

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    }

    function hitungTotal() {
        let subtotal = 0;
        const produkKeranjang = [];

        for (const id in cart) {
            const item = cart[id];
            // PENTING: Gunakan allProdukData, bukan produkData
            const produk = allProdukData.find(p => p.id == id);
            if (produk) {
                const itemSubtotal = produk.harga * item.jumlah;
                subtotal += itemSubtotal;
                produkKeranjang.push({ id: produk.id, jumlah: item.jumlah });
            }
        }

        document.getElementById('produkInput').value = JSON.stringify(produkKeranjang);
        document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);

        let diskon = 0;
        let persenDiskon = 0;
        let total = subtotal;

        const diskonRow = document.getElementById('diskonRow');
        const membershipStatus = document.getElementById('membershipStatus');

        if (currentMembership) {
            const diskonPersen = parseFloat(currentMembership.diskon) || 0;

            if (diskonPersen > 0) {
                persenDiskon = diskonPersen;
                diskon = (diskonPersen / 100) * subtotal;
                total = subtotal - diskon;
                diskonRow.classList.remove('hidden');
                diskonRow.classList.add('flex');
                document.getElementById('diskonPersen').textContent = diskonPersen;
                document.getElementById('diskonDisplay').textContent = '- ' + formatRupiah(diskon);
                membershipStatus.innerHTML = `<span class="text-green-600"><i data-feather="check-circle" class="w-4 h-4 inline mr-1"></i> Diskon ${diskonPersen}% berlaku! Hemat ${formatRupiah(diskon)}</span>`;
            } else {
                diskonRow.classList.add('hidden');
                diskonRow.classList.remove('flex');
                membershipStatus.innerHTML = `<span class="text-blue-600"><i data-feather="info" class="w-4 h-4 inline mr-1"></i> Membership ini tidak memiliki diskon.</span>`;
            }
            feather.replace();
        } else {
            diskonRow.classList.add('hidden');
            diskonRow.classList.remove('flex');
            membershipStatus.innerHTML = '';
        }

        document.getElementById('totalDisplay').textContent = formatRupiah(total);
    }

    function renderCart() {
        const cartTableBody = document.getElementById('cartTableBody');
        const cartTable = document.getElementById('cartTable');
        const emptyCartMessage = document.getElementById('emptyCartMessage');
        
        cartTableBody.innerHTML = '';
        
        let hasItems = false;
        for (const id in cart) {
            const item = cart[id];
            // PENTING: Gunakan allProdukData, bukan produkData
            const produk = allProdukData.find(p => p.id == id);
            if (produk) {
                hasItems = true;
                const itemSubtotal = produk.harga * item.jumlah;
                const cartRowHtml = `
                    <tr class="hover:bg-gray-50">
                        <td class="px-2 py-3">
                            <div class="font-medium text-xs text-gray-900 line-clamp-2 mb-1">${produk.nama}</div>
                            <div class="text-xs text-gray-500">${formatRupiah(produk.harga)}/pcs</div>
                        </td>
                        <td class="px-1 py-3">
                            <div class="flex items-center justify-center">
                                <button type="button" 
                                        class="w-6 h-6 bg-red-100 text-red-600 rounded-full hover:bg-red-200 flex items-center justify-center text-xs font-bold transition-colors" 
                                        onclick="updateCart(${produk.id}, -1)" 
                                        title="Kurangi">
                                    -
                                </button>
                                <span class="mx-2 font-medium text-xs min-w-[20px] text-center">${item.jumlah}</span>
                                <button type="button" 
                                        class="w-6 h-6 bg-green-100 text-green-600 rounded-full hover:bg-green-200 flex items-center justify-center text-xs font-bold transition-colors" 
                                        onclick="updateCart(${produk.id}, 1)" 
                                        title="Tambah">
                                    +
                                </button>
                            </div>
                        </td>
                        <td class="px-2 py-3 text-right">
                            <div class="font-bold text-xs text-gray-900">${formatRupiah(itemSubtotal)}</div>
                            <button type="button" 
                                    class="text-red-500 hover:text-red-700 text-xs underline mt-1 transition-colors" 
                                    onclick="removeFromCart(${produk.id})"
                                    title="Hapus produk">
                                Hapus
                            </button>
                        </td>
                    </tr>
                `;
                cartTableBody.insertAdjacentHTML('beforeend', cartRowHtml);
            }
        }

        if (hasItems) {
            cartTable.classList.remove('hidden');
            emptyCartMessage.classList.add('hidden');
        } else {
            cartTable.classList.add('hidden');
            emptyCartMessage.classList.remove('hidden');
        }

        hitungTotal();
    }

    function updateCart(id, change) {
        // PENTING: Gunakan allProdukData, bukan produkData
        const produk = allProdukData.find(p => p.id == id);
        if (!produk) return;

        if (cart[id]) {
            const newQuantity = cart[id].jumlah + change;
            if (newQuantity <= 0) {
                delete cart[id];
            } else if (newQuantity > produk.stok) {
                showAlert('Stok Tidak Cukup', `Maaf, stok ${produk.nama} hanya tersisa ${produk.stok} unit.`, 'warning');
            } else {
                cart[id].jumlah = newQuantity;
            }
        }
        renderCart();
    }

    function addToCart(id) {
        // PENTING: Gunakan allProdukData, bukan produkData
        const produk = allProdukData.find(p => p.id == id);
        if (!produk) return;
        
        if (cart[id]) {
            if (cart[id].jumlah < produk.stok) {
                cart[id].jumlah++;
            } else {
                showAlert('Stok Habis', `Maaf, stok ${produk.nama} sudah habis.`, 'warning');
            }
        } else {
            if (produk.stok > 0) {
                cart[id] = { id: produk.id, jumlah: 1 };
            } else {
                showAlert('Stok Habis', `Maaf, stok ${produk.nama} sudah habis.`, 'warning');
            }
        }
        renderCart();
    }
    
    function removeFromCart(id) {
        delete cart[id];
        renderCart();
    }

    function handleImageLoad(img) {
        const loadingDiv = img.parentNode.querySelector('.image-loading');
        if (loadingDiv) {
            loadingDiv.style.opacity = '0';
            setTimeout(() => {
                loadingDiv.style.display = 'none';
            }, 300);
        }
        img.style.opacity = '1';
    }
    
    function handleImageError(img) {
        const loadingDiv = img.parentNode.querySelector('.image-loading');
        if (loadingDiv) {
            loadingDiv.innerHTML = '<div class="text-gray-400 text-center text-xs">Gambar<br>tidak ada</div>';
            const spinner = loadingDiv.querySelector('.image-spinner');
            if (spinner) spinner.style.display = 'none';
        }
        img.style.display = 'none';
    }

    function renderProducts() {
        const gridContainer = document.getElementById('produkGrid');
        gridContainer.innerHTML = '';
        
        if (produkData.length === 0) {
            gridContainer.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i data-feather="box" class="w-16 h-16 text-gray-400 mx-auto mb-4"></i>
                    <h5 class="text-gray-500 text-lg">Tidak ada produk ditemukan.</h5>
                </div>
            `;
            feather.replace();
            return;
        }

        produkData.forEach(produk => {
            const isOutOfStock = produk.stok === 0;
            const isLowStock = produk.stok > 0 && produk.stok <= 5;
            const imageUrl = produk.image ? '{{ asset('storage') }}' + '/' + produk.image : 'https://via.placeholder.com/300x225/f8fafc/94a3b8?text=No+Image';
            
            let stockBadge = '';
            let stockClass = '';
            
            if (isOutOfStock) {
                stockBadge = '<div class="stock-badge out-of-stock">Habis</div>';
                stockClass = 'out-of-stock';
            } else if (isLowStock) {
                stockBadge = '<div class="stock-badge low-stock">Sisa ' + produk.stok + '</div>';
            } else {
                stockBadge = '<div class="stock-badge in-stock">' + produk.stok + '</div>';
            }
            
            const cardHtml = `
                <div class="product-card ${stockClass}" onclick="${!isOutOfStock ? `addToCart(${produk.id})` : ''}">
                    <div class="image-container">
                        <div class="image-loading">
                            <div class="image-spinner"></div>
                        </div>
                        <img src="${imageUrl}"
                             class="product-image"
                             alt="${produk.nama}"
                             onload="handleImageLoad(this)"
                             onerror="handleImageError(this)"
                             style="opacity: 0;">
                        ${stockBadge}
                    </div>
                    <div class="p-3">
                        <h5 class="font-semibold text-sm text-gray-900 mb-2 line-clamp-2 leading-tight">${produk.nama}</h5>
                        <div class="flex items-center justify-between">
                            <div class="font-bold text-base text-blue-600">${formatRupiah(produk.harga)}</div>
                            ${!isOutOfStock ? 
                                `<button type="button" 
                                        class="w-8 h-8 bg-blue-600 text-white rounded-full hover:bg-blue-700 flex items-center justify-center transition-all duration-200 hover:scale-110 shadow-md" 
                                        onclick="event.stopPropagation(); addToCart(${produk.id})" 
                                        title="Tambah ke keranjang">
                                    <i data-feather="plus" class="w-4 h-4"></i>
                                </button>` :
                                `<div class="px-2 py-1 bg-gray-200 text-gray-500 rounded text-xs font-medium">Habis</div>`
                            }
                        </div>
                    </div>
                </div>
            `;
            gridContainer.insertAdjacentHTML('beforeend', cardHtml);
        });
        feather.replace();
    }

    // Fungsi untuk mengambil data produk dari server
    async function fetchProducts() {
        const searchTerm = document.getElementById('produkSearch').value;
        
        const params = new URLSearchParams();
        if (currentKategori && currentKategori !== 'all') {
            params.append('kategori_id', currentKategori);
        }
        if (searchTerm) {
            params.append('search', searchTerm);
        }

        const url = `{{ route('transaksi.produk.filter') }}?${params.toString()}`;

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Gagal mengambil data produk.');
            }
            const data = await response.json();
            // PENTING: Update hanya produkData (untuk display), bukan allProdukData
            produkData = data;
            renderProducts();
        } catch (error) {
            console.error(error);
            const gridContainer = document.getElementById('produkGrid');
            gridContainer.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i data-feather="alert-circle" class="w-16 h-16 text-red-400 mx-auto mb-4"></i>
                    <h5 class="text-red-500 text-lg">Gagal memuat produk. Silakan coba lagi.</h5>
                </div>
            `;
            feather.replace();
        }
    }

    // Fungsi untuk inisialisasi kategori filter
    function initKategoriFilter() {
        const kategoriButtons = document.querySelectorAll('[data-kategori]');
        
        kategoriButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                kategoriButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Update current kategori
                currentKategori = this.getAttribute('data-kategori');
                
                // Fetch products with new filter
                fetchProducts();
            });
        });
    }

    // Fungsi untuk inisialisasi search
    function initSearchFunctionality() {
        const searchInput = document.getElementById('produkSearch');
        const clearButton = document.getElementById('clearSearch');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim();
            
            // Show/hide clear button
            if (searchTerm) {
                clearButton.classList.remove('hidden');
                clearButton.classList.add('flex');
            } else {
                clearButton.classList.add('hidden');
                clearButton.classList.remove('flex');
            }
            
            // Fetch products with search term
            fetchProducts();
        });
        
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            clearButton.classList.add('hidden');
            clearButton.classList.remove('flex');
            fetchProducts();
            searchInput.focus();
        });
    }

    // Fungsi pencarian pelanggan
    function initPelangganSearch() {
        const searchInput = document.getElementById('pelanggan_search');
        const dropdown = document.getElementById('pelanggan_dropdown');
        const hiddenInput = document.getElementById('pelanggan_id');
        
        let filteredPelanggan = [...pelangganData];
        
        function renderDropdown(pelangganList) {
            dropdown.innerHTML = '';
            
            if (pelangganList.length === 0) {
                dropdown.innerHTML = '<div class="px-4 py-3 text-gray-500 text-sm">Pelanggan tidak ditemukan</div>';
                dropdown.classList.remove('hidden');
                return;
            }
            
            pelangganList.forEach(pelanggan => {
                const membershipName = pelanggan.membership?.nama || 'Tidak ada membership';
                const membershipDiskon = pelanggan.membership?.diskon || 0;
                
                const item = document.createElement('div');
                item.className = 'px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-b-0';
                item.innerHTML = `
                    <div class="font-medium text-xs">${pelanggan.nama}</div>
                    <div class="text-xs text-gray-500">${pelanggan.email}</div>
                    <div class="text-xs text-blue-600">${membershipName} ${membershipDiskon > 0 ? `(${membershipDiskon}% diskon)` : ''}</div>
                `;
                
                item.addEventListener('click', () => {
                    searchInput.value = `${pelanggan.nama} - ${pelanggan.email}`;
                    hiddenInput.value = pelanggan.id;
                    dropdown.classList.add('hidden');
                    
                    // Update membership info
                    if (pelanggan.membership) {
                        currentMembership = {
                            nama: pelanggan.membership.nama,
                            diskon: parseFloat(pelanggan.membership.diskon) || 0,
                        };
                    } else {
                        currentMembership = {
                            nama: 'Tidak ada membership',
                            diskon: 0,
                        };
                    }
                    
                    updateMembershipInfo();
                    hitungTotal();
                });
                
                dropdown.appendChild(item);
            });
            
            dropdown.classList.remove('hidden');
        }
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            if (searchTerm === '') {
                filteredPelanggan = [...pelangganData];
                hiddenInput.value = '';
                currentMembership = null;
                updateMembershipInfo();
                hitungTotal();
            } else {
                filteredPelanggan = pelangganData.filter(pelanggan => 
                    pelanggan.nama.toLowerCase().includes(searchTerm) ||
                    pelanggan.email.toLowerCase().includes(searchTerm)
                );
            }
            
            renderDropdown(filteredPelanggan.slice(0, 10));
        });
        
        searchInput.addEventListener('focus', function() {
            if (filteredPelanggan.length > 0) {
                renderDropdown(filteredPelanggan.slice(0, 10));
            }
        });
        
        // Hide dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchInput.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }
    
    function updateMembershipInfo() {
        const membershipInfo = document.getElementById('membershipInfo');
        const membershipDetail = document.getElementById('membershipDetail');
        
        if (currentMembership && currentMembership.nama !== 'Tidak ada membership') {
            membershipDetail.innerHTML = `
                <div>Membership: <strong>${currentMembership.nama}</strong></div>
                <div>Diskon: <strong>${currentMembership.diskon}%</strong></div>
            `;
            membershipInfo.classList.remove('hidden');
        } else {
            membershipInfo.classList.add('hidden');
        }
    }

    // Fungsi untuk menampilkan popup peringatan
    function showAlert(title, message, type = 'warning') {
        const alertHTML = `
            <div id="alertModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-xl p-6 mx-4 max-w-md w-full transform transition-all duration-200 scale-95">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0">
                            ${type === 'warning' ? 
                                '<div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center"><i data-feather="alert-triangle" class="w-5 h-5 text-yellow-600"></i></div>' :
                                '<div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center"><i data-feather="x-circle" class="w-5 h-5 text-red-600"></i></div>'
                            }
                        </div>
                        <div class="ml-3">
                            <h3 class="text-lg font-semibold text-gray-900">${title}</h3>
                        </div>
                    </div>
                    <div class="mb-6">
                        <p class="text-gray-700">${message}</p>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                                onclick="closeAlert()">
                            Mengerti
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', alertHTML);
        
        // Animate in
        setTimeout(() => {
            const modal = document.getElementById('alertModal');
            if (modal) {
                modal.querySelector('.transform').classList.remove('scale-95');
                modal.querySelector('.transform').classList.add('scale-100');
            }
        }, 10);
        
        // Replace feather icons
        feather.replace();
        
        // Auto close after clicking outside
        document.getElementById('alertModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAlert();
            }
        });
    }

    function closeAlert() {
        const modal = document.getElementById('alertModal');
        if (modal) {
            modal.querySelector('.transform').classList.add('scale-95');
            modal.querySelector('.transform').classList.remove('scale-100');
            setTimeout(() => {
                modal.remove();
            }, 200);
        }
    }

    document.getElementById('transaksiForm').addEventListener('submit', function(e) {
        const produkKeranjang = JSON.parse(document.getElementById('produkInput').value || '[]');
        const pelangganId = document.getElementById('pelanggan_id').value;
        const metodePembayaran = document.querySelector('input[name="metode_pembayaran"]:checked');
        
        // Validasi keranjang kosong
        if (produkKeranjang.length === 0) {
            e.preventDefault();
            showAlert('Keranjang Kosong', 'Silakan tambahkan produk ke keranjang terlebih dahulu sebelum melakukan transaksi.', 'warning');
            return;
        }
        
        // Validasi pelanggan belum dipilih
        if (!pelangganId) {
            e.preventDefault();
            showAlert('Pilih Pelanggan', 'Silakan pilih pelanggan terlebih dahulu untuk melanjutkan transaksi.', 'warning');
            setTimeout(() => {
                document.getElementById('pelanggan_search').focus();
            }, 500);
            return;
        }
        
        // Validasi metode pembayaran belum dipilih
        if (!metodePembayaran) {
            e.preventDefault();
            showAlert('Pilih Metode Pembayaran', 'Silakan pilih metode pembayaran (Tunai, Transfer, atau E-Wallet) sebelum menyimpan transaksi.', 'warning');
            return;
        }
        
        // Jika semua validasi lulus, form akan disubmit
    });

    document.addEventListener('DOMContentLoaded', () => {
        initKategoriFilter();
        initSearchFunctionality();
        initPelangganSearch();
        renderProducts();
        renderCart();
        feather.replace();
    });
</script>
@endsection