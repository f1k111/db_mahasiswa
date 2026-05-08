-- ============================================================
-- File Ekspor Database
-- Proyek : Sistem Data Mahasiswa
-- Database: db_mahasiswa
-- ============================================================

CREATE DATABASE IF NOT EXISTS `db_mahasiswa`
  CHARACTER SET utf8
  COLLATE utf8_general_ci;

USE `db_mahasiswa`;

-- -------------------------------------------------------
-- Struktur tabel `mahasiswa`
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id`           INT(11)       NOT NULL AUTO_INCREMENT,
  `nim`          VARCHAR(20)   NOT NULL,
  `nama_lengkap` VARCHAR(100)  NOT NULL,
  `jurusan`      VARCHAR(100)  NOT NULL,
  `foto`         VARCHAR(255)  NOT NULL DEFAULT 'default.png',
  `created_at`   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nim` (`nim`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -------------------------------------------------------
-- Data contoh / dummy
-- -------------------------------------------------------
INSERT INTO `mahasiswa` (`nim`, `nama_lengkap`, `jurusan`, `foto`) VALUES
('2021001', 'Andi Pratama',      'Teknik Informatika',   'default.png'),
('2021002', 'Siti Rahayu',       'Sistem Informasi',     'default.png'),
('2021003', 'Budi Santoso',      'Teknik Komputer',      'default.png'),
('2021004', 'Dewi Lestari',      'Manajemen Informatika','default.png'),
('2021005', 'Rizky Firmansyah',  'Teknik Informatika',   'default.png');
