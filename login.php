<?php
/**
 * login.php — Halaman login admin.
 */
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sudah login? langsung ke dashboard
if (!empty($_SESSION['admin_id'])) {
    redirect('admin/index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']   = $admin['id'];
            $_SESSION['admin_nama'] = $admin['nama'] ?: $admin['username'];
            redirect('admin/index.php');
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Admin</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body class="admin-body">
<div class="login-wrap">
  <div class="login-card">
    <h1>Login Admin</h1>
    <p class="sub">Masuk untuk mengelola konten portfolio.</p>

    <?php if ($error): ?>
      <div class="flash error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>login.php">
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" autofocus required>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary" style="width:100%">Masuk</button>
    </form>
    <p class="mt-2" style="text-align:center;font-size:.85rem">
      <a href="<?= BASE_URL ?>index.php">&larr; Kembali ke website</a>
    </p>
  </div>
</div>
</body>
</html>
