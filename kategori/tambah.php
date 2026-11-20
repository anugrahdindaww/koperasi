<?php
include "../koneksi.php";
include "fungsi.php";

$error = '';

if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama_kategori']);

    if ($nama === '') {
        $error = 'Nama kategori wajib diisi.';
    } else {
        // Cek nama kategori sudah ada atau belum
        $cek = mysqli_prepare($koneksi, "SELECT id FROM kategori WHERE nama_kategori = ?");
        mysqli_stmt_bind_param($cek, "s", $nama);
        mysqli_stmt_execute($cek);
        mysqli_stmt_store_result($cek);
        if (mysqli_stmt_num_rows($cek) > 0) {
            $error = 'Nama kategori sudah ada.';
        }
    }

    $gambar = '';
    if ($error === '') {
        $gambar = upload_gambar_kategori($_FILES['gambar'] ?? null, $error);
        if ($gambar === false) {
            $gambar = '';
        } elseif ($gambar === null) {
            $gambar = '';
        }
    }

    if ($error === '') {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO kategori (nama_kategori, gambar) VALUES (?, ?)"
        );

        if (!$stmt) {
            hapus_gambar_kategori($gambar);
            $error = "Gagal menyiapkan query: " . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $nama, $gambar);

            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php?pesan=Data berhasil disimpan");
                exit();
            }

            hapus_gambar_kategori($gambar);
            $error = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Tambah Kategori</h2>

    <?php if ($error !== '') { ?>
        <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
    <?php } ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="nama_kategori">Nama Kategori:</label><br>
        <input type="text" name="nama_kategori" id="nama_kategori" value="<?php echo htmlspecialchars($_POST['nama_kategori'] ?? ''); ?>" required><br><br>

        <label for="gambar">Gambar:</label><br>
        <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/webp"><br>
        <small>Format JPG, PNG, atau WEBP. Maksimal 2 MB.</small><br><br>

        <input type="submit" name="simpan" value="Simpan">
        <a href="index.php">Batal</a>
    </form>
</body>
</html>
