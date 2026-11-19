-- =====================================================
-- MIGRASI: untuk database "koperasi" yang SUDAH ADA
-- (tabel produk masih memakai kolom kategori_produk)
-- Jalankan sekali saja lewat phpMyAdmin tab SQL.
-- Kalau mau mulai dari nol, cukup import schema.sql.
-- =====================================================

USE koperasi;

-- 1. Buat tabel kategori
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL UNIQUE,
    gambar VARCHAR(100) DEFAULT NULL
);

-- 1a. Pastikan nilai gambar lama tidak null
UPDATE kategori SET gambar = '' WHERE gambar IS NULL;
ALTER TABLE kategori MODIFY gambar VARCHAR(100) NOT NULL DEFAULT '';

-- 2. Pindahkan kategori yang sudah dipakai produk ke tabel kategori
INSERT IGNORE INTO kategori (nama_kategori)
SELECT DISTINCT kategori_produk FROM produk WHERE kategori_produk <> '';

-- 3. Tambah kolom kategori_id di produk
ALTER TABLE produk ADD COLUMN kategori_id INT NULL AFTER status_stok;

-- 4. Isi kategori_id sesuai nama kategori lama
UPDATE produk p
JOIN kategori k ON k.nama_kategori = p.kategori_produk
SET p.kategori_id = k.id;

-- 5. Cek dulu: query ini harus menghasilkan 0 baris
--    SELECT * FROM produk WHERE kategori_id IS NULL;

-- 6. Kunci relasi, lalu buang kolom lama
ALTER TABLE produk MODIFY kategori_id INT NOT NULL;
ALTER TABLE produk
    ADD CONSTRAINT fk_produk_kategori
    FOREIGN KEY (kategori_id) REFERENCES kategori(id)
    ON DELETE RESTRICT ON UPDATE CASCADE;
ALTER TABLE produk DROP COLUMN kategori_produk;
