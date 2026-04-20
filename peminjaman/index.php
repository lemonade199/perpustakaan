<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
require '../config/database.php';

// Ambil data peminjaman beserta nama anggota
$query = "SELECT p.*, a.nama as nama_anggota 
          FROM peminjaman p 
          JOIN anggota a ON p.anggota_id = a.id 
          ORDER BY p.id DESC";
$result = mysqli_query($conn, $query);

include '../template/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Peminjaman</h2>
    <a href="tambah.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Tambah Pinjaman</a>
</div>

<?php if (isset($_SESSION['pesan'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= $_SESSION['pesan']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['pesan']); ?>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Anggota</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Buku (ID)</th>
                        <th>Status</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // Ambil detail buku yang dipinjam
                            $pid = $row['id'];
                            $q_detail = mysqli_query($conn, "SELECT b.judul FROM detail_peminjaman dp JOIN buku b ON dp.buku_id = b.id WHERE dp.peminjaman_id = '$pid'");
                            $buku_list = [];
                            while($dtl = mysqli_fetch_assoc($q_detail)){
                                $buku_list[] = $dtl['judul'];
                            }
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama_anggota']); ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tanggal_pinjam'])); ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tanggal_kembali'])); ?></td>
                        <td>
                            <ul class="mb-0 ps-3">
                                <?php foreach($buku_list as $bk): ?>
                                    <li><?= htmlspecialchars($bk); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <?php if($row['status'] == 'dipinjam'): ?>
                                <span class="badge bg-warning text-dark">Dipinjam</span>
                            <?php else: ?>
                                <span class="badge bg-success">Kembali</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if($row['status'] == 'dipinjam'): ?>
                                <a href="kembali.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-success" title="Proses Pengembalian" onclick="return confirm('Proses pengembalian buku?');"><i class="bi bi-check2-square"></i> Kembali</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>Data peminjaman masih kosong</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>
