<?php
session_start();
if (isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../template/header.php';
?>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-5">
            <div class="card p-4">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-book-half text-primary" style="font-size: 3rem; color: #3498db !important;"></i>
                        <h3 class="mt-2">Login Admin</h3>
                        <p class="text-muted">Sistem Informasi Perpustakaan</p>
                    </div>

                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= $_SESSION['error'] ?>
                        </div>
                        <?php unset($_SESSION['error']); ?>
                    <?php endif; ?>

                    <form action="proses_login.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" style="background-color: #3498db; border-color: #3498db;">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../template/footer.php'; ?>
