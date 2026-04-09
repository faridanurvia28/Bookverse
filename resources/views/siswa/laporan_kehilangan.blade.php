@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/siswa/laporan_kehilangan.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

<!-- HEADER -->
<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa fa-book"></i>
        </div>
        <div class="header-text">
            <h3>Laporan Kehilangan Buku</h3>
            <p>Kehilangan buku</p>
        </div>
    </div>
    <img src="{{ asset('img/book.png') }}" class="header-image">
</div>

{{-- FILTER --}}
<div class="filter">
    {{-- SEARCH FORM --}}
    <form method="GET" action="{{ route('laporan-kehilangan.index') }}" id="filterForm">
        <div class="filter-item">
            <i class="fa fa-search"></i>
            <input 
                type="text" 
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari judul buku...">
        </div>

        <!-- FILTER BUTTON MODERN -->
        <button type="button" class="btn-filter-modern" id="filterBtn" onclick="openFilterModal()">
            <i class="fa fa-sliders-h"></i>
            <span class="filter-badge" id="filterBadge" style="display: {{ request('filter') ? 'flex' : 'none' }};">
                {{ request('filter') ? '1' : '0' }}
            </span>
        </button>

        <!-- Hidden input untuk menyimpan nilai filter -->
        <input type="hidden" name="filter" id="filterValue" value="{{ request('filter') }}">

        <!-- Submit Button -->
        <button type="submit" class="btn-search" title="Terapkan Filter">
            <i class="fa fa-check"></i>
            <span>Terapkan</span>
        </button>

        <!-- Clear Button -->
        @if(request('search') || request('date') || request('filter'))
            <a href="{{ route('laporan-kehilangan.index') }}" class="btn-clear" title="Hapus Semua Filter">
                <i class="fa fa-times"></i>
                <span>Hapus</span>
            </a>
        @endif
    </form>
</div>

