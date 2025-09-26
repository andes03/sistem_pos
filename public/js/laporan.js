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

        document.querySelectorAll('#table-container .pagination a').forEach(link => {
            link.classList.add('pagination-link');
        });
    })
    .catch(error => {
        console.error('AJAX request failed:', error);
        tableContainer.innerHTML = `<div class="text-center py-8 text-red-500">Gagal memuat data.</div>`;
    });
}

function updatePdfLink() {
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;
    const membershipId = document.getElementById('membershipId').value;
    const metodePembayaranId = document.getElementById('metodePembayaranId').value;
    const pdfLink = document.getElementById('cetakPdfBtn');

    if (pdfLink) {
        const url = new URL(pdfLink.href);
        url.searchParams.set('from_date', fromDate);
        url.searchParams.set('to_date', toDate);
        url.searchParams.set('membership_id', membershipId);
        url.searchParams.set('metode_pembayaran', metodePembayaranId);
        pdfLink.href = url.toString();
    }
}

function initializeLaporanFiltersAndPagination() {
    const fromDateInput = document.getElementById('fromDate');
    const toDateInput = document.getElementById('toDate');
    const membershipSelect = document.getElementById('membershipId');
    const metodePembayaranSelect = document.getElementById('metodePembayaranId');
    const resetBtn = document.getElementById('resetFilterBtn');
    
    if (!fromDateInput || !toDateInput || !membershipSelect || !metodePembayaranSelect || !resetBtn) return;

    const performFilter = debounce(() => {
        const url = new URL(window.location.href);
        url.searchParams.set('from_date', fromDateInput.value);
        url.searchParams.set('to_date', toDateInput.value);
        url.searchParams.set('membership_id', membershipSelect.value);
        url.searchParams.set('metode_pembayaran', metodePembayaranSelect.value);
        url.searchParams.set('page', '1');
        url.searchParams.set('ajax', '1');
        loadTableContent(url.toString());
        updatePdfLink();
    }, 300);

    fromDateInput.addEventListener('change', performFilter);
    toDateInput.addEventListener('change', performFilter);
    membershipSelect.addEventListener('change', performFilter);
    metodePembayaranSelect.addEventListener('change', performFilter);

    resetBtn.addEventListener('click', () => {
        fromDateInput.value = '';
        toDateInput.value = '';
        membershipSelect.value = '';
        metodePembayaranSelect.value = '';
        performFilter();
    });

    document.addEventListener('click', (event) => {
        const pageLink = event.target.closest('.pagination-link');
        if (pageLink) {
            event.preventDefault();
            const url = new URL(pageLink.href);
            url.searchParams.set('ajax', '1');
            loadTableContent(url.toString());
            updatePdfLink();
        }
    });

    // Panggil saat halaman pertama kali dimuat
    updatePdfLink();
}

document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    initializeLaporanFiltersAndPagination();
});