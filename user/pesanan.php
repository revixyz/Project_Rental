<?php
session_start();
require "../functions.php";
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION["id_user"];

if (!isset($_GET['id_laptop'])) {
    echo "<script>alert('Laptop tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$id_laptop = intval($_GET['id_laptop']);

$laptop = query("
    SELECT id_laptop, nama, harga AS harga_per_hari, stok
    FROM tb_laptop
    WHERE id_laptop = $id_laptop
");

if (!$laptop || count($laptop) == 0) {
    echo "<script>alert('Laptop tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}

$l = $laptop[0];

if (isset($_POST['pesan'])) {

    $tanggal_sewa = $_POST['tanggal_sewa'];
    $durasi = intval($_POST['durasi']);
    $harga = floatval($l['harga_per_hari']);
    $total_harga = $harga * $durasi;

    $today = date('Y-m-d');
    if ($tanggal_sewa < $today) {
        echo "<script>
            alert('Tanggal sewa tidak boleh kurang dari hari ini!');
            window.history.back();
        </script>";
        exit;
    }

    if ($durasi < 1 || $durasi > 7) {
        echo "<script>
            alert('Durasi sewa maksimal 7 hari!');
            window.history.back();
        </script>";
        exit;
    }

    $status_awal = 'Menunggu Pembayaran';

    $query = "
        INSERT INTO tb_pesanan
        (id_user, id_laptop, tanggal_sewa, durasi, total_harga, status, bukti, metode_bayar, rekening_tujuan)
        VALUES
        ($id_user, $id_laptop, '$tanggal_sewa', $durasi, $total_harga, '$status_awal', '', '', '')
    ";

    if (mysqli_query($conn, $query)) {

        mysqli_query($conn, "
            UPDATE tb_laptop 
            SET stok = stok - 1 
            WHERE id_laptop = $id_laptop
        ");

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
    <title>Form Pemesanan Laptop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require "../assets/user-header.php"; ?>

<div class="container mt-5">
    <h3>Form Pemesanan Laptop</h3>

    <div class="card p-4 shadow-sm">

        <p><strong>Laptop:</strong> <?= htmlspecialchars($l['nama']); ?></p>
        <p><strong>Harga:</strong> Rp<?= number_format($l['harga_per_hari']); ?>/hari</p>
        <p><strong>Stok:</strong> <?= $l['stok']; ?></p>

        <?php if ($l['stok'] <= 0): ?>
            <div class="alert alert-danger">Stok habis!</div>
        <?php else: ?>

        <form method="POST">

            <label class="form-label">Tanggal Sewa</label>
            <input type="date"
                   id="tanggal_sewa"
                   name="tanggal_sewa"
                   class="form-control"
                   min="<?= date('Y-m-d'); ?>"
                   value="<?= date('Y-m-d'); ?>"
                   required>

            <label class="form-label mt-3">
                Durasi (hari) <small class="text-muted">(Maksimal 7 hari)</small>
            </label>
            <input type="number"
                   name="durasi"
                   class="form-control"
                   min="1"
                   max="7"
                   required>

            <button type="submit" name="pesan" class="btn btn-primary mt-4">
                Buat Pesanan
            </button>

        </form>

        <?php endif; ?>

    </div>
</div>

<script>
    const tanggalInput = document.getElementById('tanggal_sewa');

    tanggalInput.addEventListener('keydown', e => e.preventDefault());
    tanggalInput.addEventListener('paste', e => e.preventDefault());
</script>

</body>
</html>