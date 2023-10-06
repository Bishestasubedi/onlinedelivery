<?php
$db_host = 'localhost';
$db_name = 'foodOrder';
$db_user = 'root';
$db_password = 'root';

$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
