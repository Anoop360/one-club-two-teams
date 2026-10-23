<?php
require __DIR__ . '/config.php'; $pageTitle='Club News';
$news=$pdo->query("SELECT * FROM announcements WHERE published=1 ORDER BY created_at DESC")->fetchAll();
require __DIR__ . '/header.php'; ?>
<section class="page-hero"><span class="eyebrow">CLUB NOTICEBOARD</span><h1>News & <span>announcements</span></h1><p>Updates, team news, tournament notices, and important dates.</p></section>
<?php if(!$news): ?><div class="empty">No announcements have been published yet.</div><?php else: ?><div class="news-grid"><?php foreach($news as $n): ?><article class="news-card"><small><?= e(strtoupper($n['sport'])) ?> · <?= e(date('d M Y',strtotime($n['created_at']))) ?></small><h2><?= e($n['title']) ?></h2><p><?= nl2br(e($n['body'])) ?></p></article><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
