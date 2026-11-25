<?php
/**
 * index.php — Halaman utama (one-page portfolio).
 * Berisi: Profil (hero), Keahlian, Karya, dan Kontak.
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---- Proses kirim form kontak ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'kontak') {
    $nama   = trim($_POST['nama'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $subjek = trim($_POST['subjek'] ?? '');
    $pesan  = trim($_POST['pesan'] ?? '');

    if ($nama === '' || $email === '' || $pesan === '') {
        set_flash('error', 'Nama, email, dan pesan wajib diisi.');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Format email tidak valid.');
    } else {
        $stmt = $pdo->prepare('INSERT INTO pesan (nama, email, subjek, pesan) VALUES (?, ?, ?, ?)');
        $stmt->execute([$nama, $email, $subjek, $pesan]);
        set_flash('success', 'Terima kasih, ' . $nama . '! Pesan Anda sudah terkirim.');
    }
    redirect('index.php#kontak');
}

// ---- Ambil data dari database ----
$profil   = get_profil($pdo);
$keahlian = $pdo->query('SELECT * FROM keahlian ORDER BY urutan ASC, id ASC')->fetchAll();
$karya    = $pdo->query('SELECT * FROM karya ORDER BY urutan ASC, id ASC')->fetchAll();
$flash    = get_flash();

require __DIR__ . '/includes/header.php';
?>

<!-- ============ PROFIL ============ -->
<section class="page-head" id="home">
  <div class="container">
    <div>
      <h1><?= e($profil['nama'] ?? 'Nama Anda') ?></h1>
      <p class="headline"><?= e($profil['headline'] ?? '') ?></p>
      <p class="ringkasan"><?= nl2br(e($profil['ringkasan'] ?? '')) ?></p>
      <div class="headline-meta">
        <?php if (!empty($profil['alamat'])): ?><span><?= e($profil['alamat']) ?></span><?php endif; ?>
        <?php if (!empty($profil['email'])): ?><span><?= e($profil['email']) ?></span><?php endif; ?>
        <?php if (!empty($profil['telepon'])): ?><span><?= e($profil['telepon']) ?></span><?php endif; ?>
      </div>
      <div class="cta">
        <a href="#kontak" class="btn btn-primary">Hubungi Saya</a>
        <?php if (!empty($profil['github'])): ?>
          <a href="<?= e($profil['github']) ?>" target="_blank" rel="noopener" class="btn btn-ghost">GitHub</a>
        <?php endif; ?>
      </div>
    </div>
    <div>
      <?php if (!empty($profil['foto']) && file_exists(__DIR__ . '/assets/uploads/' . $profil['foto'])): ?>
        <img class="avatar" src="<?= BASE_URL ?>assets/uploads/<?= e($profil['foto']) ?>" alt="<?= e($profil['nama']) ?>">
      <?php else: ?>
        <div class="avatar-fallback"><?= e(mb_substr($profil['nama'] ?? 'N', 0, 1)) ?></div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ============ KEAHLIAN ============ -->
<section class="section" id="keahlian">
  <div class="container">
    <div class="section-title">
      <h2>Keahlian</h2>
      <p>Kompetensi teknis dan profesional</p>
    </div>
    <?php if ($keahlian): ?>
      <div class="skills-grid">
        <?php foreach ($keahlian as $k): ?>
          <div class="skill-card">
            <div class="skill-head">
              <span><?= e($k['nama']) ?></span>
              <?php if (!empty($k['kategori'])): ?><small><?= e($k['kategori']) ?></small><?php endif; ?>
            </div>
            <?php if (!empty($k['deskripsi'])): ?>
              <p class="skill-desc"><?= e($k['deskripsi']) ?></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p style="text-align:center;color:var(--muted)">Belum ada data keahlian.</p>
    <?php endif; ?>
  </div>
</section>

<!-- ============ KARYA ============ -->
<section class="section" id="karya">
  <div class="container">
    <div class="section-title">
      <h2>Galeri Karya</h2>
      <p>Proyek dan portofolio pilihan</p>
    </div>
    <?php if ($karya): ?>
      <div class="cards">
        <?php foreach ($karya as $kr): ?>
          <article class="card">
            <div class="card-thumb">
              <?php if (!empty($kr['gambar']) && file_exists(__DIR__ . '/assets/uploads/' . $kr['gambar'])): ?>
                <img src="<?= BASE_URL ?>assets/uploads/<?= e($kr['gambar']) ?>" alt="<?= e($kr['judul']) ?>">
              <?php else: ?>
                <div class="noimg">Tanpa gambar</div>
              <?php endif; ?>
            </div>
            <div class="card-body">
              <?php if (!empty($kr['tahun'])): ?><span class="tahun"><?= e($kr['tahun']) ?></span><?php endif; ?>
              <h3><?= e($kr['judul']) ?></h3>
              <p><?= e($kr['deskripsi']) ?></p>
              <?php if (!empty($kr['link'])): ?>
                <a class="link" href="<?= e($kr['link']) ?>" target="_blank" rel="noopener">Lihat proyek &rarr;</a>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p style="text-align:center;color:var(--muted)">Belum ada karya.</p>
    <?php endif; ?>
  </div>
</section>

<!-- ============ KONTAK ============ -->
<section class="section" id="kontak">
  <div class="container">
    <div class="section-title">
      <h2>Kontak</h2>
      <p>Kirim pesan melalui formulir, atau gunakan informasi kontak di samping.</p>
    </div>
    <div class="kontak-wrap">
      <div>
        <ul class="kontak-info">
          <?php if (!empty($profil['email'])): ?>
            <li><strong>Email</strong><div class="val"><a href="mailto:<?= e($profil['email']) ?>"><?= e($profil['email']) ?></a></div></li>
          <?php endif; ?>
          <?php if (!empty($profil['telepon'])): ?>
            <li><strong>Telepon</strong><div class="val"><?= e($profil['telepon']) ?></div></li>
          <?php endif; ?>
          <?php if (!empty($profil['alamat'])): ?>
            <li><strong>Alamat</strong><div class="val"><?= e($profil['alamat']) ?></div></li>
          <?php endif; ?>
          <?php if (!empty($profil['linkedin'])): ?>
            <li><strong>LinkedIn</strong><div class="val"><a href="<?= e($profil['linkedin']) ?>" target="_blank" rel="noopener">Lihat profil</a></div></li>
          <?php endif; ?>
        </ul>
      </div>
      <div>
        <?php if ($flash): ?>
          <div class="flash <?= e($flash['tipe']) ?>"><?= e($flash['pesan']) ?></div>
        <?php endif; ?>
        <form method="post" action="<?= BASE_URL ?>index.php#kontak">
          <input type="hidden" name="aksi" value="kontak">
          <div class="grid-2">
            <div class="field">
              <label for="nama">Nama</label>
              <input type="text" id="nama" name="nama" required>
            </div>
            <div class="field">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" required>
            </div>
          </div>
          <div class="field">
            <label for="subjek">Subjek</label>
            <input type="text" id="subjek" name="subjek">
          </div>
          <div class="field">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Kirim Pesan</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
