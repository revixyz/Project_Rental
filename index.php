<?php
session_start();
require "functions.php";
require_once "config/database.php";

// Ambil data laptop
$getLaptop = query("SELECT * FROM tb_laptop ORDER BY id_laptop DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Home Rental Laptop | Solusi Perangkat Digital</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/user-style.css" />
    <link rel="stylesheet" href="assets/css/index.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        /* HERO SECTION DENGAN BACKGROUND KOMPUTER ESTETIK */
        .hero {
            background: linear-gradient(rgba(0, 0, 0, 0.65), rgba(0, 0, 0, 0.65)), 
                        url('https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; /* Efek Parallax */
            padding: 150px 0;
            color: white;
            text-align: center;
            border-bottom: 5px solid #007bff;
        }

        .hero h1 {
            font-weight: 800;
            font-size: 3.5rem;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.9;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .btn-custom:hover {
            background-color: transparent;
            border-color: #ffffff;
            color: #ffffff;
            transform: translateY(-5px);
        }

        /* STYLING CARD LAPTOP */
        .card-laptop {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
            background: #fff;
        }

        .card-laptop:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }

        .section-title {
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 40px;
        }

        .section-title::after {
            content: '';
            width: 60px;
            height: 4px;
            background: #007bff;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .feature-card {
            border-radius: 15px;
            border: none;
            transition: 0.3s;
        }

        .feature-card:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<?php require "assets/unknow-header.php"; ?>

<div class="hero">
    <div class="container">
        <h3 class="mb-3" style="color: #ffc107; font-weight: 600; letter-spacing: 1px;">SOLUSI PERANGKAT KERJA 👋</h3>
        <h1>Rental Laptop Terpercaya</h1>
        <p>Sediakan laptop performa tinggi untuk kebutuhan Kuliah, Kerja, Bisnis, hingga Event besar dengan harga bersahabat.</p>
        <a href="#produk" class="btn btn-custom btn-lg mt-4 shadow">Lihat Daftar Laptop</a>
    </div>
</div>
<br><br>

<div class="container mt-5 pt-5">
    <h3 class="section-title text-center">Kenapa Memilih Kami?</h3>
    <div class="row text-center mt-5">
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm feature-card h-100">
                <div class="mb-3">
                    <i class="bi bi-wallet2" style="font-size: 2rem; color: #007bff;"></i>
                </div>
                <h4>Harga Terjangkau</h4>
                <p class="text-muted">Biaya sewa kompetitif dan transparan, ramah untuk kantong pelajar maupun perusahaan.</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm feature-card h-100">
                <div class="mb-3">
                    <i class="bi bi-laptop" style="font-size: 2rem; color: #007bff;"></i>
                </div>
                <h4>Kualitas Terjamin</h4>
                <p class="text-muted">Setiap unit melewati proses maintenance ketat, bersih, dan siap pakai (Ready to Use).</p>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card p-4 shadow-sm feature-card h-100">
                <div class="mb-3">
                    <i class="bi bi-lightning-charge" style="font-size: 2rem; color: #007bff;"></i>
                </div>
                <h4>Proses Mudah</h4>
                <p class="text-muted">Pesan via website, verifikasi cepat, perangkat bisa langsung dikirim atau diambil.</p>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5 pb-5" id="produk">
    <h3 class="section-title text-center">Daftar Laptop Tersedia</h3>

    <div class="row mt-5">
        <?php if (count($getLaptop) > 0): ?>
            <?php foreach ($getLaptop as $l): ?>
                <?php 
                    // Tentukan foto
                    $foto = (!empty($l["foto"])) 
                            ? "assets/laptop/" . $l["foto"] 
                            : "assets/img/default-150x150.png";
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm card-laptop p-0 h-100">
                        <img src="<?= $foto; ?>" class="card-img-top" style="height: 220px; object-fit: cover;" alt="Laptop">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold"> <?= $l["nama"]; ?> </h5>
                            <p class="card-text mb-4 text-primary fw-bold" style="font-size: 1.1rem;">
                                Rp <?= number_format($l["harga"], 0, ',', '.'); ?> <small class="text-muted fw-normal">/ hari</small>
                            </p>
                            <a href="detail-laptop.php?id=<?= $l['id_laptop']; ?>" class="btn btn-outline-primary w-100 py-2 fw-bold" style="border-radius: 10px;">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center py-4">
                    <h5 class="mb-0">Mohon maaf, saat ini data laptop belum tersedia.</h5>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
