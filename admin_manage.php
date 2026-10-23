<?php
require __DIR__ . '/config.php'; require_admin();
$sections=['players'=>'Players & stats','officials'=>'Managers & officials','matches'=>'Matches & results','announcements'=>'Announcements','gallery'=>'Photo gallery'];
$section=(string)($_GET['section']??'players'); if(!isset($sections[$section])) $section='players';
$error='';
function postval(string $key, string $default=''): string { return trim((string)($_POST[$key]??$default)); }
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $action=postval('action'); $id=(int)($_POST['id']??0);
    try {
      if($action==='delete'){
        $table=['players'=>'players','officials'=>'officials','matches'=>'matches','announcements'=>'announcements','gallery'=>'gallery'][$section];
        $stmt=$pdo->prepare("DELETE FROM `$table` WHERE id=?"); $stmt->execute([$id]); flash('Record deleted.'); redirect('admin_manage.php?section='.$section);
      }
      if($section==='players'){
        $sport=postval('sport'); if(!in_array($sport,['cricket','football'],true)) throw new RuntimeException('Choose a valid team.');
        $name=postval('full_name'); if($name==='') throw new RuntimeException('Player name is required.');
        $photo=upload_image('photo',postval('old_photo')?:null);
        $data=[$sport,$name,postval('jersey_number')!==''?(int)postval('jersey_number'):null,postval('role'),postval('phone'),postval('bio'),$photo,postval('status')==='inactive'?'inactive':'active',(int)postval('matches_played'),(int)postval('runs_scored'),(int)postval('wickets_taken'),(int)postval('goals_scored'),(int)postval('assists')];
        if($id){$data[]=$id;$sql="UPDATE players SET sport=?,full_name=?,jersey_number=?,role=?,phone=?,bio=?,photo=?,status=?,matches_played=?,runs_scored=?,wickets_taken=?,goals_scored=?,assists=? WHERE id=?";}
        else {$sql="INSERT INTO players(sport,full_name,jersey_number,role,phone,bio,photo,status,matches_played,runs_scored,wickets_taken,goals_scored,assists) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";}
        $pdo->prepare($sql)->execute($data); flash($id?'Player updated.':'Player added.');
      } elseif($section==='officials'){
        $name=postval('full_name'); $position=postval('position'); if(!$name||!$position) throw new RuntimeException('Name and position are required.');
        $photo=upload_image('photo',postval('old_photo')?:null); $data=[postval('sport','club'),$name,$position,postval('phone'),$photo];
        if($id){$data[]=$id;$sql="UPDATE officials SET sport=?,full_name=?,position=?,phone=?,photo=? WHERE id=?";} else $sql="INSERT INTO officials(sport,full_name,position,phone,photo) VALUES(?,?,?,?,?)";
        $pdo->prepare($sql)->execute($data); flash($id?'Official updated.':'Official added.');
      } elseif($section==='matches'){
        $sport=postval('sport'); if(!in_array($sport,['cricket','football'],true)) throw new RuntimeException('Choose a valid sport.');
        $opponent=postval('opponent'); $date=postval('match_date'); if(!$opponent||!$date) throw new RuntimeException('Opponent and date are required.');
        $data=[$sport,$opponent,postval('venue'),date('Y-m-d H:i:s',strtotime($date)),postval('competition'),postval('our_score'),postval('opponent_score'),postval('result'),postval('status','upcoming')==='completed'?'completed':'upcoming',postval('notes')];
        if($id){$data[]=$id;$sql="UPDATE matches SET sport=?,opponent=?,venue=?,match_date=?,competition=?,our_score=?,opponent_score=?,result=?,status=?,notes=? WHERE id=?";} else $sql="INSERT INTO matches(sport,opponent,venue,match_date,competition,our_score,opponent_score,result,status,notes) VALUES(?,?,?,?,?,?,?,?,?,?)";
        $pdo->prepare($sql)->execute($data); flash($id?'Match updated.':'Match added.');
      } elseif($section==='announcements'){
        $title=postval('title'); $body=postval('body'); if(!$title||!$body) throw new RuntimeException('Title and message are required.');
        $data=[$title,$body,postval('sport','club'),isset($_POST['published'])?1:0];
        if($id){$data[]=$id;$sql="UPDATE announcements SET title=?,body=?,sport=?,published=? WHERE id=?";} else $sql="INSERT INTO announcements(title,body,sport,published) VALUES(?,?,?,?)";
        $pdo->prepare($sql)->execute($data); flash($id?'Announcement updated.':'Announcement saved.');
      } elseif($section==='gallery'){
        $title=postval('title'); if(!$title) throw new RuntimeException('Photo title is required.');
        $photo=upload_image('photo',postval('old_photo')?:null); if(!$photo) throw new RuntimeException('Choose an image.');
        $data=[$title,$photo,postval('sport','club')];
        if($id){$data[]=$id;$sql="UPDATE gallery SET title=?,image_path=?,sport=? WHERE id=?";} else $sql="INSERT INTO gallery(title,image_path,sport) VALUES(?,?,?)";
        $pdo->prepare($sql)->execute($data); flash($id?'Photo updated.':'Photo added.');
      }
      redirect('admin_manage.php?section='.$section);
    } catch(Throwable $ex) { $error=$ex instanceof RuntimeException?$ex->getMessage():'Could not save record. Check the submitted values and database configuration.'; }
}
$edit=null;
if(isset($_GET['edit'])){$stmt=$pdo->prepare("SELECT * FROM `".$section."` WHERE id=?");$stmt->execute([(int)$_GET['edit']]);$edit=$stmt->fetch()?:null;}
$rows=$pdo->query("SELECT * FROM `".$section."` ORDER BY id DESC LIMIT 200")->fetchAll();
$pageTitle=$sections[$section]; require __DIR__ . '/header.php';
?>
<section class="page-hero compact"><span class="eyebrow">ADMIN / CONTENT MANAGEMENT</span><h1><?= e($sections[$section]) ?></h1><p>Create and update public club information. Changes are published to the website immediately.</p><a class="text-link" href="dashboard.php">← Back to dashboard</a></section>
<div class="admin-tabs"><?php foreach($sections as $key=>$label): ?><a class="<?= $key===$section?'active':'' ?>" href="admin_manage.php?section=<?= e($key) ?>"><?= e($label) ?></a><?php endforeach; ?></div>
<?php if($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<section class="form-panel"><div class="section-heading"><h2><?= $edit?'Edit':'Add' ?> <span><?= e(rtrim($sections[$section],'s')) ?></span></h2><?php if($edit): ?><a class="text-link" href="admin_manage.php?section=<?= e($section) ?>">Cancel edit</a><?php endif; ?></div>
<form method="post" enctype="multipart/form-data" class="manage-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><input type="hidden" name="old_photo" value="<?= e($edit['photo']??$edit['image_path']??'') ?>">
<?php if($section==='players'): ?>
<label>Team<select name="sport"><option value="cricket" <?= ($edit['sport']??'cricket')==='cricket'?'selected':'' ?>>Cricket</option><option value="football" <?= ($edit['sport']??'')==='football'?'selected':'' ?>>Football</option></select></label>
<label>Full name<input name="full_name" required value="<?= e($edit['full_name']??'') ?>"></label><label>Jersey number<input type="number" min="0" max="999" name="jersey_number" value="<?= e((string)($edit['jersey_number']??'')) ?>"></label><label>Role / position<input name="role" placeholder="Captain, Bowler, Goalkeeper..." value="<?= e($edit['role']??'') ?>"></label><label>Phone (admin only)<input name="phone" value="<?= e($edit['phone']??'') ?>"></label><label>Status<select name="status"><option value="active" <?= ($edit['status']??'active')==='active'?'selected':'' ?>>Active</option><option value="inactive" <?= ($edit['status']??'')==='inactive'?'selected':'' ?>>Inactive</option></select></label><label>Matches played<input type="number" min="0" name="matches_played" value="<?= (int)($edit['matches_played']??0) ?>"></label><label>Runs scored (cricket)<input type="number" min="0" name="runs_scored" value="<?= (int)($edit['runs_scored']??0) ?>"></label><label>Wickets taken (cricket)<input type="number" min="0" name="wickets_taken" value="<?= (int)($edit['wickets_taken']??0) ?>"></label><label>Goals (football)<input type="number" min="0" name="goals_scored" value="<?= (int)($edit['goals_scored']??0) ?>"></label><label>Assists (football)<input type="number" min="0" name="assists" value="<?= (int)($edit['assists']??0) ?>"></label><label class="full">Short bio<textarea name="bio" rows="3"><?= e($edit['bio']??'') ?></textarea></label><label class="full">Player photo (JPG, PNG, WebP; max 5 MB)<input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp"></label>
<?php elseif($section==='officials'): ?>
<label>Team<select name="sport"><option value="club" <?= ($edit['sport']??'club')==='club'?'selected':'' ?>>Whole club</option><option value="cricket" <?= ($edit['sport']??'')==='cricket'?'selected':'' ?>>Cricket</option><option value="football" <?= ($edit['sport']??'')==='football'?'selected':'' ?>>Football</option></select></label><label>Full name<input name="full_name" required value="<?= e($edit['full_name']??'') ?>"></label><label>Position<input name="position" required placeholder="Manager, Captain, Coach..." value="<?= e($edit['position']??'') ?>"></label><label>Phone (optional)<input name="phone" value="<?= e($edit['phone']??'') ?>"></label><label class="full">Photo<input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp"></label>
<?php elseif($section==='matches'): ?>
<label>Sport<select name="sport"><option value="cricket" <?= ($edit['sport']??'cricket')==='cricket'?'selected':'' ?>>Cricket</option><option value="football" <?= ($edit['sport']??'')==='football'?'selected':'' ?>>Football</option></select></label><label>Opponent<input name="opponent" required value="<?= e($edit['opponent']??'') ?>"></label><label>Date & time<input type="datetime-local" name="match_date" required value="<?= !empty($edit['match_date'])?e(date('Y-m-d\TH:i',strtotime($edit['match_date']))):'' ?>"></label><label>Venue<input name="venue" value="<?= e($edit['venue']??'') ?>"></label><label>Competition<input name="competition" value="<?= e($edit['competition']??'') ?>"></label><label>Status<select name="status"><option value="upcoming" <?= ($edit['status']??'upcoming')==='upcoming'?'selected':'' ?>>Upcoming</option><option value="completed" <?= ($edit['status']??'')==='completed'?'selected':'' ?>>Completed</option></select></label><label>Our score<input name="our_score" placeholder="e.g. 165/7 or 2" value="<?= e($edit['our_score']??'') ?>"></label><label>Opponent score<input name="opponent_score" value="<?= e($edit['opponent_score']??'') ?>"></label><label>Result text<input name="result" placeholder="Won by 12 runs / Draw" value="<?= e($edit['result']??'') ?>"></label><label class="full">Notes<textarea name="notes" rows="2"><?= e($edit['notes']??'') ?></textarea></label>
<?php elseif($section==='announcements'): ?>
<label>Title<input name="title" required value="<?= e($edit['title']??'') ?>"></label><label>Applies to<select name="sport"><option value="club" <?= ($edit['sport']??'club')==='club'?'selected':'' ?>>Whole club</option><option value="cricket" <?= ($edit['sport']??'')==='cricket'?'selected':'' ?>>Cricket</option><option value="football" <?= ($edit['sport']??'')==='football'?'selected':'' ?>>Football</option></select></label><label class="full">Message<textarea name="body" required rows="5"><?= e($edit['body']??'') ?></textarea></label><label class="check-label"><input type="checkbox" name="published" value="1" <?= !isset($edit['published'])||(int)$edit['published']===1?'checked':'' ?>> Publish publicly</label>
<?php elseif($section==='gallery'): ?>
<label>Photo title<input name="title" required value="<?= e($edit['title']??'') ?>"></label><label>Team<select name="sport"><option value="club" <?= ($edit['sport']??'club')==='club'?'selected':'' ?>>Whole club</option><option value="cricket" <?= ($edit['sport']??'')==='cricket'?'selected':'' ?>>Cricket</option><option value="football" <?= ($edit['sport']??'')==='football'?'selected':'' ?>>Football</option></select></label><label class="full">Image (JPG, PNG, WebP; max 5 MB)<input type="file" name="photo" accept=".jpg,.jpeg,.png,.webp" <?= $edit?'':'required' ?>></label>
<?php endif; ?>
<div class="form-actions full"><button class="btn" type="submit"><?= $edit?'Save changes':'Add record' ?> →</button><?php if($edit): ?><a class="btn btn-outline" href="admin_manage.php?section=<?= e($section) ?>">Cancel</a><?php endif; ?></div></form></section>
<section class="section"><div class="section-heading"><h2>Current <span>records</span></h2><small class="muted">Latest 200 records</small></div>
<?php if(!$rows): ?><div class="empty">No records yet. Use the form above to add the first one.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>ID</th><th>Record</th><th>Details</th><th>Actions</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td>#<?= (int)$r['id'] ?></td><td><b><?= e($r['full_name']??$r['title']??(($r['sport']??'').' vs '.($r['opponent']??''))) ?></b><small><?= e($r['sport']??'') ?></small></td><td><?php if($section==='players'): ?><?= e($r['role']) ?> · <?= e($r['status']) ?><?php elseif($section==='officials'): ?><?= e($r['position']) ?><?php elseif($section==='matches'): ?><?= e(date('d M Y',strtotime($r['match_date']))) ?> · <?= e($r['status']) ?><?php elseif($section==='announcements'): ?><?= e(mb_strimwidth($r['body'],0,90,'…')) ?> · <?= (int)$r['published']?'Published':'Draft' ?><?php else: ?><a class="text-link" target="_blank" rel="noopener" href="<?= e($r['image_path']) ?>">View image</a><?php endif; ?></td><td><div class="row-actions"><a class="text-link" href="admin_manage.php?section=<?= e($section) ?>&edit=<?= (int)$r['id'] ?>">Edit</a><form method="post" onsubmit="return confirm('Delete this record permanently?')"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button class="link-danger" type="submit">Delete</button></form></div></td></tr><?php endforeach; ?>
</tbody></table></div><?php endif; ?></section>
<?php require __DIR__ . '/footer.php'; ?>
