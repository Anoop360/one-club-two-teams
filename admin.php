<?php
require __DIR__ . '/config.php';
if (is_admin()) redirect('dashboard.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    verify_csrf();
    $stmt=$pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username=? LIMIT 1");
    $stmt->execute([trim((string)($_POST['username']??''))]); $admin=$stmt->fetch();
    if ($admin && password_verify((string)($_POST['password']??''),$admin['password_hash'])) {
        session_regenerate_id(true); $_SESSION['admin_id']=$admin['id']; $_SESSION['admin_username']=$admin['username']; redirect('dashboard.php');
    }
    $error='Invalid username or password.';
}
$pageTitle='Admin Login'; require __DIR__ . '/header.php'; ?>
<section class="login-wrap"><div class="login-card"><span class="eyebrow">RESTRICTED ACCESS</span><h1>Admin <span>login</span></h1><p>Sign in to manage squads, officials, fixtures, news, and photos.</p><?php if($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Username<input name="username" required autocomplete="username"></label><label>Password<input type="password" name="password" required autocomplete="current-password"></label><button class="btn" type="submit">Sign in →</button></form><small class="muted">First time? Run setup.php once, then remove or rename it.</small></div></section>
<?php require __DIR__ . '/footer.php'; ?>
