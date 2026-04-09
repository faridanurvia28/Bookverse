@extends('layouts.app')
@section('title', 'Profil Siswa')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/profile-siswa.css') }}">
@endpush
@section('content')

        <!-- HEADER -->
        <div class="header">
            <h2>Profile saya</h2>
        </div>

        <!-- PROFILE CARD -->
        <div class="profile-wrapper">

            @php $user = Auth::user(); @endphp
            <div class="card profile-card">
                <div class="avatar">
                    <img id="profileAvatar" src="{{ $user->profile_photo ? asset('storage/'. $user->profile_photo) : asset('img/avatar.png') }}" alt="{{ $user->name }}">
                    <span class="edit-icon" onclick="window.location='{{ route('profile.edit') }}'"><i class="fa fa-pen"></i></span>
                </div>
                <h3>{{ $user->name ?? 'Robert O\'Keefe' }}</h3>
                <span class="role">{{ ucfirst($user->role ?? 'anggota') }}</span>
            </div>

            <div class="card biodata-card">
                <h4>Data diri</h4>
                <div class="biodata">
                    <p><strong>Nama</strong> : aurellya.amanda.p.a</p>
                    <p><strong>Jenis Kelamin</strong> : Perempuan</p>
                    <p><strong>Kelas</strong> : XII - RPL 1</p>
                    <p><strong>Username</strong> : aurellya.m</p>
                    <p><strong>NISN</strong> : 12345678</p>
                    <p><strong>No.Telepon</strong> : 081914547345</p>
                </div>
            </div>
        </div>

        <!-- TABLE -->
        <div class="card table-card">
            <h4>Riwayat kunjungan</h4>
            <table>
                <thead>
                    <tr>
                        <th>Aktivitas</th>
                        <th>nama_buku</th>
                        <th>Tanggal Datang</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Aurellya</td>
                        <td>Peminjaman</td>
                        <td>24/01/2026</td>
                    </tr>
                    <tr>
                        <td>Aurellya</td>
                        <td>Pengembalian</td>
                        <td>23/01/2026</td>
                    </tr>
                    <tr>
                        <td>Aurellya</td>
                        <td>Peminjaman</td>
                        <td>22/01/2026</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <button class="btn-edit"><i class="fa fa-pen"></i> edit profil</button>

    </main>
</div>

<!-- JS (DIGABUNG) -->
<script>
    const userBtn = document.getElementById('userBtn');
    const dropdown = document.getElementById('dropdown');

    userBtn.addEventListener('click', () => {
        dropdown.classList.toggle('show');
    });

    // Update avatar saat URL avatar disimpan ke localStorage (dari modal upload atau Ajax callback)
    window.addEventListener('storage', function(event) {
        if (event.key === 'profile_avatar_url') {
            const avatarImg = document.getElementById('profileAvatar');
            if (avatarImg && event.newValue) {
                avatarImg.src = event.newValue;
            }
        }
    });

    // Jika buka ulang atau berpindah halaman, pakai token update yang disimpan
    const pendingAvatar = localStorage.getItem('profile_avatar_url');
    if (pendingAvatar) {
        document.getElementById('profileAvatar').src = pendingAvatar;
        localStorage.removeItem('profile_avatar_url');
    }
</script>

@endsection
