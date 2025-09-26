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

function closeModal(modalId) {
    document.getElementById(modalId)?.classList.replace('flex', 'hidden');
    document.body.style.overflow = 'auto';
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

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeModal('addModal');
            closeModal('editModal');
            closeModal('deleteModal');
        }
    });
});