// Debounce function untuk menunda eksekusi fungsi
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

// Fungsi untuk pencarian dan filter AJAX
function initializeFilters() {
    const searchInput = document.getElementById('searchInput');
    const tableContainer = document.getElementById('table-container');

    if (!searchInput || !tableContainer) return;

    const performFilter = (query) => {
        const searchUrl = new URL(window.location.href);
        searchUrl.searchParams.set('search', query);
        searchUrl.searchParams.set('page', '1');
        searchUrl.searchParams.set('ajax', '1');

        tableContainer.innerHTML = `<div class="flex justify-center items-center py-10"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div></div>`;

        fetch(searchUrl.toString(), {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
            feather.replace();

            const newUrl = new URL(window.location);
            if (query.trim()) {
                newUrl.searchParams.set('search', query);
            } else {
                newUrl.searchParams.delete('search');
            }
            window.history.replaceState({}, '', newUrl);
        })
        .catch(error => {
            console.error('Search error:', error);
            tableContainer.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data.</div>`;
        });
    };

    const debouncedFilter = debounce(() => {
        performFilter(searchInput.value);
    }, 300);

    searchInput.addEventListener('input', debouncedFilter);

    // Handle pagination link clicks
    document.addEventListener('click', function(e) {
        if (e.target.matches('.pagination-link')) {
            e.preventDefault();
            const pageUrl = e.target.href;
            fetch(pageUrl + '&ajax=1', {
                method: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(response => response.text())
            .then(html => {
                tableContainer.innerHTML = html;
                feather.replace();
                window.history.pushState({}, '', pageUrl);
            })
            .catch(error => console.error('Pagination error:', error));
        }
    });
}

// Modal functions
function openModal(modalId) {
    document.getElementById(modalId)?.classList.replace('hidden', 'flex');
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
    modal.classList.replace('flex', 'hidden');
    document.body.style.overflow = 'auto';
    
    // Hapus pesan error saat modal ditutup (untuk kasus normal)
    const errorSpans = modal.querySelectorAll('span.text-red-500');
    errorSpans.forEach(span => span.textContent = '');
    const errorInputs = modal.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    // Reset form jika ada
    const form = modal.querySelector('form');
    if (form) {
        form.reset();
    }
}

function openAddModal() {
    document.getElementById('addMembershipForm')?.reset();
    openModal('addModal');
}

function openEditModal(membership) {
    const editForm = document.getElementById('editForm');
    const editModal = document.getElementById('editModal');
    const errorSpans = editModal.querySelectorAll('span.text-red-500');
    errorSpans.forEach(span => span.textContent = '');
    const errorInputs = editModal.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));

    document.getElementById('editNama').value = membership.nama || '';
    document.getElementById('editDiskon').value = membership.diskon || '';
    document.getElementById('editMinimalTransaksi').value = membership.minimal_transaksi || '';
    document.getElementById('membership_id_edit').value = membership.id;
    editForm.action = `/membership/${membership.id}`;

    openModal('editModal');
}

function openDeleteModal(membership) {
    document.getElementById('deleteMembershipNama').textContent = membership.nama;
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/membership/${membership.id}`;
    openModal('deleteModal');
}

// Main event listener
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    initializeFilters();

    // Cek dan tampilkan toast notification jika ada pesan sukses
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        const message = successMessage.textContent.trim();
        if (message) {
            showToast(message, 'success');
            successMessage.remove();
        }
    }

    const addModal = document.getElementById('addModal');
    if (addModal && addModal.dataset.hasErrors === 'true') {
        openModal('addModal');
    }

    const editModal = document.getElementById('editModal');
    const failedEditId = editModal?.dataset.hasErrorsEdit;
    if (failedEditId) {
        const editForm = document.getElementById('editForm');
        editForm.action = `/membership/${failedEditId}`;
        openModal('editModal');
    }

    // Event listener untuk menutup modal dengan ESC
    document.addEventListener('keydown', (event) => {
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
});