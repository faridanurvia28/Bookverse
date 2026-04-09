@extends('layouts.app')

@section('title', 'Laporan Kehilangan Buku')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/laporan_data_kehilangan.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')

<div class="header-card">
    <div class="header-left">
        <div class="header-icon">
            <i class="fa fa-file"></i>
        </div>
        <div class="header-text">
            <h3>Laporan Kehilangan Buku</h3>
            <p>Peminjaman dan pengembalian buku</p>
        </div>
    </div>
</div>

{{-- FILTER --}}
<div class="filter">
    {{-- SEARCH --}}
    <form method="GET" style="display:flex; gap:10px; align-items:center;" id="filterForm">
        <div class="search">
            <i class="fa fa-search"></i>
            <input 
                type="text" 
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari sesuatu...">
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
    </form>

    @auth
    <a href="{{ route('cetak.filter-kehilangan') }}" class="btn-print">
        <i class="fa-solid fa-print"></i>
        Cetak Laporan
    </a>
    @endauth
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
            <h3>Filter Status Laporan</h3>
            <p>Pilih status laporan kehilangan yang ingin ditampilkan</p>
            <button type="button" class="modal-close" onclick="closeFilterModal()">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="modal-filter-body">
            <!-- Search dalam modal -->
            <div class="filter-search">
                <i class="fa fa-search"></i>
                <input type="text" id="searchFilter" placeholder="Cari status..." onkeyup="searchFilter()">
            </div>

            <div class="filter-options" id="filterOptions">
                <!-- Semua Status -->
                <label class="filter-option">
                    <input type="checkbox" name="filter[]" value="" id="status_all" onchange="toggleAllStatus(this)">
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon all">
                        <i class="fa fa-list-ul"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Semua Status</span>
                        <span class="option-desc">Menampilkan semua laporan kehilangan</span>
                    </div>
                    <div class="option-badge">Semua</div>
                </label>

                <!-- Menunggu Konfirmasi -->
                <label class="filter-option">
                    <input type="checkbox" name="filter[]" value="pending" 
                           onchange="updateSelectedFilter()"
                           {{ in_array('pending', (array) request('filter', [])) ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon warning">
                        <i class="fa fa-clock"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Menunggu Konfirmasi</span>
                        <span class="option-desc">Laporan yang sedang menunggu persetujuan</span>
                    </div>
                    <div class="option-badge">Pending</div>
                </label>

                <!-- Belum Dikembalikan -->
                <label class="filter-option">
                    <input type="checkbox" name="filter[]" value="belum_dikembalikan" 
                           onchange="updateSelectedFilter()"
                           {{ in_array('belum_dikembalikan', (array) request('filter', [])) ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon danger">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Belum Dikembalikan</span>
                        <span class="option-desc">Buku hilang dan belum diganti</span>
                    </div>
                    <div class="option-badge">Hilang</div>
                </label>

                <!-- Sudah Dikembalikan -->
                <label class="filter-option">
                    <input type="checkbox" name="filter[]" value="sudah_dikembalikan" 
                           onchange="updateSelectedFilter()"
                           {{ in_array('sudah_dikembalikan', (array) request('filter', [])) ? 'checked' : '' }}>
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
                    <div class="option-badge">Selesai</div>
                </label>

                <!-- Ditolak -->
                <label class="filter-option">
                    <input type="checkbox" name="filter[]" value="rejected" 
                           onchange="updateSelectedFilter()"
                           {{ in_array('rejected', (array) request('filter', [])) ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon dark">
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
                            Menampilkan semua status
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

{{-- TABLE --}}
<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Kelas</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Mengganti</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($reports as $index => $report)

        @php
        switch($report->status){
            case 'pending':
                $status = 'Menunggu Konfirmasi';
                $statusClass = 'status-yellow';
                $statusIcon = 'fa-clock';
                break;

            case 'belum_dikembalikan':
            case 'buku_hilang':
                $status = 'Belum Dikembalikan';
                $statusClass = 'status-red';
                $statusIcon = 'fa-exclamation-triangle';
                break;

            case 'sudah_dikembalikan':
            case 'approved':
                $status = 'Sudah Dikembalikan';
                $statusClass = 'status-green';
                $statusIcon = 'fa-check-circle';
                break;

            case 'rejected':
                $status = 'Ditolak';
                $statusClass = 'status-red';
                $statusIcon = 'fa-times-circle';
                break;

            default:
                $status = ucfirst(str_replace('_', ' ', $report->status));
                $statusClass = 'status-gray';
                $statusIcon = 'fa-circle';
        }
        @endphp

        <tr>
            <td>{{ $reports->firstItem() + $index }}</td>

            <td>
                <div class="user-cell">
                    <div class="user-avatar">
                        <i class="fa fa-user-circle"></i>
                    </div>
                    <span>{{ $report->transaction->user->name ?? '-' }}</span>
                </div>
            </td>

            <td>{{ $report->transaction->book->judul ?? '-' }}</td>

            <td>{{ $report->transaction->user->kelas ?? '-' }}</td>

            <td>
                <span class="date-badge">
                    <i class="fa fa-calendar-alt"></i>
                    {{ optional($report->transaction->tanggal_peminjaman)->format('d/m/Y') }}
                </span>
            </td>

            <td>
                @if($report->tanggal_ganti)
                    <span class="date-badge">
                        <i class="fa fa-calendar-check"></i>
                        {{ \Carbon\Carbon::parse($report->tanggal_ganti)->format('d/m/Y') }}
                    </span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>

            <td>
                <span class="status-badge {{ $statusClass }}">
                    <i class="fa {{ $statusIcon }}"></i>
                    {{ $status }}
                </span>
            </td>

            <td class="aksi">
                @if($report->status === 'pending')
                    <form action="{{ route('reports.approve', $report->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-action btn-success" title="Setujui">
                            <i class="fa fa-check"></i>
                        </button>
                    </form>

                    <form action="{{ route('reports.reject', $report->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-action btn-danger" title="Tolak">
                            <i class="fa fa-times"></i>
                        </button>
                    </form>
                @elseif($report->status === 'sudah_dikembalikan' || $report->status === 'approved')
                    <button class="btn-action btn-print" onclick="window.open('{{ route('cetak.nota', [$report->id, 'hilang']) }}', '_blank')" title="Cetak Nota">
                        <i class="fa-solid fa-print"></i>
                    </button>
                @else
                    <span class="no-action">-</span>
                @endif
            </td>
        </tr>

        @empty
        <tr>
            <td colspan="8" class="empty-state">
                <i class="fa fa-inbox"></i>
                <p>Data tidak ada</p>
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
<script>
// Fungsi untuk toggle dropdown filter lama
function toggleFilterKategori() {
    let el = document.getElementById("filterKategori");
    if (el) {
        if (window.getComputedStyle(el).display === "none") {
            el.style.display = "block";
        } else {
            el.style.display = "none";
        }
    }
}

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
    const checkboxes = Array.from(document.querySelectorAll('input[name="filter[]"]'));
    const selected = checkboxes.filter(cb => cb.checked && cb.value !== '');
    const selectedCount = selected.length;
    const badge = document.getElementById('filterBadge');
    const summary = document.getElementById('selectedSummary');
    const selectedText = document.getElementById('selectedFilterText');
    const allBox = document.getElementById('status_all');

    if (selectedCount > 0) {
        badge.style.display = 'flex';
        badge.textContent = selectedCount;
        summary.style.display = 'flex';

        const statusNames = selected.map(cb => {
            switch (cb.value) {
                case 'pending': return 'Menunggu Konfirmasi';
                case 'belum_dikembalikan': return 'Belum Dikembalikan';
                case 'sudah_dikembalikan': return 'Sudah Dikembalikan';
                case 'rejected': return 'Ditolak';
                default: return cb.value;
            }
        });

        selectedText.textContent = 'Filter aktif: ' + statusNames.join(', ');
        if (allBox) {
            allBox.checked = selectedCount === checkboxes.filter(cb => cb.value !== '').length;
        }
    } else {
        badge.style.display = 'none';
        summary.style.display = 'none';
        if (allBox) {
            allBox.checked = true;
        }
    }
}

// Search filter options
function searchFilter() {
    const input = document.getElementById('searchFilter');
    const filter = input.value.toLowerCase();
    const options = document.querySelectorAll('.filter-option');
    
    options.forEach(option => {
        const title = option.querySelector('.option-title').textContent.toLowerCase();
        const desc = option.querySelector('.option-desc').textContent.toLowerCase();
        
        if (title.includes(filter) || desc.includes(filter)) {
            option.style.display = 'flex';
        } else {
            option.style.display = 'none';
        }
    });
}

// Reset filter
function resetFilter() {
    document.querySelectorAll('input[name="filter[]"]').forEach(cb => cb.checked = false);
    document.getElementById('status_all').checked = true;
    updateSelectedFilter();

    document.getElementById('filterValue').value = '';
    document.getElementById('filterForm').submit();
}

// All status checkbox
function toggleAllStatus(allCheckbox) {
    const checked = allCheckbox.checked;
    document.querySelectorAll('input[name="filter[]"]').forEach(cb => {
        if (cb.value !== '') cb.checked = checked;
    });
    updateSelectedFilter();
}

// Apply filter dan submit form
function applyFilter() {
    const selected = Array.from(document.querySelectorAll('input[name="filter[]"]:checked'))
        .filter(cb => cb.value !== '');
    const filterInput = document.getElementById('filterValue');

    if (selected.length > 0) {
        filterInput.value = selected.map(cb => cb.value).join(',');
    } else {
        filterInput.value = '';
    }

    document.getElementById('filterForm').submit();
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
    const activeFilters = @json((array) request('filter', []));

    // Set checkbox status
    const statusCheckboxes = document.querySelectorAll('input[name="filter[]"]');
    statusCheckboxes.forEach(cb => {
        if (cb.value === '') {
            return;
        }

        cb.checked = activeFilters.includes(cb.value);
    });

    const allBox = document.getElementById('status_all');
    if (allBox) {
        const nonEmpty = Array.from(statusCheckboxes).filter(cb => cb.value !== '');
        allBox.checked = nonEmpty.length > 0 && nonEmpty.every(cb => cb.checked);
    }

    updateSelectedFilter();
});

document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.querySelector('.sidebar')?.classList.toggle('active');
});
</script>
@endsection