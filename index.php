<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: auth/login.php");
    exit();
}

require 'config/database.php';

// Menghitung statistik
$jml_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM buku"))['total'];
$jml_anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota"))['total'];
$jml_peminjaman = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman"))['total'];

include 'template/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dashboard</h2>
    <span class="text-muted">Selamat datang, <?= $_SESSION['admin_nama'] ?>!</span>
</div>

<div class="row">
    <!-- Card Buku -->
    <div class="col-md-4 mb-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Buku</h6>
                        <h2 class="mt-2 mb-0"><?= $jml_buku ?></h2>
                    </div>
                    <i class="bi bi-book fa-3x" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="buku/index.php" class="text-white text-decoration-none d-flex justify-content-between align-items-center">
                    <span>Lihat Detail</span>
                    <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card Anggota -->
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Anggota</h6>
                        <h2 class="mt-2 mb-0"><?= $jml_anggota ?></h2>
                    </div>
                    <i class="bi bi-people fa-3x" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="anggota/index.php" class="text-white text-decoration-none d-flex justify-content-between align-items-center">
                    <span>Lihat Detail</span>
                    <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card Peminjaman -->
    <div class="col-md-4 mb-4">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Total Transaksi</h6>
                        <h2 class="mt-2 mb-0"><?= $jml_peminjaman ?></h2>
                    </div>
                    <i class="bi bi-journal-arrow-up fa-3x" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="peminjaman/index.php" class="text-dark text-decoration-none d-flex justify-content-between align-items-center">
                    <span>Lihat Detail</span>
                    <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0 py-2">Informasi Sistem</h5>
            </div>
            <div class="card-body">
                <p>ikuti kami di</p>
                <ul>
                    <li><a href="https://www.instagram.com/aippyaaaa19?igsh=ZWQwaWcwdzMyNTly">Instagram</a></li>
                    <li><a href="https://www.tiktok.com/@aippppz?_r=1&_t=ZS-95eqSuRFtX29">Tiktok</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'template/footer.php'; ?>