<!-- MODAL FILTER STATUS -->
<div class="modal-overlay" id="modalFilter" style="display: none;">
    <div class="modal-filter-box">
        <!-- Header Modal dengan dekorasi -->
        <div class="modal-filter-header">
            <div class="header-decoration">
                <div class="header-icon-wrapper">
                    <i class="fa fa-filter"></i>
                </div>
            </div>
            <h3>Filter Laporan Kehilangan</h3>
            <p>Pilih status laporan yang ingin ditampilkan</p>
            <button type="button" class="modal-close" onclick="closeFilterModal()">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="modal-filter-body">
            <div class="filter-options" id="filterOptions">
                <!-- Semua Laporan -->
                <label class="filter-option">
                    <input type="radio" name="filter_radio" value="" 
                           onchange="updateSelectedFilter()"
                           {{ !request('filter') ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon all">
                        <i class="fa fa-list-ul"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Semua Laporan</span>
                        <span class="option-desc">Menampilkan semua laporan kehilangan</span>
                    </div>
                    <div class="option-badge">All</div>
                </label>

                <!-- Belum Dikembalikan -->
                <label class="filter-option">
                    <input type="radio" name="filter_radio" value="belum_dikembalikan" 
                           onchange="updateSelectedFilter()"
                           {{ request('filter') == 'belum_dikembalikan' ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon warning">
                        <i class="fa fa-clock"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Belum Dikembalikan</span>
                        <span class="option-desc">Buku yang belum dikembalikan setelah laporan hilang</span>
                    </div>
                    <div class="option-badge">Pending</div>
                </label>

                <!-- Menunggu Konfirmasi -->
                <label class="filter-option">
                    <input type="radio" name="filter_radio" value="pending" 
                           onchange="updateSelectedFilter()"
                           {{ request('filter') == 'pending' ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon info">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Menunggu Konfirmasi</span>
                        <span class="option-desc">Laporan sedang menunggu persetujuan petugas</span>
                    </div>
                    <div class="option-badge">Waiting</div>
                </label>

                <!-- Sudah Dikembalikan -->
                <label class="filter-option">
                    <input type="radio" name="filter_radio" value="sudah_dikembalikan" 
                           onchange="updateSelectedFilter()"
                           {{ request('filter') == 'sudah_dikembalikan' ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon success">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Sudah Dikembalikan</span>
                        <span class="option-desc">Buku sudah diganti/dikembalikan</span>
                    </div>
                    <div class="option-badge">Done</div>
                </label>

                <!-- Ditolak -->
                <label class="filter-option">
                    <input type="radio" name="filter_radio" value="rejected" 
                           onchange="updateSelectedFilter()"
                           {{ request('filter') == 'rejected' ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon danger">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Ditolak</span>
                        <span class="option-desc">Laporan kehilangan ditolak</span>
                    </div>
                    <div class="option-badge">Reject</div>
                </label>
            </div>

            <!-- Selected Summary -->
            <div class="selected-summary" id="selectedSummary" style="display: {{ request('filter') ? 'flex' : 'none' }};">
                <div class="summary-icon">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div class="summary-text">
                    <span id="selectedFilterText">
                        @if(request('filter') == 'pending')
                            Filter aktif: Menunggu Konfirmasi
                        @elseif(request('filter') == 'belum_dikembalikan')
                            Filter aktif: Belum Dikembalikan
                        @elseif(request('filter') == 'sudah_dikembalikan')
                            Filter aktif: Sudah Dikembalikan
                        @elseif(request('filter') == 'rejected')
                            Filter aktif: Ditolak
                        @else
                            Menampilkan semua laporan
                        @endif
                    </span>
                </div>
                <button type="button" class="summary-clear" onclick="resetFilter()" title="Hapus filter">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Footer Modal -->
        <div class="modal-filter-footer">
            <div class="filter-actions">
                <button type="button" class="btn-reset" onclick="resetFilter()">
                    <i class="fa fa-undo-alt"></i>
                    Reset
                </button>
                <button type="button" class="btn-apply" onclick="applyFilter()">
                    <i class="fa fa-check"></i>
                    Terapkan Filter
                </button>
            </div>
            <div class="filter-info">
                <i class="fa fa-info-circle"></i>
                <span>Pilih salah satu status laporan</span>
            </div>
        </div>
    </div>
</div>

<!-- TABLE -->
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Judul Buku</th>
                <th>Keterangan</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Mengganti</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $item)
            <tr>
                <td>{{ $reports->firstItem() + $loop->index }}</td>
                <td>
                    <div class="book-cell">
                        <i class="fa fa-book book-icon"></i>
                        <span>{{ $item->transaction->book->judul ?? '-' }}</span>
                    </div>
                </td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ optional($item->transaction->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}</td>
                <td>{{ optional($item->tanggal_ganti)->format('d/m/Y') ?? '-' }}</td>
                <td>
                    @php
                        $statusClass = '';
                        $statusIcon = '';
                        
                        if ($item->status === 'pending') {
                            $statusClass = 'status-yellow';
                            $statusIcon = 'fa-clock';
                        } elseif ($item->status === 'sudah_dikembalikan') {
                            $statusClass = 'status-green';
                            $statusIcon = 'fa-check-circle';
                        } elseif ($item->status === 'belum_dikembalikan') {
                            $statusClass = 'status-red';
                            $statusIcon = 'fa-exclamation-triangle';
                        } elseif ($item->status === 'rejected') {
                            $statusClass = 'status-red';
                            $statusIcon = 'fa-times-circle';
                        } else {
                            $statusClass = 'status-gray';
                            $statusIcon = 'fa-circle';
                        }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        <i class="fa {{ $statusIcon }}"></i>
                        @if($item->status === 'pending')
                            Menunggu Konfirmasi
                        @elseif($item->status === 'sudah_dikembalikan')
                            Sudah Dikembalikan
                        @elseif($item->status === 'belum_dikembalikan')
                            Belum Dikembalikan
                        @elseif($item->status === 'rejected')
                            Ditolak
                        @else
                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                        @endif
                    </span>
                </td>
                <td>
                    @if(in_array($item->status, ['belum_dikembalikan', 'rejected']))
                        <form action="{{ route('laporan-kehilangan.kembalikan', $item->id) }}" method="POST" style="display: inline;" class="form-kembalikan">
                            @csrf
                            <button type="button" class="btn-pengembalian" title="Kembalikan" onclick="konfirmasiKembali(this)">
                                <i class="fa fa-rotate-left"></i>
                            </button>
                        </form>
                    @elseif($item->status === 'sudah_dikembalikan')
                        <button type="button"
                           class="btn-action btn-print"
                           onclick="window.open('{{ route('cetak.nota', [$item->id, 'hilang']) }}', '_blank')"
                            title="Cetak Nota">
                        <i class="fa-solid fa-print"></i>
                    </button>
                    @else
                        <span class="no-action">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="empty-state">
                    <i class="fa fa-inbox"></i>
                    <p>Tidak ada laporan kehilangan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    {{-- PAGINATION --}}
    <div class="pagination-wrapper">
        @include('components.pagination', ['paginator' => $reports])
    </div>
</div>

<!-- MODAL KONFIRMASI PENGEMBALIAN -->
<div class="modal-overlay" id="modalPengembalian">
    <div class="modal-box">
        <div class="modal-header">
            <i class="fa fa-rotate-left"></i>
            <h3>Kembalikan Buku</h3>
        </div>

        <div class="modal-body">
            <i class="fa fa-question-circle"></i>
            <p>Apakah kamu yakin ingin mengembalikan buku ini?</p>
            <small class="text-muted">Buku akan dikembalikan ke perpustakaan</small>
        </div>

        <div class="modal-footer">
            <button class="btn-batal" id="btnBatal">Batal</button>
            <button class="btn-ya" id="btnYa">Iya, Kembalikan</button>
        </div>
    </div>
</div>

<script>
// ================= FUNGSI UNTUK MODAL FILTER =================
// Buka modal filter
function openFilterModal() {
    document.getElementById('modalFilter').style.display = 'flex';
}

// Tutup modal filter
function closeFilterModal() {
    document.getElementById('modalFilter').style.display = 'none';
}

// Update selected filter dan badge
function updateSelectedFilter() {
    const selectedRadio = document.querySelector('input[name="filter_radio"]:checked');
    const badge = document.getElementById('filterBadge');
    const summary = document.getElementById('selectedSummary');
    const selectedText = document.getElementById('selectedFilterText');
    
    if (selectedRadio && selectedRadio.value !== '') {
        badge.style.display = 'flex';
        badge.textContent = '1';
        summary.style.display = 'flex';
        
        const filterTexts = {
            'pending': 'Menunggu Konfirmasi',
            'belum_dikembalikan': 'Belum Dikembalikan',
            'sudah_dikembalikan': 'Sudah Dikembalikan',
            'rejected': 'Ditolak'
        };
        
        selectedText.textContent = `Filter aktif: ${filterTexts[selectedRadio.value]}`;
    } else {
        badge.style.display = 'none';
        summary.style.display = 'none';
    }
}

// Reset filter
function resetFilter() {
    const allRadio = document.querySelector('input[name="filter_radio"][value=""]');
    if (allRadio) {
        allRadio.checked = true;
    }
    updateSelectedFilter();
    
    document.getElementById('filterValue').value = '';
    document.getElementById('filterForm').submit();
}

// Apply filter dan submit form
function applyFilter() {
    const selectedRadio = document.querySelector('input[name="filter_radio"]:checked');
    const filterInput = document.getElementById('filterValue');
    
    if (selectedRadio) {
        filterInput.value = selectedRadio.value;
        closeFilterModal();
        document.getElementById('filterForm').submit();
    }
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
        if (modal && modal.style.display === 'flex') {
            closeFilterModal();
        }
    }
});

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    // Set radio button dari URL jika ada filter
    @if(request('filter'))
        const filterValue = '{{ request('filter') }}';
        const radio = document.querySelector(`input[name="filter_radio"][value="${filterValue}"]`);
        if (radio) {
            radio.checked = true;
            updateSelectedFilter();
        }
    @endif

    // Auto-submit form ketika radio dipilih
    const radioButtons = document.querySelectorAll('input[name="filter_radio"]');
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            // Update visual feedback
            updateSelectedFilter();
            // Auto apply filter setelah 300ms untuk smooth UX
            setTimeout(() => {
                applyFilter();
            }, 300);
        });
    });
});

// ================= FUNGSI UNTUK MODAL PENGEMBALIAN =================
let currentForm = null;

function konfirmasiKembali(button) {
    currentForm = button.closest('form');
    document.getElementById('modalPengembalian').style.display = 'flex';
}

document.getElementById('btnBatal')?.addEventListener('click', function () {
    document.getElementById('modalPengembalian').style.display = 'none';
    currentForm = null;
});

document.getElementById('btnYa')?.addEventListener('click', function () {
    if (currentForm) {
        currentForm.submit();
    }
});

// ================= FUNGSI UNTUK TOGGLE FILTER LAMA =================
function toggleFilterKategori() {
    let el = document.getElementById("filterKategori");
    if (window.getComputedStyle(el).display === "none") {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
}

document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.querySelector('.sidebar')?.classList.toggle('active');
});
</script>

@endsection