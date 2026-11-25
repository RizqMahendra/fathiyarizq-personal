<?php
/**
 * includes/functions.php
 * Fungsi bantu yang dipakai di seluruh aplikasi.
 */

/** Escape output HTML (mencegah XSS). */
function e($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Redirect ke URL (relatif terhadap BASE_URL bila tidak absolut). */
function redirect(string $path): void
{
    if (!preg_match('#^https?://#', $path)) {
        $path = BASE_URL . ltrim($path, '/');
    }
    header('Location: ' . $path);
    exit;
}

/** Ambil 1 baris data profil (selalu id=1). */
function get_profil(PDO $pdo): array
{
    $row = $pdo->query('SELECT * FROM profil ORDER BY id ASC LIMIT 1')->fetch();
    return $row ?: [];
}

/** Set / ambil pesan flash sekali tampil. */
function set_flash(string $tipe, string $pesan): void
{
    $_SESSION['flash'] = ['tipe' => $tipe, 'pesan' => $pesan];
}

function get_flash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

/**
 * Simpan file gambar yang diunggah ke assets/uploads.
 * Mengembalikan nama file baru, atau null bila tidak ada file diunggah.
 * Melempar RuntimeException bila file tidak valid.
 */
function upload_gambar(array $file, ?string $namaLama = null): ?string
{
    if (empty($file['name']) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $namaLama; // tidak ada file baru, pertahankan yang lama
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Gagal mengunggah file (kode ' . $file['error'] . ').');
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        throw new RuntimeException('Ukuran gambar maksimal 2 MB.');
    }

    $izin = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!isset($izin[$mime])) {
        throw new RuntimeException('Format gambar harus JPG, PNG, WEBP, atau GIF.');
    }

    $namaBaru = 'img_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $izin[$mime];
    $tujuan   = __DIR__ . '/../assets/uploads/' . $namaBaru;

    if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
        throw new RuntimeException('Tidak dapat menyimpan file. Periksa izin folder assets/uploads.');
    }
    return $namaBaru;
}
