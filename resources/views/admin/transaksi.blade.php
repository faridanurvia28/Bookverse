@extends('layouts.app')

@section('title', 'Transaksi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/transaksi.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin/card.css') }}">
@endpush

@section('content')

<!-- HEADER -->
<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa fa-user"></i>
        </div>
        <div>
            <h3>Transaksi</h3>
            <p>Pengembalian dan Peminjaman Buku</p>
        </div>
    </div>

</div>

<!-- TAB -->
<div class="top-action">
    <div class="tabs">
        <a href="?mode=peminjaman"
           class="tab {{ ($mode ?? 'peminjaman') == 'peminjaman' ? 'active' : '' }}">
            Peminjaman
        </a>
        <a href="?mode=pengembalian"
           class="tab {{ ($mode ?? '') == 'pengembalian' ? 'active' : '' }}">
            Pengembalian
        </a>
    </div>
</div>

<!-- FILTER -->
<div class="filter">
    <form method="GET" style="display:flex; gap:10px; align-items:center;">
        <input type="hidden" name="mode" value="{{ $mode }}">
        <div class="search">
            <i class="icon fa fa-search"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sesuatu...">
        </div>

<!-- FILTER BUTTON MODERN -->
        <button type="button" class="btn-filter-modern" id="filterBtn" onclick="openFilterModal()">
            <i class="fa fa-sliders-h"></i>
            <span class="filter-badge" id="filterBadge" style="display: {{ 
                request('filter') && count(request('filter')) > 0 ? 'flex' : 'none' 
            }};">
                {{ request('filter') ? count(request('filter')) : 0 }}
            </span>
        </button>

        <!-- MODAL FILTER STATUS -->
        <div class="modal-overlay" id="modalFilter" style="display: none;">
            <div class="modal-filter-box">
                <!-- Header Modal -->
                <div class="modal-filter-header">
                    <div class="header-icon">
                        <i class="fa fa-filter"></i>
                    </div>
                    <h3> Status Transaksi</h3>
                    <p>Pilih status transaksi yang ingin ditampilkan</p>
                    <button type="button" class="modal-close" onclick="closeFilterModal()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="modal-filter-body">

                    <div class="status-list" id="statusList">
                        <!-- Menunggu Konfirmasi -->
                        <label class="status-item">
                            <input type="checkbox" name="filter_status[]" value="menunggu_konfirmasi" 
                                   onchange="updateSelectedCount()"
                                   {{ in_array('menunggu_konfirmasi', (array)request('filter', [])) ? 'checked' : '' }}>
                            <span class="status-checkbox">
                                <i class="fa fa-check"></i>
                            </span>
                            <span class="status-icon warning">
                                <i class="fa fa-clock"></i>
                            </span>
                            <span class="status-info">
                                <span class="status-title">Menunggu Konfirmasi</span>
                                <span class="status-desc">Transaksi yang sedang menunggu persetujuan</span>
                            </span>
                        </label>

                        <!-- Belum Dikembalikan -->
                        <label class="status-item">
                            <input type="checkbox" name="filter_status[]" value="belum_dikembalikan" 
                                   onchange="updateSelectedCount()"
                                   {{ in_array('belum_dikembalikan', (array)request('filter', [])) ? 'checked' : '' }}>
                            <span class="status-checkbox">
                                <i class="fa fa-check"></i>
                            </span>
                            <span class="status-icon info">
                                <i class="fa fa-book"></i>
                            </span>
                            <span class="status-info">
                                <span class="status-title">Belum Dikembalikan</span>
                                <span class="status-desc">Buku masih dipinjam dan belum dikembalikan</span>
                            </span>
                        </label>

                        <!-- Sudah Dikembalikan -->
                        <label class="status-item">
                            <input type="checkbox" name="filter_status[]" value="sudah_dikembalikan" 
                                   onchange="updateSelectedCount()"
                                   {{ in_array('sudah_dikembalikan', (array)request('filter', [])) ? 'checked' : '' }}>
                            <span class="status-checkbox">
                                <i class="fa fa-check"></i>
                            </span>
                            <span class="status-icon success">
                                <i class="fa fa-check-circle"></i>
                            </span>
                            <span class="status-info">
                                <span class="status-title">Sudah Dikembalikan</span>
                                <span class="status-desc">Buku telah dikembalikan tepat waktu</span>
                            </span>
                        </label>

                        <!-- Terlambat -->
                        <label class="status-item">
                            <input type="checkbox" name="filter_status[]" value="terlambat" 
                                   onchange="updateSelectedCount()"
                                   {{ in_array('terlambat', (array)request('filter', [])) ? 'checked' : '' }}>
                            <span class="status-checkbox">
                                <i class="fa fa-check"></i>
                            </span>
                            <span class="status-icon danger">
                                <i class="fa fa-exclamation-triangle"></i>
                            </span>
                            <span class="status-info">
                                <span class="status-title">Terlambat</span>
                                <span class="status-desc">Pengembalian melebihi batas waktu</span>
                            </span>
                        </label>

                        <!-- Buku Hilang -->
                        <label class="status-item">
                            <input type="checkbox" name="filter_status[]" value="buku_hilang" 
                                   onchange="updateSelectedCount()"
                                   {{ in_array('buku_hilang', (array)request('filter', [])) ? 'checked' : '' }}>
                            <span class="status-checkbox">
                                <i class="fa fa-check"></i>
                            </span>
                            <span class="status-icon dark">
                                <i class="fa fa-times-circle"></i>
                            </span>
                            <span class="status-info">
                                <span class="status-title">Buku Hilang</span>
                                <span class="status-desc">Buku dinyatakan hilang</span>
                            </span>
                        </label>
                    </div>

                    <!-- Selected Summary -->
                    <div class="selected-summary" id="selectedSummary" style="display: none;">
                        <i class="fa fa-check-circle"></i>
                        <span id="selectedCount">0</span> status dipilih
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="modal-filter-footer">
                    <div class="filter-actions">
                        <button type="button" class="btn-reset" onclick="resetFilters()">
                            <i class="fa fa-undo-alt"></i>
                            Reset
                        </button>
                        <button type="button" class="btn-apply" onclick="applyFilters()">
                            <i class="fa fa-check"></i>
                            Terapkan Filter
                        </button>
                    </div>
                    <div class="filter-info">
                        <i class="fa fa-info-circle"></i>
                        <span>Anda dapat memilih lebih dari satu status</span>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @auth
    <a href="{{ route('cetak.filter-transaksi') }}" class="btn-print">
        <i class="fa-solid fa-print"></i>
        Cetak Laporan
    </a>
    @endauth
