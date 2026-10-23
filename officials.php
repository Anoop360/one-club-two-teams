<?php
require __DIR__ . '/config.php';
$pageTitle='Managers & Officials';
$officials=$pdo->query("SELECT * FROM officials ORDER BY FIELD(sport,'club','cricket','football'), position, full_name")->fetchAll();
require __DIR__ . '/header.php'; ?>
<section class="page-hero"><span class="eyebrow">THE PEOPLE BEHIND THE TEAM</span><h1>Managers & <span>officials</span></h1><p>Meet the people who lead, organise, and support our squads.</p></section>
<?php if(!$officials): ?><div class="empty">Officials have not been added yet.</div><?php else: ?><div class="official-grid"><?php foreach($officials as $o): ?><article class="official-card"><?php if($o['photo']): ?><img src="<?= e($o['photo']) ?>" alt="<?= e($o['full_name']) ?>"><?php else: ?><div class="official-placeholder"><?= e(strtoupper(substr($o['full_name'],0,1))) ?></div><?php endif; ?><small><?= e(strtoupper($o['sport'])) ?></small><h3><?= e($o['full_name']) ?></h3><p><?= e($o['position']) ?></p></article><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
