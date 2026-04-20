<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "perpustakaan";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Database Gagal: " . $conn->connect_error);
}

// Set timezone
date_default_timezone_set("Asia/Jakarta");
?>
