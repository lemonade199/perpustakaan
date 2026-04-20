<?php
$host = "localhost";
$user = "root";
$pass = ""; // Ganti jika MySQL Anda menggunakan password

// 1. Koneksi ke MySQL (tanpa database)
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. Buat Database
$sql = "CREATE DATABASE IF NOT EXISTS perpustakaan";
if ($conn->query($sql) === TRUE) {
    echo "Database 'perpustakaan' berhasil dibuat atau sudah ada.<br>";
} else {
    die("Error buat database: " . $conn->error);
}

// 3. Pilih Database
$conn->select_db("perpustakaan");

// 4. Buat Tabel-Tabel
// Tabel Admin
$sql_admin = "CREATE TABLE IF NOT EXISTS admin (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL
)";

// Tabel Buku
$sql_buku = "CREATE TABLE IF NOT EXISTS buku (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    penulis VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100) NOT NULL,
    tahun YEAR NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    stok INT(11) NOT NULL DEFAULT 0
)";

// Tabel Anggota
$sql_anggota = "CREATE TABLE IF NOT EXISTS anggota (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    kelas VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    no_hp VARCHAR(20) NOT NULL
)";

// Tabel Peminjaman
$sql_peminjaman = "CREATE TABLE IF NOT EXISTS peminjaman (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    anggota_id INT(11) NOT NULL,
    tanggal_pinjam DATE NOT NULL,
    tanggal_kembali DATE NOT NULL,
    status ENUM('dipinjam', 'kembali') NOT NULL DEFAULT 'dipinjam',
    FOREIGN KEY (anggota_id) REFERENCES anggota(id) ON DELETE CASCADE
)";

// Tabel Detail Peminjaman
$sql_detail = "CREATE TABLE IF NOT EXISTS detail_peminjaman (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    peminjaman_id INT(11) NOT NULL,
    buku_id INT(11) NOT NULL,
    FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE,
    FOREIGN KEY (buku_id) REFERENCES buku(id) ON DELETE CASCADE
)";

// Tabel Denda
$sql_denda = "CREATE TABLE IF NOT EXISTS denda (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    peminjaman_id INT(11) NOT NULL,
    jumlah_denda INT(11) NOT NULL,
    keterangan VARCHAR(200),
    FOREIGN KEY (peminjaman_id) REFERENCES peminjaman(id) ON DELETE CASCADE
)";

$tables = [
    'Admin' => $sql_admin,
    'Buku' => $sql_buku,
    'Anggota' => $sql_anggota,
    'Peminjaman' => $sql_peminjaman,
    'Detail Peminjaman' => $sql_detail,
    'Denda' => $sql_denda
];

foreach ($tables as $name => $sql) {
    if ($conn->query($sql) === TRUE) {
        echo "Tabel '$name' berhasil disiapkan.<br>";
    } else {
        echo "Error buat tabel '$name': " . $conn->error . "<br>";
    }
}

// 5. Insert Akun Admin Default (username: admin, password: admin)
$password_hash = md5('admin');
$check_admin = $conn->query("SELECT * FROM admin WHERE username = 'admin'");
if ($check_admin->num_rows == 0) {
    $insert_admin = "INSERT INTO admin (username, password, nama_lengkap) VALUES ('admin', '$password_hash', 'Administrator')";
    if ($conn->query($insert_admin) === TRUE) {
        echo "Akun admin default berhasil dibuat! (Username: admin, Password: admin)<br>";
    } else {
        echo "Gagal membuat akun admin: " . $conn->error . "<br>";
    }
} else {
    echo "Akun admin default sudah ada.<br>";
}

echo "<h3>Setup selesai! Silakan hapus file INI jika sudah di sistem produksi.</h3>";
echo "<a href='index.php'>Lanjut ke Aplikasi</a>";

$conn->close();
?>
