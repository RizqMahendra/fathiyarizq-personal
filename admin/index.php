<?php
/**
 * admin/index.php — Dashboard.
 */
require_once __DIR__ . '/auth.php';

$judul  = 'Dashboard';
$active = 'dashboard';

$nKeahlian = (int)$pdo->query('SELECT COUNT(*) FROM keahlian')->fetchColumn();
$nKarya    = (int)$pdo->query('SELECT COUNT(*) FROM karya')->fetchColumn();
$nPesan    = (int)$pdo->query('SELECT COUNT(*) FROM pesan')->fetchColumn();
$nBelum    = (int)$pdo->query('SELECT COUNT(*) FROM pesan WHERE dibaca = 0')->fetchColumn();
$profil    = get_profil($pdo);

require __DIR__ . '/../includes/admin_header.php';
?>
<div class="stat-grid">
  <div class="stat"><div class="num"><?= $nKeahlian ?></div><div class="lbl">Keahlian</div></div>
  <div class="stat"><div class="num"><?= $nKarya ?></div><div class="lbl">Karya</div></div>
  <div class="stat"><div class="num"><?= $nPesan ?></div><div class="lbl">Total Pesan</div></div>
  <div class="stat"><div class="num"><?= $nBelum ?></div><div class="lbl">Pesan Belum Dibaca</div></div>
</div>

<div class="panel mt-2">
  <h3 style="margin-top:0">Selamat datang, <?= e($adminNama) ?></h3>
  <p style="color:var(--muted)">
    Kelola konten portfolio Anda melalui menu di samping. Halaman profil menampilkan data diri
    <strong><?= e($profil['nama'] ?? '—') ?></strong> yang tampil di website.
  </p>
  <div class="actions">
    <a href="<?= BASE_URL ?>admin/profil.php" class="btn btn-primary btn-sm">Edit Data Diri</a>
    <a href="<?= BASE_URL ?>admin/karya.php" class="btn btn-primary btn-sm">Kelola Karya</a>
  </div>
</div>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
