<?php
// Pastikan session sudah dimulai jika belum
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = "http://localhost/perpus";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Perpustakaan</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #3498db;
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important;
        }
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #ffffff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
        }
        .sidebar a {
            color: #333;
            text-decoration: none;
            padding: 15px 20px;
            display: block;
            border-bottom: 1px solid #f1f1f1;
        }
        .sidebar a:hover {
            background-color: #f8f9fa;
            color: #3498db;
        }
        .sidebar a.active {
            background-color: #e9ecef;
            color: #3498db;
            font-weight: bold;
        }
        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .content-area {
            padding: 20px;
        }
    </style>
</head>
<body>

<?php if(isset($_SESSION['admin_id'])): ?>
<!-- Navbar untuk Admin yang sudah login -->
<nav class="navbar navbar-expand-lg sticky-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?= $base_url ?>/index.php">
        <i class="bi bi-book-half"></i> PerpusKita
    </a>
    <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
             <i class="bi bi-person-circle"></i> <?= $_SESSION['admin_nama'] ?? 'Admin' ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="<?= $base_url ?>/auth/logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 p-0 sidebar d-none d-md-block">
            <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
            <?php $current_dir = basename(dirname($_SERVER['PHP_SELF'])); ?>
            
            <a href="<?= $base_url ?>/index.php" class="<?= ($current_page == 'index.php' && $current_dir == 'perpus') ? 'active' : '' ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="<?= $base_url ?>/buku/index.php" class="<?= ($current_dir == 'buku') ? 'active' : '' ?>">
                <i class="bi bi-book me-2"></i> Data Buku
            </a>
            <a href="<?= $base_url ?>/anggota/index.php" class="<?= ($current_dir == 'anggota') ? 'active' : '' ?>">
                <i class="bi bi-people me-2"></i> Data Anggota
            </a>
            <a href="<?= $base_url ?>/peminjaman/index.php" class="<?= ($current_dir == 'peminjaman') ? 'active' : '' ?>">
                <i class="bi bi-journal-arrow-up me-2"></i> Peminjaman
            </a>
            <a href="<?= $base_url ?>/auth/logout.php" class="text-danger">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </a>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 col-lg-10 content-area">
<?php endif; ?>
