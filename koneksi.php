<?php
/**
 * File Koneksi Database
 * Proyek: Sistem Data Mahasiswa
 * Tema: Data Mahasiswa
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Ganti sesuai user MySQL Anda
define('DB_PASS', '');            // Ganti sesuai password MySQL Anda
define('DB_NAME', 'db_mahasiswa');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("<div style='font-family:sans-serif; padding:20px; background:#fee; border:1px solid red; color:red;'>
        <strong>Koneksi Database Gagal!</strong><br>
        Error: " . mysqli_connect_error() . "
        <br><small>Pastikan MySQL aktif dan konfigurasi di koneksi.php sudah benar.</small>
    </div>");
}

mysqli_set_charset($conn, 'utf8');
?>
