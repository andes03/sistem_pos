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
 */
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
    
    modal.classList.remove('flex');
    modal.classList.add('hidden');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = 'auto';
    
    const errorSpans = modal.querySelectorAll('span[role="alert"], .error-message');
    errorSpans.forEach(span => {
        span.classList.add('hidden');
        span.textContent = '';
        span.remove();
    });
    const errorInputs = modal.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    const form = modal.querySelector('form');
    if (form) {
        resetFormToDefault(form);
    }
}

/**
 * Reset form ke kondisi default
 */
function resetFormToDefault(form) {
    form.reset();
    
    const imagePreviewAdd = form.querySelector('#image-preview');
    const imagePreviewEdit = form.querySelector('#edit-image-preview');

    if (imagePreviewAdd) {
        imagePreviewAdd.src = 'https://via.placeholder.com/100';
        imagePreviewAdd.classList.add('hidden');
    }
    if (imagePreviewEdit) {
        imagePreviewEdit.src = 'https://via.placeholder.com/100';
        imagePreviewEdit.classList.remove('hidden');
    }
    
    const modal = form.closest('[id$="Modal"]');
    const hasErrors = modal && (
        modal.dataset.hasErrors === 'true' || 
        modal.dataset.hasErrorsEdit !== ''
    );
    
    if (!hasErrors) {
        const passwordInputs = form.querySelectorAll('input[type="text"][data-original-type="password"], input[type="password"]');
        passwordInputs.forEach(input => {
            if (input.type === 'text' && input.name.includes('password')) {
                input.type = 'password';
            }
        });
        
        const toggleIcons = form.querySelectorAll('[id*="toggle"][id*="Icon"]');
        toggleIcons.forEach(icon => {
            icon.setAttribute('data-feather', 'eye');
        });
    }
    
    feather.replace();
}

/**
 * Membuka modal tambah user.
 */
function openAddModal() {
    openModal('addModal');
}

/**
 * Toggle show/hide password
 */
function togglePassword(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        passwordInput.setAttribute('data-original-type', 'password');
        toggleIcon.setAttribute('data-feather', 'eye-off');
    } else {
        passwordInput.type = 'password';
        passwordInput.removeAttribute('data-original-type');
        toggleIcon.setAttribute('data-feather', 'eye');
    }
    
    feather.replace();
}

/**
 * Preview image function
 */
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    const file = input.files[0];
    
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            showToast('Ukuran file melebihi batas maksimum 2MB.', 'error');
            input.value = '';
            preview.src = 'https://via.placeholder.com/100';
            preview.classList.add('hidden');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = 'https://via.placeholder.com/100';
        preview.classList.add('hidden');
    }
}

/**
 * Membuka modal edit user dengan data yang sudah terisi.
 */
function openEditModal(user, imagePath) {
    const editForm = document.getElementById('editForm');
    const editImagePreview = document.getElementById('edit-image-preview');
    const editImageInput = document.getElementById('editImage');

    document.getElementById('editUsername').value = user.username;
    document.getElementById('editNama').value = user.nama;
    document.getElementById('editEmail').value = user.email;
    document.getElementById('editNomorHp').value = user.nomor_hp;
    document.getElementById('editAlamat').value = user.alamat || '';
    document.getElementById('editJabatan').value = user.jabatan || '';
    document.getElementById('editRole').value = user.role;
    
    editImagePreview.src = imagePath;
    editImagePreview.classList.remove('hidden');

    if (editImageInput) {
        editImageInput.value = '';
    }

    document.getElementById('current-image-path').value = user.image || '';
    editForm.action = `/users/${user.id}`;
    
    const hasErrorsEdit = document.getElementById('editModal').dataset.hasErrorsEdit;
    if (hasErrorsEdit && hasErrorsEdit === user.id.toString()) {
        const oldData = JSON.parse(document.getElementById('editModal').dataset.userData);
        document.getElementById('editUsername').value = oldData.username;
        document.getElementById('editNama').value = oldData.nama;
        document.getElementById('editEmail').value = oldData.email;
        document.getElementById('editNomorHp').value = oldData.nomor_hp;
        document.getElementById('editAlamat').value = oldData.alamat || '';
        document.getElementById('editJabatan').value = oldData.jabatan || '';
        document.getElementById('editRole').value = oldData.role;
    }
    
    openModal('editModal');
}

/**
 * Membuka modal hapus user.
 */
