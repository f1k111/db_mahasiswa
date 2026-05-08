<?php
require_once 'koneksi.php';

$query  = "SELECT * FROM mahasiswa ORDER BY id DESC";
$result = mysqli_query($conn, $query);

$pesan = '';
if (isset($_GET['pesan'])) {
    if ($_GET['pesan'] === 'tambah') $pesan = 'Data berhasil ditambahkan!';
    if ($_GET['pesan'] === 'edit')   $pesan = 'Data berhasil diperbarui!';
    if ($_GET['pesan'] === 'hapus')  $pesan = 'Data berhasil dihapus!';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">🎓 Sistem Data Mahasiswa</div>

<div class="container">

    <h2>Daftar Mahasiswa</h2>

    <?php if ($pesan): ?>
        <div class="alert alert-success"><?= $pesan ?></div>
    <?php endif; ?>

    <p><a href="form.php" class="btn btn-success">+ Tambah Mahasiswa</a></p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>NIM</th>
                <th>Nama Lengkap</th>
                <th>Jurusan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <?php
                        $src = (file_exists('uploads/' . $row['foto']) && $row['foto'] !== 'default.png')
                            ? 'uploads/' . htmlspecialchars($row['foto'])
                            : 'https://placehold.co/50x50?text=foto';
                        ?>
                        <img class="thumb" src="<?= $src ?>" alt="foto">
                    </td>
                    <td><?= htmlspecialchars($row['nim']) ?></td>
                    <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
                    <td><?= htmlspecialchars($row['jurusan']) ?></td>
                    <td>
                        <a href="form.php?id=<?= $row['id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="hapus.php?id=<?= $row['id'] ?>" class="btn btn-danger"
                           onclick="return confirm('Yakin ingin menghapus data <?= htmlspecialchars($row['nama_lengkap'], ENT_QUOTES) ?>?')">
                           Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align:center; color:#aaa; padding:20px;">
                        Belum ada data mahasiswa.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<div class="footer">Sistem Data Mahasiswa &copy; 2025</div>

<script>
    // Auto hide alert setelah 3 detik
    var alert = document.querySelector('.alert');
    if (alert) {
        setTimeout(function () { alert.style.display = 'none'; }, 3000);
    }
</script>

</body>
</html>