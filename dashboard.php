<?php
require __DIR__ . '/config.php'; require_admin();
$stats=[
 'Cricket players'=>(int)$pdo->query("SELECT COUNT(*) FROM players WHERE sport='cricket'")->fetchColumn(),
 'Football players'=>(int)$pdo->query("SELECT COUNT(*) FROM players WHERE sport='football'")->fetchColumn(),
 'Officials'=>(int)$pdo->query("SELECT COUNT(*) FROM officials")->fetchColumn(),
 'Matches'=>(int)$pdo->query("SELECT COUNT(*) FROM matches")->fetchColumn(),
 'Announcements'=>(int)$pdo->query("SELECT COUNT(*) FROM announcements")->fetchColumn(),
 'Gallery photos'=>(int)$pdo->query("SELECT COUNT(*) FROM gallery")->fetchColumn()
];
$pageTitle='Admin Dashboard'; require __DIR__ . '/header.php'; ?>
<section class="page-hero"><span class="eyebrow">CONTROL ROOM</span><h1>Admin <span>dashboard</span></h1><p>Welcome, <?= e($_SESSION['admin_username']??'Admin') ?>. Manage the club from one place.</p><div class="actions"><a class="btn" href="admin_manage.php?section=players">Manage content</a><a class="btn btn-outline" href="logout.php">Log out</a></div></section>
<div class="admin-stats"><?php foreach($stats as $label=>$value): ?><div class="stat"><strong><?= $value ?></strong><span><?= e($label) ?></span></div><?php endforeach; ?></div>
<section class="section"><div class="section-heading"><h2>Management <span>tools</span></h2></div><div class="admin-grid">
<a class="admin-tile" href="admin_manage.php?section=players"><b>01</b><h3>Players & stats</h3><p>Add, edit, deactivate, or remove players in either squad.</p><span>Open manager ↗</span></a>
<a class="admin-tile" href="admin_manage.php?section=officials"><b>02</b><h3>Managers & officials</h3><p>Maintain coaches, captains, managers, and coordinators.</p><span>Open manager ↗</span></a>
<a class="admin-tile" href="admin_manage.php?section=matches"><b>03</b><h3>Matches & results</h3><p>Publish fixtures, scores, venues, and match results.</p><span>Open manager ↗</span></a>
<a class="admin-tile" href="admin_manage.php?section=announcements"><b>04</b><h3>Announcements</h3><p>Publish club notices and team-specific news.</p><span>Open manager ↗</span></a>
<a class="admin-tile" href="admin_manage.php?section=gallery"><b>05</b><h3>Photo gallery</h3><p>Upload and manage team memories.</p><span>Open manager ↗</span></a>
<a class="admin-tile" href="index.php"><b>06</b><h3>View public site</h3><p>Check how the website appears to visitors.</p><span>Visit website ↗</span></a>
</div></section>
<?php require __DIR__ . '/footer.php'; ?>
