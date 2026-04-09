<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil User</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/siswa/edit-profil-user.css') }}">
</head>
<body>

<div class="profile-container">
    <h2 class="title">
        <i class="fas fa-user-edit"></i>
        Edit Profil User
    </h2>

    <form id="profileForm">
        @csrf

        <div class="form-grid">
            <!-- Nama -->
            <div class="form-group">
                <label>Nama :</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan Nama Lengkap" value="Budi Santoso">
                <small class="error" id="namaError" style="display: none;">ⓘ Nama wajib diisi!</small>
            </div>

            <!-- Username -->
            <div class="form-group">
                <label>Username :</label>
                <input type="text" id="username" name="username" placeholder="Masukkan Username" value="budi_santoso">
                <small class="error" id="usernameError" style="display: none;">ⓘ Username wajib diisi!</small>
            </div>

            <!-- Jenis Kelamin -->
            <div class="form-group">
                <label>Jenis Kelamin :</label>
                <input type="text" id="jenis_kelamin" name="jenis_kelamin" placeholder="Masukkan Jenis Kelamin" value="Laki-laki">
                <small class="error" id="jkError" style="display: none;">ⓘ Jenis Kelamin wajib diisi!</small>
            </div>

            <!-- NISN -->
            <div class="form-group">
                <label>NISN :</label>
                <input type="text" id="nisn" name="nisn" placeholder="Masukkan NISN" value="1234567890">
                <small class="error" id="nisnError" style="display: none;">ⓘ NISN wajib diisi!</small>
                <small class="error" id="nisnFormatError" style="display: none;">ⓘ NISN harus 10 digit angka!</small>
            </div>

            <!-- Kelas -->
            <div class="form-group">
                <label>Kelas :</label>
                <input type="text" id="kelas" name="kelas" placeholder="Masukkan Kelas" value="XI RPL 1">
                <small class="error" id="kelasError" style="display: none;">ⓘ Kelas wajib diisi!</small>
            </div>

            <!-- No. Telepon -->
            <div class="form-group">
                <label>No. Telepon :</label>
                <input type="text" id="telepon" name="telepon" placeholder="Masukkan No. Telepon" value="081234567890">
                <small class="error" id="teleponError" style="display: none;">ⓘ No. Telepon wajib diisi!</small>
                <small class="error" id="teleponFormatError" style="display: none;">ⓘ Format No. Telepon tidak valid!</small>
            </div>
        </div>

        <div class="btn-wrapper">
            <button type="submit" class="btn-save">
                <span>Simpan</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </form>
</div>

