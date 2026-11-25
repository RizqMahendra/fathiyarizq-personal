<?php
/**
 * includes/header.php — kepala halaman publik (one-page).
 * Mengharapkan variabel opsional: $profil (array data diri).
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/functions.php';

if (!isset($profil) || !is_array($profil)) {
    $profil = get_profil($pdo);
}
$namaTampil = $profil['nama'] ?? 'Nama Anda';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($namaTampil) ?> — Portfolio</title>
  <meta name="description" content="<?= e($profil['headline'] ?? 'Web Portfolio') ?>">
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
  <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>&#128187;</text></svg>">
</head>
<body>
<header class="navbar">
  <div class="container">
    <a href="<?= BASE_URL ?>#home" class="brand"><?= e($namaTampil) ?></a>
    <button class="nav-toggle" aria-label="Menu">&#9776;</button>
    <ul class="nav-links">
      <li><a href="#home">Home</a></li>
      <li><a href="#keahlian">Keahlian</a></li>
      <li><a href="#karya">Karya</a></li>
      <li><a href="#kontak">Kontak</a></li>
      <li><a href="<?= BASE_URL ?>login.php">Admin</a></li>
    </ul>
  </div>
</header>
<main>
