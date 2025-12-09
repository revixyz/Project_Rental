<?php
session_start();
require "../functions.php";

// roteksi halaman 
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data laptop
$laptop = query("SELECT * FROM laptop ORDER BY id DESC");
?>

<?php require "../assets/user-header.php"; ?>

<h3 class="mb-4">Selamat Datang, <?= $_SESSION["nama"]; ?></h3>

<div class="row">

    <?php if (count($laptop) > 0): ?>
        <?php foreach ($laptop as $l): ?>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm">
                    
                    <img src="../assets/img/default-150x150.png" class="card-img-top" alt="Laptop">

                    <div class="card-body">
                        <h5 class="card-title"><?= $l["nama"]; ?></h5>
                        <p class="card-text">
                            Harga: <strong>Rp <?= number_format($l["Harga"]); ?>/hari</strong>
                        </p>

                        <a href="sewa.php?id=<?= $l["id"]; ?>" class="btn btn-primary w-100">
                            Sewa Sekarang
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="alert alert-warning text-center">
                Data laptop belum tersedia.
            </div>
        </div>
    <?php endif; ?>

</div>

<?php require "../assets/footer.php"; ?>
