
@extends('layouts.app')

@section('title', 'Dashboard Anggota')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/kelola_data_buku.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
        <!-- HEADER CARD -->
        <div class="header-card">
            <div>
                <h3>Kelola Data Buku</h3>
                <p>Mengelola data buku perpustakaan</p>
            </div>
        </div>

        <!-- TABLE CARD -->
        <div class="table-card">

            <div class="table-header">
                <form method="GET" action="{{ route('books.index') }}">

<div class="filter">

    <!-- SEARCH -->
    <div class="search">
        <i class="fa fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sesuatu...">
    </div>

    <!-- BUTTON FILTER MODERN -->
<button type="button" class="btn-filter-modern" id="filterKategoriBtn" onclick="openFilterModal()">
    <i class="fa fa-sliders-h"></i>
    <span class="btn-filter-text">Kategori</span>
    <span class="btn-filter-badge" id="kategoriBadge" style="display: none;">•</span>
</button>

<!-- MODAL FILTER KATEGORI -->
<div class="modal-overlay" id="modalFilterKategori" style="display: none;">
    <div class="modal-kategori-box">
        <!-- Header Modal dengan dekorasi -->
        <div class="modal-kategori-header">
            <div class="header-icon-wrapper">
                <i class="fa fa-tags"></i>
            </div>
            <h3>Pilih Kategori Buku</h3>
            <p>Filter buku berdasarkan kategori yang Anda inginkan</p>
            <button type="button" class="modal-kategori-close" onclick="closeFilterModal()">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- Body Modal dengan pilihan kategori -->
        <div class="modal-kategori-body">
            <form id="filterKategoriForm" method="GET" action="{{ route('books.index') }}">
                <!-- Semua Kategori -->
                <label class="kategori-card" for="kategoriSemua">
                    <input type="radio" name="filter" id="kategoriSemua" value="" 
                           onchange="this.form.submit()" 
                           {{ request('filter') == '' ? 'checked' : '' }}>
                    <div class="kategori-card-content">
                        <div class="kategori-icon semua">
                            <i class="fa fa-layer-group"></i>
                        </div>
                        <div class="kategori-info">
                            <h4>Semua Kategori</h4>
                            <p>Tampilkan semua koleksi buku</p>
                        </div>
                        <div class="kategori-check">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <div class="kategori-card-glow"></div>
                </label>

                <!-- Kategori Fiksi -->
                <label class="kategori-card" for="kategoriFiksi">
                    <input type="radio" name="filter" id="kategoriFiksi" value="fiksi" 
                           onchange="this.form.submit()" 
                           {{ request('filter') == 'fiksi' ? 'checked' : '' }}>
                    <div class="kategori-card-content">
                        <div class="kategori-icon fiksi">
                            <i class="fa fa-book-open"></i>
                        </div>
                        <div class="kategori-info">
                            <h4>Fiksi</h4>
                            <p>Novel, cerpen, komik, dan karya imajinatif</p>
                        </div>
                        <div class="kategori-check">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <div class="kategori-card-glow"></div>
                </label>

                <!-- Kategori Non Fiksi -->
                <label class="kategori-card" for="kategoriNonFiksi">
                    <input type="radio" name="filter" id="kategoriNonFiksi" value="nonfiksi" 
                           onchange="this.form.submit()" 
                           {{ request('filter') == 'nonfiksi' ? 'checked' : '' }}>
                    <div class="kategori-card-content">
                        <div class="kategori-icon nonfiksi">
                            <i class="fa fa-book"></i>
                        </div>
                        <div class="kategori-info">
                            <h4>Non Fiksi</h4>
                            <p>Buku ilmiah, biografi, sejarah, dan pengetahuan</p>
                        </div>
                        <div class="kategori-check">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <div class="kategori-card-glow"></div>
                </label>
            </form>
        </div>

        <!-- Footer Modal -->
        <div class="modal-kategori-footer">
            <div class="filter-info">
                <i class="fa fa-info-circle"></i>
                <span>Pilih salah satu kategori untuk memfilter buku</span>
            </div>
            <button type="button" class="btn-filter-reset" onclick="resetFilterKategori()">
                <i class="fa fa-undo-alt"></i>
                Reset Filter
            </button>
        </div>
    </div>
