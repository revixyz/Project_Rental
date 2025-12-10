<?php
session_start();
require "../functions.php";
require_once "../config/database.php";

// Proteksi halaman
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data laptop
$getLaptop = query("SELECT * FROM tb_laptop ORDER BY id_laptop DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Rental Laptop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/user-style.css" />
</head>
<body>

<?php require "../assets/user-header.php"; ?>

<!-- SELAMAT DATANG -->
<div class="container mt-5">
    <h3 class="text-center mb-4">Selamat Datang, <?= $_SESSION["nama"]; ?></h3>
</div>

<!-- HERO SECTION -->
<div class="hero">
    <div>
        <h1>Rental Laptop Terpercaya</h1>
        <p>Sewa laptop berkualitas untuk kebutuhan kuliah, kerja, bisnis, dan event</p>
        <a href="#produk" class="btn btn-primary btn-lg mt-3">Sewa Sekarang</a>
    </div>
</div>

<!-- FITUR -->
<div class="container mt-4">
    <h3 class="section-title text-center">Kenapa Memilih Kami?</h3>
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm">
                <h4>Harga Terjangkau</h4>
                <p>Biaya sewa ramah kantong untuk pelajar dan profesional.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm">
                <h4>Kualitas Terjamin</h4>
                <p>Laptop bersih, cepat, dan siap digunakan kapan saja.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm">
                <h4>Proses Mudah</h4>
                <p>Pesan online, bayar, dan langsung ambil perangkat.</p>
            </div>
        </div>
    </div>
</div>

<!-- PRODUK (DATA LAPTOP) -->
<div class="container mt-5" id="produk">
    <h3 class="section-title text-center">Daftar Laptop Tersedia</h3>

    <div class="row mt-4">
        <?php if (count($getLaptop) > 0): ?>
            <?php foreach ($getLaptop as $l): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm card-laptop p-2">
                        <img src="../assets/img/default-150x150.png" class="card-img-top" alt="Laptop">
                        <div class="card-body">
                            <h5 class="card-title"> <?= $l["nama"]; ?> </h5>
                            <p class="card-text">Harga: <strong>Rp <?= number_format($l["Harga"]); ?>/hari</strong></p>
                            <a href="sewa.php" class="btn btn-primary w-100">Lihat Detail</a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">Data laptop belum tersedia.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- FOOTER -->
<footer>
    <p>&copy; 2025 Rental Laptop - Semua Hak Dilindungi</p>
</footer>

<?php require "../assets/footer.php"; ?>
</body>
</html>
