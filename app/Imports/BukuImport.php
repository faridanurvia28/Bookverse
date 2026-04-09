<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Row;
use App\Models\Bookshelf;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BukuImport implements ToCollection, WithHeadingRow
{
    private $successCount = 0;
    private $errors = [];
    private const DEFAULT_COVER_PATH = 'covers/default-book.png';
    
    public function collection(Collection $rows)
    {
        DB::beginTransaction();
        
        try {
            foreach ($rows as $index => $row) {
                try {
                    // Handle kemungkinan nama kolom yang berbeda (contoh 'judul_buku' vs 'judul')
                    $judul = $row['judul'] ?? $row['judul_buku'] ?? null;
                    $kodeBuku = $row['kode_buku'] ?? null;

                    // Validasi data minimal
                    if (empty($judul) || empty($kodeBuku)) {
                        $this->errors[] = [
                            'row' => $index + 2,
                            'errors' => ['Judul dan Kode Buku wajib diisi (pastikan format kolom Excel: "Judul" dan "Kode Buku")']
                        ];
                        continue;
                    }
                    
                    // Cek apakah kode_buku sudah ada
                    if (Book::where('kode_buku', $kodeBuku)->exists()) {
                        $this->errors[] = [
                            'row' => $index + 2,
                            'errors' => ['Kode buku ' . $kodeBuku . ' sudah terdaftar']
                        ];
                        continue;
                    }
                    
                    // Cari atau buat Bookshelf berdasarkan no_rak
                    $bookshelf = null;
                    if (!empty($row['no_rak'])) {
                        // Bersihkan "R1" menjadi "1" dsb
                        $cleanNoRak = preg_replace('/[^0-9]/', '', $row['no_rak']);
                        if (!empty($cleanNoRak)) {
                            $bookshelf = Bookshelf::firstOrCreate(
                                ['no_rak' => $cleanNoRak],
                                ['keterangan' => '-']
                            );
                        }
                    }
                    
                    // Cari atau buat Row
                    $rowModel = null;
                    if ($bookshelf && !empty($row['baris_ke'])) {
                        $cleanBarisKe = preg_replace('/[^0-9]/', '', $row['baris_ke']);
                        if (!empty($cleanBarisKe)) {
                            $rowModel = Row::firstOrCreate(
                                [
                                    'rak_id' => $bookshelf->id,
                                    'baris_ke' => $cleanBarisKe
                                ],
                                ['keterangan' => '-']
                            );
                        }
                    }
                    
                    $cover = $row['cover'] ?? $row['cover_path'] ?? null;
                    if (empty($cover)) {
                        $cover = self::DEFAULT_COVER_PATH;
                    }

                    // Siapkan data buku
                    $bookData = [
                        'judul' => $judul,
                        'kode_buku' => $kodeBuku,
                        'pengarang' => $row['pengarang'] ?? '',
                        'tahun_terbit' => $row['tahun_terbit'] ?? date('Y'),
                        'kategori_buku' => isset($row['kategori_buku']) && strtolower($row['kategori_buku']) == 'fiksi' ? 'fiksi' : 'nonfiksi',
                        'deskripsi' => $row['deskripsi'] ?? $row['sinopsis'] ?? '',
                        'id_baris' => $rowModel ? $rowModel->id : null,
                        'status' => 'tersedia',
                        'stok' => $row['stok'] ?? $row['stok_buku'] ?? 1,
                        'cover' => $cover
                    ];
                    
                    // Buat buku baru
                    Book::create($bookData);
                    
                    $this->successCount++;
                    
                } catch (\Exception $e) {
                    $this->errors[] = [
                        'row' => $index + 2,
                        'errors' => [$e->getMessage()]
                    ];
                }
            }
            
            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    public function getSuccessCount()
    {
        return $this->successCount;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}
