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
$query = "SELECT * FROM buku WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$buku = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul    = mysqli_real_escape_string($conn, $_POST['judul']);
    $penulis  = mysqli_real_escape_string($conn, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($conn, $_POST['penerbit']);
    $tahun    = $_POST['tahun'];
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $stok     = $_POST['stok'];

    $update_query = "UPDATE buku SET 
                    judul='$judul', 
                    penulis='$penulis', 
                    penerbit='$penerbit', 
                    tahun='$tahun', 
                    kategori='$kategori', 
                    stok='$stok' 
                    WHERE id='$id'";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['pesan'] = "Data buku berhasil diperbarui!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Terjadi kesalahan: " . mysqli_error($conn);
    }
}

include '../template/header.php';
?>

<div class="mb-4">
    <h2>Edit Data Buku</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Buku</label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($buku['judul']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="penulis" class="form-label">Penulis</label>
                <input type="text" class="form-control" id="penulis" name="penulis" value="<?= htmlspecialchars($buku['penulis']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?= htmlspecialchars($buku['penerbit']) ?>" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="tahun" class="form-label">Tahun Terbit</label>
                    <input type="number" min="1900" max="2099" class="form-control" id="tahun" name="tahun" value="<?= $buku['tahun'] ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="kategori" class="form-label">Kategori</label>
                    <input type="text" class="form-control" id="kategori" name="kategori" value="<?= htmlspecialchars($buku['kategori']) ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="stok" class="form-label">Jumlah Stok</label>
                    <input type="number" min="0" class="form-control" id="stok" name="stok" value="<?= $buku['stok'] ?>" required>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-warning text-white"><i class="bi bi-save"></i> Update Buku</button>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include '../template/footer.php'; ?>
