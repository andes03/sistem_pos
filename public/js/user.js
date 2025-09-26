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
    const errorSpans = modal.querySelectorAll('span[role="alert"], .error-message');
    errorSpans.forEach(span => {
        span.classList.add('hidden');
        span.textContent = '';
        span.remove();
    });
    const errorInputs = modal.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => input.classList.remove('border-red-500'));
    
    // Reset form
    const form = modal.querySelector('form');
    if (form) {
        resetFormToDefault(form);
    }
}

/**
 * Reset form ke kondisi default tanpa konfirmasi (untuk close modal)
 */
function resetFormToDefault(form) {
    form.reset();
    
    // Reset preview image jika ada
    const imagePreview = form.querySelector('#image-preview, #edit-image-preview');
    if (imagePreview) {
        imagePreview.src = 'https://via.placeholder.com/100';
        imagePreview.classList.add('hidden');
    }
    
    // Reset password field types ke password (hidden) hanya jika tidak ada error
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
        
        // Reset toggle icons
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
    
    // Re-render the icon
    feather.replace();
}

/**
 * Reset form dengan konfirmasi
 */
function resetForm() {
    const form = document.getElementById('addUserForm');
    
    // Konfirmasi sebelum reset
    if (confirm('Apakah Anda yakin ingin mengosongkan semua field form?')) {
        // Reset semua input
        form.reset();
        
        // Reset preview image
        const imagePreview = document.getElementById('image-preview');
        if (imagePreview) {
            imagePreview.src = 'https://via.placeholder.com/100';
            imagePreview.classList.add('hidden');
        }
        
        // Reset password field types
        const passwordInput = document.getElementById('password');
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        const editPasswordInput = document.getElementById('editPassword');
        const editPasswordConfirmationInput = document.getElementById('editPasswordConfirmation');
        
        const resetPasswordField = (input, iconId) => {
            if (input && input.type === 'text') {
                input.type = 'password';
                input.removeAttribute('data-original-type');
                const icon = document.getElementById(iconId);
                if (icon) {
                    icon.setAttribute('data-feather', 'eye');
                }
            }
        };
        
        resetPasswordField(passwordInput, 'togglePasswordIcon');
        resetPasswordField(passwordConfirmationInput, 'togglePasswordConfirmationIcon');
        resetPasswordField(editPasswordInput, 'toggleEditPasswordIcon');
        resetPasswordField(editPasswordConfirmationInput, 'toggleEditPasswordConfirmationIcon');
        
        // Hapus semua pesan error
        const errorMessages = form.querySelectorAll('span[role="alert"], .error-message');
        errorMessages.forEach(error => {
            error.classList.add('hidden');
            error.textContent = '';
        });
        
        // Hapus border merah dari input
        const errorInputs = form.querySelectorAll('.border-red-500');
        errorInputs.forEach(input => {
            input.classList.remove('border-red-500');
        });
        
        // Re-render icons
        feather.replace();
        
        // Focus ke input pertama
        const firstInput = form.querySelector('input[name="username"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    }
}

/**
 * Preview gambar yang dipilih
 */
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = 'https://via.placeholder.com/100';
        preview.classList.add('hidden');
    }
}

/**
 * Membuka modal edit user dengan data yang sudah terisi.
 * @param {object} user - Objek user yang akan diedit.
 * @param {string} imagePath - URL gambar user.
 */
function openEditModal(user, imagePath) {
    const editForm = document.getElementById('editForm');
    document.getElementById('editUsername').value = user.username;
    document.getElementById('editNama').value = user.nama;
    document.getElementById('editEmail').value = user.email;
    document.getElementById('editNomorHp').value = user.nomor_hp;
    document.getElementById('editAlamat').value = user.alamat || '';
    document.getElementById('editJabatan').value = user.jabatan || '';
    document.getElementById('editRole').value = user.role;
    document.getElementById('edit-image-preview').src = imagePath;
    document.getElementById('current-image-path').value = user.image || '';
    
    // Atur action form ke route update
    editForm.action = `/users/${user.id}`;
    
    // Tangkap data lama dari session jika ada error validasi
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
 * @param {string} url - URL endpoint untuk menghapus user.
 * @param {string} namaUser - Nama user yang akan dihapus.
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
 * @param {string} url - URL endpoint untuk mengambil data user.
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

    // Tampilkan modal dan spinner/loading
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
        
        // Panggil Feather Icons untuk me-render ulang ikon
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

    // Event listeners
    searchInput.addEventListener('input', performFilter);
    roleFilter.addEventListener('change', performFilter);

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
    if (searchInput.value || roleFilter.value) {
        performFilter();
    }
}

// Event listener utama saat dokumen siap
document.addEventListener('DOMContentLoaded', () => {
    feather.replace();
    initializeUserFiltersAndPagination();
    
    // Panggil modal jika ada error validasi saat submit form
    const addModal = document.getElementById('addModal');
    const editModal = document.getElementById('editModal');
    
    if (addModal && addModal.dataset.hasErrors === 'true') {
        openModal('addModal');
    }

    if (editModal && editModal.dataset.hasErrorsEdit !== '') {
        const userIdEdit = editModal.dataset.hasErrorsEdit;
        const userData = JSON.parse(editModal.dataset.userData);
        // Panggil openEditModal dengan data yang ada di old()
        const imagePath = userData.image ? `/storage/${userData.image}` : 'https://via.placeholder.com/100';
        
        // Buat objek user sementara untuk mengisi modal
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