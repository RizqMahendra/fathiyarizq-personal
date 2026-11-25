<?php
/**
 * admin/keahlian.php — CRUD Keahlian.
 */
require_once __DIR__ . '/auth.php';

$judul  = 'Kelola Keahlian';
$active = 'keahlian';

// ---- Hapus ----
if (($_GET['aksi'] ?? '') === 'hapus' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('DELETE FROM keahlian WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    set_flash('success', 'Keahlian dihapus.');
    redirect('admin/keahlian.php');
}

// ---- Simpan (tambah / ubah) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id        = (int)($_POST['id'] ?? 0);
    $nama      = trim($_POST['nama'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $urutan    = (int)($_POST['urutan'] ?? 0);

    if ($nama === '') {
        set_flash('error', 'Nama keahlian wajib diisi.');
    } elseif ($id > 0) {
        $stmt = $pdo->prepare('UPDATE keahlian SET nama=?, kategori=?, deskripsi=?, urutan=? WHERE id=?');
        $stmt->execute([$nama, $kategori, $deskripsi, $urutan, $id]);
        set_flash('success', 'Keahlian diperbarui.');
    } else {
        $stmt = $pdo->prepare('INSERT INTO keahlian (nama, kategori, deskripsi, urutan) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nama, $kategori, $deskripsi, $urutan]);
        set_flash('success', 'Keahlian ditambahkan.');
    }
    redirect('admin/keahlian.php');
}

// ---- Mode edit ----
$edit = null;
if (($_GET['aksi'] ?? '') === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM keahlian WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $edit = $stmt->fetch();
}

$daftar = $pdo->query('SELECT * FROM keahlian ORDER BY urutan ASC, id ASC')->fetchAll();

require __DIR__ . '/../includes/admin_header.php';
?>
<form method="post" action="<?= BASE_URL ?>admin/keahlian.php" class="panel">
  <h3 style="margin-top:0"><?= $edit ? 'Ubah Keahlian' : 'Tambah Keahlian' ?></h3>
  <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">
  <div class="grid-2">
    <div class="field"><label>Nama Keahlian</label><input type="text" name="nama" value="<?= e($edit['nama'] ?? '') ?>" required></div>
    <div class="field"><label>Kategori</label><input type="text" name="kategori" value="<?= e($edit['kategori'] ?? '') ?>" placeholder="mis. Programming"></div>
  </div>
  <div class="field">
    <label>Deskripsi Keahlian <small style="color:var(--muted)">(gunakan kata/kalimat, mis. "Mahir — 4 tahun berpengalaman")</small></label>
    <textarea name="deskripsi" placeholder="Contoh: Mahir — membangun custom theme WordPress untuk proyek klien."><?= e($edit['deskripsi'] ?? '') ?></textarea>
  </div>
  <div class="field"><label>Urutan</label><input type="number" name="urutan" value="<?= e($edit['urutan'] ?? 0) ?>"></div>
  <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan Perubahan' : 'Tambah' ?></button>
  <?php if ($edit): ?><a href="<?= BASE_URL ?>admin/keahlian.php" class="btn btn-ghost" style="color:var(--text);border-color:var(--line)">Batal</a><?php endif; ?>
</form>

<div class="panel">
  <table>
    <thead><tr><th>#</th><th>Nama</th><th>Kategori</th><th>Deskripsi</th><th>Urutan</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if ($daftar): foreach ($daftar as $i => $row): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><strong><?= e($row['nama']) ?></strong></td>
        <td><?= e($row['kategori']) ?></td>
        <td style="max-width:360px"><?= e($row['deskripsi']) ?></td>
        <td><?= (int)$row['urutan'] ?></td>
        <td class="actions">
          <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>admin/keahlian.php?aksi=edit&id=<?= (int)$row['id'] ?>">Edit</a>
          <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>admin/keahlian.php?aksi=hapus&id=<?= (int)$row['id'] ?>" onclick="return confirm('Hapus keahlian ini?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr><td colspan="6" style="text-align:center;color:var(--muted)">Belum ada data.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
