# 🎓 Sistem Data Mahasiswa — CRUD PHP Native & MySQL

Proyek ujian praktik pemrograman web berbasis CRUD (Create, Read, Update, Delete)
menggunakan **HTML**, **CSS**, **JavaScript**, **PHP Native**, dan **MySQL**.

---

## 📌 Tema Proyek
**Data Mahasiswa** — dengan atribut:
- NIM (Nomor Induk Mahasiswa)
- Nama Lengkap
- Jurusan
- Foto Profil

---

## 🗂️ Struktur File

```
crud-mahasiswa/
│
├── koneksi.php        ← Konfigurasi & koneksi database MySQLi
├── index.php          ← Halaman utama: daftar semua data mahasiswa
├── form.php           ← Form tambah & edit data (dual-mode)
├── proses.php         ← Handler INSERT & UPDATE (dipanggil via POST)
├── hapus.php          ← Handler DELETE (dipanggil via GET)
│
├── uploads/           ← Folder penyimpanan foto yang diunggah
│
├── database.sql       ← File ekspor/import database MySQL
└── README.md          ← Dokumentasi proyek (file ini)
```

---

## ⚙️ Cara Instalasi & Menjalankan

### 1. Persiapan Server Lokal
Pastikan XAMPP / WAMP / Laragon sudah terinstal dan **Apache** + **MySQL** aktif.

### 2. Clone / Salin Proyek
```bash
git clone https://github.com/username/crud-mahasiswa.git
```
Letakkan folder proyek di dalam:
- **XAMPP:** `C:/xampp/htdocs/crud-mahasiswa/`
- **WAMP :** `C:/wamp64/www/crud-mahasiswa/`

### 3. Import Database
1. Buka **phpMyAdmin** → `http://localhost/phpmyadmin`
2. Klik **Import** → pilih file `database.sql`
3. Klik **Go** / **Execute**

### 4. Konfigurasi Koneksi
Buka file `koneksi.php` dan sesuaikan:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');   // username MySQL Anda
define('DB_PASS', '');       // password MySQL Anda (kosong jika default)
define('DB_NAME', 'db_mahasiswa');
```

### 5. Buat Folder Uploads
Buat folder `uploads/` di root proyek (jika belum ada):
```
crud-mahasiswa/uploads/
```
Pastikan folder ini dapat ditulisi (writable).

### 6. Akses Aplikasi
Buka browser dan kunjungi:
```
http://localhost/crud-mahasiswa/index.php
```

---

## ✅ Fitur yang Diimplementasikan

| Fitur | Status |
|---|---|
| Koneksi database dengan MySQLi | ✅ |
| Halaman daftar data (tabel + thumbnail foto) | ✅ |
| Form tambah data baru | ✅ |
| Form edit data (pre-filled otomatis) | ✅ |
| Hapus data dengan konfirmasi JS `confirm()` | ✅ |
| Upload foto (JPG/JPEG/PNG, maks 2 MB) | ✅ |
| Nama file otomatis dengan `time()` + `uniqid()` | ✅ |
| Validasi JavaScript (client-side) | ✅ |
| Validasi PHP (server-side, lapisan kedua) | ✅ |
| Pesan sukses setelah operasi CRUD | ✅ |
| Hapus foto lama saat foto diperbarui/dihapus | ✅ |
| Desain responsif & modern (CSS native) | ✅ |

---

## 🛠️ Teknologi yang Digunakan

- **PHP** 7.4+ (Native, tanpa framework)
- **MySQL** 5.7+ / MariaDB 10.4+
- **HTML5** & **CSS3** (Native, tanpa Bootstrap/jQuery)
- **JavaScript** ES5/ES6 (Vanilla, tanpa library)
- **MySQLi** (ekstensi koneksi database)

---

## 👤 Informasi Pengembang

- **Nama   :** *(Taufiq Komara)*
- **NIM    :** *(2430511052)*
- **Kelas  :** *(4C)*
- **Tanggal:** 08-05-2026

---

## 📝 Catatan Commit

Proyek ini dikerjakan dengan minimal **3 commit** deskriptif:

1. `feat: inisialisasi proyek dan struktur database`
2. `feat: implementasi halaman index dan form CRUD`
3. `feat: implementasi upload foto dan validasi JS/PHP`

---

*Proyek ini dibuat sebagai tugas ujian praktik mata kuliah Pemrograman Web.*
