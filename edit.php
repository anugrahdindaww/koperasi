<?php
include "koneksi.php";

$id = (int) $_GET['id'];

$stmt = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    die("Produk tidak ditemukan.");
}

// Ambil daftar kategori dari modul Kategori
$kategori = mysqli_query($koneksi, "SELECT id, nama_kategori FROM kategori ORDER BY nama_kategori ASC");

if (isset($_POST['update'])) {
    $kode_produk = $_POST['kode_produk'];
    $nama_produk = $_POST['nama_produk'];
    $harga       = (int) $_POST['harga'];
    $deskripsi   = $_POST['deskripsi'];
    $status_stok = $_POST['status_stok'];
    $kategori_id = (int) $_POST['kategori_id'];

    $stmt = mysqli_prepare($koneksi,
        "UPDATE produk SET
            kode_produk = ?,
            nama_produk = ?,
            harga = ?,
            deskripsi = ?,
            status_stok = ?,
            kategori_id = ?
        WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssissii", $kode_produk, $nama_produk, $harga, $deskripsi, $status_stok, $kategori_id, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php?pesan=Data berhasil diubah");
        exit();
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit Produk</h2>
    <form method="POST" action="">
        <label for="kode_produk">Kode Produk:</label><br>
        <input type="text" name="kode_produk" id="kode_produk" value="<?php echo htmlspecialchars($data['kode_produk']); ?>"><br><br>

        <label for="nama_produk">Nama Produk:</label><br>
        <input type="text" name="nama_produk" id="nama_produk" value="<?php echo htmlspecialchars($data['nama_produk']); ?>"><br><br>

        <label for="harga">Harga:</label><br>
        <input type="number" name="harga" id="harga" value="<?php echo $data['harga']; ?>"><br><br>

        <label for="deskripsi">Deskripsi:</label><br>
        <textarea name="deskripsi" id="deskripsi"><?php echo htmlspecialchars($data['deskripsi']); ?></textarea><br><br>

        <label>Status Stok:</label><br>
        <input type="radio" name="status_stok" id="stok_tersedia" value="Tersedia" <?php if ($data['status_stok'] == 'Tersedia') echo 'checked'; ?>> <label for="stok_tersedia">Tersedia</label>
        <input type="radio" name="status_stok" id="stok_habis" value="Habis" <?php if ($data['status_stok'] == 'Habis') echo 'checked'; ?>> <label for="stok_habis">Habis</label><br><br>

        <label for="kategori_id">Kategori:</label><br>
        <select name="kategori_id" id="kategori_id" required>
            <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                <option value="<?php echo $k['id']; ?>" <?php if ($data['kategori_id'] == $k['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($k['nama_kategori']); ?>
                </option>
            <?php } ?>
        </select><br><br>

        <button type="submit" name="update" value="Update">Update</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