function openDeleteModal(url, namaUser) {
    const deleteForm = document.getElementById('deleteForm');
    const deleteUserNama = document.getElementById('deleteUserNama');
    deleteForm.action = url;
    deleteUserNama.textContent = namaUser;
    openModal('deleteModal');
}

/**
 * Membuka modal detail user dan memuat data melalui AJAX.
 */
function openDetailModal(url) {
    const detailModal = document.getElementById('detailModal');
    const detailUsername = document.getElementById('detail-username');
    const detailNama = document.getElementById('detail-nama');
    const detailEmail = document.getElementById('detail-email');
    const detailNomorHp = document.getElementById('detail-nomor-hp');
    const detailAlamat = document.getElementById('detail-alamat');
    const detailJabatan = document.getElementById('detail-jabatan');
    const detailRole = document.getElementById('detail-role');
    const detailImage = document.getElementById('detail-image');
    const detailCreated = document.getElementById('detail-created');
    const detailUpdated = document.getElementById('detail-updated');

    detailModal.classList.remove('hidden');
    detailModal.classList.add('flex');
    detailUsername.textContent = 'Memuat...';
    detailNama.textContent = '';
    detailEmail.textContent = '';
    detailNomorHp.textContent = '';
    detailAlamat.textContent = '';
    detailJabatan.textContent = '';
    detailRole.textContent = '';
    detailImage.src = 'https://via.placeholder.com/200';
    detailCreated.textContent = '';
    detailUpdated.textContent = '';
    document.body.style.overflow = 'hidden';

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
        detailUsername.textContent = data.username;
        detailNama.textContent = data.nama;
        detailEmail.textContent = data.email;
        detailNomorHp.textContent = data.nomor_hp;
        detailAlamat.textContent = data.alamat;
        detailJabatan.textContent = data.jabatan;
        detailRole.textContent = data.role;
        detailImage.src = data.image;
        detailCreated.textContent = data.dibuat_pada;
        detailUpdated.textContent = data.diperbarui_pada;
        feather.replace();
    })
    .catch(error => {
        console.error('Error:', error);
        detailUsername.textContent = 'Gagal memuat detail.';
        detailNama.textContent = 'Terjadi kesalahan saat mengambil data.';
    });
}

/**
 * Menginisialisasi fungsionalitas pencarian, filter, dan paginasi.
 */
function initializeUserFiltersAndPagination() {
    const searchInput = document.getElementById('search');
    const roleFilter = document.getElementById('roleFilter');
    
    if (!searchInput || !roleFilter) return;

    const performFilter = debounce(() => {
        const url = new URL(window.location.href);
        url.searchParams.set('search', searchInput.value);
        url.searchParams.set('role', roleFilter.value);
        url.searchParams.set('page', '1');
        url.searchParams.set('ajax', '1');
        loadTableContent(url.toString());
    }, 300);

    searchInput.addEventListener('input', performFilter);
    roleFilter.addEventListener('change', performFilter);

    document.addEventListener('click', (event) => {
        const pageLink = event.target.closest('.pagination-link');
        if (pageLink) {
            event.preventDefault();
            const url = new URL(pageLink.href);
            url.searchParams.set('ajax', '1');
            loadTableContent(url.toString());
        }
    });

    if (searchInput.value || roleFilter.value) {
        performFilter();
    }
}

// Event listener utama saat dokumen siap
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    initializeUserFiltersAndPagination();
    
    // Cek dan tampilkan toast notification
    const successMessage = document.getElementById('success-message');
    if (successMessage) {
        const message = successMessage.textContent.trim();
        if (message) {
            showToast(message, 'success');
            successMessage.remove();
        }
    }

    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        const message = errorMessage.textContent.trim();
        if (message) {
            showToast(message, 'error');
            errorMessage.remove();
        }
    }
    
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (addModal && addModal.dataset.hasErrors === 'true') {
        openModal('addModal');
    }

    if (editModal && editModal.dataset.hasErrorsEdit !== '') {
        const userIdEdit = editModal.dataset.hasErrorsEdit;
        const userData = JSON.parse(editModal.dataset.userData);
        const imagePath = userData.current_image_path ? `/storage/${userData.current_image_path}` : 'https://via.placeholder.com/100';
        
        const userTemp = {
            id: userIdEdit,
            username: userData.username,
            nama: userData.nama,
            email: userData.email,
            nomor_hp: userData.nomor_hp,
            alamat: userData.alamat,
            jabatan: userData.jabatan,
            role: userData.role,
            image: userData.current_image_path || null
        };
        openEditModal(userTemp, imagePath);
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