<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require '../config/database.php';

// Ambil data anggota
$anggota_query = mysqli_query($conn, "SELECT id, nama FROM anggota ORDER BY nama ASC");

// Ambil data buku yang stoknya > 0
$buku_query = mysqli_query($conn, "SELECT id, judul, stok FROM buku WHERE stok > 0 ORDER BY judul ASC");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $anggota_id = $_POST['anggota_id'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $buku_id = $_POST['buku_id'] ?? []; // array of buku
    
    if (empty($buku_id)) {
        $error = "Pilih minimal satu buku!";
    } else {
        // Mulai transaksi (opsional, tapi disarankan)
        mysqli_begin_transaction($conn);
        try {
            // 1. Insert ke tabel peminjaman
            $q_pinjam = "INSERT INTO peminjaman (anggota_id, tanggal_pinjam, tanggal_kembali, status) 
                         VALUES ('$anggota_id', '$tanggal_pinjam', '$tanggal_kembali', 'dipinjam')";
            mysqli_query($conn, $q_pinjam);
            
            $peminjaman_id = mysqli_insert_id($conn);
            
            // 2. Insert ke detail dan update stok buku
            foreach ($buku_id as $bid) {
                // Insert detail
                $q_detail = "INSERT INTO detail_peminjaman (peminjaman_id, buku_id) VALUES ('$peminjaman_id', '$bid')";
                mysqli_query($conn, $q_detail);
                
                // Update stok
                $q_stok = "UPDATE buku SET stok = stok - 1 WHERE id = '$bid'";
                mysqli_query($conn, $q_stok);
            }
            
            mysqli_commit($conn);
            $_SESSION['pesan'] = "Transaksi Peminjaman berhasil disimpan!";
            header("Location: index.php");
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}

include '../template/header.php';
?>

<div class="mb-4">
    <h2>Tambah Pinjaman Baru</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <form action="" method="POST">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="anggota_id" class="form-label">Pilih Anggota</label>
                    <select class="form-select" id="anggota_id" name="anggota_id" required>
                        <option value="">-- Pilih Anggota --</option>
                        <?php while($row = mysqli_fetch_assoc($anggota_query)): ?>
                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Pilih Buku (centang)</label>
                    <div class="border p-2" style="max-height: 200px; overflow-y: scroll;">
                        <?php while($row = mysqli_fetch_assoc($buku_query)): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="buku_id[]" value="<?= $row['id'] ?>" id="buku_<?= $row['id'] ?>">
                                <label class="form-check-label" for="buku_<?= $row['id'] ?>">
                                    <?= htmlspecialchars($row['judul']) ?> (Stok: <?= $row['stok'] ?>)
                                </label>
                            </div>
                        <?php endwhile; ?>
                   </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                    <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                    <?php 
                        // Default 7 hari ke depan
                        $default_kembali = date('Y-m-d', strtotime("+7 days")); 
                    ?>
                    <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" value="<?= $default_kembali ?>" required>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan Transaksi</button>
                <a href="index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<?php include '../template/footer.php'; ?>