</div>

{{-- ================= PEMINJAMAN ================= --}}
@if(($mode ?? 'peminjaman') == 'peminjaman')
<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Anggota</th>
    <th>Judul Buku</th>
    <th>Kelas</th>
    <th>Tgl Pinjam</th>
    <th>Jatuh Tempo</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $transactions->firstItem() + $loop->index }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
        @if($trx->status == 'belum_dikembalikan')
            <span class="status blue">Belum Dikembalikan</span>
        @elseif($trx->status == 'buku_hilang')
            <span class="status danger">Buku Hilang</span>
        @elseif($trx->status == 'terlambat')
            <span class="status warning">Terlambat</span>
        @endif
    </td>
    <td class="aksi">
        @if($trx->status == 'belum_dikembalikan')
            <span class="btn-filter btn-nota" onclick="window.open('{{ route('cetak.nota', [$trx->id, 'peminjaman']) }}','_blank')">
                <i class="fa-solid fa-print"></i>
            </span>
        @else
            <span>-</span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="8" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>

<tfoot>
<tr>
    <td colspan="8">
        @include('components.pagination', ['paginator' => $transactions])
    </td>
</tr>
</tfoot>
</table>
</div>
@endif

{{-- ================= PENGEMBALIAN ================= --}}
@if(($mode ?? '') == 'pengembalian')
<div class="table-wrapper">
<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Anggota</th>
    <th>Judul Buku</th>
    <th>Kelas</th>
    <th>Jatuh Tempo</th>
    <th>Status</th>
    <th>Tgl Kembali</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($transactions as $trx)
