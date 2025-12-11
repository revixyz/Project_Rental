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

    // ambil & sanitize input
    $tanggal_sewa = mysqli_real_escape_string($conn, $_POST['tanggal_sewa']);
    $durasi = intval($_POST['durasi']);
    $harga = floatval($l['harga_per_hari']);
    $total_harga = $harga * $durasi;

    // jika kamu tidak ingin mengisi bukti saat pemesanan, set NULL atau ''
    $bukti = ''; // atau NULL jika kolom menerima NULL

    // Gunakan status awal yang konsisten dengan ENUM di DB (sesuaikan jika berbeda)
    $status_awal = 'Menunggu Pembayaran';

    // Build query (pastikan nama tabel tb_pesanan sesuai DB)
    $query_insert = "
        INSERT INTO tb_pesanan 
            (id_user, id_laptop, tanggal_sewa, durasi, total_harga, status, bukti, metode_bayar, rekening_tujuan)
        VALUES 
            ($id_user, $id_laptop, '$tanggal_sewa', $durasi, $total_harga, '$status_awal', '$bukti', '', '')
    ";

    $insert = mysqli_query($conn, $query_insert);

    if ($insert) {
        // Kurangi stok hanya jika insert sukses
        mysqli_query($conn, "UPDATE tb_laptop SET stok = stok - 1 WHERE id_laptop = $id_laptop");

        echo "<script>
            alert('Pesanan berhasil dibuat!');
            window.location='pesanan-saya.php';
        </script>";
        exit;
    } else {
        // tampilkan error DB untuk debugging (hapus/ubah di production)
        $err = mysqli_error($conn);
        echo "<script>alert('Gagal membuat pesanan! DB error: " . addslashes($err) . "');</script>";
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
