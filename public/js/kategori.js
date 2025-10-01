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
 * Fungsi untuk menampilkan toast notification
 */
function showToast(message, type = 'success') {
    // Hapus toast yang sudah ada jika ada
    const existingToast = document.getElementById('toast-notification');
    if (existingToast) {
        existingToast.remove();
    }

    // Tentukan warna berdasarkan tipe
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500',
        warning: 'bg-yellow-500'
    };

    const icons = {
        success: 'check-circle',
        error: 'x-circle',
        info: 'info',
        warning: 'alert-triangle'
    };

    // Buat elemen toast
    const toast = document.createElement('div');
    toast.id = 'toast-notification';
    toast.className = `fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 ${colors[type]} text-white px-6 py-4 rounded-lg shadow-2xl z-[9999] flex items-center gap-3 transform transition-all duration-300 ease-in-out`;
    toast.style.minWidth = '350px';
    toast.style.maxWidth = '500px';
    toast.style.opacity = '0';
    toast.style.scale = '0.9';

    toast.innerHTML = `
        <i data-feather="${icons[type]}" class="w-6 h-6 flex-shrink-0"></i>
        <span class="flex-1 font-medium">${message}</span>
        <button onclick="closeToast()" class="ml-2 hover:bg-white hover:bg-opacity-20 rounded p-1 transition-colors">
            <i data-feather="x" class="w-5 h-5"></i>
        </button>
    `;

    // Tambahkan ke body
    document.body.appendChild(toast);

    // Inisialisasi feather icons
    feather.replace();

    // Animasi masuk
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.scale = '1';
    }, 10);

    // Auto hide setelah 3 detik
    setTimeout(() => {
        closeToast();
    }, 3000);
}

/**
 * Fungsi untuk menutup toast
 */
