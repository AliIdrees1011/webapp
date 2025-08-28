<?php
$DB_HOST = 'localhost';
$DB_NAME = 'u635821533_LbhOO';
$DB_USER = 'u635821533_ykUFD';
$DB_PASS = '2ADCO51#;k';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
  die('DB connection error: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
?>