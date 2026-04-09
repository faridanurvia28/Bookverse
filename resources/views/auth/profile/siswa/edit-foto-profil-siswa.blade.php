<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Foto Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/siswa/edit-foto-profil.css') }}">
</head>
<body>

<div class="overlay" id="modalOverlay">
    <div class="modal">

        {{-- HEADER --}}
        <div class="modal-header">
            <h2>Edit Foto Profile</h2>
            <span class="close-icon" onclick="closeModal()">×</span>
        </div>
        <hr>

        <form id="uploadForm" enctype="multipart/form-data" style="display: contents;">
            @csrf
            {{-- UPLOAD --}}
            <div class="upload-box" id="uploadBox">
                <div class="upload-content">
                    <div class="icon">🖼️+</div>
                    <p class="text">Masukkan foto</p>
                    <span class="info">PNG, JPEG (max 200 x 200)</span>
                </div>
                <input type="file" class="file-input" id="fileInput" name="avatar" accept="image/png, image/jpeg">
                <img id="preview" class="preview-image" alt="Preview">
            </div>

            {{-- FILE INFO --}}
            <div class="file-info" id="fileInfo" style="display: none;">
                <div class="info-item">
                    <span class="info-label">File:</span>
                    <span class="info-value" id="fileName">-</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Ukuran:</span>
                    <span class="info-value" id="fileSize">-</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Dimensi:</span>
                    <span class="info-value" id="fileDimensions">-</span>
                </div>
            </div>

            {{-- SUCCESS MESSAGE --}}
            <div class="success-message" id="successMessage" style="display: none;">
                <div class="message-content">
                    <i class="fas fa-check-circle"></i>
                    <span>Foto profil berhasil diubah!</span>
                </div>
            </div>

            {{-- BUTTON --}}
            <div class="btn-wrapper">
                <button type="button" class="btn-reset" id="btnReset" onclick="resetPreview()" style="display: none;">
                    <i class="fas fa-times"></i>
                    <span>Hapus Preview</span>
                </button>
                <button type="submit" class="btn-upload" id="btnUpload" style="display: none;">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Upload Foto</span>
                </button>
                <button type="button" class="btn-close" id="btnClose" onclick="closeModal()">
                    <span>Tutup</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>

    </div>
</div>

<script>
function closeModal() {
    document.getElementById('modalOverlay').style.display = 'none';
}

function resetPreview() {
    const fileInput = document.getElementById('fileInput');
    const preview = document.getElementById('preview');
    const uploadBox = document.getElementById('uploadBox');
    const fileInfo = document.getElementById('fileInfo');
    const btnReset = document.getElementById('btnReset');
    const btnUpload = document.getElementById('btnUpload');
    const successMessage = document.getElementById('successMessage');
    
    fileInput.value = '';
    preview.src = '';
    preview.style.display = 'none';
    uploadBox.classList.remove('has-preview');
    fileInfo.style.display = 'none';
    successMessage.style.display = 'none';
    btnReset.style.display = 'none';
    btnUpload.style.display = 'none';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function updateParentAvatar(imageData) {
    try {
        // Update avatar di parent window jika ada
        if (window.opener && !window.opener.closed) {
            const parentAvatar = window.opener.document.querySelector('.avatar img');
            if (parentAvatar) {
                parentAvatar.src = imageData;
                // Trigger reload di parent jika diperlukan
                if (window.opener.location) {
                    // Optional: reload parent window
                    // window.opener.location.reload();
                }
            }
        }
    } catch (error) {
        console.log('Could not update parent window');
    }
}

// Preview image sebelum upload
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('fileInput');
    const uploadBox = document.getElementById('uploadBox');
    const preview = document.getElementById('preview');
    const fileInfo = document.getElementById('fileInfo');
    const btnReset = document.getElementById('btnReset');
    const btnUpload = document.getElementById('btnUpload');
    const btnClose = document.getElementById('btnClose');
    const uploadForm = document.getElementById('uploadForm');
    const successMessage = document.getElementById('successMessage');
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            
            if (file) {
                // Validasi tipe file
                if (!file.type.match('image.*')) {
                    alert('File harus berupa gambar (PNG atau JPEG)');
                    fileInput.value = '';
                    return;
                }

                // Validasi ukuran file (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    fileInput.value = '';
                    return;
                }

                const reader = new FileReader();
                
                reader.onload = function(e) {
                    // Load preview image
                    const img = new Image();
                    img.onload = function() {
                        // Display preview dengan animasi
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                        uploadBox.classList.add('has-preview');
                        
                        // Tampilkan file info
                        document.getElementById('fileName').textContent = file.name;
                        document.getElementById('fileSize').textContent = formatFileSize(file.size);
                        document.getElementById('fileDimensions').textContent = img.width + ' × ' + img.height + ' px';
                        fileInfo.style.display = 'block';
                        
                        // Tampilkan buttons
                        btnReset.style.display = 'inline-flex';
                        btnUpload.style.display = 'inline-flex';
                        btnClose.style.display = 'none';
                        
                        // Animasi
                        preview.style.animation = 'fadeIn 0.5s ease-out';
                        fileInfo.style.animation = 'slideUp 0.5s ease-out 0.1s both';
                    };
                    img.src = e.target.result;
                };
                
                reader.readAsDataURL(file);
            }
        });
    }

    // Form submission untuk upload
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(uploadForm);
            const csrf = document.querySelector('input[name="_token"]').value;
            
            // Show loading state
            btnUpload.disabled = true;
            btnUpload.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Mengupload...</span>';
            
            fetch('{{ route("profile.update-avatar") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Upload failed');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update parent window avatar
                    updateParentAvatar(data.avatar_url);
                    // Set localStorage supaya halaman profile di tab sama/baru ikut update
                    localStorage.setItem('profile_avatar_url', data.avatar_url);
                    
                    // Show success message
                    successMessage.style.display = 'block';
                    successMessage.style.animation = 'slideUp 0.5s ease-out';
                    
                    // Hide upload button
                    btnUpload.style.display = 'none';
                    btnReset.style.display = 'none';
                    btnClose.style.display = 'inline-flex';
                    
                    // Auto close setelah 3 detik
                    setTimeout(() => {
                        closeModal();
                        // Reload parent page untuk update lengkap
                        if (window.opener && !window.opener.closed) {
                            window.opener.location.reload();
                        } else {
                            window.location.href = '{{ route("profile.show") }}';
                        }
                    }, 2000);
                } else {
                    alert('Gagal mengupload foto: ' + (data.message || 'Unknown error'));
                    btnUpload.disabled = false;
                    btnUpload.innerHTML = '<i class="fas fa-cloud-upload-alt"></i><span>Upload Foto</span>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupload foto');
                btnUpload.disabled = false;
                btnUpload.innerHTML = '<i class="fas fa-cloud-upload-alt"></i><span>Upload Foto</span>';
            });
        });
    }

    // Klik di luar modal untuk menutup
    const overlay = document.getElementById('modalOverlay');
    if (overlay) {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }
});
</script>

</body>
</html>