<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require '../config/database.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

// Cek apakah data valid dan status masih dipinjam
$query = "SELECT * FROM peminjaman WHERE id = '$id' AND status = 'dipinjam'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $peminjaman = mysqli_fetch_assoc($result);
    $tgl_kembali_seharusnya = strtotime($peminjaman['tanggal_kembali']);
    $tgl_sekarang = strtotime(date('Y-m-d'));
    
    $denda = 0;
    
    // Hitung denda jika terlambat (misal denda Rp 1000 per hari)
    if ($tgl_sekarang > $tgl_kembali_seharusnya) {
        $selisih = $tgl_sekarang - $tgl_kembali_seharusnya;
        $hari_terlambat = floor($selisih / (60 * 60 * 24));
        $denda = $hari_terlambat * 1000;
    }
    
    // Mulai transaksi
    mysqli_begin_transaction($conn);
    try {
        // 1. Ubah status jadi kembali
        mysqli_query($conn, "UPDATE peminjaman SET status = 'kembali' WHERE id = '$id'");
        
        // 2. Jika ada denda, catat denda
        if ($denda > 0) {
            mysqli_query($conn, "INSERT INTO denda (peminjaman_id, jumlah_denda, keterangan) VALUES ('$id', '$denda', 'Terlambat $hari_terlambat hari')");
        }
        
        // 3. Kembalikan stok buku
        $q_detail = mysqli_query($conn, "SELECT buku_id FROM detail_peminjaman WHERE peminjaman_id = '$id'");
        while ($dtl = mysqli_fetch_assoc($q_detail)) {
            $buku_id = $dtl['buku_id'];
            mysqli_query($conn, "UPDATE buku SET stok = stok + 1 WHERE id = '$buku_id'");
        }
        
        mysqli_commit($conn);
        
        $pesan = "Buku berhasil dikembalikan!";
        if ($denda > 0) {
            $pesan .= " (Ada denda keterlambatan: Rp " . number_format($denda,0,',','.') . ")";
        }
        $_SESSION['pesan'] = $pesan;
        
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['pesan'] = "Gagal memproses pengembalian: " . $e->getMessage();
    }
} else {
    $_SESSION['pesan'] = "Peminjaman tidak ditemukan atau sudah dikembalikan.";
}

header("Location: index.php");
exit();
?>
