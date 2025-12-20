<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION["id_user"];

if (!isset($_GET['id'])) {
    die("ID pesanan tidak valid.");
}

$id_pesanan = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT 
        p.id_pesanan,
        p.durasi,
        p.total_harga,
        p.status,
        l.nama AS nama_laptop,
        l.harga AS harga_per_hari
    FROM tb_pesanan p
    JOIN tb_laptop l ON p.id_laptop = l.id_laptop
    WHERE p.id_pesanan = $id_pesanan
      AND p.id_user = $id_user
");

if (mysqli_num_rows($query) == 0) {
    die("Pesanan tidak ditemukan.");
}

$p = mysqli_fetch_assoc($query);

if (
    $p['status'] != 'Menunggu Pembayaran' &&
    $p['status'] != 'Ditolak Pembayaran'
) {
    echo "<script>
        alert('Pesanan tidak dapat diubah karena sudah diproses.');
        window.location='pesanan-saya.php';
    </script>";
    exit;
}

if (isset($_POST['simpan'])) {

    $durasi = intval($_POST['durasi']);

    // validasi durasi (1–7 hari)
    if ($durasi < 1 || $durasi > 7) {
        echo "<script>
            alert('Durasi sewa minimal 1 hari dan maksimal 7 hari!');
            window.history.back();
        </script>";
        exit;
    }

    $total = $durasi * $p['harga_per_hari'];

    mysqli_query($conn, "
        UPDATE tb_pesanan SET
            durasi = '$durasi',
            total_harga = '$total'
        WHERE id_pesanan = '$id_pesanan'
    ");

    $_SESSION['pesan'] = "Pesanan berhasil diperbarui.";
    header("Location: pesanan-saya.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<?php require "../assets/user-header.php"; ?>

<div class="container mt-5">

    <h3 class="mb-4">Edit Pesanan</h3>

    <div class="card shadow-sm p-4">

        <p>
            <strong>Laptop:</strong><br>
            <?= htmlspecialchars($p['nama_laptop']); ?>
        </p>

        <p>
            <strong>Harga per hari:</strong><br>
            Rp<?= number_format($p['harga_per_hari']); ?>
        </p>

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">
                    Durasi Sewa (hari)
                    <small class="text-muted">(maksimal 7 hari)</small>
                </label>
                <input type="number"
                       name="durasi"
                       class="form-control"
                       min="1"
                       max="7"
                       value="<?= $p['durasi']; ?>"
                       required>
            </div>

            <button type="submit" name="simpan" class="btn btn-primary">
                Simpan Perubahan
            </button>

            <a href="pesanan-saya.php" class="btn btn-secondary">
                Batal
            </a>

        </form>

    </div>
</div>

</body>
</html>
