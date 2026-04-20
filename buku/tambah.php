<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul    = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis  = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun    = $_POST['tahun'];
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok     = $_POST['stok'];

    $query = "INSERT INTO buku (judul, penulis, penerbit, tahun, kategori, stok) 
              VALUES ('$judul', '$penulis', '$penerbit', '$tahun', '$kategori', '$stok')";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['pesan'] = "Buku berhasil ditambahkan!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Terjadi kesalahan: " . mysqli_error($conn);
    }
}

include '../template/header.php';
?>

<div class="mb-4">
    <h2>Tambah Buku Baru</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Buku</label>
                <input type="text" class="form-control" id="judul" name="judul" required>
            </div>
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" class="form-control" id="penulis" name="penulis" required>
            </div>
            <div class="mb-3">
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" class="form-control" id="penerbit" name="penerbit" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="tahun" class="form-label">Tahun Terbit</label>
                    <input type="number" min="1900" max="2099" class="form-control" id="tahun" name="tahun" required>
                </div>
                <div class="col-md-4">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" required>
                </div>
                <div class="col-md-4">
                    <label for="stok" class="form-label">Jumlah Stok</label>
                    <input type="number" min="0" class="form-control" id="stok" name="stok" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Buku</button>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include '../template/footer.php'; ?>
