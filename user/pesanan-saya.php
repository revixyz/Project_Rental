<?php
session_start();
require "../functions.php";
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION["id_user"];

$query = "
    SELECT 
        p.id_pesanan,
        p.tanggal_sewa,
        p.durasi,
        p.total_harga,
        p.status,
        p.bukti,
        l.nama AS nama_laptop,
        l.spesifikasi
    FROM tb_pesanan p
    JOIN tb_laptop l ON p.id_laptop = l.id_laptop
    WHERE p.id_user = '$id_user'
    ORDER BY p.id_pesanan DESC
";

$data = $conn->query($query);
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

    <div class="table-responsive shadow-sm">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Laptop</th>
                    <th>Spesifikasi</th>
                    <th>Tanggal Sewa</th>
                    <th>Durasi</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Bukti Pembayaran</th>
                    <th>Ubah Pesanan</th>
                </tr>
            </thead>

            <tbody>

            <?php if ($data->num_rows == 0): ?>
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <strong>Tidak ada pesanan.</strong><br>
                        Anda belum memiliki riwayat pesanan.
                    </td>
                </tr>
            <?php endif; ?>

            <?php $no = 1; foreach ($data as $p): ?>

                <tr>
                    <td><?= $no++; ?></td>

                    <td><strong><?= $p['nama_laptop']; ?></strong></td>

                    <td><small><?= nl2br($p['spesifikasi']); ?></small></td>

                    <td><?= $p['tanggal_sewa']; ?></td>

                    <td><?= $p['durasi']; ?> hari</td>

                    <td>Rp<?= number_format($p['total_harga']); ?></td>

                    <td class="text-center">
                        <?php
                        switch ($p['status']) {
                            case 'Menunggu Pembayaran':
                                echo '<span class="badge bg-primary">Menunggu Pembayaran</span>';
                                break;
                            case 'Menunggu Konfirmasi':
                                echo '<span class="badge bg-info text-dark">Menunggu Konfirmasi</span>';
                                break;
                            case 'Ditolak Pembayaran':
                                echo '<span class="badge bg-danger">Ditolak Pembayaran</span>';
                                break;
                            case 'Disetujui':
                                echo '<span class="badge bg-success">Disetujui</span>';
                                break;
                            case 'Menunggu Pengembalian':
                                echo '<span class="badge bg-secondary">Menunggu Pengembalian</span>';
                                break;
                            case 'Selesai':
                                echo '<span class="badge bg-success">Selesai</span>';
                                break;
                            case 'Dibatalkan':
                                echo '<span class="badge bg-danger">Dibatalkan</span>';
                                break;
                            case 'Masa Sewa Berakhir':
                                echo '<span class="badge bg-danger">Masa Sewa Berakhir</span>';
                                break;
                            default:
                                echo '<span class="badge bg-secondary">'.$p['status'].'</span>';
                        }
                        ?>
                    </td>

                    <td class="text-center">

                        <?php if (!empty($p['bukti'])): ?>
                            <a href="../assets/uploads/<?= $p['bukti']; ?>" target="_blank">
                                <img src="../assets/uploads/<?= $p['bukti']; ?>"
                                     class="rounded border mb-2"
                                     style="width:80px;height:80px;object-fit:cover;">
                            </a>
                        <?php endif; ?>

                        <?php if (
                            $p['status'] == 'Menunggu Pembayaran' ||
                            $p['status'] == 'Ditolak Pembayaran' ||
                            $p['status'] == 'Menunggu Konfirmasi'
                        ): ?>

                            <a href="upload-bukti.php?id=<?= $p['id_pesanan']; ?>" 
                            class="btn btn-warning btn-sm">
                                <?= $p['status'] == 'Ditolak Pembayaran'
                                    ? 'Upload Ulang Bukti'
                                    : 'Upload Bukti'; ?>
                            </a>

                        <?php elseif ($p['status'] == 'Dibatalkan'): ?>

                            <span class="badge bg-danger">
                                Tidak ada pembayaran
                            </span>

                        <?php endif; ?>


                    </td>

                    <td>
                        <?php if ($p['status'] == 'Menunggu Pembayaran'): ?>

                            <a href="edit-pesanan.php?id=<?= $p['id_pesanan']; ?>"
                            class="btn btn-sm btn-warning mb-1 d-block">
                                Edit Durasi
                            </a>

                            <a href="batalkan-pesanan.php?id=<?= $p['id_pesanan']; ?>"
                            onclick="return confirm('Yakin ingin membatalkan pesanan ini?');"
                            class="btn btn-sm btn-danger d-block">
                                Batalkan Pesanan
                            </a>

                        <?php else: ?>
                            <span class="text-muted">Tidak bisa diedit</span>
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
