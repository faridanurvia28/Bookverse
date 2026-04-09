@extends('layouts.app')
@section('title', 'Profil Admin')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/profile-admin.css') }}">
<style>
    /* Additional styles for photo editing functionality */
    .profile-info {
        position: relative;
        cursor: pointer;
    }
    
    .profile-info img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }
    
    .profile-info:hover img {
        transform: scale(1.05);
    }
    
    /* Modal overlay for photo editing */
    .photo-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(6px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        visibility: visible;
        opacity: 1;
        transition: all 0.2s;
        padding: 20px;
    }
    
    .photo-modal-overlay.hidden {
        visibility: hidden;
        opacity: 0;
        pointer-events: none;
    }
    
    .photo-modal {
        background: white;
        border-radius: 28px;
        width: 100%;
        max-width: 520px;
        overflow: hidden;
        animation: modalSlideIn 0.25s ease-out;
        box-shadow: 0 30px 50px rgba(0,0,0,0.3);
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.96) translateY(15px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    .photo-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.2rem 1.5rem;
        background: white;
    }
    
    .photo-modal-header h3 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
    }
    
    .photo-modal-close {
        font-size: 2rem;
        font-weight: 300;
        cursor: pointer;
        color: #9ca3af;
        transition: color 0.2s;
        line-height: 1;
    }
    
    .photo-modal-close:hover {
        color: #1f2937;
    }
    
    .photo-preview-area {
        padding: 20px 24px;
        text-align: center;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .preview-circle {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        background: #e5e7eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 4px solid white;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    .preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .empty-preview {
        font-size: 3rem;
        color: #9ca3af;
    }
    
    .upload-box-photo {
        margin: 20px 24px;
        border: 2px dashed #d1d5db;
        border-radius: 20px;
        background: #f9fafb;
        transition: all 0.2s;
        position: relative;
        cursor: pointer;
    }
    
    .upload-box-photo:hover {
        border-color: #6366f1;
        background: #eef2ff;
    }
    
    .upload-content-photo {
        padding: 1.2rem;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
    }
    
    .upload-icon {
        font-size: 2rem;
    }
    
    .upload-text {
        font-weight: 500;
        color: #374151;
        margin: 0;
    }
    
    .upload-info {
        font-size: 0.7rem;
        color: #6b7280;
    }
    
    .file-input-photo {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }
    
    .zoom-control-photo {
        margin: 12px 24px;
        background: #f3f4f6;
        border-radius: 40px;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .zoom-control-photo label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .zoom-range {
        flex: 1;
        min-width: 140px;
        height: 4px;
        -webkit-appearance: none;
        background: #cbd5e1;
        border-radius: 10px;
    }
    
    .zoom-range::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 16px;
        height: 16px;
        background: #6366f1;
        border-radius: 50%;
        cursor: pointer;
    }
    
    .zoom-value {
        background: white;
        padding: 4px 10px;
        border-radius: 30px;
        font-size: 0.7rem;
        font-weight: 600;
        color: #6366f1;
    }
    
    .photo-modal-footer {
        padding: 1.2rem 1.5rem;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        background: white;
        border-top: 1px solid #e5e7eb;
    }
    
    .btn-cancel-photo {
        padding: 0.6rem 1.4rem;
        border-radius: 40px;
        font-weight: 600;
        border: none;
        background: #e5e7eb;
        color: #1f2937;
        cursor: pointer;
        transition: 0.2s;
    }
    
    .btn-cancel-photo:hover {
        background: #d1d5db;
    }
    
    .btn-save-photo {
        padding: 0.6rem 1.4rem;
        border-radius: 40px;
        font-weight: 600;
        border: none;
        background: #6366f1;
        color: white;
        cursor: pointer;
        transition: 0.2s;
    }
    
    .btn-save-photo:hover {
        background: #4f46e5;
        transform: scale(0.98);
    }
    
    .edit-photo-hint {
        font-size: 0.7rem;
        color: #6b7280;
        margin-top: 8px;
        text-align: center;
    }
    
    /* Edit button on profile card */
    .edit-photo-btn {
        position: absolute;
        bottom: -5px;
        right: -5px;
        background: #6366f1;
        border: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        color: white;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        border: 2px solid white;
    }
    
    .edit-photo-btn:hover {
        background: #4f46e5;
        transform: scale(1.1);
    }
    
    .profile-img-container {
        position: relative;
        display: inline-block;
    }
    
    @media (max-width: 550px) {
        .preview-circle {
            width: 120px;
            height: 120px;
        }
        .photo-modal {
            margin: 0 16px;
        }
        .zoom-control-photo {
            flex-direction: column;
            align-items: stretch;
        }
        .photo-modal-footer {
            justify-content: center;
        }
    }
    
    .toast-notification {
        position: fixed;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        background: #10b981;
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 500;
        z-index: 2100;
        animation: fadeInUp 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateX(-50%) translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
    }
</style>
@endpush
@section('content')

<!-- HEADER PROFIL -->
<div class="profile-header">
    <div class="profile-info" id="profileToggle">
        <div class="profile-img-container">
            <img id="profileAvatar" src="{{ asset('img/profile.png') }}" alt="Profile">
            <button class="edit-photo-btn" id="openPhotoModalBtn" type="button">✎</button>
        </div>
        <div class="user-text">
            <h3>Seulgi Putri</h3>
            <p>@seulgi123 <span class="badge">Admin</span></p>
        </div>
    </div>
    <div class="icon-right">📚</div>
</div>

<!-- POPUP PROFILE -->
<div class="profile-popup hidden" id="profilePopup">
    <p class="popup-title">Profile admin</p>
    <div class="popup-user">
        <img id="popupAvatar" src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png">
        <div>
            <strong>Seulgi Putri</strong><br>
            <span>@seulgi123</span>
        </div>
    </div>
    <a href="/profile_admin" class="popup-btn blue">Profile Saya</a>
    <form action="/logout" method="POST">
        @csrf
        <button class="popup-btn red">Log out</button>
    </form>
</div>

<!-- TAB MENU -->
<div class="tab-menu">
    <button class="tab-btn active" id="btnProfil" onclick="showTab('profil')">
        👤 Profil
    </button>
    <button class="tab-btn" id="btnPassword" onclick="showTab('password')">
        🔒 Ubah Password
    </button>
</div>

<!-- ================= PROFIL ================= -->
<div class="card" id="profilForm">
    <h2>Profil Admin</h2>

    <label>Nama :</label>
    <input type="text" value="Seulgi Putri" readonly>

    <label>No. Telepon :</label>
    <input type="text" value="08184567823" readonly>

    <label>Username :</label>
    <input type="text" value="seulgi123" readonly>

    <button class="action-btn" id="editProfileBtn">✏ Edit Profil</button>
</div>

<!-- ================= PASSWORD ================= -->
<div class="card hidden" id="passwordForm">
    <h2>Ubah Password</h2>

    <label>Password Lama :</label>
    <input type="password" id="oldPassword">

    <label>Password Baru :</label>
    <input type="password" id="newPassword">

    <label>Konfirmasi Password :</label>
    <input type="password" id="confirmPassword">

    <button class="action-btn" id="changePasswordBtn">🔁 Ganti Password</button>
</div>

<!-- ================= MODAL EDIT FOTO PROFIL ================= -->
<div id="photoModal" class="photo-modal-overlay hidden">
    <div class="photo-modal">
        <div class="photo-modal-header">
            <h3>✎ Edit Foto Profil</h3>
            <span class="photo-modal-close" id="closePhotoModal">&times;</span>
        </div>
        
        <div class="photo-preview-area">
            <div class="preview-circle" id="previewCircle">
                <img id="livePreview" class="preview-img" src="{{ asset('img/profile.png') }}" alt="Preview">
            </div>
            <div class="edit-photo-hint">Foto akan ditampilkan dalam lingkaran</div>
        </div>
        
        <div class="upload-box-photo" id="uploadBox">
            <div class="upload-content-photo">
                <div class="upload-icon">🖼️+</div>
                <p class="upload-text">Upload foto baru</p>
                <span class="upload-info">PNG, JPEG, JPG, WEBP (max 5MB)</span>
            </div>
            <input type="file" id="photoFileInput" class="file-input-photo" accept="image/png, image/jpeg, image/jpg, image/webp">
        </div>
        
        <div class="zoom-control-photo">
            <label>🔍 Zoom & Crop</label>
            <input type="range" id="zoomSlider" class="zoom-range" min="0.7" max="1.8" step="0.01" value="1.0">
            <span id="zoomPercent" class="zoom-value">100%</span>
        </div>
        
        <div class="photo-modal-footer">
            <button class="btn-cancel-photo" id="cancelPhotoBtn">Batal</button>
            <button class="btn-save-photo" id="savePhotoBtn">Simpan Foto</button>
        </div>
    </div>
</div>

<script>
// ==================== PHOTO EDITOR FUNCTIONALITY ====================
// DOM Elements
const photoModal = document.getElementById('photoModal');
const openPhotoBtn = document.getElementById('openPhotoModalBtn');
const closePhotoModal = document.getElementById('closePhotoModal');
const cancelPhotoBtn = document.getElementById('cancelPhotoBtn');
const savePhotoBtn = document.getElementById('savePhotoBtn');
const photoFileInput = document.getElementById('photoFileInput');
const livePreview = document.getElementById('livePreview');
const profileAvatar = document.getElementById('profileAvatar');
const popupAvatar = document.getElementById('popupAvatar');
const zoomSlider = document.getElementById('zoomSlider');
const zoomPercent = document.getElementById('zoomPercent');
const uploadBox = document.getElementById('uploadBox');

// State for editing
let currentImageFile = null;
let currentImageUrl = null;
let currentZoom = 1.0;
let originalImgWidth = 200, originalImgHeight = 200;

// Setup preview container style
const previewCircle = document.getElementById('previewCircle');
previewCircle.style.position = 'relative';
previewCircle.style.overflow = 'hidden';
previewCircle.style.display = 'flex';
previewCircle.style.alignItems = 'center';
previewCircle.style.justifyContent = 'center';

livePreview.style.width = '100%';
livePreview.style.height = '100%';
livePreview.style.objectFit = 'cover';
livePreview.style.transition = 'transform 0.1s ease';
livePreview.style.transform = `scale(${currentZoom})`;

// Helper: Revoke blob URL
function revokeBlobUrl() {
    if (currentImageUrl && currentImageUrl.startsWith('blob:')) {
        URL.revokeObjectURL(currentImageUrl);
        currentImageUrl = null;
    }
}

// Update zoom display
function updateZoomDisplay() {
    currentZoom = parseFloat(zoomSlider.value);
    const percent = Math.round(currentZoom * 100);
    zoomPercent.innerText = percent + '%';
    livePreview.style.transform = `scale(${currentZoom})`;
}

// Load image from file
function loadImageFromFile(file) {
    if (!file) return false;
    
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (!validTypes.includes(file.type)) {
        alert('Format tidak didukung! Gunakan PNG, JPEG, JPG, atau WEBP.');
        return false;
    }
    
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file terlalu besar. Maksimal 5MB.');
        return false;
    }
    
    revokeBlobUrl();
    const newUrl = URL.createObjectURL(file);
    currentImageUrl = newUrl;
    currentImageFile = file;
    
    const tempImg = new Image();
    tempImg.onload = () => {
        originalImgWidth = tempImg.width;
        originalImgHeight = tempImg.height;
        livePreview.src = newUrl;
        zoomSlider.value = '1.0';
        updateZoomDisplay();
    };
    tempImg.onerror = () => {
        alert('Gambar tidak dapat dimuat, coba file lain.');
        revokeBlobUrl();
        currentImageFile = null;
        currentImageUrl = null;
    };
    tempImg.src = newUrl;
    return true;
}

