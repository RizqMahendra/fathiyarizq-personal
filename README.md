# Portfolio Dinamis — Fathiyarizq Mahendra Putra

Website profil/portfolio **one-page** dinamis berbasis **HTML, CSS, PHP, MySQL**.
Data diri, keahlian, dan karya disimpan di database dan dikelola melalui **halaman admin**
dengan **login (session)** serta CRUD sederhana.

## Fitur

- **One-page publik** (`index.php`): Profil (hero), Keahlian, Galeri Karya, dan Kontak.
- **Form kontak** menyimpan pesan ke database.
- **Admin panel** dengan login session:
  - Data Diri (edit profil + upload foto)
  - Keahlian (tambah/ubah/hapus)
  - Karya (tambah/ubah/hapus + upload gambar)
  - Pesan (lihat/tandai dibaca/hapus)
- Identitas **Nama & NIM** tampil di footer (sesuai ketentuan UAS).

## Kredensial Admin Default

| Username | Password |
|----------|----------|
| `admin`  | `admin123` |

> Segera ganti password setelah login pertama (ubah hash pada tabel `admins`).

## Struktur Folder

```
portfolio/
├── config/db.php            # konfigurasi & koneksi database
├── includes/                # functions, header, footer, layout admin
├── assets/css, assets/js    # tampilan
├── assets/uploads/          # gambar yang diunggah (foto & karya)
├── admin/                   # halaman admin (CRUD)
├── database/portfolio.sql   # skema + data awal
├── index.php                # halaman one-page
├── login.php / logout.php
└── README.md
```

## Cara Menjalankan (Lokal — XAMPP)

1. Salin folder `portfolio` ke `C:\xampp\htdocs\` (sudah ada jika Anda di sini).
2. Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin` → tab **Import** → pilih `database/portfolio.sql` → **Go**.
   (Database `portfolio` akan dibuat otomatis beserta data awalnya.)
4. Buka website: `http://localhost/fathiyarizq/portfolio/`
5. Buka admin: `http://localhost/fathiyarizq/portfolio/login.php`

Jika MySQL lokal Anda memakai password root, sesuaikan `DB_PASS` di `config/db.php`.

## Cara Upload ke InfinityFree

1. Daftar di [infinityfree.com](https://www.infinityfree.com), buat akun, lalu **Create Account**
   (pilih subdomain gratis seperti `namamu.rf.gd` atau domain sendiri).
2. Buka **Control Panel (vPanel) → MySQL Databases**, buat database baru
   (mis. `epiz_12345678_portfolio`). Catat 4 hal ini:
   - **MySQL Host** → mis. `sql123.infinityfree.com` (bukan `localhost`)
   - **Username** → mis. `epiz_12345678`
   - **Password** → password DB yang Anda buat
   - **Database name** → mis. `epiz_12345678_portfolio`
3. Import struktur tabel: buka **phpMyAdmin** dari vPanel, **klik nama database** di kiri,
   lalu tab **Import** → pilih `database/portfolio_hosting.sql` → **Go**.
   (Pakai `portfolio_hosting.sql`, bukan `portfolio.sql`, karena di hosting database
   dibuat lewat vPanel dan file versi lokal memakai `CREATE DATABASE` yang tidak diizinkan.)
4. Buka `config/db.php`, isi sesuai data di langkah 2:
   ```php
   define('DB_HOST', 'sql123.infinityfree.com');
   define('DB_NAME', 'epiz_12345678_portfolio');
   define('DB_USER', 'epiz_12345678');
   define('DB_PASS', 'password_db_anda');
   ```
5. Upload file website:
   - **Cara mudah:** zip seluruh isi folder `portfolio/`, di **File Manager** vPanel masuk ke
     folder `htdocs`, upload zip-nya, lalu **Extract**. Pastikan `index.php` berada langsung
     di dalam `htdocs` (bukan di dalam subfolder `portfolio` lagi).
   - **Cara FTP:** sambungkan FileZilla memakai kredensial FTP dari vPanel, pindahkan isi
     folder ke `htdocs`.
6. Pastikan folder `assets/uploads` bisa ditulisi (permission `755`, atau `777` bila upload gagal).
7. Buka domain Anda. Tes: halaman utama, form kontak, dan login admin (`login.php`).
8. **Segera ganti password default** `admin123` setelah bisa login.

> `config/db.php` menghitung `BASE_URL` otomatis, jadi tidak ada URL yang perlu diubah
> antara lokal dan hosting.

## Catatan Keamanan

- Password admin disimpan sebagai **hash** (`password_hash()` / `password_verify()`).
- Semua query memakai **prepared statements (PDO)** untuk mencegah SQL Injection.
- Output di-escape dengan `htmlspecialchars()` untuk mencegah XSS.
- Upload gambar divalidasi tipe MIME dan dibatasi 2 MB.
