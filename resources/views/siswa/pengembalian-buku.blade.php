@extends('layouts.app')
@section('title', 'Pengembalian Buku')

@push('styles')
    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('css/siswa/pengembalian-buku.css') }}">
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
            <h3>Pengembalian Buku</h3>
            <p>Pengelolaan pengembalian buku</p>
        </div>
    </div>
    <img src="{{ asset('img/book.png') }}" class="header-image">
</div>

{{-- FILTER --}}
<div class="filter-card">
    <form method="GET" action="{{ route('anggota.pengembalian') }}" id="filterForm">
        <div class="filter-group">
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
            
            <!-- Hidden input untuk mode (peminjaman/pengembalian) -->
            <input type="hidden" name="mode" value="pengembalian">

            <!-- Submit Button -->
            <button type="submit" class="btn-search" title="Terapkan Filter">
                <i class="fa fa-check"></i>
                <span>Terapkan</span>
            </button>

            <!-- Clear Button -->
            @if(request('search') || request('date') || request('filter'))
                <a href="{{ route('anggota.pengembalian') }}" class="btn-clear" title="Hapus Semua Filter">
                    <i class="fa fa-times"></i>
                    <span>Hapus</span>
                </a>
            @endif
        </div>
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
            <h3>Filter Status Pengembalian</h3>
            <p>Pilih status pengembalian yang ingin ditampilkan</p>
            <button type="button" class="modal-close" onclick="closeFilterModal()">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- Body Modal -->
        <div class="modal-filter-body">
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
                        <span class="option-desc">Menampilkan semua status pengembalian</span>
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
                        <span class="option-desc">Buku yang masih dipinjam dan belum dikembalikan</span>
                    </div>
                    <div class="option-badge">Pending</div>
                </label>

                <!-- Menunggu Konfirmasi -->
                <label class="filter-option">
                    <input type="radio" name="filter_radio" value="menunggu_konfirmasi" 
                           onchange="updateSelectedFilter()"
                           {{ request('filter') == 'menunggu_konfirmasi' ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon info">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Menunggu Konfirmasi</span>
                        <span class="option-desc">Pengembalian sedang menunggu persetujuan petugas</span>
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
                        <span class="option-desc">Buku sudah dikembalikan tepat waktu</span>
                    </div>
                    <div class="option-badge">Done</div>
                </label>

                <!-- Terlambat -->
                <label class="filter-option">
                    <input type="radio" name="filter_radio" value="terlambat" 
                           onchange="updateSelectedFilter()"
                           {{ request('filter') == 'terlambat' ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon danger">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Terlambat</span>
                        <span class="option-desc">Pengembalian melebihi batas waktu</span>
                    </div>
                    <div class="option-badge">Late</div>
                </label>

                <!-- Buku Hilang -->
                <label class="filter-option">
                    <input type="radio" name="filter_radio" value="buku_hilang" 
                           onchange="updateSelectedFilter()"
                           {{ request('filter') == 'buku_hilang' ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon dark">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Buku Hilang</span>
                        <span class="option-desc">Buku dinyatakan hilang</span>
                    </div>
                    <div class="option-badge">Lost</div>
                </label>

                <!-- Ditolak -->
                <label class="filter-option">
                    <input type="radio" name="filter_radio" value="rejected" 
                           onchange="updateSelectedFilter()"
                           {{ request('filter') == 'rejected' ? 'checked' : '' }}>
                    <span class="option-radio">
                        <span class="radio-dot"></span>
                    </span>
                    <div class="option-icon dark">
                        <i class="fa fa-times-circle"></i>
                    </div>
                    <div class="option-content">
                        <span class="option-title">Ditolak</span>
                        <span class="option-desc">Pengembalian ditolak oleh petugas</span>
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
                        @if(request('filter') == 'belum_dikembalikan')
                            Filter aktif: Belum Dikembalikan
                        @elseif(request('filter') == 'menunggu_konfirmasi')
                            Filter aktif: Menunggu Konfirmasi
                        @elseif(request('filter') == 'sudah_dikembalikan')
                            Filter aktif: Sudah Dikembalikan
                        @elseif(request('filter') == 'terlambat')
                            Filter aktif: Terlambat
                        @elseif(request('filter') == 'buku_hilang')
                            Filter aktif: Buku Hilang
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
                <span>Pilih salah satu status pengembalian</span>
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
                <th>Judul Buku</th>
                <th>Kode Buku</th>
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
                    <td>
                        <div class="book-cell">
                            <i class="fa fa-book book-icon"></i>
                            <span>{{ $trx->book->judul ?? '-' }}</span>
                        </div>
                    </td>
                    <td>{{ $trx->book->kode_buku ?? '-' }}</td>
                    <td>{{ optional($trx->tanggal_peminjaman)->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        @php
                            $statusClass = '';
                            $statusIcon = '';
                            
                            switch($trx->status) {
                                case 'belum_dikembalikan':
                                    $statusClass = 'status-warning';
                                    $statusIcon = 'fa-clock';
                                    $statusText = 'Belum Dikembalikan';
                                    break;
                                case 'menunggu_konfirmasi':
                                    $statusClass = 'status-info';
                                    $statusIcon = 'fa-hourglass-half';
                                    $statusText = 'Menunggu Persetujuan';
                                    break;
                                case 'sudah_dikembalikan':
                                    $statusClass = 'status-success';
                                    $statusIcon = 'fa-check-circle';
                                    $statusText = 'Selesai';
                                    break;
                                case 'terlambat':
                                    $statusClass = 'status-danger';
                                    $statusIcon = 'fa-exclamation-triangle';
                                    $statusText = 'Terlambat';
                                    break;
                                case 'buku_hilang':
                                    $statusClass = 'status-danger';
                                    $statusIcon = 'fa-times-circle';
                                    $statusText = 'Buku Hilang';
                                    break;
                                case 'rejected':
                                    $statusClass = 'status-danger';
                                    $statusIcon = 'fa-times-circle';
                                    $statusText = 'Ditolak';
                                    break;
                                default:
                                    $statusClass = 'status-gray';
                                    $statusIcon = 'fa-circle';
                                    $statusText = ucfirst(str_replace('_', ' ', $trx->status));
                            }
                        @endphp
                        <span class="status-badge {{ $statusClass }}">
                            <i class="fa {{ $statusIcon }}"></i>
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="aksi">
                        {{-- Kembalikan Buku (hanya jika belum dikembalikan) --}}
                        @if($trx->status == 'belum_dikembalikan')
                            <button class="aksi-btn blue"
                                data-bs-toggle="modal"
                                data-bs-target="#modalKembalikan{{ $trx->id }}"
                                title="Kembalikan Buku">
                                <i class="bi bi-arrow-return-left"></i>
                            </button>
                        @elseif($trx->status == 'sudah_dikembalikan')
                            <button class="aksi-btn print"
                                onclick="window.open('{{ route('cetak.nota', [$trx->id, 'pengembalian']) }}', '_blank')"
                                title="Cetak Nota">
                                <i class="fa-solid fa-print"></i>
                            </button>
                        @endif

                        {{-- Perpanjang (hanya jika belum dikembalikan/terlambat) --}}
                        @if(in_array($trx->status, ['belum_dikembalikan', 'terlambat']))
                            <button class="aksi-btn orange"
                                data-bs-toggle="modal"
                                data-bs-target="#modalPerpanjang{{ $trx->id }}"
                                title="Perpanjang">
                                <i class="bi bi-calendar-event"></i>
                            </button>
                        @endif

                        {{-- Laporan Kehilangan (hanya jika belum dikembalikan) --}}
                        @if($trx->status == 'belum_dikembalikan')
                            <button class="aksi-btn red"
                                data-bs-toggle="modal"
                                data-bs-target="#modalKehilangan{{ $trx->id }}"
                                title="Laporan Kehilangan">
                                <i class="bi bi-chat-dots"></i>
                            </button>
                        @endif
                    </td>
                </tr>

                {{-- Modal Kembalikan Buku --}}
                <div class="modal fade" id="modalKembalikan{{ $trx->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header custom-header">
                                <h5 class="modal-title">Kembalikan Buku</h5>
                            </div>
                            <div class="modal-body text-center">
                                <i class="bi bi-arrow-return-left" style="font-size: 48px; color: #3B82F6; margin-bottom: 15px;"></i>
                                <p>Apakah kamu yakin ingin mengembalikan <strong>{{ $trx->book->judul }}</strong>?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <form action="{{ route('transactions.ajukanPengembalian', $trx->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-yakin btn-rounded">
                                        Iya, Kembalikan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Perpanjang --}}
                <div class="modal fade" id="modalPerpanjang{{ $trx->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header custom-header">
                                <h5 class="modal-title">Perpanjang Peminjaman</h5>
                            </div>
                            <div class="modal-body text-center">
                                <i class="bi bi-calendar-event" style="font-size: 48px; color: #F59E0B; margin-bottom: 15px;"></i>
                                <p class="fs-6">
                                    Apakah kamu yakin ingin memperpanjang waktu peminjaman <strong>{{ $trx->book->judul }}</strong> selama <strong>3 hari</strong>?
                                </p>
                                <small class="text-muted">
                                    Jatuh tempo saat ini: {{ optional($trx->tanggal_jatuh_tempo)->format('d/m/Y') }}
                                </small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <form action="{{ route('transactions.perpanjang', $trx->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-yakin btn-rounded">
                                        Iya, Perpanjang
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Laporan Kehilangan --}}
                <div class="modal fade" id="modalKehilangan{{ $trx->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header custom-header">
                                <h5 class="modal-title">Laporan Kehilangan Buku</h5>
                            </div>
                            <form action="{{ route('laporan-kehilangan.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="transactions_id" value="{{ $trx->id }}">
                                
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Judul Buku</label>
                                        <input type="text" class="form-control" value="{{ $trx->book->judul }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal Kejadian</label>
                                        <input type="date" class="form-control" name="tanggal_ganti" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alasan Kehilangan</label>
                                        <textarea class="form-control" name="keterangan" rows="5" placeholder="Jelaskan alasan buku Anda hilang..." required maxlength="500"></textarea>
                                        <small class="text-muted d-block text-end mt-1">Max 500 karakter</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-batal btn-rounded" data-bs-dismiss="modal">
                                        Batal
                                    </button>
                                    <button type="submit" class="btn btn-simpan btn-rounded">
                                        Lapor Kehilangan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <i class="fa fa-inbox"></i>
                        <p>Tidak ada data peminjaman</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    <div class="pagination-wrapper">
        {{ $transactions->links() }}
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
    const selectedRadio = document.querySelector('input[name="filter_radio"]:checked');
    const badge = document.getElementById('filterBadge');
    const summary = document.getElementById('selectedSummary');
    const selectedText = document.getElementById('selectedFilterText');
    
    if (selectedRadio && selectedRadio.value !== '') {
        badge.style.display = 'flex';
        badge.textContent = '1';
        summary.style.display = 'flex';
        
        const filterTexts = {
            'belum_dikembalikan': 'Belum Dikembalikan',
            'menunggu_konfirmasi': 'Menunggu Konfirmasi',
            'sudah_dikembalikan': 'Sudah Dikembalikan',
            'terlambat': 'Terlambat',
            'buku_hilang': 'Buku Hilang',
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

// Apply filter dan submit form - tetap menjaga search dan date
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

// Toggle sidebar
document.getElementById('toggleSidebar')?.addEventListener('click', function () {
    document.querySelector('.sidebar')?.classList.toggle('active');
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection