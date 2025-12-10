<?php
session_start();
require "../functions.php";
require "../config/database.php";

// Cek apakah user sudah login
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION["id_user"]; // ambil id user dari session

// Cek apakah ada id_laptop di URL
if (!isset($_GET['id_laptop'])) {
    echo "<script>alert('Laptop tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$id_laptop = intval($_GET['id_laptop']);

// Ambil detail laptop dari tb_laptop
$laptop = query("
    SELECT 
        id_laptop,
        nama, 
        harga AS harga_per_hari, 
        stok 
    FROM tb_laptop 
    WHERE id_laptop = $id_laptop
");

if (!$laptop || count($laptop) == 0) {
    echo "<script>alert('Laptop tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$l = $laptop[0];

// PROSES INSERT PEMESANAN
if (isset($_POST['pesan'])) {

    $tanggal_sewa = $_POST['tanggal_sewa'];
    $durasi = intval($_POST['durasi']);
    $harga = $l['harga_per_hari'];
    $total_harga = $harga * $durasi;

    // Insert ke tabel tb_pemesanan
    $query_insert = "
        INSERT INTO tb_pemesanan 
            (id_user, id_laptop, tanggal_sewa, durasi, total_harga, status)
        VALUES 
            ($id_user, $id_laptop, '$tanggal_sewa', $durasi, $total_harga, 'Menunggu')
    ";

    $insert = mysqli_query($conn, $query_insert);

    if ($insert) {
        // Kurangi stok
        mysqli_query($conn, "UPDATE tb_laptop SET stok = stok - 1 WHERE id_laptop = $id_laptop");

        echo "<script>
            alert('Pesanan berhasil dibuat!');
            window.location='pesanan-saya.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Gagal membuat pesanan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require "../assets/user-header.php"; ?>

<div class="container mt-5">

    <h3>Form Pemesanan Laptop</h3>
    <div class="card p-4 shadow-sm">

        <p><strong>Laptop:</strong> <?= htmlspecialchars($l['nama']); ?></p>
        <p><strong>Harga:</strong> Rp<?= number_format($l['harga_per_hari']); ?>/hari</p>
        <p><strong>Stok tersedia:</strong> <?= $l['stok']; ?></p>

        <?php if ($l['stok'] <= 0): ?>
            <div class="alert alert-danger">Stok habis! Tidak bisa disewa.</div>
        <?php else: ?>

        <form action="" method="POST">

            <label for="tanggal_sewa" class="form-label">Tanggal Sewa:</label>
            <input type="date" name="tanggal_sewa" id="tanggal_sewa" class="form-control" required>

            <label for="durasi" class="form-label mt-3">Durasi (hari):</label>
            <input type="number" name="durasi" id="durasi" class="form-control" min="1" required>

            <button type="submit" name="pesan" class="btn btn-primary mt-4">Buat Pesanan</button>
        </form>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
