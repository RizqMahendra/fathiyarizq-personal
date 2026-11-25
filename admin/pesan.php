<?php
/**
 * admin/pesan.php — Daftar pesan dari form kontak.
 */
require_once __DIR__ . '/auth.php';

$judul  = 'Pesan Masuk';
$active = 'pesan';

// ---- Hapus ----
if (($_GET['aksi'] ?? '') === 'hapus' && isset($_GET['id'])) {
    $pdo->prepare('DELETE FROM pesan WHERE id = ?')->execute([(int)$_GET['id']]);
    set_flash('success', 'Pesan dihapus.');
    redirect('admin/pesan.php');
}

// ---- Tandai dibaca / belum ----
if (($_GET['aksi'] ?? '') === 'baca' && isset($_GET['id'])) {
    $pdo->prepare('UPDATE pesan SET dibaca = 1 - dibaca WHERE id = ?')->execute([(int)$_GET['id']]);
    redirect('admin/pesan.php');
}

$daftar = $pdo->query('SELECT * FROM pesan ORDER BY dibuat_pada DESC, id DESC')->fetchAll();

require __DIR__ . '/../includes/admin_header.php';
?>
<div class="panel">
  <table>
    <thead><tr><th>Status</th><th>Pengirim</th><th>Subjek</th><th>Pesan</th><th>Waktu</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if ($daftar): foreach ($daftar as $row): ?>
      <tr>
        <td>
          <?php if ($row['dibaca']): ?>
            <span style="color:#16a34a;font-weight:600">Dibaca</span>
          <?php else: ?>
            <span style="color:#dc2626;font-weight:600">Baru</span>
          <?php endif; ?>
        </td>
        <td><strong><?= e($row['nama']) ?></strong><br><small><a href="mailto:<?= e($row['email']) ?>"><?= e($row['email']) ?></a></small></td>
        <td><?= e($row['subjek'] ?: '—') ?></td>
        <td style="max-width:340px"><?= nl2br(e($row['pesan'])) ?></td>
        <td><small><?= e(date('d M Y, H:i', strtotime($row['dibuat_pada']))) ?></small></td>
        <td class="actions">
          <a class="btn btn-ghost btn-sm" style="color:var(--text);border-color:var(--line)"
             href="<?= BASE_URL ?>admin/pesan.php?aksi=baca&id=<?= (int)$row['id'] ?>">
             <?= $row['dibaca'] ? 'Tandai Baru' : 'Tandai Dibaca' ?>
          </a>
          <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>admin/pesan.php?aksi=hapus&id=<?= (int)$row['id'] ?>" onclick="return confirm('Hapus pesan ini?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr><td colspan="6" style="text-align:center;color:var(--muted)">Belum ada pesan masuk.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
