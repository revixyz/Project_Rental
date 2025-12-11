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
        tb_pesanan.id_pesanan,
        tb_pesanan.tanggal_sewa,
        tb_pesanan.durasi,
        tb_pesanan.total_harga,
        tb_pesanan.status,
        tb_pesanan.bukti,
        tb_laptop.nama AS nama_laptop,
        tb_laptop.spesifikasi
    FROM tb_pesanan
    JOIN tb_laptop ON tb_pesanan.id_laptop = tb_laptop.id_laptop
    WHERE tb_pesanan.id_user = $id_user
    ORDER BY tb_pesanan.id_pesanan DESC
";

$data = $conn->query($query);

$today = date('Y-m-d');

// AUTO UPDATE STATUS MASA SEWA HABIS
foreach ($data as $row) {

    $tanggal_selesai = date('Y-m-d', strtotime($row['tanggal_sewa'] . " + {$row['durasi']} days"));

    if ($today > $tanggal_selesai && $row['status'] == 'Disetujui') {
        $id_pesanan = $row['id_pesanan'];
        mysqli_query($conn, "UPDATE tb_pesanan 
                             SET status='Masa Sewa Berakhir' 
                             WHERE id_pesanan='$id_pesanan'");
    }
}

// reload data setelah update
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
                    <th>Bukti</th>
                </tr>
            </thead>

            <tbody>

            <!-- KONDISI JIKA DATA KOSONG -->
            <?php if ($data->num_rows == 0): ?>
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <strong>Tidak ada pesanan.</strong><br>
                        Anda belum memiliki pesanan di sistem.
                    </td>
                </tr>
            <?php endif; ?>

            <?php $no = 1; foreach ($data as $p): ?>

                <?php
                    // Hitung tanggal selesai
                    $tanggal_selesai = date('Y-m-d', strtotime($p['tanggal_sewa'] . " + {$p['durasi']} days"));
                ?>

                <!-- ALERT masa sewa berakhir -->
                <?php if ($p['status'] == 'Masa Sewa Berakhir'): ?>
                    <tr>
                        <td colspan="9">
                            <div class="alert alert-danger mb-0">
                                ⚠ Masa sewa laptop <strong><?= $p['nama_laptop']; ?></strong> telah berakhir.
                                Harap segera mengembalikan laptop.
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td><?= $no++; ?></td>

                    <td><strong><?= $p['nama_laptop']; ?></strong></td>

                    <td><small><?= nl2br($p['spesifikasi']); ?></small></td>

                    <td><?= $p['tanggal_sewa']; ?></td>

                    <td><?= $p['durasi']; ?> hari</td>

                    <td>Rp<?= number_format($p['total_harga']); ?></td>

                    <!-- STATUS -->
                    <td>
                        <?php
                            if ($p['status'] == 'Menunggu') {
                                echo '<span class="badge bg-warning text-dark">Menunggu</span>';
                            }
                            elseif ($p['status'] == 'Menunggu Pembayaran') {
                                echo '<span class="badge bg-primary">Menunggu Pembayaran</span>';
                            }
                            elseif ($p['status'] == 'Menunggu Konfirmasi') {
                                echo '<span class="badge bg-info text-dark">Menunggu Konfirmasi</span>';
                            }
                            elseif ($p['status'] == 'Disetujui') {
                                echo '<span class="badge bg-success">Disetujui</span>';
                            }
                            elseif ($p['status'] == 'Masa Sewa Berakhir') {
                                echo '<span class="badge bg-danger">Masa Sewa Berakhir</span>';
                            }
                            elseif ($p['status'] == 'Menunggu Pengembalian') {
                                echo '<span class="badge bg-info">Menunggu Pengembalian</span>';
                            }
                            elseif ($p['status'] == 'Selesai') {
                                echo '<span class="badge bg-success">Selesai</span>';
                            }
                            elseif ($p['status'] == 'Ditolak') {
                                echo '<span class="badge bg-danger">Ditolak</span>';
                            }
                        ?>
                    </td>

                    <!-- BUKTI PEMBAYARAN -->
                    <td>
                        <?php if ($p['status'] == 'Menunggu Pembayaran' && empty($p['bukti'])): ?>

                            <a href="upload-bukti.php?id=<?= $p['id_pesanan']; ?>" 
                               class="btn btn-primary btn-sm">Upload Bukti</a>

                        <?php elseif (!empty($p['bukti'])): ?>

                            <a href="../assets/uploads/<?= $p['bukti']; ?>" target="_blank">
                                <img src="../assets/uploads/<?= $p['bukti']; ?>" 
                                     class="rounded border"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            </a>

                        <?php else: ?>
                            <span class="badge bg-secondary">-</span>
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
