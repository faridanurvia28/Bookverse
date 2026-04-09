@extends('layouts.app')

@section('title', 'Daftar Pengunjung')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/daftar_pengunjung.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
    <!-- HEADER CARD -->
    <div class="header-card">
        <div class="header-left">
            <div class="header-icon">
                <i class="fa fa-user"></i>
            </div>
            <div>
                <h3>Daftar Pengunjung</h3>
                <p>Mencatat data pengunjung perpustakaan</p>
            </div>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="table-card">

        <div class="table-header">
            <form method="GET" action="{{ route('visits.index') }}" id="filterForm">
                <div class="filter">
                    <!-- SEARCH -->
                    <div class="search">
                        <i class="fa fa-search"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pengunjung...">
                    </div>

                    <!-- FILTER BUTTON MODERN -->
                    <button type="button" class="btn-filter-modern" id="filterBtn" onclick="openFilterModal()">
                        <i class="fa fa-sliders-h"></i>
                        <span>Filter Transaksi</span>
                        <span class="filter-badge" id="filterBadge" style="display: {{ request('filter') ? 'flex' : 'none' }};">
                            {{ request('filter') ? '1' : '0' }}
                        </span>
                    </button>

                    <!-- Hidden input untuk menyimpan nilai filter -->
                    <input type="hidden" name="filter" id="filterValue" value="{{ request('filter') }}">

                    @auth
                    <a href="{{ route('cetak.filter-daftar-kunjungan') }}" class="btn-print">
                        <i class="fa-solid fa-print"></i>
                        Cetak Laporan
                    </a>
                    @endauth
                </div>
            </form>
        </div>

        <!-- MODAL FILTER KATEGORI -->
        <div class="modal-overlay" id="modalFilter" style="display: none;">
            <div class="modal-filter-box">
                <!-- Header Modal dengan dekorasi -->
                <div class="modal-filter-header">
                    <div class="header-decoration">
                        <div class="header-icon-wrapper">
                            <i class="fa fa-filter"></i>
                        </div>
                    </div>
                    <h3>Filter Jenis Transaksi</h3>
                    <p>Pilih jenis transaksi yang ingin ditampilkan</p>
                    <button type="button" class="modal-close" onclick="closeFilterModal()">
                        <i class="fa fa-times"></i>
                    </button>
                </div>

                <!-- Body Modal -->
                <div class="modal-filter-body">
                    <!-- Search dalam modal -->
                    <div class="filter-search">
                        <i class="fa fa-search"></i>
                        <input type="text" id="searchFilter" placeholder="Cari jenis transaksi..." onkeyup="searchFilter()">
                    </div>

                    <div class="filter-options" id="filterOptions">
                        <!-- Semua Transaksi -->
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
                                <span class="option-title">Semua Transaksi</span>
                                <span class="option-desc">Menampilkan semua data kunjungan</span>
                            </div>
                            <div class="option-badge">Semua</div>
                        </label>

                        <!-- Peminjaman -->
                        <label class="filter-option">
                            <input type="radio" name="filter_radio" value="dipinjam" 
                                   onchange="updateSelectedFilter()"
                                   {{ request('filter') == 'dipinjam' ? 'checked' : '' }}>
                            <span class="option-radio">
                                <span class="radio-dot"></span>
                            </span>
                            <div class="option-icon primary">
                                <i class="fa fa-book"></i>
                            </div>
                            <div class="option-content">
                                <span class="option-title">Peminjaman</span>
                                <span class="option-desc">Pengunjung dengan transaksi peminjaman buku</span>
                            </div>
                            <div class="option-badge">Pinjam</div>
                        </label>

                        <!-- Pengembalian -->
                        <label class="filter-option">
                            <input type="radio" name="filter_radio" value="dikembalikan" 
                                   onchange="updateSelectedFilter()"
                                   {{ request('filter') == 'dikembalikan' ? 'checked' : '' }}>
                            <span class="option-radio">
                                <span class="radio-dot"></span>
                            </span>
                            <div class="option-icon success">
                                <i class="fa fa-undo-alt"></i>
                            </div>
                            <div class="option-content">
                                <span class="option-title">Pengembalian</span>
                                <span class="option-desc">Pengunjung dengan transaksi pengembalian buku</span>
                            </div>
                            <div class="option-badge">Kembali</div>
                        </label>
                    </div>

                    <!-- Selected Summary -->
                    <div class="selected-summary" id="selectedSummary" style="display: {{ request('filter') ? 'flex' : 'none' }};">
                        <div class="summary-icon">
                            <i class="fa fa-check-circle"></i>
                        </div>
                        <div class="summary-text">
                            <span id="selectedFilterText">
                                @if(request('filter') == 'dipinjam')
                                    Filter aktif: Peminjaman
                                @elseif(request('filter') == 'dikembalikan')
                                    Filter aktif: Pengembalian
                                @else
                                    Menampilkan semua transaksi
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
                        <span>Pilih salah satu jenis transaksi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABLE WRAPPER -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengunjung</th>
                        <th>Transaksi</th>
                        <th>Kelas</th>
                        <th>Tanggal Datang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visits as $visit)
                    <tr>
                        <td>{{ $visits->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="user-cell">
                                <div class="user-avatar">
                                    <i class="fa fa-user-circle"></i>
                                </div>
                                <span>{{ $visit->user->name }}</span>
                            </div>
                        </td>
                        <td>
                            @if($visit->transaction && $visit->transaction->jenis_transaksi == 'dipinjam')
                                <span class="badge badge-primary">
                                    <i class="fa fa-book"></i> Peminjaman
                                </span>
                            @elseif($visit->transaction && $visit->transaction->jenis_transaksi == 'dikembalikan')
                                <span class="badge badge-success">
                                    <i class="fa fa-undo-alt"></i> Pengembalian
                                </span>
                            @else
                                <span class="badge badge-secondary">
                                    <i class="fa fa-minus"></i> {{ $visit->transaction->jenis_transaksi ?? 'Tidak ada transaksi' }}
                                </span>
                            @endif
                        </td>
                        <td>{{ $visit->user->kelas ?? '-' }}</td>
                        <td>
                            <span class="date-badge">
                                <i class="fa fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($visit->tanggal_datang)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td>
                            <button class="btn-delete" onclick="openModal(this)" data-id="{{ $visit->id }}" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="empty-state">
                            <i class="fa fa-inbox"></i>
                            <p>Tidak ada data kunjungan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div class="pagination-wrapper">
            @include('components.pagination', ['paginator' => $visits])
        </div>

        <!-- MODAL HAPUS -->
        <div class="modal-overlay" id="modalHapus" style="display:none;">
            <div class="modal-box">
                <div class="modal-header">
                    <i class="fa fa-trash"></i>
                    <h3>Hapus Data Kunjungan</h3>
                </div>
                <div class="modal-body">
                    <i class="fa fa-exclamation-circle"></i>
                    <p>Apakah kamu yakin ingin menghapus data kunjungan ini?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn-modal batal" onclick="closeModal()">Batal</button>
                    <button class="btn-modal yakin" onclick="hapusData()">Iya, Hapus</button>
                </div>
            </div>
        </div>
    </div>
    <script>
// Fungsi untuk toggle dropdown filter lama (jika masih digunakan)
function toggleFilterKategori() {
    let el = document.getElementById("filterKategori");
    if (el) {
        if (el.style.display === "none" || el.style.display === "") {
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
    const selectedRadio = document.querySelector('input[name="filter_radio"]:checked');
    const badge = document.getElementById('filterBadge');
    const summary = document.getElementById('selectedSummary');
    const selectedText = document.getElementById('selectedFilterText');
    
    if (selectedRadio && selectedRadio.value !== '') {
        badge.style.display = 'flex';
        badge.textContent = '1';
        summary.style.display = 'flex';
        
        if (selectedRadio.value === 'dipinjam') {
            selectedText.textContent = 'Filter aktif: Peminjaman';
        } else if (selectedRadio.value === 'dikembalikan') {
            selectedText.textContent = 'Filter aktif: Pengembalian';
        }
    } else {
        badge.style.display = 'none';
        summary.style.display = 'none';
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
    // Pilih radio "Semua Transaksi"
    const allRadio = document.querySelector('input[name="filter_radio"][value=""]');
    if (allRadio) {
        allRadio.checked = true;
    }
    updateSelectedFilter();
    
    // Langsung submit dengan filter kosong
    document.getElementById('filterValue').value = '';
    document.getElementById('filterForm').submit();
}

// Apply filter dan submit form
function applyFilter() {
    const selectedRadio = document.querySelector('input[name="filter_radio"]:checked');
    const filterInput = document.getElementById('filterValue');
    
    if (selectedRadio) {
        filterInput.value = selectedRadio.value;
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
    // Set radio button sesuai dengan filter yang aktif
    const filterValue = '{{ request('filter') }}';
    if (filterValue) {
        const radio = document.querySelector(`input[name="filter_radio"][value="${filterValue}"]`);
        if (radio) {
            radio.checked = true;
            updateSelectedFilter();
        }
    }
});

// ================= FUNGSI UNTUK MODAL HAPUS =================
let selectedRow = null;
let selectedId = null;

function openModal(button) {
    selectedRow = button.closest('tr');
    selectedId = button.getAttribute('data-id');
    document.getElementById('modalHapus').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modalHapus').style.display = 'none';
}

function hapusData() {
    fetch(`{{ url('admin/visits') }}/${selectedId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            selectedRow.remove();
            closeModal();
            alert('Data kunjungan berhasil dihapus');
        } else {
            alert('Error: ' + (data.message || 'Gagal menghapus data'));
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Gagal menghapus data');
    });
}

document.getElementById('modalHapus').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection