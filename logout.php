<?php
/**
 * logout.php — Menghapus sesi admin lalu kembali ke halaman login.
 */
session_start();
session_unset();
session_destroy();
header('Location: login.php');
exit;
