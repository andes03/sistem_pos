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

// Fungsi untuk menampilkan toast notification
function showToast(message, type = 'success') {
    const existingToast = document.getElementById('toast-notification');
    if (existingToast) {
        existingToast.remove();
    }

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

    document.body.appendChild(toast);
    feather.replace();

    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.scale = '1';
    }, 10);

    setTimeout(() => {
        closeToast();
    }, 3000);
}

// Fungsi untuk menutup toast
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
    const filterSelect = document.getElementById('filterMembership');
    const tableContainer = document.getElementById('table-container');

    if (!searchInput || !filterSelect || !tableContainer) return;

    const performFilter = (query, membership) => {
        const searchUrl = new URL(window.location.href);
        searchUrl.searchParams.set('search', query);
        searchUrl.searchParams.set('membership', membership);
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
            if (membership) {
                newUrl.searchParams.set('membership', membership);
            } else {
                newUrl.searchParams.delete('membership');
            }
            window.history.replaceState({}, '', newUrl);
        })
        .catch(error => {
            console.error('Search/Filter error:', error);
            tableContainer.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data.</div>`;
        });
    };

    const debouncedFilter = debounce(() => {
        const query = searchInput.value;
        const membership = filterSelect.value;
        performFilter(query, membership);
    }, 300);

    searchInput.addEventListener('input', debouncedFilter);
    filterSelect.addEventListener('change', debouncedFilter);
}

// Fungsi manajemen Modal
function openModal(modalId) {
    document.getElementById(modalId)?.classList.replace('hidden', 'flex');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (!modal) return;
    
    let hasErrors = false;
    
    if (modalId === 'addModal') {
        hasErrors = modal.dataset.hasErrors === 'true';
    } else if (modalId === 'editModal') {
        hasErrors = modal.dataset.hasErrorsEdit !== '' && modal.dataset.hasErrorsEdit !== undefined;
    }
    
    if (hasErrors) {
        const url = new URL(window.location);
        url.searchParams.delete('errors');
        window.history.replaceState({}, '', url);
        window.location.reload();
        return;
    }
    
    modal.classList.replace('flex', 'hidden');
    document.body.style.overflow = 'auto';
    
    const errorSpans = modal.querySelectorAll('span.text-red-500');
    errorSpans.forEach(span => span.textContent = '');
    const errorInputs = modal.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    const form = modal.querySelector('form');
    if (form) {
        form.reset();
    }
}

function openAddModal() {
    document.getElementById('addCustomerForm')?.reset();
    openModal('addModal');
}

function openEditModal(pelanggan) {
    const editForm = document.getElementById('editForm');
    const editModal = document.getElementById('editModal');

    const errorSpans = editModal.querySelectorAll('span.text-red-500');
    errorSpans.forEach(span => span.textContent = '');
    const errorInputs = editModal.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));

    document.getElementById('editNama').value = pelanggan.nama || '';
    document.getElementById('editEmail').value = pelanggan.email || '';
    document.getElementById('editNomorHp').value = pelanggan.nomor_hp || '';
    document.getElementById('editAlamat').value = pelanggan.alamat || '';
    document.getElementById('pelanggan_id_edit').value = pelanggan.id;
    editForm.action = `/pelanggan/${pelanggan.id}`;

    openModal('editModal');
}

function openDeleteModal(pelanggan) {
    document.getElementById('deletePelangganNama').textContent = pelanggan.nama;
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `/pelanggan/${pelanggan.id}`;
    openModal('deleteModal');
}

// Event listener utama saat dokumen siap
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    initializeFilters();

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
        editForm.action = `/pelanggan/${failedEditId}`;
        openModal('editModal');
    }

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

    document.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('[id$="Modal"]');
        modals.forEach(modal => {
            if (modal.classList.contains('flex') && event.target === modal) {
                closeModal(modal.id);
            }
        });
    });
});