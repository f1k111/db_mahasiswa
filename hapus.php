<?php
/**
 * hapus.php — Handler untuk DELETE data mahasiswa
 * Dipanggil via GET dari tombol Hapus di index.php
 * (Konfirmasi dilakukan di sisi klien dengan confirm() JS)
 */
require_once 'koneksi.php';

// Validasi parameter id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];

// Ambil data mahasiswa (untuk mendapatkan nama file foto)
$sql_select = "SELECT foto FROM mahasiswa WHERE id = $id LIMIT 1";
$result     = mysqli_query($conn, $sql_select);

if (!$result || mysqli_num_rows($result) === 0) {
    // Data tidak ditemukan, kembali ke index
    header('Location: index.php');
    exit;
}

$row      = mysqli_fetch_assoc($result);
$nama_foto = $row['foto'];

// Jalankan query DELETE
$sql_delete = "DELETE FROM mahasiswa WHERE id = $id";

if (mysqli_query($conn, $sql_delete)) {
    // Hapus file foto dari folder uploads/ (jika bukan default)
    if (!empty($nama_foto) && $nama_foto !== 'default.png') {
        $path_foto = 'uploads/' . $nama_foto;
        if (file_exists($path_foto)) {
            unlink($path_foto);
        }
    }
    header('Location: index.php?pesan=hapus');
} else {
    // Gagal hapus
    header('Location: index.php?pesan=gagal&detail=' . urlencode(mysqli_error($conn)));
}

exit;
?>