<script>
    const form = document.getElementById('profileForm');
    const nama = document.getElementById('nama');
    const username = document.getElementById('username');
    const jenisKelamin = document.getElementById('jenis_kelamin');
    const nisn = document.getElementById('nisn');
    const kelas = document.getElementById('kelas');
    const telepon = document.getElementById('telepon');

    const namaError = document.getElementById('namaError');
    const usernameError = document.getElementById('usernameError');
    const jkError = document.getElementById('jkError');
    const nisnError = document.getElementById('nisnError');
    const nisnFormatError = document.getElementById('nisnFormatError');
    const kelasError = document.getElementById('kelasError');
    const teleponError = document.getElementById('teleponError');
    const teleponFormatError = document.getElementById('teleponFormatError');

    // Validasi Nama
    function validateNama() {
        if (nama.value.trim() === '') {
            namaError.style.display = 'flex';
            nama.classList.add('error-input');
            return false;
        } else {
            namaError.style.display = 'none';
            nama.classList.remove('error-input');
            return true;
        }
    }

    // Validasi Username
    function validateUsername() {
        if (username.value.trim() === '') {
            usernameError.style.display = 'flex';
            username.classList.add('error-input');
            return false;
        } else {
            usernameError.style.display = 'none';
            username.classList.remove('error-input');
            return true;
        }
    }

    // Validasi Jenis Kelamin
    function validateJenisKelamin() {
        if (jenisKelamin.value.trim() === '') {
            jkError.style.display = 'flex';
            jenisKelamin.classList.add('error-input');
            return false;
        } else {
            jkError.style.display = 'none';
            jenisKelamin.classList.remove('error-input');
            return true;
        }
    }

    // Validasi NISN
    function validateNISN() {
        const nisnValue = nisn.value.trim();
        const nisnRegex = /^[0-9]{10}$/; // 10 digit angka
        
        if (nisnValue === '') {
            nisnError.style.display = 'flex';
            nisnFormatError.style.display = 'none';
            nisn.classList.add('error-input');
            return false;
        } else if (!nisnRegex.test(nisnValue)) {
            nisnError.style.display = 'none';
            nisnFormatError.style.display = 'flex';
            nisn.classList.add('error-input');
            return false;
        } else {
            nisnError.style.display = 'none';
            nisnFormatError.style.display = 'none';
            nisn.classList.remove('error-input');
            return true;
        }
    }

    // Validasi Kelas
    function validateKelas() {
        if (kelas.value.trim() === '') {
            kelasError.style.display = 'flex';
            kelas.classList.add('error-input');
            return false;
        } else {
            kelasError.style.display = 'none';
            kelas.classList.remove('error-input');
            return true;
        }
    }

    // Validasi No. Telepon
    function validateTelepon() {
        const telp = telepon.value.trim();
        const telpRegex = /^[0-9]{10,13}$/; // 10-13 digit angka
        
        if (telp === '') {
            teleponError.style.display = 'flex';
            teleponFormatError.style.display = 'none';
            telepon.classList.add('error-input');
            return false;
        } else if (!telpRegex.test(telp)) {
            teleponError.style.display = 'none';
            teleponFormatError.style.display = 'flex';
            telepon.classList.add('error-input');
            return false;
        } else {
            teleponError.style.display = 'none';
            teleponFormatError.style.display = 'none';
            telepon.classList.remove('error-input');
            return true;
        }
    }

    // Format No. Telepon otomatis (hanya angka)
    telepon.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        validateTelepon();
    });

    // Format NISN otomatis (hanya angka)
    nisn.addEventListener('input', function(e) {
        this.value = this.value.replace(/[^0-9]/g, '');
        validateNISN();
    });

    // Real-time validation
    nama.addEventListener('input', validateNama);
    username.addEventListener('input', validateUsername);
    jenisKelamin.addEventListener('input', validateJenisKelamin);
    nisn.addEventListener('input', validateNISN);
    kelas.addEventListener('input', validateKelas);
    telepon.addEventListener('input', validateTelepon);

    // Initial validation (untuk mengecek value default)
    document.addEventListener('DOMContentLoaded', function() {
        validateNama();
        validateUsername();
        validateJenisKelamin();
        validateNISN();
        validateKelas();
        validateTelepon();
    });

    // Form submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const isNamaValid = validateNama();
        const isUsernameValid = validateUsername();
        const isJKValid = validateJenisKelamin();
        const isNISNValid = validateNISN();
        const isKelasValid = validateKelas();
        const isTeleponValid = validateTelepon();

        if (isNamaValid && isUsernameValid && isJKValid && isNISNValid && isKelasValid && isTeleponValid) {
            // Simulasi sukses
            alert('Profil berhasil diperbarui! (demo)');
            
            // Tambahkan class success ke form-group
            document.querySelectorAll('.form-group').forEach(group => {
                group.classList.add('success');
            });
            
            // form.submit(); // uncomment untuk submit beneran
        }
    });

    // Hapus class success saat mulai mengetik
    nama.addEventListener('focus', () => {
        nama.closest('.form-group').classList.remove('success');
    });
    
    username.addEventListener('focus', () => {
        username.closest('.form-group').classList.remove('success');
    });
    
    jenisKelamin.addEventListener('focus', () => {
        jenisKelamin.closest('.form-group').classList.remove('success');
    });
    
    nisn.addEventListener('focus', () => {
        nisn.closest('.form-group').classList.remove('success');
    });
    
    kelas.addEventListener('focus', () => {
        kelas.closest('.form-group').classList.remove('success');
    });
    
    telepon.addEventListener('focus', () => {
        telepon.closest('.form-group').classList.remove('success');
    });
</script>

</body>
</html>