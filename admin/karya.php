<?php
/**
 * admin/karya.php — CRUD Karya (galeri) + upload gambar.
 */
require_once __DIR__ . '/auth.php';

$judul  = 'Kelola Karya';
$active = 'karya';

// ---- Hapus ----
if (($_GET['aksi'] ?? '') === 'hapus' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT gambar FROM karya WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $gambar = $stmt->fetchColumn();
    if ($gambar && file_exists(__DIR__ . '/../assets/uploads/' . $gambar)) {
        @unlink(__DIR__ . '/../assets/uploads/' . $gambar);
    }
    $pdo->prepare('DELETE FROM karya WHERE id = ?')->execute([(int)$_GET['id']]);
    set_flash('success', 'Karya dihapus.');
    redirect('admin/karya.php');
}

// ---- Simpan (tambah / ubah) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id       = (int)($_POST['id'] ?? 0);
    $judulK   = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $link     = trim($_POST['link'] ?? '');
    $tahun    = trim($_POST['tahun'] ?? '');
    $urutan   = (int)($_POST['urutan'] ?? 0);
    $lama     = $_POST['gambar_lama'] ?? null;

    if ($judulK === '') {
        set_flash('error', 'Judul karya wajib diisi.');
    } else {
        try {
            $gambar = upload_gambar($_FILES['gambar'] ?? [], $lama);
            if ($id > 0) {
                $stmt = $pdo->prepare('UPDATE karya SET judul=?, deskripsi=?, gambar=?, link=?, tahun=?, urutan=? WHERE id=?');
                $stmt->execute([$judulK, $deskripsi, $gambar, $link, $tahun, $urutan, $id]);
                set_flash('success', 'Karya diperbarui.');
            } else {
                $stmt = $pdo->prepare('INSERT INTO karya (judul, deskripsi, gambar, link, tahun, urutan) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$judulK, $deskripsi, $gambar, $link, $tahun, $urutan]);
                set_flash('success', 'Karya ditambahkan.');
            }
        } catch (RuntimeException $ex) {
            set_flash('error', $ex->getMessage());
        }
    }
    redirect('admin/karya.php');
}

// ---- Mode edit ----
$edit = null;
if (($_GET['aksi'] ?? '') === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM karya WHERE id = ?');
    $stmt->execute([(int)$_GET['id']]);
    $edit = $stmt->fetch();
}

$daftar = $pdo->query('SELECT * FROM karya ORDER BY urutan ASC, id ASC')->fetchAll();

require __DIR__ . '/../includes/admin_header.php';
?>
<form method="post" action="<?= BASE_URL ?>admin/karya.php" enctype="multipart/form-data" class="panel">
  <h3 style="margin-top:0"><?= $edit ? 'Ubah Karya' : 'Tambah Karya' ?></h3>
  <input type="hidden" name="id" value="<?= e($edit['id'] ?? 0) ?>">
  <input type="hidden" name="gambar_lama" value="<?= e($edit['gambar'] ?? '') ?>">
  <div class="grid-2">
    <div class="field"><label>Judul</label><input type="text" name="judul" value="<?= e($edit['judul'] ?? '') ?>" required></div>
    <div class="field"><label>Tahun</label><input type="text" name="tahun" value="<?= e($edit['tahun'] ?? '') ?>" placeholder="mis. 2024"></div>
  </div>
  <div class="field"><label>Deskripsi</label><textarea name="deskripsi"><?= e($edit['deskripsi'] ?? '') ?></textarea></div>
  <div class="grid-2">
    <div class="field"><label>Link Proyek (URL)</label><input type="text" name="link" value="<?= e($edit['link'] ?? '') ?>"></div>
    <div class="field"><label>Gambar <small style="color:var(--muted)">(maks 2MB)</small></label><input type="file" name="gambar" accept="image/*"></div>
  </div>
  <div class="field"><label>Urutan</label><input type="number" name="urutan" value="<?= e($edit['urutan'] ?? 0) ?>"></div>
  <button type="submit" class="btn btn-primary"><?= $edit ? 'Simpan Perubahan' : 'Tambah' ?></button>
  <?php if ($edit): ?><a href="<?= BASE_URL ?>admin/karya.php" class="btn btn-ghost" style="color:var(--text);border-color:var(--line)">Batal</a><?php endif; ?>
</form>

<div class="panel">
  <table>
    <thead><tr><th>Gambar</th><th>Judul</th><th>Tahun</th><th>Urutan</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php if ($daftar): foreach ($daftar as $row): ?>
      <tr>
        <td>
          <?php if (!empty($row['gambar']) && file_exists(__DIR__ . '/../assets/uploads/' . $row['gambar'])): ?>
            <img class="thumb-sm" src="<?= BASE_URL ?>assets/uploads/<?= e($row['gambar']) ?>" alt="">
          <?php else: ?><span style="color:var(--muted)">—</span><?php endif; ?>
        </td>
        <td><strong><?= e($row['judul']) ?></strong><br><small style="color:var(--muted)"><?= e(mb_strimwidth($row['deskripsi'] ?? '', 0, 60, '…')) ?></small></td>
        <td><?= e($row['tahun']) ?></td>
        <td><?= (int)$row['urutan'] ?></td>
        <td class="actions">
          <a class="btn btn-primary btn-sm" href="<?= BASE_URL ?>admin/karya.php?aksi=edit&id=<?= (int)$row['id'] ?>">Edit</a>
          <a class="btn btn-danger btn-sm" href="<?= BASE_URL ?>admin/karya.php?aksi=hapus&id=<?= (int)$row['id'] ?>" onclick="return confirm('Hapus karya ini?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; else: ?>
      <tr><td colspan="5" style="text-align:center;color:var(--muted)">Belum ada karya.</td></tr>
    <?php endif; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
