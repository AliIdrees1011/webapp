<?php
$host = getenv('DB_HOST');
$db = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$port = getenv('DB_PORT');

$mysqli = new mysqli($host, $user, $pass, $db, $port);

if ($mysqli->connect_error) {
  die("DB connection failed: " . $mysqli->connect_error);
}
else {
echo "connected to azule";
}
?>