function closeToast() {
    const toast = document.getElementById('toast-notification');
    if (toast) {
        toast.style.opacity = '0';
        toast.style.scale = '0.9';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
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
        window.history.replaceState({}, '', url);
    })
    .catch(error => {
        console.error('AJAX request failed:', error);
        tableContainer.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data.</div>`;
    });
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
    document.body.style.overflow = 'hidden';
}

/**
 * Menutup modal dan mengatur tampilan.
 * Melakukan reload halaman jika ada error untuk reset form state.
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    // Cek apakah modal memiliki error
    let hasErrors = false;
    
    if (modalId === 'addModal') {
        hasErrors = modal.dataset.hasErrors === 'true';
    } else if (modalId === 'editModal') {
        hasErrors = modal.dataset.hasErrorsEdit !== '' && modal.dataset.hasErrorsEdit !== undefined;
    }
    
    // Jika ada error, reload halaman untuk reset state
    if (hasErrors) {
        // Hapus parameter error dari URL sebelum reload
        const url = new URL(window.location);
        url.searchParams.delete('errors');
        window.history.replaceState({}, '', url);
        
        // Reload halaman
        window.location.reload();
        return;
    }
    
    // Proses normal close modal jika tidak ada error
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = 'auto';
    
    // Hapus pesan error saat modal ditutup (untuk kasus normal)
    const errorSpans = modal.querySelectorAll('span[role="alert"]');
    errorSpans.forEach(span => span.remove());
    const errorInputs = modal.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    // Reset form jika ada
    const form = modal.querySelector('form');
    if (form) {
        form.reset();
    }
}

/**
 * Membuka modal tambah kategori.
 */
function openAddModal() {
    openModal('addModal');
}

/**
 * Membuka modal edit kategori dengan data yang sudah terisi.
 * @param {object} kategori - Objek kategori yang akan diedit.
 */
function openEditModal(kategori) {
    const editForm = document.getElementById('editForm');
    document.getElementById('editNama').value = kategori.nama;
    document.getElementById('editDeskripsi').value = kategori.deskripsi ?? '';
    editForm.action = `/kategori/${kategori.id}`;
    
    // Tangkap data lama dari session jika ada error validasi
    const hasErrorsEdit = document.getElementById('editModal').dataset.hasErrorsEdit;
    if (hasErrorsEdit && hasErrorsEdit === kategori.id.toString()) {
        const oldData = JSON.parse(document.getElementById('editModal').dataset.kategoriData);
        document.getElementById('editNama').value = oldData.nama;
        document.getElementById('editDeskripsi').value = oldData.deskripsi;
    }
    
    openModal('editModal');
}

/**
 * Membuka modal hapus kategori.
 * @param {string} url - URL endpoint untuk menghapus kategori.
 * @param {string} namaKategori - Nama kategori yang akan dihapus.
 */
function openDeleteModal(url, namaKategori) {
    const deleteForm = document.getElementById('deleteForm');
    const deleteKategoriNama = document.getElementById('deleteKategoriNama');
    deleteForm.action = url;
    deleteKategoriNama.textContent = namaKategori;
    openModal('deleteModal');
}

/**
 * Membuka modal detail kategori dan memuat data melalui AJAX.
 * @param {string} url - URL endpoint untuk mengambil data kategori.
 */
function openDetailModal(url) {
    const detailModal = document.getElementById('detailModal');
    const kategoriNama = document.getElementById('detailKategoriNama');
    const kategoriDeskripsi = document.getElementById('detailKategoriDeskripsi');
    const produkList = document.getElementById('produkList');

    // Tampilkan modal dan reset konten sebelumnya
    openModal('detailModal');
    kategoriNama.textContent = 'Memuat...';
    kategoriDeskripsi.textContent = '';
    produkList.innerHTML = `<div class="md:col-span-3 text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 inline-block"></div></div>`;

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
        // Isi data kategori
        kategoriNama.textContent = data.nama;
        kategoriDeskripsi.textContent = data.deskripsi;
        
        // Isi daftar produk
        if (data.produk.length > 0) {
            produkList.innerHTML = ''; // Kosongkan placeholder loading
            data.produk.forEach(produk => {
                const card = `
                    <div class="bg-gray-50 rounded-lg p-4 shadow-sm flex items-center gap-4">
                        <img src="${produk.image}" alt="${produk.nama}" class="w-16 h-16 object-cover rounded-md flex-shrink-0">
                        <div class="flex-grow">
                            <h4 class="font-bold text-gray-800 truncate">${produk.nama}</h4>
                            <p class="text-sm text-gray-600">Harga: Rp${produk.harga}</p>
                            <p class="text-sm text-gray-600">Stok: ${produk.stok}</p>
                        </div>
                    </div>
                `;
                produkList.innerHTML += card;
            });
        } else {
            produkList.innerHTML = `<div class="md:col-span-3 text-center py-8 text-gray-500">
                <i data-feather="box" class="w-12 h-12 text-gray-300 mb-2 mx-auto"></i>
                <p>Tidak ada produk dalam kategori ini.</p>
            </div>`;
        }

        feather.replace();
    })
    .catch(error => {
        console.error('Error:', error);
        kategoriNama.textContent = 'Gagal memuat detail.';
        kategoriDeskripsi.textContent = 'Terjadi kesalahan saat mengambil data.';
        produkList.innerHTML = `<div class="md:col-span-3 text-center py-8 text-red-500">Gagal memuat daftar produk.</div>`;
    });
}

/**
 * Menginisialisasi fungsionalitas pencarian, dan paginasi.
 */
function initializeKategoriFiltersAndPagination() {
    const searchInput = document.getElementById('searchInput');
    
    if (!searchInput) return;

    const performFilter = debounce(() => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('page', '1');
        url.searchParams.set('ajax', '1');
        loadTableContent(url.toString());
    }, 300);

    // Event listeners
    searchInput.addEventListener('input', performFilter);

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
    if (searchInput.value) {
        performFilter();
    }
}

// Event listener utama saat dokumen siap
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    initializeKategoriFiltersAndPagination();
    
    // Cek dan tampilkan toast notification jika ada pesan sukses
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        const message = successMessage.textContent.trim();
        if (message) {
            showToast(message, 'success');
            successMessage.remove();
        }
    }

    // Cek dan tampilkan toast notification jika ada pesan error
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        const message = errorMessage.textContent.trim();
        if (message) {
            showToast(message, 'error');
            errorMessage.remove();
        }
    }
    
    // Panggil modal jika ada error validasi saat submit form
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (addModal && addModal.dataset.hasErrors === 'true') {
        openModal('addModal');
    }

    if (editModal && editModal.dataset.hasErrorsEdit !== '') {
        const kategoriIdEdit = editModal.dataset.hasErrorsEdit;
        const oldData = JSON.parse(editModal.dataset.kategoriData);
        // Buat objek kategori sementara
        const kategoriTemp = {
            id: kategoriIdEdit,
            nama: oldData.nama,
            deskripsi: oldData.deskripsi
        };
        openEditModal(kategoriTemp);
    }
});

// Event listener untuk menutup modal dengan ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modals = document.querySelectorAll('[id$="Modal"]');
        modals.forEach(modal => {
            if (modal.classList.contains('flex')) {
                closeModal(modal.id);
            }
        });
        closeToast();
    }
});

// Event listener untuk menutup modal dengan klik di luar area modal
document.addEventListener('click', function(event) {
    const modals = document.querySelectorAll('[id$="Modal"]');
    modals.forEach(modal => {
        if (modal.classList.contains('flex') && event.target === modal) {
            closeModal(modal.id);
        }
    });
});