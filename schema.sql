-- =====================================
-- DATABASE MANAJEMEN KOPERASI SEKOLAH
-- =====================================

CREATE DATABASE IF NOT EXISTS koperasi;

USE koperasi;

-- ====================================
-- MEMBUAT TABEL KATEGORI
-- =====================================

CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL UNIQUE,
    gambar VARCHAR(100) NOT NULL DEFAULT ''
);

-- =====================================
-- MEMBUAT TABEL PRODUK
-- =====================================

CREATE TABLE IF NOT EXISTS produk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_produk VARCHAR(20) NOT NULL,
    nama_produk VARCHAR(100) NOT NULL,
    harga INT NOT NULL,
    deskripsi TEXT,
    status_stok ENUM('Tersedia', 'Habis') NOT NULL,
    kategori_id INT NOT NULL,
    CONSTRAINT fk_produk_kategori
        FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

-- =====================================
-- INSERT DATA KATEGORI
-- =====================================

INSERT INTO kategori (nama_kategori) VALUES
('Alat Tulis'),
('Makanan'),
('Minuman'),
('Seragam & Atribut'),
('Jasa Cetak');

-- =====================================
-- INSERT DATA PRODUK
-- =====================================

INSERT INTO produk
(kode_produk, nama_produk, harga, deskripsi, status_stok, kategori_id)
VALUES
('0089-ATK', 'Buku Tulis', 5000, 'Buku tulis 38 lembar', 'Tersedia', (SELECT id FROM kategori WHERE nama_kategori = 'Alat Tulis')),
('0090-ATK', 'Pensil 2B', 3000, 'Pensil untuk menulis dan menggambar', 'Tersedia', (SELECT id FROM kategori WHERE nama_kategori = 'Alat Tulis')),
('0091-ATK', 'Pulpen Hitam', 4000, 'Pulpen tinta hitam', 'Tersedia', (SELECT id FROM kategori WHERE nama_kategori = 'Alat Tulis')),
('0092-MKN', 'Roti Coklat', 5000, 'Roti isi coklat', 'Tersedia', (SELECT id FROM kategori WHERE nama_kategori = 'Makanan')),
('0093-MNM', 'Air Mineral', 3000, 'Air mineral kemasan', 'Tersedia', (SELECT id FROM kategori WHERE nama_kategori = 'Minuman')),
('0094-MNM', 'Teh Botol', 5000, 'Minuman teh kemasan', 'Habis', (SELECT id FROM kategori WHERE nama_kategori = 'Minuman')),
('0095-SRG', 'Dasi Sekolah', 12000, 'Dasi seragam sekolah', 'Tersedia', (SELECT id FROM kategori WHERE nama_kategori = 'Seragam & Atribut')),
('0096-SRG', 'Topi Sekolah', 15000, 'Topi identitas sekolah', 'Tersedia', (SELECT id FROM kategori WHERE nama_kategori = 'Seragam & Atribut')),
('0097-JCT', 'Cetak Hitam Putih', 500, 'Jasa cetak dokumen hitam putih', 'Tersedia', (SELECT id FROM kategori WHERE nama_kategori = 'Jasa Cetak')),
('0098-JCT', 'Cetak Warna', 1500, 'Jasa cetak dokumen berwarna', 'Habis', (SELECT id FROM kategori WHERE nama_kategori = 'Jasa Cetak'));
