<?php
session_start();
require "../functions.php";
require_once "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

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
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>

<?php require "../assets/user-header.php"; ?>

<div class="container mt-5">
    
</div>

<div class="hero">
    <div class="container">
        <h3 class="mb-3">Selamat Datang <?= $_SESSION["nama"]; ?>👋</h3>
        <h1>Rental Laptop Terpercaya</h1>
        <p>Sewa laptop berkualitas untuk kuliah, kerja, bisnis, dan event</p>
        <a href="#produk" class="btn btn-lg mt-4">Lihat Daftar Laptop</a>
    </div>
</div>
<br><br>


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

<div class="container mt-5" id="produk">
    <h3 class="section-title text-center">Daftar Laptop Tersedia</h3>

    <div class="row mt-4">
        <?php if (count($getLaptop) > 0): ?>
            <?php foreach ($getLaptop as $l): ?>
                <?php 
                    
                    $foto = (!empty($l["foto"])) 
                                ? "../assets/laptop/" . $l["foto"] 
                                : "../assets/img/default-150x150.png";
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm card-laptop p-3 h-100">
                          <img src="<?= $foto; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Laptop">
                        <div class="card-body">
                            <h5 class="card-title"> <?= $l["nama"]; ?> </h5>
                            <p class="card-text">Harga: <strong>Rp <?= number_format($l["harga"]); ?>/hari</strong></p>
                            <a href="detail-laptop.php?id=<?= $l['id_laptop']; ?>" class="btn btn-primary w-100">Lihat Detail</a>

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



</body>
</html>
