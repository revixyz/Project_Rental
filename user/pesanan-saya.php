<?php
session_start();
require "../functions.php";
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

$sql1="SELECT * FROM tb_laptop";
$query1=mysqli_query($conn,$sql1);

$id_user = $_SESSION["id_user"];

// Ambil data pesanan + data tb_laptop
$pesanan = "SELECT tb_pesanan.id_pesanan, tb_laptop.nama as nama_laptop, tb_laptop.spesifikasi, tb_pesanan.tanggal_sewa, tb_pesanan.durasi, tb_pesanan.total_harga, tb_pesanan.status FROM tb_pesanan
JOIN tb_laptop ON tb_pesanan.id_laptop = tb_laptop.id_laptop";

$data=$conn->query($pesanan);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require "../assets/user-header.php"; ?>

<div class="container mt-5">

    <h3 class="mb-4">Pesanan Saya</h3>

    

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Laptop</th>
                        <th>Spesifikasi</th>
                        <th>Tanggal Sewa</th>
                        <th>Durasi</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                <?php $no = 1; foreach ($data as $p): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <strong><?= $p['nama_laptop']; ?></strong><br>
                            
                        </td>
                        <td>
                            <small><?= $p['spesifikasi']; ?></small>
                        </td>
                        <td><?= $p['tanggal_sewa']; ?></td>
                        <td><?= $p['durasi']; ?> hari</td>
                        <td>Rp<?= number_format($p['total_harga']); ?></td>
                        <td>
                            <?php if ($p['status'] == 'Menunggu'): ?>
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            <?php elseif ($p['status'] == 'Disetujui'): ?>
                                <span class="badge bg-success">Disetujui</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>

            </table>
        </div>

    

</div>

</body>
</html>
