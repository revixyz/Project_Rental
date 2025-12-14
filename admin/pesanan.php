<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

/* ==========================================================
   PROSES SETUJUI / TOLAK PEMBAYARAN (STATUS MENUNGGU KONFIRMASI)
   ========================================================== */
if (isset($_GET['aksi']) && isset($_GET['id'])) {

    $id_pesanan = $_GET['id'];
    $aksi = $_GET['aksi'];

    // Ambil ID laptop dari pesanan
    $q = mysqli_query($conn, "SELECT id_laptop FROM tb_pesanan WHERE id_pesanan='$id_pesanan'");
    $r = mysqli_fetch_assoc($q);
    $id_laptop = $r['id_laptop'];

    if ($aksi == "setuju") {

        // Update status menjadi Disetujui
        mysqli_query($conn, "
            UPDATE tb_pesanan 
            SET status='Disetujui' 
            WHERE id_pesanan='$id_pesanan'
        ");

        // Laptop diset jadi Disewa
        mysqli_query($conn, "
            UPDATE tb_laptop 
            SET status='Disewa' 
            WHERE id_laptop='$id_laptop'
        ");

        $_SESSION['pesan'] = "Pembayaran berhasil disetujui.";

    } elseif ($aksi == "tolak") {

        mysqli_query($conn, "
            UPDATE tb_pesanan 
            SET status='Ditolak' 
            WHERE id_pesanan='$id_pesanan'
        ");

        $_SESSION['pesan'] = "Pesanan ditolak.";
    }

    header("Location: pesanan.php");
    exit;
}

/* ===============================
   AMBIL SEMUA PESANAN 
   =============================== */
$sql = "
    SELECT 
        tb_pesanan.*,
        tb_user.nama AS nama_user,
        tb_laptop.nama AS nama_laptop
    FROM tb_pesanan
    JOIN tb_user ON tb_pesanan.id_user = tb_user.id_user
    JOIN tb_laptop ON tb_pesanan.id_laptop = tb_laptop.id_laptop
    ORDER BY tb_pesanan.id_pesanan DESC
";

$data = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<?php require "../assets/header.php"; ?>

<div class="container mt-5">

    <h3 class="mb-4">Kelola Pesanan User</h3>

    <!-- Notifikasi -->
    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Laptop</th>
                    <th>Tanggal Sewa</th>
                    <th>Durasi</th>
                    <th>Total Harga</th>
                    <th>Status</th>
                    <th>Bukti Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; foreach ($data as $p): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $p['nama_user']; ?></td>
                    <td><?= $p['nama_laptop']; ?></td>

                    <td><?= $p['tanggal_sewa']; ?></td>
                    <td><?= $p['durasi']; ?> hari</td>
                    <td>Rp<?= number_format($p['total_harga']); ?></td>

                    <!-- STATUS -->
                    <td>
                        <?php
                            if ($p['status'] == 'Menunggu Pembayaran') {
                                echo '<span class="badge bg-warning text-dark">Menunggu Pembayaran</span>';
                            }
                            elseif ($p['status'] == 'Menunggu Konfirmasi') {
                                echo '<span class="badge bg-info text-dark">Menunggu Konfirmasi</span>';
                            }
                            elseif ($p['status'] == 'Disetujui') {
                                echo '<span class="badge bg-success">Disetujui</span>';
                            }
                            elseif ($p['status'] == 'Ditolak') {
                                echo '<span class="badge bg-danger">Ditolak</span>';
                            }
                            else {
                                echo '<span class="badge bg-secondary">'.$p['status'].'</span>';
                            }
                        ?>
                    </td>

                    <!-- BUKTI PEMBAYARAN -->
                    <td>
                        <?php if (!empty($p['bukti'])): ?>
                            <a href="../assets/uploads/<?= $p['bukti']; ?>" 
                               target="_blank" class="btn btn-sm btn-primary">
                                Lihat Bukti
                            </a>
                        <?php else: ?>
                            <small class="text-danger">Belum Upload</small>
                        <?php endif; ?>
                    </td>

                    <!-- Aksi Admin -->
                    <td>

                        <!-- Admin hanya bisa menyetujui jika status = Menunggu Konfirmasi -->
                        <?php if ($p['status'] == 'Menunggu Konfirmasi'): ?>

                            <a href="pesanan.php?id=<?= $p['id_pesanan']; ?>&aksi=setuju"
                               onclick="return confirm('Setujui pembayaran ini?')"
                               class="btn btn-success btn-sm">
                                Setujui
                            </a>

                            <a href="pesanan.php?id=<?= $p['id_pesanan']; ?>&aksi=tolak"
                               onclick="return confirm('Tolak pesanan ini?')"
                               class="btn btn-danger btn-sm">
                                Tolak
                            </a>

                        <?php else: ?>
                            <small>Tidak ada aksi</small>
                        <?php endif; ?>

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </
