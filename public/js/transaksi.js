// File: public/js/transaksi.js

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
        window.history.replaceState({}, '', url);
    })
    .catch(error => {
        console.error('AJAX request failed:', error);
        tableContainer.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data.</div>`;
    });
}

/**
 * Menginisialisasi fungsionalitas pencarian, filter, dan paginasi untuk transaksi.
 */
function initializeTransaksiFiltersAndPagination() {
    const searchInput = document.getElementById('searchInput');
    const paymentMethodSelect = document.getElementById('paymentMethodFilter');
    const dateFromInput = document.getElementById('dateFrom');
    const dateToInput = document.getElementById('dateTo');
    const resetBtn = document.getElementById('resetFilterBtn');
    
    if (!searchInput || !paymentMethodSelect || !dateFromInput || !dateToInput || !resetBtn) return;

    const performFilter = debounce(() => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('metode_pembayaran', paymentMethodSelect.value);
        url.searchParams.set('date_from', dateFromInput.value);
        url.searchParams.set('date_to', dateToInput.value);
        url.searchParams.set('page', '1');
        url.searchParams.set('ajax', '1');
        loadTableContent(url.toString());
    }, 300);

    // Event listeners untuk filter
    searchInput.addEventListener('input', performFilter);
    paymentMethodSelect.addEventListener('change', performFilter);
    dateFromInput.addEventListener('change', performFilter);
    dateToInput.addEventListener('change', performFilter);

    // Event listener untuk tombol reset
    resetBtn.addEventListener('click', () => {
        searchInput.value = '';
        paymentMethodSelect.value = '';
        dateFromInput.value = '';
        dateToInput.value = '';
        performFilter();
    });

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
}

// Event listener utama saat dokumen siap
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    initializeTransaksiFiltersAndPagination();
});