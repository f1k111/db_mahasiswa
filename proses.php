<?php
/**
 * proses.php — Handler untuk INSERT dan UPDATE data mahasiswa
 * Dipanggil via POST dari form.php
 */
require_once 'koneksi.php';

// Hanya terima method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

/* ================================================================
   1. AMBIL & SANITASI INPUT
   ================================================================ */
$mode         = isset($_POST['mode']) ? $_POST['mode'] : '';
$nim          = isset($_POST['nim'])          ? trim(mysqli_real_escape_string($conn, $_POST['nim']))          : '';
$nama_lengkap = isset($_POST['nama_lengkap']) ? trim(mysqli_real_escape_string($conn, $_POST['nama_lengkap'])) : '';
$jurusan      = isset($_POST['jurusan'])      ? trim(mysqli_real_escape_string($conn, $_POST['jurusan']))      : '';
$id           = ($mode === 'edit' && isset($_POST['id'])) ? (int) $_POST['id'] : 0;
$foto_lama    = ($mode === 'edit' && isset($_POST['foto_lama'])) ? $_POST['foto_lama'] : 'default.png';

/* ================================================================
   2. VALIDASI SERVER-SIDE (lapisan keamanan kedua)
   ================================================================ */
$errors = [];
if (empty($nim))          $errors[] = 'NIM tidak boleh kosong.';
if (empty($nama_lengkap)) $errors[] = 'Nama lengkap tidak boleh kosong.';
if (empty($jurusan))      $errors[] = 'Jurusan tidak boleh kosong.';
if ($mode === 'edit' && $id <= 0) $errors[] = 'ID data tidak valid.';

if (!empty($errors)) {
    // Kembalikan ke form dengan pesan error sederhana
    $pesan_error = implode(' | ', $errors);
    header('Location: ' . ($mode === 'edit' ? "form.php?id=$id" : 'form.php') . '&error=' . urlencode($pesan_error));
    exit;
}

/* ================================================================
   3. PROSES UPLOAD FOTO
   ================================================================ */
$nama_foto   = $foto_lama; // Default: pakai foto lama (untuk mode edit tanpa ganti foto)
$upload_dir  = 'uploads/';
$ada_foto_baru = false;

// Buat folder uploads jika belum ada
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $file      = $_FILES['foto'];
    $tmp_name  = $file['tmp_name'];
    $ukuran    = $file['size'];
    $tipe      = strtolower($file['type']);
    $ekstensi  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Validasi tipe dan ukuran
    $tipe_diizinkan = ['image/jpeg', 'image/jpg', 'image/png'];
    $eks_diizinkan  = ['jpg', 'jpeg', 'png'];
    $maks_ukuran    = 2 * 1024 * 1024; // 2 MB

    if (!in_array($tipe, $tipe_diizinkan) || !in_array($ekstensi, $eks_diizinkan)) {
        header('Location: ' . ($mode === 'edit' ? "form.php?id=$id" : 'form.php') . '&error=' . urlencode('Format foto tidak valid. Gunakan JPG, JPEG, atau PNG.'));
        exit;
    }

    if ($ukuran > $maks_ukuran) {
        header('Location: ' . ($mode === 'edit' ? "form.php?id=$id" : 'form.php') . '&error=' . urlencode('Ukuran foto melebihi batas 2 MB.'));
        exit;
    }

    // Verifikasi file benar-benar gambar menggunakan getimagesize()
    $info_gambar = @getimagesize($tmp_name);
    if ($info_gambar === false) {
        header('Location: ' . ($mode === 'edit' ? "form.php?id=$id" : 'form.php') . '&error=' . urlencode('File yang diunggah bukan gambar yang valid.'));
        exit;
    }

    // Buat nama file unik: timestamp + uniqid + ekstensi
    $nama_foto     = time() . '_' . uniqid() . '.' . $ekstensi;
    $tujuan        = $upload_dir . $nama_foto;
    $ada_foto_baru = true;

    if (!move_uploaded_file($tmp_name, $tujuan)) {
        header('Location: ' . ($mode === 'edit' ? "form.php?id=$id" : 'form.php') . '&error=' . urlencode('Gagal menyimpan foto. Periksa izin folder uploads/.'));
        exit;
    }

} elseif ($mode === 'tambah') {
    // Mode tambah: foto wajib
    header('Location: form.php&error=' . urlencode('Foto profil wajib diunggah.'));
    exit;
}

/* ================================================================
   4. QUERY INSERT atau UPDATE
   ================================================================ */
if ($mode === 'tambah') {
    // INSERT baru
    $sql = "INSERT INTO mahasiswa (nim, nama_lengkap, jurusan, foto)
            VALUES ('$nim', '$nama_lengkap', '$jurusan', '$nama_foto')";

    if (mysqli_query($conn, $sql)) {
        header('Location: index.php?pesan=tambah');
        exit;
    } else {
        // Cek apakah error karena NIM duplikat
        if (mysqli_errno($conn) === 1062) {
            // Hapus foto yang sudah terlanjur diupload
            if ($ada_foto_baru && file_exists($upload_dir . $nama_foto)) {
                unlink($upload_dir . $nama_foto);
            }
            header('Location: form.php&error=' . urlencode('NIM sudah terdaftar. Gunakan NIM yang berbeda.'));
        } else {
            header('Location: form.php&error=' . urlencode('Gagal menyimpan data: ' . mysqli_error($conn)));
        }
        exit;
    }

} elseif ($mode === 'edit') {
    // UPDATE data yang ada
    $sql = "UPDATE mahasiswa
            SET nim          = '$nim',
                nama_lengkap = '$nama_lengkap',
                jurusan      = '$jurusan',
                foto         = '$nama_foto'
            WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        // Hapus foto lama jika berhasil diganti dan bukan default
        if ($ada_foto_baru && !empty($foto_lama) && $foto_lama !== 'default.png') {
            $path_foto_lama = $upload_dir . $foto_lama;
            if (file_exists($path_foto_lama)) {
                unlink($path_foto_lama);
            }
        }
        header('Location: index.php?pesan=edit');
        exit;
    } else {
        if (mysqli_errno($conn) === 1062) {
            header('Location: form.php?id=' . $id . '&error=' . urlencode('NIM sudah digunakan oleh mahasiswa lain.'));
        } else {
            header('Location: form.php?id=' . $id . '&error=' . urlencode('Gagal memperbarui data: ' . mysqli_error($conn)));
        }
        exit;
    }

} else {
    // Mode tidak dikenal
    header('Location: index.php');
    exit;
}
?>