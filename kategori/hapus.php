<?php
include "../koneksi.php";
include "fungsi.php";

$id = (int) ($_GET['id'] ?? 0);

// Tolak jika kategori masih dipakai produk
$cek = mysqli_prepare($koneksi, "SELECT COUNT(*) FROM produk WHERE kategori_id = ?");
mysqli_stmt_bind_param($cek, "i", $id);
mysqli_stmt_execute($cek);
mysqli_stmt_bind_result($cek, $jumlah);
mysqli_stmt_fetch($cek);
mysqli_stmt_close($cek);

if ($jumlah > 0) {
    header("Location: index.php?pesan=Kategori tidak bisa dihapus karena masih dipakai $jumlah produk");
    exit();
}

// Ambil nama file gambar
$ambil = mysqli_prepare($koneksi, "SELECT gambar FROM kategori WHERE id = ?");
mysqli_stmt_bind_param($ambil, "i", $id);
mysqli_stmt_execute($ambil);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($ambil));

if (!$data) {
    header("Location: index.php?pesan=Kategori tidak ditemukan");
    exit();
}

$stmt = mysqli_prepare($koneksi, "DELETE FROM kategori WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    hapus_gambar_kategori($data['gambar']);
    header("Location: index.php?pesan=Data berhasil dihapus");
    exit();
} else {
    echo "Gagal menghapus data: " . mysqli_error($koneksi);
}
?>
