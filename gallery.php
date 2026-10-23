<?php
require __DIR__ . '/config.php'; $pageTitle='Photo Gallery';
$photos=$pdo->query("SELECT * FROM gallery ORDER BY created_at DESC")->fetchAll();
require __DIR__ . '/header.php'; ?>
<section class="page-hero"><span class="eyebrow">MOMENTS THAT MATTER</span><h1>Club <span>gallery</span></h1><p>Match days, celebrations, team photos, and memories from the season.</p></section>
<?php if(!$photos): ?><div class="empty">Photos will appear here once the club admin adds them.</div><?php else: ?><div class="gallery-grid"><?php foreach($photos as $p): ?><figure class="gallery-card"><img src="<?= e($p['image_path']) ?>" alt="<?= e($p['title']) ?>"><figcaption><small><?= e(strtoupper($p['sport'])) ?></small><b><?= e($p['title']) ?></b></figcaption></figure><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
