<?php
session_start();
require "../config/database.php";

/* =========================================
   CEK LOGIN ADMIN
========================================= */
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

/* =========================================
   AMBIL RIWAYAT PENGEMBALIAN
========================================= */
$sql = "
    SELECT 
        k.id_pengembalian,
        k.tanggal_kembali,
        k.denda,
        k.kondisi_laptop,
        k.catatan_admin,
        k.status,
        u.nama AS nama_user,
        l.nama AS nama_laptop
    FROM tb_pengembalian k
    JOIN tb_pesanan p ON k.id_pesanan = p.id_pesanan
    JOIN tb_user u ON p.id_user = u.id_user
    JOIN tb_laptop l ON p.id_laptop = l.id_laptop
    ORDER BY k.id_pengembalian DESC
";

$data = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Riwayat Pengembalian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require "../assets/header.php"; ?>

<div class="container mt-5">

    <h3 class="mb-4">Riwayat Pengembalian Laptop</h3>

    <div class="table-responsive shadow-sm">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Laptop</th>
                    <th>Tanggal Kembali</th>
                    <th>Denda</th>
                    <th>Kondisi Laptop</th>
                    <th>Catatan Admin</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $no = 1;

            if ($data->num_rows == 0): ?>
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        Belum ada riwayat pengembalian.
                    </td>
                </tr>
            <?php else: ?>

                <?php foreach ($data as $k): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $k['nama_user']; ?></td>
                    <td><?= $k['nama_laptop']; ?></td>
                    <td><?= $k['tanggal_kembali']; ?></td>
                    <td>Rp<?= number_format($k['denda']); ?></td>
                    <td><?= $k['kondisi_laptop']; ?></td>
                    <td><?= $k['catatan_admin'] ?: '-'; ?></td>
                    <td>
                        <?php if ($k['status'] == 'Disetujui'): ?>
                            <span class="badge bg-success">Disetujui</span>
                        <?php elseif ($k['status'] == 'Ditolak'): ?>
                            <span class="badge bg-danger">Ditolak</span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?= $k['status']; ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
