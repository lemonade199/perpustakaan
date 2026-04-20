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
$query = "SELECT * FROM anggota WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: index.php");
    exit();
}

$anggota = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas  = mysqli_real_escape_string($conn, $_POST['kelas']);
    $no_hp  = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    $update_query = "UPDATE anggota SET 
                    nama='$nama', 
                    kelas='$kelas', 
                    no_hp='$no_hp', 
                    alamat='$alamat' 
                    WHERE id='$id'";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['pesan'] = "Data anggota berhasil diperbarui!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Terjadi kesalahan: " . mysqli_error($conn);
    }
}

include '../template/header.php';
?>

<div class="mb-4">
    <h2>Edit Data Anggota</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?= htmlspecialchars($anggota['nama']) ?>" required>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" class="form-control" id="kelas" name="kelas" value="<?= htmlspecialchars($anggota['kelas']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="no_hp" class="form-label">Nomor Handphone</label>
                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($anggota['no_hp']) ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat Lengkap</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= htmlspecialchars($anggota['alamat']) ?></textarea>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-warning text-white"><i class="bi bi-save"></i> Update Anggota</button>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include '../template/footer.php'; ?>
