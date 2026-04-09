<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Anggota - Perpustakaan Digital SMKN 4 Bojonegoro</title>

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Font Modern --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Clash+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Animated Background -->
<div class="animated-bg">
    <div class="gradient-sphere sphere-1"></div>
    <div class="gradient-sphere sphere-2"></div>
    <div class="gradient-sphere sphere-3"></div>
    <div class="noise-overlay"></div>
</div>

<div class="register-wrapper">
    <div class="register-card">
        
        <!-- LEFT SIDE - INFO & ILLUSTRATION -->
        <div class="info-side">
            <div class="info-content">
                <!-- Logo -->
                <div class="info-logo">
                    <i class="fas fa-book-open"></i>
                </div>

                <!-- Title -->
                <h1 class="info-title">Bergabunglah dengan</h1>
                <h2 class="info-subtitle">Komunitas Literasi</h2>
                
                <!-- Description -->
                <p class="info-description">
                    Daftar sebagai anggota perpustakaan digital dan nikmati ribuan koleksi buku, akses 24/7, dan berbagai fitur menarik lainnya.
                </p>

                <!-- Benefits Cards -->
                <div class="benefits-grid">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>5.000+ Buku</h4>
                            <p>Koleksi terlengkap</p>
                        </div>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Akses 24/7</h4>
                            <p>Baca kapan saja</p>
                        </div>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Komunitas</h4>
                            <p>Diskusi & review</p>
                        </div>
                    </div>
                    
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <div class="benefit-text">
                            <h4>Gratis</h4>
                            <p>Untuk siswa</p>
                        </div>
                    </div>
                </div>

                <!-- Testimonial Mini -->
                <div class="testimonial-mini">
                    <div class="testimonial-avatars">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User">
                        <span>+1.2k</span>
                    </div>
                    <p>Bergabung dengan 1.200+ anggota aktif lainnya!</p>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE - REGISTRATION FORM -->
        <div class="form-side">
            <div class="form-container">
                <!-- Header -->
                <div class="form-header">
                    <h3>Daftar Anggota Baru</h3>
                    <p>Isi data diri Anda dengan lengkap</p>
                </div>

                <!-- Progress Steps -->
                <div class="progress-steps">
                    <div class="step active">
                        <span class="step-number">1</span>
                        <span class="step-label">Data Diri</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <span class="step-number">2</span>
                        <span class="step-label">Akun</span>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <span class="step-number">3</span>
                        <span class="step-label">Selesai</span>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('registerAnggota') }}" method="POST" enctype="multipart/form-data" class="modern-form">
                    @csrf

                    <!-- Photo Upload Section -->
                    <div class="photo-section">
                        <div class="photo-wrapper">
                            <input type="file" id="photo" name="photo_profile" accept="image/*" hidden>
                            <label for="photo" class="photo-label" id="photoLabel">
                                <div class="photo-preview" id="photoPreview">
                                    <img id="previewImage" alt="Preview" style="display: none;">
                                    <i class="fa-solid fa-camera" id="cameraIcon"></i>
                                </div>
                                <div class="photo-text">
                                    <span class="photo-title">Upload Foto Profile</span>
                                    <span class="photo-subtitle">Maksimal 2MB</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Form Grid -->
                    <div class="form-grid">
                        <!-- Nama Lengkap -->
                        <div class="input-group full-width">
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="input-field">
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name"
                                    placeholder=" "
                                    value="{{ old('name') }}"
                                    required
                                >
                                <label for="name">Nama Lengkap</label>
                            </div>
                        </div>

                        <!-- Username -->
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-at"></i>
                            </div>
                            <div class="input-field">
                                <input 
                                    type="text" 
                                    name="username" 
                                    id="username"
                                    placeholder=" "
                                    value="{{ old('username') }}"
                                    required
                                >
                                <label for="username">Username</label>
                            </div>
                        </div>

                        <!-- No Telp -->
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="input-field">
                                <input 
                                    type="text" 
                                    name="telephone" 
                                    id="telephone"
                                    placeholder=" "
                                    value="{{ old('telephone') }}"
                                    required
                                >
                                <label for="telephone">No. Telp</label>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="input-group full-width">
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div class="input-field">
                                <div class="password-wrapper">
                                    <input 
                                        type="password" 
                                        name="password" 
                                        id="password"
                                        placeholder=" "
                                        class="pwd-input"
                                        required
                                    >
                                    <label for="password">Password</label>
                                    <button type="button" class="password-toggle" tabindex="-1">
                                        <i class="far fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="input-group full-width">
                            <div class="input-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="input-field">
                                <input 
                                    type="text" 
                                    name="alamat" 
                                    id="alamat"
                                    placeholder=" "
                                    value="{{ old('alamat') }}"
                                    required
                                >
                                <label for="alamat">Alamat Lengkap</label>
                            </div>
                        </div>

                        <!-- NIS -->
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="input-field">
                                <input 
                                    type="text" 
                                    name="nis_nisn" 
                                    id="nis"
                                    placeholder=" "
                                    value="{{ old('nis_nisn') }}"
                                    required
                                >
                                <label for="nis">NIS</label>
                            </div>
                        </div>

                        <!-- Kelas -->
                        <div class="input-group">
                            <div class="input-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div class="input-field">
                                <input 
                                    type="text" 
                                    name="kelas" 
                                    id="kelas"
                                    placeholder=" "
                                    value="{{ old('kelas') }}"
                                    required
                                >
                                <label for="kelas">Kelas</label>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions -->
                    <div class="terms-section">
                        <label class="checkbox-style">
                            <input type="checkbox" name="terms" required>
                            <span class="checkbox-box">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="checkbox-label">
                                Saya setuju dengan <a href="#">Syarat & Ketentuan</a> yang berlaku
                            </span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        <span>Daftar Sekarang</span>
                        <i class="fas fa-arrow-right"></i>
                        <div class="btn-shine"></div>
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Success Toast (if any) -->
@if(session('success'))
<div class="modern-toast success show">
    <div class="toast-icon">
        <i class="fas fa-check-circle"></i>
    </div>
    <div class="toast-message">
        <strong>Berhasil!</strong>
        <p>{{ session('success') }}</p>
    </div>
    <button class="toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
    <div class="toast-progress"></div>
