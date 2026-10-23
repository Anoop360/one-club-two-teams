<?php
require __DIR__ . '/config.php'; $pageTitle='Matches & Results';
$matches=$pdo->query("SELECT * FROM matches ORDER BY match_date DESC")->fetchAll();
require __DIR__ . '/header.php'; ?>
<section class="page-hero"><span class="eyebrow">FIXTURES / SCOREBOARD</span><h1>Matches & <span>results</span></h1><p>Upcoming fixtures, recent results, and the story of our season.</p></section>
<?php if(!$matches): ?><div class="empty">Fixtures and results will be published here.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Date</th><th>Sport / Competition</th><th>Match</th><th>Score</th><th>Status / Result</th></tr></thead><tbody><?php foreach($matches as $m): ?><tr><td><?= e(date('d M Y, H:i',strtotime($m['match_date']))) ?></td><td><b><?= e(ucfirst($m['sport'])) ?></b><small><?= e($m['competition']??'') ?></small></td><td>Our team vs <?= e($m['opponent']) ?><small><?= e($m['venue']??'') ?></small></td><td><?= e($m['our_score'] ?: '—') ?> : <?= e($m['opponent_score'] ?: '—') ?></td><td><span class="pill"><?= e(ucfirst($m['status'])) ?></span><small><?= e($m['result']??'') ?></small></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
