<?php
require_once('db.php');
session_start();
$err=''; $msg='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  if (isset($_POST['action']) && $_POST['action']==='signup') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = ($_POST['role'] ?? 'consumer') === 'creator' ? 'creator' : 'consumer';
    if ($username && $password) {
      $stmt = $conn->prepare('SELECT id FROM users WHERE username=?');
      $stmt->bind_param('s',$username); $stmt->execute(); $stmt->store_result();
      if ($stmt->num_rows>0) { $err='Username already taken'; }
      else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $conn->prepare('INSERT INTO users (username, password_hash, role) VALUES (?,?,?)');
        $ins->bind_param('sss',$username,$hash,$role);
        if ($ins->execute()) { $msg='Account created. You can now login.'; }
        else { $err='Failed to create account'; }
      }
    } else { $err='Fill all fields'; }
  } elseif (isset($_POST['action']) && $_POST['action']==='login') {
    $username = trim($_POST['username'] ?? ''); $password = $_POST['password'] ?? '';
    if ($username && $password) {
      $stmt = $conn->prepare('SELECT id, password_hash, role FROM users WHERE username=? LIMIT 1');
      $stmt->bind_param('s',$username); $stmt->execute(); $res=$stmt->get_result();
      if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password_hash'])) {
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['username'] = $username;
          $_SESSION['role'] = $row['role'];
          header('Location: dashboard.php'); exit;
        }
      }
      $err='Invalid credentials';
    } else { $err='Fill all fields'; }
  }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Auth</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="bg-light">
<div class="container py-5" style="max-width:720px">
  <h2 class="mb-4">Sign up or Login</h2>
  <?php if($err) echo '<div class="alert alert-danger">'.htmlspecialchars($err).'</div>'; ?>
  <?php if($msg) echo '<div class="alert alert-success">'.htmlspecialchars($msg).'</div>'; ?>
  <div class="row">
    <div class="col-md-6">
      <h4>Create Account</h4>
      <form method="POST">
        <input type="hidden" name="action" value="signup">
        <div class="mb-2"><input name="username" class="form-control" placeholder="username" required></div>
        <div class="mb-2"><input name="password" type="password" class="form-control" placeholder="password" required></div>
        <div class="mb-2">
          <label class="form-label">Sign up as</label>
          <select name="role" class="form-select">
            <option value="consumer" selected>Consumer (view, comment, rate)</option>
            <option value="creator">Creator (upload videos)</option>
          </select>
          <div class="form-text">Creators are auto-approved.</div>
        </div>
        <button class="btn btn-danger w-100">Sign up</button>
      </form>
    </div>
    <div class="col-md-6">
      <h4>Login</h4>
      <form method="POST">
        <input type="hidden" name="action" value="login">
        <div class="mb-2"><input name="username" class="form-control" placeholder="username" required></div>
        <div class="mb-2"><input name="password" type="password" class="form-control" placeholder="password" required></div>
        <button class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
  <p class="mt-3"><a href="index.php">Back to feed</a></p>
</div>
</body></html>