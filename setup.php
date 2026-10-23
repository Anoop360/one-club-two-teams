<?php
require __DIR__ . '/config.php';
$count=(int)$pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
if($count>0){http_response_code(403);exit('An admin already exists. Delete or rename setup.php for security.');}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $username=trim((string)($_POST['username']??'')); $password=(string)($_POST['password']??''); $confirm=(string)($_POST['confirm']??'');
    if(!preg_match('/^[A-Za-z0-9_.-]{4,80}$/',$username)) $error='Username must be 4–80 characters (letters, numbers, dot, dash, underscore).';
    elseif(strlen($password)<12) $error='Use a password with at least 12 characters.';
    elseif($password!==$confirm) $error='Passwords do not match.';
    else { $stmt=$pdo->prepare("INSERT INTO admins(username,password_hash) VALUES(?,?)"); $stmt->execute([$username,password_hash($password,PASSWORD_DEFAULT)]); flash('Admin account created. Sign in now.'); redirect('admin.php'); }
}
$pageTitle='Initial Setup'; require __DIR__ . '/header.php'; ?>
<section class="login-wrap"><div class="login-card"><span class="eyebrow">ONE-TIME SETUP</span><h1>Create <span>admin</span></h1><p>Create the first administrator account. Use a strong, unique password.</p><?php if($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Username<input name="username" required minlength="4" maxlength="80"></label><label>Password<input type="password" name="password" required minlength="12"></label><label>Confirm password<input type="password" name="confirm" required minlength="12"></label><button class="btn" type="submit">Create admin →</button></form><div class="notice error">After setup, delete or rename setup.php.</div></div></section>
<?php require __DIR__ . '/footer.php'; ?>
