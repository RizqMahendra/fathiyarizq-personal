<?php
/**
 * config/db.php
 * Konfigurasi database + base URL.
 *
 * LOKAL (XAMPP): host=localhost, user=root, pass='', db=portfolio
 * INFINITYFREE : isi host/user/pass/nama db dari Control Panel (vPanel) Anda.
 */

// ==== PENGATURAN DATABASE ====
// LOKAL (XAMPP) : host=localhost, user=root, pass='', db=portfolio
// INFINITYFREE  : isi dari vPanel -> MySQL Databases. PENTING: DB_HOST BUKAN
//                 'localhost', melainkan seperti 'sql123.infinityfree.com'
//                 (atau 'sql123.epizy.com'), user 'epiz_12345678',
//                 db 'epiz_12345678_portfolio', dan password DB Anda.
define('DB_HOST', 'localhost');
define('DB_NAME', 'portfolio');
define('DB_USER', 'root');
define('DB_PASS', '');

// ==== BASE URL OTOMATIS ====
// Menghitung URL dasar proyek relatif terhadap document root,
// sehingga bekerja baik di http://localhost/fathiyarizq/portfolio/
// maupun di root hosting InfinityFree.
if (!defined('BASE_URL')) {
    $projectRoot = str_replace('\\', '/', realpath(__DIR__ . '/..'));
    $docRoot     = str_replace('\\', '/', realpath($_SERVER['DOCUMENT_ROOT'] ?? ''));
    $base        = ($docRoot && strpos($projectRoot, $docRoot) === 0)
        ? substr($projectRoot, strlen($docRoot))
        : '';
    define('BASE_URL', rtrim($base, '/') . '/');
}

// ==== KONEKSI PDO ====
try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    die('Koneksi database gagal. Periksa config/db.php. Detail: ' . htmlspecialchars($e->getMessage()));
}