// Generate cropped image with zoom effect (circle crop)
async function generateCroppedImage() {
    return new Promise((resolve, reject) => {
        if (!currentImageUrl) {
            resolve(null);
            return;
        }
        
        const imgElement = new Image();
        imgElement.onload = () => {
            const targetSize = 400; // High quality output
            const canvas = document.createElement('canvas');
            canvas.width = targetSize;
            canvas.height = targetSize;
            const ctx = canvas.getContext('2d');
            
            const imgW = imgElement.width;
            const imgH = imgElement.height;
            
            // Calculate cover area for 1:1 aspect ratio
            let drawW, drawH, offsetX, offsetY;
            const ratioCover = imgW / imgH;
            if (ratioCover > 1) {
                drawH = imgH;
                drawW = imgH;
                offsetX = (imgW - drawW) / 2;
                offsetY = 0;
            } else {
                drawW = imgW;
                drawH = imgW;
                offsetX = 0;
                offsetY = (imgH - drawH) / 2;
            }
            
            // Apply zoom
            const zoom = currentZoom;
            const newCropW = drawW / zoom;
            const newCropH = drawH / zoom;
            const centerX = offsetX + drawW / 2;
            const centerY = offsetY + drawH / 2;
            
            let finalX = Math.max(0, centerX - newCropW / 2);
            let finalY = Math.max(0, centerY - newCropH / 2);
            let finalW = Math.min(newCropW, imgW - finalX);
            let finalH = Math.min(newCropH, imgH - finalY);
            
            ctx.clearRect(0, 0, targetSize, targetSize);
            ctx.drawImage(imgElement, finalX, finalY, finalW, finalH, 0, 0, targetSize, targetSize);
            
            canvas.toBlob(blob => {
                if (blob) {
                    const roundedUrl = URL.createObjectURL(blob);
                    resolve(roundedUrl);
                } else {
                    reject(new Error('Gagal membuat gambar'));
                }
            }, 'image/png', 0.92);
        };
        imgElement.onerror = () => reject(new Error('Gagal memuat gambar'));
        imgElement.src = currentImageUrl;
    });
}

