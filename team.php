<?php
require __DIR__ . '/config.php';
$sport = ($_GET['sport'] ?? 'cricket') === 'football' ? 'football' : 'cricket';
$pageTitle = ucfirst($sport) . ' Squad';
$stmt=$pdo->prepare("SELECT * FROM players WHERE sport=? AND status='active' ORDER BY jersey_number IS NULL, jersey_number, full_name");
$stmt->execute([$sport]); $players=$stmt->fetchAll();
$officialStmt=$pdo->prepare("SELECT * FROM officials WHERE sport IN ('club',?) ORDER BY FIELD(sport,'club',?) LIMIT 4"); $officialStmt->execute([$sport,$sport]); $officials=$officialStmt->fetchAll();
require __DIR__ . '/header.php';
?>
<section class="page-hero"><span class="eyebrow">THE ROSTER / <?= e(strtoupper($sport)) ?></span><h1><?= $sport==='cricket'?'Cricket':'Football' ?> <span>Squad</span></h1><p>Meet the players representing our club. Squad information is maintained by the club admin.</p><div class="tabs"><a class="<?= $sport==='cricket'?'active':'' ?>" href="team.php?sport=cricket">Cricket</a><a class="<?= $sport==='football'?'active':'' ?>" href="team.php?sport=football">Football</a></div></section>
<div class="section-heading"><h2>First team <span>(<?= count($players) ?>)</span></h2></div>
<?php if (!$players): ?><div class="empty">The squad list is not published yet. Please check back soon.</div><?php else: ?><div class="player-grid"><?php foreach($players as $p): ?><article class="player-card"><div class="player-photo"><?php if($p['photo']): ?><img src="<?= e($p['photo']) ?>" alt="<?= e($p['full_name']) ?>"><?php else: ?><span><?= e(strtoupper(substr($p['full_name'],0,1))) ?></span><?php endif; ?><b><?= $p['jersey_number']!==null?'#'.(int)$p['jersey_number']:'PLAYER' ?></b></div><div class="player-details"><small><?= e(strtoupper($p['role'] ?: ($sport==='cricket'?'SQUAD PLAYER':'SQUAD PLAYER'))) ?></small><h3><?= e($p['full_name']) ?></h3><?php if($p['bio']): ?><p><?= e($p['bio']) ?></p><?php endif; ?><div class="player-stats"><?php if($sport==='cricket'): ?><span><b><?= (int)$p['matches_played'] ?></b> Matches</span><span><b><?= (int)$p['runs_scored'] ?></b> Runs</span><span><b><?= (int)$p['wickets_taken'] ?></b> Wickets</span><?php else: ?><span><b><?= (int)$p['matches_played'] ?></b> Matches</span><span><b><?= (int)$p['goals_scored'] ?></b> Goals</span><span><b><?= (int)$p['assists'] ?></b> Assists</span><?php endif; ?></div></div></article><?php endforeach; ?></div><?php endif; ?>
<?php require __DIR__ . '/footer.php'; ?>
