<?php
require_once 'koneksi.php';

$mode      = 'tambah';
$data      = ['id' => '', 'nim' => '', 'nama_lengkap' => '', 'jurusan' => '', 'foto' => ''];
$judul     = 'Tambah Mahasiswa';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id  = (int) $_GET['id'];
    $sql = "SELECT * FROM mahasiswa WHERE id = $id LIMIT 1";
    $res = mysqli_query($conn, $sql);
    if ($res && mysqli_num_rows($res) > 0) {
        $data  = mysqli_fetch_assoc($res);
        $mode  = 'edit';
        $judul = 'Edit Mahasiswa';
    } else {
        header('Location: index.php');
        exit;
    }
}

$daftar_jurusan = [
    'Teknik Informatika',
    'Sistem Informasi',
    'Teknik Komputer',
    'Manajemen Informatika',
    'Komputerisasi Akuntansi',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $judul ?> | Data Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">🎓 Sistem Data Mahasiswa</div>

<div class="container" style="max-width: 600px;">

    <h2><?= $judul ?></h2>
    <p><a href="index.php">&larr; Kembali ke daftar</a></p>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <form id="formMahasiswa" action="proses.php" method="POST" enctype="multipart/form-data" novalidate>

        <input type="hidden" name="mode" value="<?= $mode ?>">
        <?php if ($mode === 'edit'): ?>
            <input type="hidden" name="id"        value="<?= (int)$data['id'] ?>">
            <input type="hidden" name="foto_lama" value="<?= htmlspecialchars($data['foto']) ?>">
        <?php endif; ?>

        <div class="form-group">
            <label>NIM <span style="color:red;">*</span></label>
            <input type="text" id="nim" name="nim"
                   value="<?= htmlspecialchars($data['nim']) ?>"
                   placeholder="Contoh: 2021001">
            <div class="error-msg" id="err-nim">NIM tidak boleh kosong.</div>
        </div>

        <div class="form-group">
            <label>Nama Lengkap <span style="color:red;">*</span></label>
            <input type="text" id="nama_lengkap" name="nama_lengkap"
                   value="<?= htmlspecialchars($data['nama_lengkap']) ?>"
                   placeholder="Contoh: Andi Pratama">
            <div class="error-msg" id="err-nama">Nama lengkap tidak boleh kosong.</div>
        </div>

        <div class="form-group">
            <label>Jurusan <span style="color:red;">*</span></label>
            <select id="jurusan" name="jurusan">
                <option value="">-- Pilih Jurusan --</option>
                <?php foreach ($daftar_jurusan as $j): ?>
                    <option value="<?= $j ?>" <?= ($data['jurusan'] === $j) ? 'selected' : '' ?>>
                        <?= $j ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div class="error-msg" id="err-jurusan">Jurusan harus dipilih.</div>
        </div>

        <div class="form-group">
            <label>
                Foto Profil
                <?= ($mode === 'tambah') ? '<span style="color:red;">*</span>' : '(kosongkan jika tidak diganti)' ?>
            </label>
            <input type="file" id="foto" name="foto" accept="image/jpeg,image/jpg,image/png">
            <small style="color:#888;">Format: JPG, JPEG, PNG. Maks. 2 MB.</small>
            <div class="error-msg" id="err-foto">Foto tidak valid atau ukuran terlalu besar.</div>

            <?php if ($mode === 'edit' && !empty($data['foto']) && $data['foto'] !== 'default.png'): ?>
                <br>
                <small>Foto saat ini:</small><br>
                <img id="preview-img"
                     src="uploads/<?= htmlspecialchars($data['foto']) ?>"
                     style="display:block;">
            <?php else: ?>
                <img id="preview-img" src="" alt="preview">
            <?php endif; ?>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">
                <?= ($mode === 'tambah') ? 'Simpan' : 'Perbarui' ?>
            </button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </div>

    </form>

</div>

<div class="footer">Sistem Data Mahasiswa &copy; 2025</div>

<script>
(function () {
    var form      = document.getElementById('formMahasiswa');
    var nim       = document.getElementById('nim');
    var nama      = document.getElementById('nama_lengkap');
    var jurusan   = document.getElementById('jurusan');
    var foto      = document.getElementById('foto');
    var mode      = document.querySelector('input[name="mode"]').value;
    var MAX_SIZE  = 2 * 1024 * 1024;
    var ALLOWED   = ['image/jpeg', 'image/jpg', 'image/png'];

    // Preview foto
    foto.addEventListener('change', function () {
        var file   = this.files[0];
        var prevEl = document.getElementById('preview-img');
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                prevEl.src           = e.target.result;
                prevEl.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    function showErr(el, msgId, txt) {
        el.classList.add('error');
        var m = document.getElementById(msgId);
        if (txt) m.textContent = txt;
        m.style.display = 'block';
    }

    function clearErr(el, msgId) {
        el.classList.remove('error');
        document.getElementById(msgId).style.display = 'none';
    }

    form.addEventListener('submit', function (e) {
        var ok = true;

        clearErr(nim,     'err-nim');
        clearErr(nama,    'err-nama');
        clearErr(jurusan, 'err-jurusan');
        clearErr(foto,    'err-foto');

        if (nim.value.trim() === '') {
            showErr(nim, 'err-nim'); ok = false;
        }
        if (nama.value.trim() === '') {
            showErr(nama, 'err-nama'); ok = false;
        }
        if (jurusan.value === '') {
            showErr(jurusan, 'err-jurusan'); ok = false;
        }

        var file = foto.files[0];
        if (mode === 'tambah' && !file) {
            showErr(foto, 'err-foto', 'Foto wajib diunggah.'); ok = false;
        }
        if (file) {
            if (ALLOWED.indexOf(file.type) === -1) {
                showErr(foto, 'err-foto', 'Format harus JPG, JPEG, atau PNG.'); ok = false;
            } else if (file.size > MAX_SIZE) {
                showErr(foto, 'err-foto', 'Ukuran file melebihi 2 MB.'); ok = false;
            }
        }

        if (!ok) e.preventDefault();
    });

    nim.addEventListener('input',     function () { clearErr(nim,     'err-nim');     });
    nama.addEventListener('input',    function () { clearErr(nama,    'err-nama');    });
    jurusan.addEventListener('change',function () { clearErr(jurusan, 'err-jurusan'); });
    foto.addEventListener('change',   function () { clearErr(foto,    'err-foto');    });
}());
</script>

</body>
</html>