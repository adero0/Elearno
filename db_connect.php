<?php
// db_connect.php - dostosuj dane dostępu
$host = '127.0.0.1';
$db   = 'adaptive_db';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_errno) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");