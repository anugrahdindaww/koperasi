<?php
include "koneksi.php";

// Ambil daftar kategori dari modul Kategori
$kategori = mysqli_query($koneksi, "SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");

if (isset($_POST['simpan'])) {
    $kode_produk = $_POST['kode_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga       = (int) $_POST['harga'];
    $deskripsi   = $_POST['deskripsi'];
    $status_stok = $_POST['status_stok'];
    $kategori_id = (int) $_POST['kategori_id'];

    $stmt = mysqli_prepare($koneksi,
        "INSERT INTO produk (kode_produk, nama_produk, harga, deskripsi, status_stok, kategori_id)
        VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssissi", $kode_produk, $nama_produk, $harga, $deskripsi, $status_stok, $kategori_id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?pesan=Data berhasil disimpan");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
</head>
<body>
    <h2>Tambah Produk</h2>
    <form method="POST" action="">
        <label for="kode_produk">Kode Produk:</label>
        <input type="text" name="kode_produk" id="kode_produk" required><br><br>

        <label for="nama_produk">Nama Produk:</label>
        <input type="text" name="nama_produk" id="nama_produk" required><br><br>

        <label for="harga">Harga:</label>
        <input type="number" name="harga" id="harga" required><br><br>

        <label for="deskripsi">Deskripsi:</label>
        <textarea name="deskripsi" id="deskripsi" required></textarea><br><br>

        <label >Status Stok:</label>
        <input type="radio" name="status_stok" id="stok_tersedia" value="Tersedia" required> <label for="stok_tersedia">Tersedia</label>
        <input type="radio" name="status_stok" id="stok_habis" value="Habis"> <label for="stok_habis">Habis</label><br><br>

        <label for="kategori_id">Kategori Produk:</label>
        <select name="kategori_id" id="kategori_id" required>
            <option value="">-- Pilih Kategori --</option>
            <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                <option value="<?php echo $k['id']; ?>">
                    <?php echo htmlspecialchars($k['nama_kategori']); ?>
                </option>
            <?php } ?>
        </select>
        <a href="kategori/tambah.php">+ Kategori baru</a><br><br>

        <input type="submit" name="simpan" value="Simpan">
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
