@extends('layouts.app')
@section('title', 'Profile Saya')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
    <div class="profile-header" style="background: linear-gradient(135deg, #FCA5F1, #B5FFFF); color: #1E293B; padding: 1.5rem 2rem; border-radius: 24px; margin-bottom: 2rem; box-shadow: 0 10px 25px -5px rgba(252, 165, 241, 0.4);">
        <h2 style="margin:0; font-size: 26px; font-weight: 700;">Profile Saya</h2>
    </div>

    <div class="profile-grid" style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: start;">
        <!-- Kartu kiri: foto & nama -->
        <div class="card" style="background: white; border-radius: 24px; padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08); border: 1px solid rgba(255, 255, 255, 0.5); text-align: center; position: relative; overflow: hidden;">
            <div class="avatar1" onclick="openModal()" style="position: relative; width: 140px; height: 140px; margin: 0 auto 1rem; cursor: pointer;">
                <img src="{{ auth()->user()->profile_photo ? asset('storage/'.auth()->user()->profile_photo) : asset('img/avatar.png') }}" 
                     alt="Avatar1" style="width:100%; height:100%; object-fit:cover; border-radius:50%; border:4px solid #FCA5F1;">
                <span class="edit-icon" style="position:absolute; bottom:10px; right:10px; background: linear-gradient(145deg, #FCA5F1, #B5FFFF); color:#1E293B; width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; border:2px solid white; box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.05);">
                    <i class="fa fa-pen"></i>
                </span>
            </div>
            <h3 style="margin: 0.5rem 0 0.25rem; color: #1E293B;">{{ auth()->user()->name }}</h3>
            <span class="role" style="display:inline-block; background:#FFF0FD; color:#FCA5F1; font-weight:600; padding:0.4rem 1.4rem; border-radius:999px; font-size:13px; box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.05);">{{ ucfirst(auth()->user()->role) }}</span>
        </div>

        <!-- Kartu kanan: data diri -->
        <div class="card" style="background: white; border-radius: 24px; padding: 1.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08); border: 1px solid rgba(255, 255, 255, 0.5); position: relative; overflow: hidden;">
            <h4 style="font-size:1.25rem; margin-bottom:1.5rem; border-bottom:3px solid #FCA5F1; padding-bottom:0.75rem; display:inline-block; color:#1E293B; font-weight:700;">Data Diri</h4>
            
            <div style="margin-bottom:0.75rem; display:flex; padding: 8px 12px; border-radius: 12px; transition: all 0.3s ease;">
                <strong style="min-width:120px; color:#64748B; font-weight:600; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-user" style="color:#FCA5F1; width:20px;"></i> Nama
                </strong>
                <span style="color:#334155; font-weight:500;">: {{ auth()->user()->name }}</span>
            </div>
            
            <div style="margin-bottom:0.75rem; display:flex; padding: 8px 12px; border-radius: 12px; transition: all 0.3s ease;">
                <strong style="min-width:120px; color:#64748B; font-weight:600; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-id-card" style="color:#FCA5F1; width:20px;"></i> Username
                </strong>
                <span style="color:#334155; font-weight:500;">: {{ auth()->user()->username }}</span>
            </div>
            
            <div style="margin-bottom:0.75rem; display:flex; padding: 8px 12px; border-radius: 12px; transition: all 0.3s ease;">
                <strong style="min-width:120px; color:#64748B; font-weight:600; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-phone" style="color:#FCA5F1; width:20px;"></i> No. Telepon
                </strong>
                <span style="color:#334155; font-weight:500;">: {{ auth()->user()->telephone ?? '-' }}</span>
            </div>
            
            <div style="margin-bottom:0.75rem; display:flex; padding: 8px 12px; border-radius: 12px; transition: all 0.3s ease;">
                <strong style="min-width:120px; color:#64748B; font-weight:600; display:flex; align-items:center; gap:8px;">
                    <i class="fas fa-map-marker-alt" style="color:#FCA5F1; width:20px;"></i> Alamat
                </strong>
                <span style="color:#334155; font-weight:500;">: {{ auth()->user()->alamat ?? '-' }}</span>
            </div>
            
            @if(auth()->user()->role == 'anggota')
                <div style="margin-bottom:0.75rem; display:flex; padding: 8px 12px; border-radius: 12px; transition: all 0.3s ease;">
                    <strong style="min-width:120px; color:#64748B; font-weight:600; display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-graduation-cap" style="color:#FCA5F1; width:20px;"></i> Kelas
                    </strong>
                    <span style="color:#334155; font-weight:500;">: {{ auth()->user()->kelas ?? '-' }}</span>
                </div>
                
                <div style="margin-bottom:0.75rem; display:flex; padding: 8px 12px; border-radius: 12px; transition: all 0.3s ease;">
                    <strong style="min-width:120px; color:#64748B; font-weight:600; display:flex; align-items:center; gap:8px;">
                        <i class="fas fa-id-card" style="color:#FCA5F1; width:20px;"></i> NISN
                    </strong>
                    <span style="color:#334155; font-weight:500;">: {{ auth()->user()->nis_nisn ?? '-' }}</span>
                </div>
            @endif
            
            <a href="{{ route('profile.edit') }}" class="btn-edit" style="position:absolute; top:1.5rem; right:1.5rem; background: linear-gradient(145deg, #FCA5F1, #B5FFFF); color:#1E293B; padding:0.6rem 1.5rem; border-radius:999px; text-decoration:none; display:inline-flex; align-items:center; gap:0.6rem; font-weight:600; font-size:13px; box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.05); border:none;">
                <i class="fa fa-pen"></i> Edit Profil
            </a>
        </div>
    </div>

    @if(auth()->user()->role == 'anggota')
    <div class="card" style="background: white; border-radius: 24px; padding:1.5rem; margin-top:1.5rem; overflow-x:auto; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.08); border: 1px solid rgba(255, 255, 255, 0.5);">
        <h4 style="margin-bottom:1.2rem; font-size:1.2rem; font-weight:600; border-bottom:2px solid #FCA5F1; padding-bottom:0.5rem; display:inline-block; color:#1E293B;">Riwayat Kunjungan Saya</h4>
        
        <table style="width:100%; border-collapse:collapse; min-width:500px;">
            <thead>
                <tr style="background: linear-gradient(145deg, #FCA5F1, #B5FFFF);">
                    <th style="padding:1rem; text-align:left; color:#1E293B; font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Aktivitas</th>
                    <th style="padding:1rem; text-align:left; color:#1E293B; font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Nama Buku</th>
                    <th style="padding:1rem; text-align:left; color:#1E293B; font-size:13px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                <tr style="border-bottom:1px solid #E2E8F0; transition: all 0.3s ease;">
                    <td style="padding:1rem; color:#334155;">
                        <span style="display:inline-flex; align-items:center; gap:6px; padding:4px 12px; border-radius:999px; font-size:12px; font-weight:600; background:#F0FFFF; color:#B5FFFF;">
                            <i class="fas fa-book"></i> {{ $item->transaction->jenis_transaksi ?? '-' }}
                        </span>
                    </td>
                    <td style="padding:1rem; color:#334155; font-weight:500;">{{ $item->transaction->book->judul ?? '-' }}</td>
                    <td style="padding:1rem; color:#334155;">
                        <span style="display:inline-flex; align-items:center; gap:6px;">
                            <i class="fas fa-calendar-alt" style="color:#FCA5F1;"></i>
                            {{ \Carbon\Carbon::parse($item->tanggal_datang)->format('d M Y') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="padding:2rem; text-align:center; color:#64748B; font-style:italic;">
                        <i class="fas fa-book-open" style="font-size: 48px; color: #E2E8F0; margin-bottom: 15px; display: block;"></i>
                        Belum ada riwayat kunjungan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif

<!-- MODAL EDIT FOTO -->
@include('auth.profile.edit-foto-modal')

<style>
/* Hover effects */
.card > div:not(.avatar1):not(h4):not(.btn-edit):hover {
    background: linear-gradient(90deg, #FFF0FD, #F0FFFF);
    transform: translateX(5px);
}

tbody tr:hover {
    background: linear-gradient(90deg, #FFF0FD, #F0FFFF);
}

.btn-edit:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 10px 25px -5px rgba(252, 165, 241, 0.4);
}

.btn-edit:hover i {
    transform: rotate(10deg) scale(1.1);
}

.edit-icon {
    animation: pulse 2s ease-in-out infinite;
}

.edit-icon:hover {
    animation: none;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #F8FAFC;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #FCA5F1, #B5FFFF);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #FFB6F5, #C5FFFF);
}
</style>

<script>
    function openModal() {
        document.getElementById('modalOverlay').style.display = 'flex';
    }
    function closeModal() {
        document.getElementById('modalOverlay').style.display = 'none';
    }
</script>
@endsection