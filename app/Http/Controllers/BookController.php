<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Row;
use App\Models\Bookshelf;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BukuImport;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    private const DEFAULT_COVER_PATH = 'covers/default-book.png';
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $search = $request->input('search', '');
        $date = $request->input('date', '');
        $filter = $request->input('filter');

        $query = Book::with('row.bookshelf');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('pengarang', 'like', "%{$search}%")
                  ->orWhere('kode_buku', 'like', "%{$search}%");
            });
        }

        if ($date) {
            $query->whereYear('tahun_terbit', $date);
        }

        if (!empty($filter)) {
            $query->where('kategori_buku', $filter);
        }

        $books = $query->paginate(10);

        return view('admin.kelola_data_buku', compact('books', 'search', 'date', 'filter'));
    }

    public function import(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        try {
            $import = new BukuImport();
            Excel::import($import, $request->file('file'));
            
            $successCount = $import->getSuccessCount();
            $errors = $import->getErrors();
            
            if ($successCount > 0) {
                if (count($errors) > 0) {
                    session()->flash('importErrors', $errors);
                    return redirect()->back()->with('warning', "Berhasil import $successCount data, namun ada " . count($errors) . " data yang gagal.");
                }
                return redirect()->back()->with('success', "Berhasil import $successCount data buku!");
            } else {
                if (count($errors) > 0) {
                    session()->flash('importErrors', $errors);
                }
                return redirect()->back()->with('error', "Tidak ada data yang berhasil diimport. Ada kesalahan pada format data Excel.");
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }
    
    public function create()
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $rows = Row::all();
        $bookshelves = Bookshelf::all();
        return view('admin.CRUD_kelola_buku', [
            'book' => null,
            'rows' => $rows,
            'bookshelves' => $bookshelves,
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'kode_buku' => 'nullable|string|unique:books,kode_buku',
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:' . date('Y'),
            'kategori_buku' => 'required|in:fiksi,nonfiksi',
            'id_baris' => 'nullable|exists:row,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'stok' => 'required|integer|min:0',
            'deskripsi' => 'required|string',
            'new_bookshelf_no' => 'nullable|string|max:50',
            'new_bookshelf_keterangan' => 'nullable|string|max:255',
            'new_row_baris' => 'nullable|integer',
            'new_row_keterangan' => 'nullable|string|max:255',
            'nomor_rak' => 'nullable|string|max:50',
        ]);
        
        if (empty($data['kode_buku'])) {
            do {
                $generated = str_pad(random_int(100, 999), 3, '0', STR_PAD_LEFT);
            } while (Book::where('kode_buku', $generated)->exists());
            $data['kode_buku'] = $generated;
        }

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        } else {
            $data['cover'] = self::DEFAULT_COVER_PATH;
        }
        $data['status'] = 'tersedia';

        if (!empty($data['new_bookshelf_no'])) {
            $cleanNoRak = preg_replace('/[^0-9]/', '', (string) $data['new_bookshelf_no']);
            if ($cleanNoRak === '') {
                return back()->withInput()->withErrors(['new_bookshelf_no' => 'Nomor rak tidak valid. Gunakan angka atau format seperti R1.']);
            }

            $bookshelf = Bookshelf::create([
                'no_rak' => (int) $cleanNoRak,
                'keterangan' => $data['new_bookshelf_keterangan'] ?? null,
            ]);

            if (!empty($data['new_row_baris'])) {
                $row = Row::create([
                    'rak_id' => $bookshelf->id,
                    'baris_ke' => $data['new_row_baris'],
                    'keterangan' => $data['new_row_keterangan'] ?? null,
                ]);
                $data['id_baris'] = $row->id;
            }
        } elseif (!empty($data['new_row_baris'])) {
            if (!empty($data['nomor_rak'])) {
                $cleanNoRak = preg_replace('/[^0-9]/', '', (string) $data['nomor_rak']);
                $bookshelf = Bookshelf::where('no_rak', (int) $cleanNoRak)->first();
                if ($bookshelf) {
                    $row = Row::create([
                        'rak_id' => $bookshelf->id,
                        'baris_ke' => $data['new_row_baris'],
                        'keterangan' => $data['new_row_keterangan'] ?? null,
                    ]);
                    $data['id_baris'] = $row->id;
                }
            }
        }

        if (empty($data['id_baris'])) {
            return back()->withInput()->withErrors(['id_baris' => 'Baris rak harus dipilih atau dibuat terlebih dahulu.']);
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan');
    }

    public function show(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $book->load('row'); 
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        $rows = Row::all();
        $bookshelves = Bookshelf::all();
        return view('admin.CRUD_kelola_buku', [
            'book' => $book,
            'rows' => $rows,
            'bookshelves' => $bookshelves
        ]);
    }

    public function update(Request $request, Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);

        $data = $request->validate([
            'kode_buku' => 'nullable|string|unique:books,kode_buku,' . $book->id,
            'judul' => 'nullable|string|max:255',
            'pengarang' => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1900|max:' . date('Y'),
            'kategori_buku' => 'nullable|in:fiksi,nonfiksi',
            'id_baris' => 'nullable|exists:row,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'stok' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover'] = $path; 
        }

        if (empty($data['kode_buku'])) {
            unset($data['kode_buku']);
        }

        $data = array_filter($data, fn($value) => !is_null($value));

        $book->update($data);

        if ($request->wantsJson() || $request->isXmlHttpRequest()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil diupdate',
                'book' => $book
            ]);
        }

        return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate');
    }

    public function destroy(Book $book)
    {
        if (Auth::user()?->role !== 'admin') abort(403);
        
        try {
            $book->delete();
            
            if (request()->wantsJson() || request()->isXmlHttpRequest()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Buku berhasil dihapus'
                ], 200);
            }
            
            return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->wantsJson() || request()->isXmlHttpRequest()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus buku: ' . $e->getMessage()
                ], 400);
            }
            
            return redirect()->route('books.index')->with('error', 'Gagal menghapus buku');
        }
    }

    public function browse()
    {
        if (Auth::user()?->role !== 'anggota') abort(403);
        $books = Book::where('status', 'tersedia')->with('row')->get();

        $hasActiveLoan = Transaction::where('user_id', Auth::id())
            ->whereIn('status', ['belum_dikembalikan', 'menunggu_konfirmasi', 'terlambat'])
            ->exists();

        return view('siswa.pinjam-buku', compact('books', 'hasActiveLoan'));
    }
}
