/**
 * Fungsi debounce untuk menunda eksekusi fungsi.
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Mengirim permintaan AJAX untuk memuat ulang konten tabel.
 */
function loadTableContent(url) {
    const tableContainer = document.getElementById('table-container');
    if (!tableContainer) return;

    tableContainer.innerHTML = `<div class="flex justify-center items-center py-10"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div></div>`;

    fetch(url, {
        method: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(html => {
        tableContainer.innerHTML = html;
        feather.replace();
        
        // Inisialisasi loading untuk gambar-gambar baru
        initializeImageLoading();
        
        window.history.replaceState({}, '', url);
    })
    .catch(error => {
        console.error('AJAX request failed:', error);
        tableContainer.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data.</div>`;
    });
}

/**
 * Inisialisasi loading state untuk gambar-gambar produk
 */
function initializeImageLoading() {
    document.querySelectorAll('.product-image').forEach(img => {
        if (img.complete && img.naturalHeight !== 0) {
            handleImageLoad(img);
        }
    });
}

/**
 * Fungsi untuk menangani ketika gambar berhasil dimuat
 */
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

/**
 * Fungsi untuk menangani ketika gambar gagal dimuat
 */
function handleImageError(img) {
    const loadingDiv = img.parentNode.querySelector('.image-loading');
    if (loadingDiv) {
        loadingDiv.innerHTML = '<div class="text-gray-400 text-xs text-center">Gambar<br>tidak tersedia</div>';
        loadingDiv.classList.remove('animate-spin');
    }
    img.style.display = 'none';
}

/**
 * Membuka modal dan mengatur tampilan.
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    modal.setAttribute('aria-hidden', 'false');
    modal.focus();
    // Nonaktifkan scroll pada body
    document.body.style.overflow = 'hidden';
}

/**
 * Menutup modal dan mengatur tampilan.
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    // Aktifkan kembali scroll pada body
    document.body.style.overflow = 'auto';
    // Hapus pesan error saat modal ditutup
    const errorSpans = modal.querySelectorAll('span[role="alert"]');
    errorSpans.forEach(span => span.remove());
    const errorInputs = modal.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
}

/**
 * Membuka modal tambah produk.
 */
function openAddModal() {
    openModal('addModal');
}

/**
 * Membuka modal edit produk dengan data yang sudah terisi.
 * @param {object} produk - Objek produk yang akan diedit.
 * @param {string} imagePath - URL gambar produk.
 */
function openEditModal(produk, imagePath) {
    const editForm = document.getElementById('editForm');
    document.getElementById('editNama').value = produk.nama;
    document.getElementById('editDeskripsi').value = produk.deskripsi;
    document.getElementById('editHarga').value = produk.harga;
    document.getElementById('editStok').value = produk.stok;
    document.getElementById('editKategoriId').value = produk.kategori_id;
    
    // Set preview image dengan loading state (tetap ukuran w-24 h-24)
    const previewImg = document.getElementById('image-preview');
    previewImg.style.opacity = '0';
    previewImg.onload = function() {
        this.style.opacity = '1';
        this.style.transition = 'opacity 0.3s ease';
    };
    previewImg.src = imagePath;
    
    document.getElementById('current-image-path').value = produk.image;
    
    // Atur action form ke route update
    editForm.action = `/produk/${produk.id}`;
    
    // Tangkap data lama dari session jika ada error validasi
    const hasErrorsEdit = document.getElementById('editModal').dataset.hasErrorsEdit;
    if (hasErrorsEdit && hasErrorsEdit === produk.id.toString()) {
        const oldData = JSON.parse(document.getElementById('editModal').dataset.produkData);
        document.getElementById('editNama').value = oldData.nama;
        document.getElementById('editDeskripsi').value = oldData.deskripsi;
        document.getElementById('editHarga').value = oldData.harga;
        document.getElementById('editStok').value = oldData.stok;
        document.getElementById('editKategoriId').value = oldData.kategori_id;
    }
    
    openModal('editModal');
}

/**
 * Membuka modal hapus produk.
 * @param {string} url - URL endpoint untuk menghapus produk.
 * @param {string} namaProduk - Nama produk yang akan dihapus.
 */
function openDeleteModal(url, namaProduk) {
    const deleteForm = document.getElementById('deleteForm');
    const deleteProdukNama = document.getElementById('deleteProdukNama');
    deleteForm.action = url;
    deleteProdukNama.textContent = namaProduk;
    openModal('deleteModal');
}

/**
 * Membuka modal detail produk dan memuat data melalui AJAX.
 * @param {string} url - URL endpoint untuk mengambil data produk.
 */
function openDetailModal(url) {
    const detailModal = document.getElementById('detailModal');
    const detailNama = document.getElementById('detail-nama');
    const detailDeskripsi = document.getElementById('detail-deskripsi');
    const detailHarga = document.getElementById('detail-harga');
    const detailStok = document.getElementById('detail-stok');
    const detailKategori = document.getElementById('detail-kategori');
    const detailTotalTerjual = document.getElementById('detail-total-terjual');
    const detailImage = document.getElementById('detail-image');
    const detailCreated = document.getElementById('detail-created');
    const detailUpdated = document.getElementById('detail-updated');

    // Tampilkan modal dan spinner/loading
    detailModal.classList.remove('hidden');
    detailModal.classList.add('flex');
    detailNama.textContent = 'Memuat...';
    detailDeskripsi.textContent = '';
    detailHarga.textContent = '';
    detailStok.textContent = '';
    detailKategori.textContent = '';
    detailTotalTerjual.textContent = '';
    
    // Set loading state untuk gambar detail
    detailImage.style.opacity = '0';
    detailImage.src = 'https://via.placeholder.com/200';
    
    detailCreated.textContent = '';
    detailUpdated.textContent = '';
    document.body.style.overflow = 'hidden';

    // Ambil data dari API
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Gagal memuat data. Status: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        // Isi data ke dalam elemen-elemen modal
        detailNama.textContent = data.nama;
        detailDeskripsi.textContent = data.deskripsi;
        detailHarga.textContent = `Rp${data.harga}`;
        detailStok.textContent = data.stok;
        detailKategori.textContent = data.kategori;
        detailTotalTerjual.textContent = data.total_terjual;
        
        // Set gambar dengan loading effect
        detailImage.onload = function() {
            this.style.opacity = '1';
            this.style.transition = 'opacity 0.3s ease';
        };
        detailImage.onerror = function() {
            this.src = 'https://via.placeholder.com/200?text=Gambar+Tidak+Tersedia';
            this.style.opacity = '1';
        };
        detailImage.src = data.image;
        
        detailCreated.textContent = data.dibuat_pada;
        detailUpdated.textContent = data.diperbarui_pada;
        
        // Panggil Feather Icons untuk me-render ulang ikon
        feather.replace();
    })
    .catch(error => {
        console.error('Error:', error);
        detailNama.textContent = 'Gagal memuat detail.';
        detailDeskripsi.textContent = 'Terjadi kesalahan saat mengambil data.';
        detailImage.style.opacity = '1';
    });
}


/**
 * Menginisialisasi fungsionalitas pencarian, filter, dan paginasi.
 */
function initializeProdukFiltersAndPagination() {
    const searchInput = document.getElementById('searchInput');
    const kategoriSelect = document.getElementById('kategoriFilter');
    
    if (!searchInput || !kategoriSelect) return;

    const performFilter = debounce(() => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('kategori', kategoriSelect.value);
        url.searchParams.set('page', '1');
        url.searchParams.set('ajax', '1');
        loadTableContent(url.toString());
    }, 300);

    // Event listeners
    searchInput.addEventListener('input', performFilter);
    kategoriSelect.addEventListener('change', performFilter);

    // Menggunakan event delegation untuk paginasi
    document.addEventListener('click', (event) => {
        const pageLink = event.target.closest('.pagination-link');
        if (pageLink) {
            event.preventDefault();
            const url = new URL(pageLink.href);
            url.searchParams.set('ajax', '1');
            loadTableContent(url.toString());
        }
    });

    // Jalankan filter saat halaman dimuat jika ada input yang terisi
    if (searchInput.value || kategoriSelect.value) {
        performFilter();
    }
}

// Event listener utama saat dokumen siap
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    initializeProdukFiltersAndPagination();
    
    // Inisialisasi loading untuk gambar yang ada saat halaman dimuat
    initializeImageLoading();
    
    // Panggil modal jika ada error validasi saat submit form
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (addModal && addModal.dataset.hasErrors === 'true') {
        openModal('addModal');
    }

    if (editModal && editModal.dataset.hasErrorsEdit !== '') {
        const produkIdEdit = editModal.dataset.hasErrorsEdit;
        const produkData = JSON.parse(editModal.dataset.produkData);
        // Panggil openEditModal dengan data yang ada di old()
        const imagePath = produkData.image ? `/storage/${produkData.image}` : 'https://via.placeholder.com/100';
        
        // Buat objek produk sementara untuk mengisi modal
        const produkTemp = {
            id: produkIdEdit,
            nama: produkData.nama,
            deskripsi: produkData.deskripsi,
            harga: produkData.harga,
            stok: produkData.stok,
            kategori_id: produkData.kategori_id,
            image: produkData.current_image_path || null
        };
        openEditModal(produkTemp, imagePath);
    }
});