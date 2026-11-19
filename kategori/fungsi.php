<?php
// Fungsi bantu upload gambar kategori (dipakai tambah.php dan edit.php)

define('FOLDER_GAMBAR_KATEGORI', __DIR__ . '/../uploads/kategori/');

/**
 * Memproses upload gambar.
 * Return: nama file baru (string) jika sukses,
 *         null jika tidak ada file yang diunggah,
 *         false jika gagal ($error terisi).
 */
function upload_gambar_kategori($file, &$error)
{
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 2 * 1024 * 1024) {
        $error = 'Gambar gagal diunggah atau melebihi 2 MB.';
        return false;
    }

    $info = @getimagesize($file['tmp_name']);
    $mime = $info['mime'] ?? '';
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!$info || !isset($allowed[$mime])) {
        $error = 'Format gambar harus JPG, PNG, atau WEBP.';
        return false;
    }

    if (!is_dir(FOLDER_GAMBAR_KATEGORI)) {
        mkdir(FOLDER_GAMBAR_KATEGORI, 0755, true);
    }

    $namaFile = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];

    if (!move_uploaded_file($file['tmp_name'], FOLDER_GAMBAR_KATEGORI . $namaFile)) {
        $error = 'Gagal menyimpan gambar.';
        return false;
    }

    return $namaFile;
}

function hapus_gambar_kategori($namaFile)
{
    if ($namaFile) {
        $path = FOLDER_GAMBAR_KATEGORI . basename($namaFile);
        if (is_file($path)) {
            unlink($path);
        }
    }
}
