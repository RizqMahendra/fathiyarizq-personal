<?php
/**
 * includes/admin_header.php — kepala layout admin.
 * Variabel opsional: $judul (judul halaman), $active (menu aktif).
 */
$judul  = $judul  ?? 'Dashboard';
$active = $active ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($judul) ?> — Admin</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body class="admin-body">
<div class="admin-layout">
  <aside class="admin-side">
    <h2>Admin Panel</h2>
    <div class="who"><?= e($adminNama) ?></div>
    <nav>
      <a href="<?= BASE_URL ?>admin/index.php"     class="<?= $active === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
      <a href="<?= BASE_URL ?>admin/profil.php"    class="<?= $active === 'profil' ? 'active' : '' ?>">Data Diri</a>
      <a href="<?= BASE_URL ?>admin/keahlian.php"  class="<?= $active === 'keahlian' ? 'active' : '' ?>">Keahlian</a>
      <a href="<?= BASE_URL ?>admin/karya.php"     class="<?= $active === 'karya' ? 'active' : '' ?>">Karya</a>
      <a href="<?= BASE_URL ?>admin/pesan.php"     class="<?= $active === 'pesan' ? 'active' : '' ?>">Pesan</a>
      <a href="<?= BASE_URL ?>index.php" target="_blank">Lihat Website</a>
      <a href="<?= BASE_URL ?>logout.php">Logout</a>
    </nav>
  </aside>
  <main class="admin-main">
    <div class="admin-head">
      <h1><?= e($judul) ?></h1>
      <a href="<?= BASE_URL ?>logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
    <?php if ($flash = get_flash()): ?>
      <div class="flash <?= e($flash['tipe']) ?>"><?= e($flash['pesan']) ?></div>
    <?php endif; ?>
