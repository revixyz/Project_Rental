<?php
session_start();
require "../functions.php";
require_once "../config/database.php";

// Proteksi halaman
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

// Pastikan ada ID laptop
if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = intval($_GET["id"]);

// Ambil data laptop berdasarkan ID
$laptop = query("SELECT * FROM tb_laptop WHERE id_laptop = $id");

if (!$laptop || count($laptop) == 0) {
    echo "<script>alert('Laptop tidak ditemukan'); window.location='index.php';</script>";
    exit;
}

$l = $laptop[0];

// Cek foto
$foto = (!empty($l["foto"])) 
        ? "../assets/laptop/" . $l["foto"]
        : "../assets/img/default-150x150.png";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laptop - <?= $l["nama"]; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require "../assets/user-header.php"; ?>

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <div class="row">
            
            <!-- FOTO -->
            <div class="col-md-5 text-center">
                <img src="<?= $foto; ?>" class="img-fluid rounded" style="max-height: 300px; object-fit: cover;">
            </div>

            <!-- DETAIL -->
            <div class="col-md-7">
                <h2><?= $l["nama"]; ?></h2>
                
                <p class="mt-3">
                    <strong>Harga Sewa:</strong>  
                    <span class="text-danger fs-4">Rp <?= number_format($l["harga"]); ?>/hari</span>
                </p>

                <p><strong>Spesifikasi:</strong>
                <?= ($l['spesifikasi']); ?>
                </p>
                <!-- Tombol sewa -->
                <a href="index.php" class="btn btn-secondary btn-lg mt-3 w-100">Kembali</a>

            </div>
        </div>
    </div>

    

</div>

<?php require "../assets/footer.php"; ?>

</body>
</html>
