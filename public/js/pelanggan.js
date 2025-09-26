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
        searchUrl.searchParams.set('page', '1'); // Reset to page 1 on new search
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

            // Memperbarui URL di browser tanpa reload
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
    document.getElementById(modalId)?.classList.replace('flex', 'hidden');
    document.body.style.overflow = 'auto';
}

function openAddModal() {
    document.getElementById('addCustomerForm')?.reset();
    openModal('addModal');
}

// Fungsi untuk membuka modal Edit
function openEditModal(pelanggan) {
    const editForm = document.getElementById('editForm');
    const editModal = document.getElementById('editModal');

    // Hapus pesan error sebelumnya saat membuka modal
    const errorSpans = editModal.querySelectorAll('span.text-red-500');
    errorSpans.forEach(span => span.textContent = '');
    const errorInputs = editModal.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));

    // Isi form dengan data pelanggan yang dipilih
    document.getElementById('editNama').value = pelanggan.nama || '';
    document.getElementById('editEmail').value = pelanggan.email || '';
    document.getElementById('editNomorHp').value = pelanggan.nomor_hp || '';
    document.getElementById('editAlamat').value = pelanggan.alamat || '';
    document.getElementById('editMembershipId').value = pelanggan.membership_id || '';
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

    // Cek jika ada error validasi dari server untuk modal TAMBAH
    const addModal = document.getElementById('addModal');
    if (addModal && addModal.dataset.hasErrors === 'true') {
        openModal('addModal');
    }

    // Cek jika ada error validasi dari server untuk modal EDIT
    const editModal = document.getElementById('editModal');
    const failedEditId = editModal?.dataset.hasErrorsEdit;
    if (failedEditId) {
        const editForm = document.getElementById('editForm');
        editForm.action = `/pelanggan/${failedEditId}`;
        openModal('editModal');
    }

    // Menutup modal dengan tombol Escape
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal('addModal');
            closeModal('editModal');
            closeModal('deleteModal');
        }
    });
});