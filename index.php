<?php
include "koneksi.php";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$query = "SELECT produk.*, kategori.nama_kategori, kategori.gambar
        FROM produk
        LEFT JOIN kategori ON produk.kategori_id = kategori.id";

if ($search != "") {
    $search = mysqli_real_escape_string($koneksi, $search);
    $query .= " WHERE
        produk.kode_produk LIKE '%$search%' OR
        produk.nama_produk LIKE '%$search%' OR
        kategori.nama_kategori LIKE '%$search%' OR
        produk.deskripsi LIKE '%$search%' OR
        produk.harga LIKE '%$search%'";
}

$query .= " ORDER BY produk.id DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengambil data produk: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi</title>
</head>
<body>

    <h2>Koperasi</h2>

    <form method="GET" action="index.php" style="margin-bottom: 20px;">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari produk..." />
        <button type="submit">Cari</button>
        <?php if ($search != "") { ?>
            <a href="index.php">Reset</a>
        <?php } ?>
    </form>

    <a href="tambah.php">Tambah Produk</a> |
    <a href="kategori/index.php">Kelola Kategori</a>
    <br><br>

    <?php if (isset($_GET['pesan'])) { ?>
        <p><b>Status:</b> <?php echo htmlspecialchars($_GET['pesan']); ?></p>
    <?php } ?>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Kode Produk</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Status Stok</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($data['kode_produk']); ?></td>
                    <td><?php echo htmlspecialchars($data['nama_produk']); ?></td>
                    <td><?php echo number_format($data['harga'], 0, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($data['deskripsi']); ?></td>
                    <td><?php echo $data['status_stok']; ?></td>
                    <td>
                        <?php
                        $namaKategori = $data['nama_kategori'] ?? '-';
                        $namaGambar = $data['gambar'] ?? '';
                        ?>
                        <?php if ($namaGambar !== '' && is_file(__DIR__ . '/uploads/kategori/' . basename($namaGambar))) { ?>
                            <img src="uploads/kategori/<?php echo htmlspecialchars(basename($namaGambar)); ?>"
                                alt="<?php echo htmlspecialchars($namaKategori); ?>"
                                width="100"><br>
                        <?php } ?>
                        <?php echo htmlspecialchars($namaKategori); ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?php echo $data['id']; ?>">Edit</a> |
                        <a href="hapus.php?id=<?php echo $data['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="8" align="center">Data tidak ditemukan</td>
            </tr>
        <?php } ?>
    </table>

</body>
</html>
