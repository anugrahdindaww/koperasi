<?php
include "../koneksi.php";
include "fungsi.php";

$id = (int) ($_GET['id'] ?? 0);

$stmt = mysqli_prepare($koneksi, "SELECT * FROM kategori WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    die("Kategori tidak ditemukan.");
}

$error = '';

if (isset($_POST['update'])) {
    $nama = trim($_POST['nama_kategori']);
    $gambar = $data['gambar'];

    if ($nama === '') {
        $error = 'Nama kategori wajib diisi.';
    } else {
        // Cek nama kembar (selain kategori ini sendiri)
        $cek = mysqli_prepare($koneksi, "SELECT id FROM kategori WHERE nama_kategori = ? AND id <> ?");
        mysqli_stmt_bind_param($cek, "si", $nama, $id);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);
        if (mysqli_stmt_num_rows($cek) > 0) {
            $error = 'Nama kategori sudah ada.';
        }
    }

    $gambarBaru = null;
    if ($error === '') {
        $gambarBaru = upload_gambar_kategori($_FILES['gambar'] ?? null, $error);
    }

    if ($error === '') {
        if ($gambarBaru) {
            $gambar = $gambarBaru;
        }

        $stmt = mysqli_prepare($koneksi, "UPDATE kategori SET nama_kategori = ?, gambar = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssi", $nama, $gambar, $id);

        if (mysqli_stmt_execute($stmt)) {
            // Hapus gambar lama hanya jika gambar baru berhasil disimpan
            if ($gambarBaru) {
                hapus_gambar_kategori($data['gambar']);
            }
            header("Location: index.php?pesan=Data berhasil diubah");
            exit();
        } else {
            hapus_gambar_kategori($gambarBaru);
            $error = "Gagal mengubah data: " . mysqli_error($koneksi);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Edit Kategori</h2>

    <?php if ($error !== '') { ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="nama_kategori">Nama Kategori:</label><br>
        <input type="text" name="nama_kategori" id="nama_kategori" value="<?php echo htmlspecialchars($_POST['nama_kategori'] ?? $data['nama_kategori']); ?>" required><br><br>

        <label>Gambar Saat Ini:</label><br>
        <?php if ($data['gambar']) { ?>
            <img src="../uploads/kategori/<?php echo htmlspecialchars($data['gambar']); ?>" width="100" alt="Gambar kategori">
        <?php } else { ?>
            Belum ada gambar
        <?php } ?>
        <br><br>

        <label for="gambar">Ganti Gambar:</label><br>
        <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/webp"><br>
        <small>Kosongkan jika tidak ingin mengganti gambar. Format JPG, PNG, atau WEBP, maksimal 2 MB.</small><br><br>

        <button type="submit" name="update" value="Update">Update</button>
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