// Update all profile images
function updateProfileImages(newImageUrl) {
    profileAvatar.src = newImageUrl;
    popupAvatar.src = newImageUrl;
    
    // Store in localStorage to persist (simulate server storage)
    localStorage.setItem('adminProfilePhoto', newImageUrl);
    
    // Show toast notification
    showToast('✓ Foto profil berhasil diperbarui!');
}

// Show toast message
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerText = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.remove();
    }, 2500);
}

// Open modal
function openPhotoModal() {
    // Reset preview with current profile photo
    const currentPhotoSrc = profileAvatar.src;
    if (currentPhotoSrc && !currentPhotoSrc.includes('blob:')) {
        livePreview.src = currentPhotoSrc;
    } else {
        livePreview.src = "{{ asset('img/profile.png') }}";
    }
    
    // Reset zoom
    zoomSlider.value = '1.0';
    updateZoomDisplay();
    
    // Clear current file state
    if (currentImageUrl && currentImageUrl.startsWith('blob:')) {
        revokeBlobUrl();
    }
    currentImageFile = null;
    currentImageUrl = null;
    
    photoModal.classList.remove('hidden');
}

// Close modal
function closePhotoModal() {
    photoModal.classList.add('hidden');
    revokeBlobUrl();
    currentImageFile = null;
    currentImageUrl = null;
}

