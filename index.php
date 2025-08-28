<?php
require_once('db.php');
$res = $conn->query('SELECT id, title, filename, username FROM videos ORDER BY uploaded_at DESC LIMIT 50');
$videos = $res->fetch_all(MYSQLI_ASSOC);
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>RedTok</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{margin:0;background:#000;color:#fff;font-family:Arial} .video-container{height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;border-bottom:1px solid #111;padding:20px} video{max-height:90vh;width:auto;max-width:100%;border-radius:8px}</style>
</head><body>
<div id="videos">
<?php foreach($videos as $v): ?>
  <section class="video-container">
    <div style="position:absolute;top:16px;left:16px;color:#ff5a5a;font-weight:bold">@<?php echo htmlspecialchars($v['username']); ?></div>
    <video controls playsinline muted loop>
      <source src="/uploads/<?php echo htmlspecialchars($v['filename']); ?>" type="video/mp4">
    </video>
  </section>
<?php endforeach; ?>
</div>
<div style="position:fixed;top:16px;right:16px"><a class="btn btn-sm btn-light me-2" href="auth.php">Login / Sign up</a><a class="btn btn-sm btn-danger" href="dashboard.php">Dashboard</a></div>
</body></html>