import pandas as pd

data = {
    "Judul": ["Buku Contoh 1", "Buku Contoh 2"],
    "Kode Buku": ["KB001", "KB002"],
    "Pengarang": ["Penulis 1", "Penulis 2"],
    "Tahun Terbit": [2022, 2023],
    "Kategori Buku": ["fiksi", "nonfiksi"],
    "Stok Buku": [10, 5],
    "Sinopsis": ["Sinopsis buku pertama", "Sinopsis buku kedua"],
    "No Rak": ["R1", "R2"],
    "Baris Ke": [1, 2]
}

df = pd.DataFrame(data)
df.to_excel("Template_Data_Buku.xlsx", index=False)
