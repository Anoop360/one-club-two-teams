
<?php
require __DIR__ . '/config.php';

$players = $pdo->query("SELECT sport, COUNT(*) total FROM players WHERE status='active' GROUP BY sport")->fetchAll();

$counts = ['cricket' => 0, 'football' => 0];

foreach ($players as $p) {
    if (isset($counts[$p['sport']])) {
        $counts[$p['sport']] = (int) $p['total'];
    }
}

$matches = $pdo->query("SELECT * FROM matches WHERE status='upcoming' AND match_date >= NOW() ORDER BY match_date ASC LIMIT 4")->fetchAll();

$news = $pdo->query("SELECT * FROM announcements WHERE published=1 ORDER BY created_at DESC LIMIT 3")->fetchAll();

$officials = $pdo->query("SELECT * FROM officials ORDER BY FIELD(sport,'club','cricket','football'), id LIMIT 4")->fetchAll();

require __DIR__ . '/header.php';
?>

<section class="hero">
  <div class="hero-copy">
    <span class="eyebrow">MALABAR IMPERIAL EAGLES</span>

    <h1>One Club.<br><span>One Legacy.</span></h1>

    <p>
      Two teams. One family. Follow our cricket and football squads,
      meet the players, and stay updated with every match.
    </p>

    <div class="actions">
      <a class="btn" href="team.php?sport=cricket">Explore Cricket</a>
      <a class="btn btn-outline" href="team.php?sport=football">Explore Football</a>
    </div>
  </div>

  <div class="hero-mark">
    <div class="mark-ball">MIE</div>
    <span>MALABAR IMPERIAL EAGLES</span>
    <small>RISE ABOVE · RULE THE GAME</small>
  </div>
</section>

<section class="stats-grid">
  <div class="stat">
    <strong><?= $counts['cricket'] ?></strong>
    <span>Cricket players</span>
  </div>

  <div class="stat">
    <strong><?= $counts['football'] ?></strong>
    <span>Football players</span>
  </div>

  <div class="stat">
    <strong><?= count($matches) ?></strong>
    <span>Upcoming fixtures</span>
  </div>

  <div class="stat">
    <strong>02</strong>
    <span>Teams. One legacy.</span>
  </div>
</section>

<section class="section">
  <div class="section-heading">
    <div>
      <span class="eyebrow">OUR SQUADS</span>
      <h2>Two games. <span>One spirit.</span></h2>
    </div>
  </div>

  <div class="sport-cards">
    <a class="sport-card cricket-card" href="team.php?sport=cricket">
      <span class="card-tag">01 / CRICKET</span>
      <div>
        <h3>Cricket</h3>
        <p>Every run matters. Every player counts.</p>
        <span class="text-link">Meet the squad ↗</span>
      </div>
    </a>

    <a class="sport-card football-card" href="team.php?sport=football">
      <span class="card-tag">02 / FOOTBALL</span>
      <div>
        <h3>Football</h3>
        <p>Play together. Fight for every goal.</p>
        <span class="text-link">Meet the squad ↗</span>
      </div>
    </a>
  </div>
</section>

<section class="section two-col">

  <div>
    <div class="section-heading">
      <div>
        <span class="eyebrow">NEXT UP</span>
        <h2>Upcoming <span>fixtures</span></h2>
      </div>

      <a class="text-link" href="matches.php">All matches ↗</a>
    </div>

    <?php if (!$matches): ?>
      <div class="empty">
        No upcoming matches have been announced yet.
      </div>
    <?php endif; ?>

    <?php foreach ($matches as $m): ?>
      <article class="match-row">
        <div class="match-date">
          <?= e(date('d M', strtotime($m['match_date']))) ?>
          <small>
            <?= e(date('H:i', strtotime($m['match_date']))) ?>
          </small>
        </div>

        <div class="match-info">
          <b>
            <?= e(ucfirst($m['sport'])) ?> vs <?= e($m['opponent']) ?>
          </b>
          <small>
            <?= e($m['venue'] ?: 'Venue to be confirmed') ?>
          </small>
        </div>

        <span class="pill">
          <?= e(ucfirst($m['status'])) ?>
        </span>
      </article>
    <?php endforeach; ?>
  </div>

  <div>
    <div class="section-heading">
      <div>
        <span class="eyebrow">CLUB NOTICEBOARD</span>
        <h2>Latest <span>news</span></h2>
      </div>

      <a class="text-link" href="announcements.php">All news ↗</a>
    </div>

    <?php if (!$news): ?>
      <div class="empty">
        Club announcements will appear here.
      </div>
    <?php endif; ?>

    <?php foreach ($news as $n): ?>
      <article class="news-row">
        <small>
          <?= e(strtoupper($n['sport'])) ?>
          ·
          <?= e(date('d M Y', strtotime($n['created_at']))) ?>
        </small>

        <h3><?= e($n['title']) ?></h3>

        <p>
          <?= e(mb_strimwidth($n['body'], 0, 150, '…')) ?>
        </p>
      </article>
    <?php endforeach; ?>
  </div>

</section>

<?php require __DIR__ . '/footer.php'; ?>