</div>

</div>

</form>
                @auth
                <div class="btn-group-actions">
                    <a href="{{ route('books.exportExcel') }}" class="btn-export-excel">
                        <i class="fa fa-file-excel"></i>
                        Export Excel
                    </a>
                <!-- Tombol pilih file -->
<form id="importForm" action="/import" method="POST" enctype="multipart/form-data" style="display:none;">
    @csrf
    <input type="file" name="file" id="fileInput" hidden>
</form>

<button type="button" onclick="document.getElementById('fileInput').click()" class="btn-open">
    Upload File Excel
</button>
<div style="margin-top: 15px; text-align: center;">
</div>

@if(session('success'))
    <div class="alert-success" style="background-color: #d4edda; color: #155724; padding: 10px; margin-top: 15px; border-radius: 5px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert-error" style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-top: 15px; border-radius: 5px;">
        {{ session('error') }}
    </div>
@endif

@if(session('importErrors'))
    <div class="alert-warning" style="background-color: #fff3cd; color: #856404; padding: 10px; margin-top: 15px; border-radius: 5px; text-align: left; max-height: 150px; overflow-y: auto;">
        <strong>Detail Gagal Import:</strong>
        <ul style="margin-top: 5px; padding-left: 20px; font-size: 0.9em;">
            @foreach(session('importErrors') as $error)
                <li>Baris {{ $error['row'] }}: {{ implode(', ', $error['errors']) }}</li>
            @endforeach
        </ul>
    </div>
@endif
                    <a href="{{ route('books.create') }}" class="btn-add">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Data Buku
                    </a>
                </div>
                @endauth
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Kode Buku</th>
                        <th>Pengarang</th>
                        <th>Tahun Terbit</th>
                        <th>Kategori</th>
                        <th>Rak</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($books as $book)
                    @if (Auth::user()->role == 'admin')
                    <tr>
                        <td>{{ $books->firstItem() + $loop->index }}</td>
                        <td>{{ $book->judul }}</td>
                        <td>{{ $book->kode_buku }}</td>
                        <td>{{ $book->pengarang }}</td>
                        <td>{{ $book->tahun_terbit }}</td>
                        <td>{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}</td>
                        <td>
                            {{ $book->row?->bookshelf?->no_rak }} - {{ $book->row?->baris_ke ?? $book->id_baris }}
                        </td>
                        <td>{{ $book->stok }}</td>
                        <td class="aksi">
                            @auth
                            <a href="{{ route('books.edit', $book->id) }}" class="btn edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
      <button class="btn delete" onclick="openModal(this)" data-id="{{ $book->id }}">
    <i class="fa-solid fa-trash"></i>
</button>
                            @endauth

<button class="btn view"
    onclick="openDetail(this)"
    data-judul="{{ $book->judul }}"
    data-penulis="{{ $book->pengarang }}"
    data-kategori="{{ $book->kategori_buku == 'fiksi' ? 'Fiksi' : 'Non Fiksi' }}"
    data-deskripsi="{{ $book->deskripsi }}"
    data-gambar="{{ $book->cover ? asset('storage/' . $book->cover) : asset('img/buku.png') }}"
>
    <i class="fa-solid fa-eye"></i>
</button>

                        </td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="8">
                            @include('components.pagination', ['paginator' => $books])
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </main>
</div>

