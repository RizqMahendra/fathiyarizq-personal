<?php
/**
 * admin/auth.php — Penjaga sesi. Wajib di-include di awal setiap halaman admin.
 * Mengalihkan ke halaman login bila admin belum masuk.
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (empty($_SESSION['admin_id'])) {
    redirect('login.php');
}

$adminNama = $_SESSION['admin_nama'] ?? 'Admin';
