<?php
/**
 * includes/footer.php — penutup halaman publik.
 * Menampilkan nama & NIM sesuai ketentuan UAS.
 */
$namaFooter = $profil['nama'] ?? 'Nama Anda';
$nimFooter  = $profil['nim'] ?? 'NIM';
?>
</main>
<footer>
  <div class="container">
    <p>
      &copy; <?= date('Y') ?> <strong><?= e($namaFooter) ?></strong>
      &middot; NIM: <span class="nim"><?= e($nimFooter) ?></span>
    </p>
    <p>
      <?php if (!empty($profil['github'])): ?><a href="<?= e($profil['github']) ?>" target="_blank" rel="noopener">GitHub</a> &middot; <?php endif; ?>
      <?php if (!empty($profil['linkedin'])): ?><a href="<?= e($profil['linkedin']) ?>" target="_blank" rel="noopener">LinkedIn</a><?php endif; ?>
    </p>
    <p class="note">Dibuat dengan HTML, CSS, PHP &amp; MySQL.</p>
  </div>
</footer>
<script src="<?= BASE_URL ?>assets/js/main.js"></script>
</body>
</html>
