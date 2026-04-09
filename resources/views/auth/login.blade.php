<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perpustakaan Digital SMKN 4 Bojonegoro</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    {{-- Font Modern --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Clash+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Particle Background -->
<div id="particles-js"></div>

<div class="modern-wrapper">
    <div class="glass-card">
        
        <!-- LEFT SIDE - ILLUSTRATION -->
        <div class="illustration-side">
            <div class="illustration-content">
                <div class="floating-badge badge-1">
                    <i class="fas fa-book"></i>
                    <span>5.000+ Buku</span>
                </div>
                <div class="floating-badge badge-2">
                    <i class="fas fa-users"></i>
                    <span>1.200+ Anggota</span>
                </div>
                <div class="floating-badge badge-3">
                    <i class="fas fa-star"></i>
                    <span>Literasi Digital</span>
                </div>

                <!-- Main Illustration - Menggunakan gambar berbeda (Undraw style) -->
                <div class="illustration-main">
                    <img src="https://cdn-icons-png.flaticon.com/512/2942/2942789.png" alt="Reading illustration" class="main-img">
                    <div class="img-glow"></div>
                </div>

                <div class="illustration-text">
                    <h2>Selamat Datang di</h2>
                    <h3>Perpustakaan Digital</h3>
                    <p>SMKN 4 Bojonegoro</p>
                </div>

                <div class="progress-ring">
                    <svg viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>
                        <circle cx="50" cy="50" r="45" fill="none" stroke="white" stroke-width="3" stroke-dasharray="283" stroke-dashoffset="70" stroke-linecap="round" transform="rotate(-90 50 50)"/>
                    </svg>
                    <div class="progress-text">
                        <span class="progress-number">75%</span>
                        <span class="progress-label">Literasi Meningkat</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE - LOGIN FORM -->
        <div class="form-side">
            <div class="form-wrapper">
                <!-- Logo Kecil -->
                <div class="small-logo">
                    <i class="fas fa-book-open"></i>
                </div>

                <div class="welcome-text">
                    <h1>Masuk ke Akun</h1>
                    <p>Silakan masuk untuk mengakses perpustakaan digital</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="modern-form">
                    @csrf

                    <!-- Username Field with Floating Label -->
                    <div class="floating-group">
                        <div class="input-icon-wrapper">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                name="username"
                                id="username"
                                placeholder=" "
                                value="{{ old('username') }}"
                                required
                                class="{{ $errors->has('username') ? 'error' : '' }}"
                            >
                            <label for="username">Username</label>
                        </div>
                        <div class="input-focus-bg"></div>
                    </div>
                    @error('username')
                        <div class="error-tooltip">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- Password Field with Floating Label -->
                    <div class="floating-group">
                        <div class="input-icon-wrapper">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="input-wrapper">
                            <input 
                                type="password" 
                                name="password"
                                id="password"
                                placeholder=" "
                                required
                                class="pwd-input {{ $errors->has('password') ? 'error' : '' }}"
                            >
                            <label for="password">Password</label>
                            <button type="button" class="password-toggle" tabindex="-1">
                                <i class="far fa-eye-slash"></i>
                            </button>
                        </div>
                        <div class="input-focus-bg"></div>
                    </div>
                    @error('password')
                        <div class="error-tooltip">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- Options -->
                    <div class="form-extras">
                        <label class="checkbox-style">
                            <input type="checkbox" name="remember">
                            <span class="checkbox-box">
                                <i class="fas fa-check"></i>
                            </span>
                            <span class="checkbox-label">Ingat saya</span>
                        </label>
                        
                        <a href="#" class="forgot-link">
                            <i class="fas fa-chevron-right"></i>
                            <span>Lupa password?</span>
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="submit-btn">
                        <span>Masuk</span>
                        <i class="fas fa-arrow-right"></i>
                        <div class="btn-shine"></div>
                    </button>

                    <!-- Register Link -->
                    <div class="register-section">
                        <p>Belum punya akun?</p>
                        <a href="{{ route('home') }}#register" class="register-link">
                            <i class="fas fa-user-plus"></i>
                            <span>Daftar Sekarang</span>
                        </a>
                    </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<!-- Toast Notifications -->
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

@if(session('error'))
<div class="modern-toast error show">
    <div class="toast-icon">
        <i class="fas fa-exclamation-circle"></i>
    </div>
    <div class="toast-message">
        <strong>Gagal!</strong>
        <p>{{ session('error') }}</p>
    </div>
    <button class="toast-close" onclick="this.parentElement.remove()">
        <i class="fas fa-times"></i>
    </button>
    <div class="toast-progress"></div>
</div>
@endif

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
    // Particles Background
    particlesJS('particles-js', {
        particles: {
            number: { value: 80, density: { enable: true, value_area: 800 } },
            color: { value: ['#FCA5F1', '#B5FFFF', '#ffffff'] },
            shape: { type: 'circle' },
            opacity: { value: 0.5, random: true },
            size: { value: 3, random: true },
            line_linked: { enable: false },
            move: { enable: true, speed: 2, direction: 'none', random: true, straight: false }
        },
        interactivity: {
            detect_on: 'canvas',
            events: {
                onhover: { enable: true, mode: 'repulse' },
                onclick: { enable: true, mode: 'push' }
            }
        }
    });

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

    // Auto hide toast
    document.querySelectorAll('.modern-toast').forEach(toast => {
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 500);
        }, 5000);
    });

    // Floating Label
    document.querySelectorAll('.floating-group input').forEach(input => {
        input.addEventListener('focus', () => {
            input.closest('.floating-group').classList.add('focused');
        });
        
        input.addEventListener('blur', () => {
            if (!input.value) {
                input.closest('.floating-group').classList.remove('focused');
            }
        });
        
        // Check initial value
        if (input.value) {
            input.closest('.floating-group').classList.add('focused');
        }
    });
</script>

</body>
</html>