<tr>
    <td>{{ $transactions->firstItem() + $loop->index }}</td>
    <td>{{ $trx->user->name ?? '-' }}</td>
    <td>{{ $trx->book->judul ?? '-' }}</td>
    <td>{{ $trx->user->kelas ?? '-' }}</td>
    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}</td>
    <td>
        @if($trx->status == 'menunggu_konfirmasi')
            <span class="status warning">Menunggu Persetujuan</span>
        @elseif($trx->status == 'sudah_dikembalikan')
            <span class="status success">Sudah Dikembalikan</span>
        @endif
    </td>
    <td>{{ $trx->tanggal_pengembalian ? $trx->tanggal_pengembalian->format('d/m/Y') : '-' }}</td>
    <td class="aksi" style="display:flex; gap:5px; justify-content:center;">
        @if($trx->status == 'menunggu_konfirmasi')
        <form action="{{ route('transactions.terimaPengembalian', $trx->id) }}" method="POST" onsubmit="return confirm('Terima pengembalian buku ini?')">
            @csrf
            @method('PUT')
            <button type="submit" class="btn-green">✔</button>
        </form>
        <form action="{{ route('transactions.tolakPengembalian', $trx->id) }}" method="POST" onsubmit="return confirm('Tolak pengembalian buku ini?')">
            @csrf
            @method('PUT')
            <button type="submit" class="btn-red">✖</button>
        </form>
        @elseif($trx->status == 'sudah_dikembalikan')
        <span class="btn-filter btn-nota" onclick="window.open('{{ route('cetak.nota', [$trx->id, 'pengembalian']) }}','_blank')">
            <i class="fa-solid fa-print"></i>
        </span>
        @endif
    </td>
</tr>
@empty
<tr>
    <td colspan="8" style="text-align:center">Tidak ada data</td>
</tr>
@endforelse
</tbody>

<tfoot>
<tr>
    <td colspan="8">
        @include('components.pagination', ['paginator' => $transactions])
    </td>
</tr>
</tfoot>
</table>
</div>
@endif
<script>
// Fungsi untuk toggle dropdown filter lama (jika masih digunakan)
function toggleFilterKategori() {
    let el = document.getElementById("filterKategori");
    if (el.style.display === "none" || el.style.display === "") {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
}

// ================= FUNGSI UNTUK MODAL FILTER =================
// Buka modal filter
function openFilterModal() {
    document.getElementById('modalFilter').style.display = 'flex';
    updateSelectedCount();
}

// Tutup modal filter
function closeFilterModal() {
    document.getElementById('modalFilter').style.display = 'none';
}

// Update jumlah status yang dipilih
function updateSelectedCount() {
    const checkboxes = document.querySelectorAll('input[name="filter_status[]"]:checked');
    const count = checkboxes.length;
    const badge = document.getElementById('filterBadge');
    const summary = document.getElementById('selectedSummary');
    const selectedCount = document.getElementById('selectedCount');
    
    if (count > 0) {
        badge.style.display = 'flex';
        badge.textContent = count;
        summary.style.display = 'flex';
        selectedCount.textContent = count;
    } else {
        badge.style.display = 'none';
        summary.style.display = 'none';
    }
}

// Search status list
function searchStatusList() {
    const input = document.getElementById('searchStatus');
    const filter = input.value.toLowerCase();
    const items = document.querySelectorAll('.status-item');
    
    items.forEach(item => {
        const title = item.querySelector('.status-title').textContent.toLowerCase();
        const desc = item.querySelector('.status-desc').textContent.toLowerCase();
        
        if (title.includes(filter) || desc.includes(filter)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
}

// Reset semua filter
function resetFilters() {
    document.querySelectorAll('input[name="filter_status[]"]').forEach(cb => {
        cb.checked = false;
    });
    updateSelectedCount();
}

// Apply filters dan submit form
function applyFilters() {
    const form = document.querySelector('.filter form');
    const checkboxes = document.querySelectorAll('input[name="filter_status[]"]:checked');
    
    // Hapus input filter yang sudah ada
    const existingFilters = document.querySelectorAll('input[name="filter[]"]');
    existingFilters.forEach(input => input.remove());
    
    // Tambahkan input baru untuk setiap status yang dipilih
    checkboxes.forEach(cb => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'filter[]';
        input.value = cb.value;
        form.appendChild(input);
    });
    
    // Submit form
    form.submit();
}

// Tutup modal saat klik di luar
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modalFilter');
    const modalBox = document.querySelector('.modal-filter-box');
    const btnFilter = document.getElementById('filterBtn');
    
    if (modal && modal.style.display === 'flex') {
        if (!modalBox.contains(e.target) && !btnFilter.contains(e.target)) {
            closeFilterModal();
        }
    }
});

// Escape key untuk tutup modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('modalFilter');
        if (modal.style.display === 'flex') {
            closeFilterModal();
        }
    }
});

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    updateSelectedCount();
});
</script>

@endsection