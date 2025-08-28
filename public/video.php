<?php
require_once('db.php'); session_start();
$id = intval($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM videos WHERE id=? LIMIT 1'); $stmt->bind_param('i',$id); $stmt->execute(); $res=$stmt->get_result(); $video=$res->fetch_assoc();
if (!$video) { echo 'Not found'; exit; }

if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (isset($_POST['rating']) && isset($_SESSION['username'])) {
    $rating = intval($_POST['rating']); $user = $_SESSION['username'];
    $ins = $conn->prepare('REPLACE INTO ratings (video_id, username, rating) VALUES (?,?,?)'); $ins->bind_param('isi',$id,$user,$rating); $ins->execute();
  }
  if (isset($_POST['comment']) && isset($_SESSION['username'])) {
    $comment = trim($_POST['comment']); $user = $_SESSION['username'];
    if ($comment!=='') { $ins = $conn->prepare('INSERT INTO comments (video_id, username, comment) VALUES (?,?,?)'); $ins->bind_param('iss',$id,$user,$comment); $ins->execute(); }
  }
  header('Location: /public/video.php?id=' . $id); exit;
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo htmlspecialchars($video['title']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-dark text-white">
<div class="container py-4">
  <h3><?php echo htmlspecialchars($video['title']); ?></h3>
  <div class="small text-muted">@<?php echo htmlspecialchars($video['username']); ?> | <?php echo htmlspecialchars($video['genre']); ?> | Age: <?php echo htmlspecialchars($video['age_rating']); ?></div>
  <div class="my-3"><video controls width="100%"><source src="/uploads/<?php echo htmlspecialchars($video['filename']); ?>" type="video/mp4"></video></div>

  <h5>Rate this video</h5>
  <?php if(isset($_SESSION['username'])): ?>
    <form method="POST" class="mb-3 d-flex">
      <select name="rating" class="form-select me-2" style="max-width:120px">
        <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>
      </select>
      <button class="btn btn-danger">Submit</button>
    </form>
  <?php else: ?>
    <div class="mb-3">Please <a href="/auth.php">login</a> to rate or comment.</div>
  <?php endif; ?>

  <h5>Comments</h5>
  <?php if(isset($_SESSION['username'])): ?>
    <form method="POST" class="mb-3"><textarea name="comment" class="form-control mb-2" required></textarea><button class="btn btn-primary">Post comment</button></form>
  <?php endif; ?>
  <div>
  <?php $c = $conn->prepare('SELECT username,comment,created_at FROM comments WHERE video_id=? ORDER BY created_at DESC'); $c->bind_param('i',$id); $c->execute(); $cres=$c->get_result(); while($r=$cres->fetch_assoc()){ echo '<div class="mb-2"><strong>@'.htmlspecialchars($r['username']).'</strong> <small class="text-muted">'.$r['created_at'].'</small><div>'.nl2br(htmlspecialchars($r['comment'])).'</div></div>'; } ?>
  </div>
</div>
</body></html>