// Save photo changes
async function savePhotoChanges() {
    if (!currentImageFile && !currentImageUrl) {
        closePhotoModal();
        return;
    }
    
    const originalBtnText = savePhotoBtn.innerText;
    savePhotoBtn.innerText = 'Menyimpan...';
    savePhotoBtn.disabled = true;
    
    try {
        const croppedImageUrl = await generateCroppedImage();
        if (croppedImageUrl) {
            updateProfileImages(croppedImageUrl);
            
            // Store blob reference for cleanup
            if (window._currentProfileBlob) {
                URL.revokeObjectURL(window._currentProfileBlob);
            }
            window._currentProfileBlob = croppedImageUrl;
            
            closePhotoModal();
        } else {
            closePhotoModal();
        }
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan saat memproses gambar.');
    } finally {
        savePhotoBtn.innerText = originalBtnText;
        savePhotoBtn.disabled = false;
    }
}

// Load saved photo from localStorage on page load
function loadSavedPhoto() {
    const savedPhoto = localStorage.getItem('adminProfilePhoto');
    if (savedPhoto && savedPhoto !== 'null' && !savedPhoto.includes('undefined')) {
        profileAvatar.src = savedPhoto;
        popupAvatar.src = savedPhoto;
    }
}

// ==================== EVENT LISTENERS ====================
openPhotoBtn.addEventListener('click', openPhotoModal);
closePhotoModal.addEventListener('click', closePhotoModal);
cancelPhotoBtn.addEventListener('click', closePhotoModal);
savePhotoBtn.addEventListener('click', savePhotoChanges);

