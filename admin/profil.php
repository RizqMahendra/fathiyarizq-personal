<?php
/**
 * admin/profil.php — Edit data diri (1 record).
 */
require_once __DIR__ . '/auth.php';

$judul  = 'Data Diri';
$active = 'profil';
$profil = get_profil($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nama'      => trim($_POST['nama'] ?? ''),
        'nim'       => trim($_POST['nim'] ?? ''),
        'headline'  => trim($_POST['headline'] ?? ''),
        'ringkasan' => trim($_POST['ringkasan'] ?? ''),
        'alamat'    => trim($_POST['alamat'] ?? ''),
        'telepon'   => trim($_POST['telepon'] ?? ''),
        'email'     => trim($_POST['email'] ?? ''),
        'github'    => trim($_POST['github'] ?? ''),
        'linkedin'  => trim($_POST['linkedin'] ?? ''),
    ];

    if ($data['nama'] === '') {
        set_flash('error', 'Nama wajib diisi.');
    } else {
        try {
            $data['foto'] = upload_gambar($_FILES['foto'] ?? [], $profil['foto'] ?? null);

            if (!empty($profil['id'])) {
                $sql = 'UPDATE profil SET nama=:nama, nim=:nim, headline=:headline, ringkasan=:ringkasan,
                        alamat=:alamat, telepon=:telepon, email=:email, github=:github, linkedin=:linkedin, foto=:foto
                        WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute($data + ['id' => $profil['id']]);
            } else {
                $cols = implode(', ', array_keys($data));
                $ph   = ':' . implode(', :', array_keys($data));
                $stmt = $pdo->prepare("INSERT INTO profil ($cols) VALUES ($ph)");
                $stmt->execute($data);
            }
            set_flash('success', 'Data diri berhasil disimpan.');
        } catch (RuntimeException $ex) {
            set_flash('error', $ex->getMessage());
        }
    }
    redirect('admin/profil.php');
}

require __DIR__ . '/../includes/admin_header.php';
?>
<form method="post" action="<?= BASE_URL ?>admin/profil.php" enctype="multipart/form-data" class="panel">
  <div class="grid-2">
    <div class="field">
      <label>Nama Lengkap</label>
      <input type="text" name="nama" value="<?= e($profil['nama'] ?? '') ?>" required>
    </div>
    <div class="field">
      <label>NIM <small style="color:var(--muted)">(tampil di footer)</small></label>
      <input type="text" name="nim" value="<?= e($profil['nim'] ?? '') ?>">
    </div>
  </div>
  <div class="field">
    <label>Headline / Jabatan</label>
    <input type="text" name="headline" value="<?= e($profil['headline'] ?? '') ?>">
  </div>
  <div class="field">
    <label>Ringkasan / Tentang Saya</label>
    <textarea name="ringkasan"><?= e($profil['ringkasan'] ?? '') ?></textarea>
  </div>
  <div class="grid-2">
    <div class="field"><label>Alamat</label><input type="text" name="alamat" value="<?= e($profil['alamat'] ?? '') ?>"></div>
    <div class="field"><label>Telepon</label><input type="text" name="telepon" value="<?= e($profil['telepon'] ?? '') ?>"></div>
  </div>
  <div class="grid-2">
    <div class="field"><label>Email</label><input type="email" name="email" value="<?= e($profil['email'] ?? '') ?>"></div>
    <div class="field"><label>GitHub (URL)</label><input type="text" name="github" value="<?= e($profil['github'] ?? '') ?>"></div>
  </div>
  <div class="grid-2">
    <div class="field"><label>LinkedIn (URL)</label><input type="text" name="linkedin" value="<?= e($profil['linkedin'] ?? '') ?>"></div>
    <div class="field">
      <label>Foto Profil <small style="color:var(--muted)">(JPG/PNG, maks 2MB)</small></label>
      <input type="file" name="foto" accept="image/*">
      <?php if (!empty($profil['foto'])): ?><small>Foto saat ini: <?= e($profil['foto']) ?></small><?php endif; ?>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Simpan</button>
</form>
<?php require __DIR__ . '/../includes/admin_footer.php'; ?>