</div>
@endif

@if($errors->any())
<div class="modern-toast error show">
    <div class="toast-icon">
        <i class="fas fa-exclamation-circle"></i>
    </div>
    <div class="toast-message">
        <strong>Gagal!</strong>
        <p>Mohon periksa kembali data Anda</p>
    </div>
    <button class="toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
    <div class="toast-progress"></div>
</div>
@endif

<script>
    // Password Toggle
    document.querySelectorAll('.password-toggle').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling.previousElementSibling;
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });
    });

    // Photo Upload Preview
    const photoInput = document.getElementById('photo');
    const previewImage = document.getElementById('previewImage');
    const cameraIcon = document.getElementById('cameraIcon');
    const photoPreview = document.getElementById('photoPreview');

    photoInput.addEventListener('change', function() {
        const file = this.files[0];

        if (file) {
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB');
                this.value = '';
                return;
            }

            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar');
                this.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                cameraIcon.style.display = 'none';
                photoPreview.classList.add('has-image');
            }

            reader.readAsDataURL(file);
        }
    });

    // Floating Label
    document.querySelectorAll('.input-field input').forEach(input => {
        // Set initial state if input has value
        if (input.value) {
            input.closest('.input-group').classList.add('focused');
        }

        input.addEventListener('focus', () => {
            input.closest('.input-group').classList.add('focused');
        });
        
        input.addEventListener('blur', () => {
            if (!input.value) {
                input.closest('.input-group').classList.remove('focused');
            }
        });
    });

    // Auto hide toast
    document.querySelectorAll('.modern-toast').forEach(toast => {
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    });

    // Form validation
    document.querySelector('.modern-form')?.addEventListener('submit', function(e) {
        const terms = document.querySelector('input[name="terms"]');
        if (terms && !terms.checked) {
            e.preventDefault();
            alert('Harap setuju dengan Syarat & Ketentuan yang berlaku');
        }
    });
</script>

</body>
</html>