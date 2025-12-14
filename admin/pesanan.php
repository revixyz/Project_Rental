<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit;
}

$id_laptop = $_GET['id'];

// Ambil data laptop
$q = mysqli_query($conn, "SELECT * FROM tb_laptop WHERE id_laptop='$id_laptop'");
$laptop = mysqli_fetch_assoc($q);

// CEK STOK
if ($laptop['stok'] <= 0) {
    echo "<script>
        alert('Stok tidak tersedia');
        window.location.href='laptop.php';
    </script>";
    exit;
}

// PROSES SIMPAN PESANAN
if (isset($_POST['pesan'])) {

    $tanggal_sewa = $_POST['tanggal_sewa'];
    $durasi = $_POST['durasi'];
    $tanggal_hari_ini = date('Y-m-d');

    // VALIDASI TANGGAL
    if ($tanggal_sewa < $tanggal_hari_ini) {
        echo "<script>
            alert('Tanggal sewa tidak boleh kurang dari hari ini');
            window.history.back();
        </script>";
        exit;
    }

    $total = $durasi * $laptop['harga'];
    $id_user = $_SESSION['id_user'];

    mysqli_query($conn, "
        INSERT INTO tb_pesanan
        (id_user, id_laptop, tanggal_sewa, durasi, total_harga, status)
        VALUES
        ('$id_user', '$id_laptop', '$tanggal_sewa', '$durasi', '$total', 'Menunggu Pembayaran')
    ");

    header("Location: riwayat.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesan Laptop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-5">

    <h3>Pesan Laptop</h3>

    <div class="card mt-3">
        <div class="card-body">

            <h5><?= $laptop['nama']; ?></h5>
            <p><?= $laptop['spesifikasi']; ?></p>
            <p><strong>Harga:</strong> Rp <?= number_format($laptop['harga']); ?> / hari</p>

            <form method="POST">
                <div class="mb-3">
                    <label>Tanggal Sewa</label>
                    <input type="date"
                           name="tanggal_sewa"
                           class="form-control"
                           min="<?= date('Y-m-d'); ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label>Durasi (hari)</label>
                    <input type="number"
                           name="durasi"
                           class="form-control"
                           min="1"
                           required>
                </div>

                <button type="submit" name="pesan" class="btn btn-primary">
                    Pesan Sekarang
                </button>

            </form>

        </div>
    </div>

</div>
</body>
</html>