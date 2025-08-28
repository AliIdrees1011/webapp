<?php
$DB_HOST = 'aliidreesmysqserver.mysql.database.azure.com';
$DB_NAME = 'u635821533_LbhOO';
$DB_USER = 'aliidrees1011';
$DB_PASS = 'naeem@123';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
  die('DB connection error: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
?>