photoFileInput.addEventListener('change', (e) => {
    if (e.target.files && e.target.files.length > 0) {
        loadImageFromFile(e.target.files[0]);
    }
});

zoomSlider.addEventListener('input', updateZoomDisplay);

// Upload box click trigger
uploadBox.addEventListener('click', (e) => {
    if (e.target !== photoFileInput) {
        photoFileInput.click();
    }
});

// Drag and drop support
uploadBox.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = '#6366f1';
    uploadBox.style.backgroundColor = '#eef2ff';
});

uploadBox.addEventListener('dragleave', () => {
    uploadBox.style.borderColor = '#d1d5db';
    uploadBox.style.backgroundColor = '#f9fafb';
});

uploadBox.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadBox.style.borderColor = '#d1d5db';
    uploadBox.style.backgroundColor = '#f9fafb';
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        loadImageFromFile(files[0]);
    }
});

// Close modal when clicking overlay
photoModal.addEventListener('click', (e) => {
    if (e.target === photoModal) {
        closePhotoModal();
    }
});

// ==================== EXISTING FUNCTIONALITY ====================
/* POPUP PROFILE */
const profileToggle = document.getElementById('profileToggle');
const profilePopup = document.getElementById('profilePopup');

if (profileToggle && profilePopup) {
    profileToggle.addEventListener('click', function (e) {
        e.stopPropagation();
        profilePopup.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!profileToggle.contains(e.target) && !profilePopup.contains(e.target)) {
            profilePopup.classList.add('hidden');
        }
    });
}

/* TAB SWITCH */
function showTab(tab) {
    const profilForm = document.getElementById('profilForm');
    const passwordForm = document.getElementById('passwordForm');
    const btnProfil = document.getElementById('btnProfil');
    const btnPassword = document.getElementById('btnPassword');

    if (tab === 'profil') {
        if (profilForm) profilForm.classList.remove('hidden');
        if (passwordForm) passwordForm.classList.add('hidden');
        if (btnProfil) btnProfil.classList.add('active');
        if (btnPassword) btnPassword.classList.remove('active');
    }

    if (tab === 'password') {
        if (passwordForm) passwordForm.classList.remove('hidden');
        if (profilForm) profilForm.classList.add('hidden');
        if (btnPassword) btnPassword.classList.add('active');
        if (btnProfil) btnProfil.classList.remove('active');
    }
}

/* Change Password Functionality */
const changePasswordBtn = document.getElementById('changePasswordBtn');
if (changePasswordBtn) {
    changePasswordBtn.addEventListener('click', function() {
        const oldPass = document.getElementById('oldPassword').value;
        const newPass = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('confirmPassword').value;
        
        if (!oldPass || !newPass || !confirmPass) {
            alert('Harap isi semua field password!');
            return;
        }
        
        if (newPass !== confirmPass) {
            alert('Password baru dan konfirmasi password tidak cocok!');
            return;
        }
        
        if (newPass.length < 6) {
            alert('Password baru minimal 6 karakter!');
            return;
        }
        
        alert('Password berhasil diubah! (Simulasi)');
        document.getElementById('oldPassword').value = '';
        document.getElementById('newPassword').value = '';
        document.getElementById('confirmPassword').value = '';
    });
}

/* Edit Profile Button */
const editProfileBtn = document.getElementById('editProfileBtn');
if (editProfileBtn) {
    editProfileBtn.addEventListener('click', function() {
        alert('Fitur edit profil sedang dalam pengembangan.');
    });
}

// Load saved photo when page loads
loadSavedPhoto();

// Cleanup blob on page unload
window.addEventListener('beforeunload', () => {
    if (window._currentProfileBlob && window._currentProfileBlob.startsWith('blob:')) {
        URL.revokeObjectURL(window._currentProfileBlob);
    }
    if (currentImageUrl && currentImageUrl.startsWith('blob:')) {
        URL.revokeObjectURL(currentImageUrl);
    }
});
</script>
@endsection