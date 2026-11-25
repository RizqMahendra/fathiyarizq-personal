-- ============================================================
--  Database Portfolio (UAS WEB I) — VERSI HOSTING (InfinityFree)
--
--  Cara pakai:
--   1. Buat database dulu di vPanel InfinityFree -> MySQL Databases.
--   2. Buka phpMyAdmin hosting, PILIH database tsb (klik namanya di kiri).
--   3. Tab Import -> pilih file ini -> Go.
--
--  File ini TIDAK membuat database (di hosting database sudah dibuat
--  lewat vPanel dan namanya berawalan epiz_xxxxx), jadi langsung aman.
--  Login admin default -> username: admin  |  password: admin123
-- ============================================================

SET NAMES utf8mb4;

-- ---------- Tabel Admin ----------
DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id`       INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50)  NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama`     VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB;

-- password = admin123
INSERT INTO `admins` (`username`, `password`, `nama`) VALUES
('admin', '$2y$10$S3SdCnaEp1A176qhLbYB6uRbIWZ6ntrCCoFeIoGXUN035yjNv8xVW', 'Administrator');

-- ---------- Tabel Profil (data diri) ----------
DROP TABLE IF EXISTS `profil`;
CREATE TABLE `profil` (
  `id`        INT AUTO_INCREMENT PRIMARY KEY,
  `nama`      VARCHAR(120) NOT NULL,
  `nim`       VARCHAR(30)  DEFAULT NULL,
  `headline`  VARCHAR(160) DEFAULT NULL,
  `ringkasan` TEXT,
  `alamat`    VARCHAR(180) DEFAULT NULL,
  `telepon`   VARCHAR(40)  DEFAULT NULL,
  `email`     VARCHAR(120) DEFAULT NULL,
  `github`    VARCHAR(200) DEFAULT NULL,
  `linkedin`  VARCHAR(200) DEFAULT NULL,
  `foto`      VARCHAR(200) DEFAULT NULL
) ENGINE=InnoDB;

INSERT INTO `profil`
(`nama`, `nim`, `headline`, `ringkasan`, `alamat`, `telepon`, `email`, `github`, `linkedin`, `foto`) VALUES
('Fathiyarizq Mahendra Putra', 'NIM Anda', 'WordPress Developer, MCs Graduate',
 'Experienced in Web-based Programming dengan keahlian kuat pada pengembangan aplikasi web dan data science. Lebih dari 4 tahun berpengalaman sebagai Website Developer, menguasai Python untuk membangun sistem Natural Language Processing (NLP) khususnya Text Classification dan Named Entity Recognition (NER).',
 'Denpasar, Bali, Indonesia', '+62 887-390-6394', 'Fathiyarizq.mahendra@gmail.com',
 'https://github.com/RizqMahendra', 'https://www.linkedin.com/in/fathiyarizq-mahendra-putra-5118b0148', NULL);

-- ---------- Tabel Keahlian ----------
DROP TABLE IF EXISTS `keahlian`;
CREATE TABLE `keahlian` (
  `id`       INT AUTO_INCREMENT PRIMARY KEY,
  `nama`     VARCHAR(120) NOT NULL,
  `kategori` VARCHAR(80)  DEFAULT NULL,
  `deskripsi` TEXT,
  `urutan`   INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO `keahlian` (`nama`, `kategori`, `deskripsi`, `urutan`) VALUES
('WordPress Development', 'Web', 'Mahir — membuat custom theme & plugin, lebih dari 4 tahun menangani proyek klien.', 1),
('PHP',                   'Programming', 'Mahir — membangun back-end dinamis, integrasi database, dan tema WordPress kustom.', 2),
('Elementor',             'Web', 'Mahir — merancang halaman landing dan company profile yang responsif.', 3),
('TailwindCSS',           'Frontend', 'Mahir — menyusun antarmuka modern dan cepat untuk sistem berbasis web.', 4),
('VueJS',                 'Frontend', 'Terampil — mengembangkan komponen interaktif pada proyek SIC Lab UGM.', 5),
('Python (NLP / NER)',    'Data Science', 'Terampil — membangun sistem Natural Language Processing untuk Text Classification dan Named Entity Recognition.', 6),
('SEO (Yoast / AIO SEO)', 'Marketing', 'Terampil — mengoptimalkan meta, struktur konten, dan strategi peringkat pencarian.', 7),
('MySQL',                 'Database', 'Terampil — merancang skema dan menulis query relasional untuk aplikasi web.', 8);

-- ---------- Tabel Karya ----------
DROP TABLE IF EXISTS `karya`;
CREATE TABLE `karya` (
  `id`        INT AUTO_INCREMENT PRIMARY KEY,
  `judul`     VARCHAR(160) NOT NULL,
  `deskripsi` TEXT,
  `gambar`    VARCHAR(200) DEFAULT NULL,
  `link`      VARCHAR(250) DEFAULT NULL,
  `tahun`     VARCHAR(10)  DEFAULT NULL,
  `urutan`    INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO `karya` (`judul`, `deskripsi`, `gambar`, `link`, `tahun`, `urutan`) VALUES
('Front-End Sistem SIC Lab UGM', 'Pengembangan template dan komponen front-end untuk sistem berbasis web menggunakan TailwindCSS dan VueJS di SIC Laboratory UGM.', NULL, NULL, '2024', 1),
('Custom WordPress Theme (U-DIGITAL BV)', 'Pembuatan custom theme WordPress menggunakan PHP dan plugin ACF berdasarkan desain Adobe XD untuk klien U-DIGITAL.', NULL, NULL, '2023', 2),
('Company Profile PT APPKEY', 'Pengembangan dan pemeliharaan halaman custom menggunakan WordPress dan Elementor sesuai kebutuhan klien.', NULL, NULL, '2020', 3);

-- ---------- Tabel Pesan (kontak) ----------
DROP TABLE IF EXISTS `pesan`;
CREATE TABLE `pesan` (
  `id`           INT AUTO_INCREMENT PRIMARY KEY,
  `nama`         VARCHAR(120) NOT NULL,
  `email`        VARCHAR(120) NOT NULL,
  `subjek`       VARCHAR(180) DEFAULT NULL,
  `pesan`        TEXT NOT NULL,
  `dibaca`       TINYINT(1) DEFAULT 0,
  `dibuat_pada`  DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
