<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require '../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM anggota WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['pesan'] = "Anggota berhasil dihapus!";
    } else {
        $_SESSION['pesan'] = "Gagal menghapus anggota: " . mysqli_error($conn);
    }
}

header("Location: index.php");
exit();
?>