</body>

    <!-- ================= MODAL HAPUS ================= -->
    <div class="modal-overlay" id="modalHapus" style="display:none;">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Hapus Data Buku</h3>
            </div>

            <div class="modal-body">
                <p>Apakah kamu yakin ingin menghapus data buku?</p>
            </div>

            <div class="modal-footer">
                <button class="btn-modal batal" onclick="closeModal()">Batal</button>
                <button class="btn-modal yakin" onclick="hapusData()">Iya, saya yakin</button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL DETAIL BUKU ================= -->
    <div class="modal-overlay" id="modalDetail" style="display:none;">
        <div class="modal-detail-box">
            <div class="modal-header">
                <h3>Detail Buku</h3>
            </div>

            <div class="modal-detail-body">
                <img id="detailGambar" src="" alt="Buku">

                <div class="detail-text">
                    <h2 id="detailJudul"></h2>
                    <p class="penulis">By: <span id="detailPenulis"></span></p>
                    <span class="badge" id="detailKategori"></span>
                    <p class="deskripsi" id="detailDeskripsi"></p>
                </div>
            </div>

            <div class="modal-footer-detail">
                <button class="btn-tutup" onclick="closeDetail()">Tutup</button>
            </div>
        </div>
    </div>

    

<script>

function toggleFilterKategori(){
    let el = document.getElementById("filterKategori");
    el.style.display = el.style.display === "none" ? "block" : "none";
}

// Fungsi untuk membuka modal filter
function openFilterModal() {
    document.getElementById('modalFilterKategori').style.display = 'flex';
    updateFilterBadge();
}

// Fungsi untuk menutup modal filter
function closeFilterModal() {
    document.getElementById('modalFilterKategori').style.display = 'none';
}

// Fungsi untuk reset filter kategori
function resetFilterKategori() {
    // Submit form tanpa filter (semua kategori)
    const form = document.getElementById('filterKategoriForm');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'filter';
    input.value = '';
    form.appendChild(input);
    form.submit();
}

// Fungsi untuk update badge filter
function updateFilterBadge() {
    const badge = document.getElementById('kategoriBadge');
    const filterValue = '{{ request('filter') }}';
    
    if (filterValue && filterValue !== '') {
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

// Tutup modal saat klik di luar
document.addEventListener('click', function(e) {
    const modal = document.getElementById('modalFilterKategori');
    const modalBox = document.querySelector('.modal-kategori-box');
    const btn = document.getElementById('filterKategoriBtn');
    
    if (modal && modal.style.display === 'flex') {
        if (!modalBox.contains(e.target) && !btn.contains(e.target)) {
            closeFilterModal();
        }
    }
});

// Escape key untuk menutup modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('modalFilterKategori');
        if (modal.style.display === 'flex') {
            closeFilterModal();
        }
    }
});

// Update badge saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    updateFilterBadge();
    
    // Set radio button sesuai dengan filter yang aktif
    const filterValue = '{{ request('filter') }}';
    if (filterValue === 'fiksi') {
        document.getElementById('kategoriFiksi').checked = true;
    } else if (filterValue === 'nonfiksi') {
        document.getElementById('kategoriNonFiksi').checked = true;
    } else {
        document.getElementById('kategoriSemua').checked = true;
    }
});
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
        fetch(`{{ url('admin/books') }}/${selectedId}`, {
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
                alert('Buku berhasil dihapus');
            } else {
                alert('Error: ' + (data.message || 'Gagal menghapus data'));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('Gagal menghapus data: ' + err.message);
        });
    }

    document.getElementById('modalHapus').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    function openDetail(btn) {
        document.getElementById('detailJudul').innerText = btn.dataset.judul;
        document.getElementById('detailPenulis').innerText = btn.dataset.penulis;
        document.getElementById('detailKategori').innerText = btn.dataset.kategori;
        document.getElementById('detailDeskripsi').innerText = btn.dataset.deskripsi;
        document.getElementById('detailGambar').src = btn.dataset.gambar;

        document.getElementById('modalDetail').style.display = 'flex';
    }

    function closeDetail() {
        document.getElementById('modalDetail').style.display = 'none';
    }

    document.getElementById('modalDetail').addEventListener('click', function(e) {
        if (e.target === this) closeDetail();
    });
    
const fileInput = document.getElementById("fileInput");
const importForm = document.getElementById("importForm");

// Saat file dipilih, langsung submit tanpa popup modal
fileInput.addEventListener("change", function () {
    if (this.files.length > 0) {
        importForm.submit();
    }
});
</script>

@endsection
