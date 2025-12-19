<?php
session_start();
require "../config/database.php";

// ==========================
// CEK LOGIN ADMIN
// ==========================
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

/* ==========================================================
   PROSES SETUJUI / TOLAK PEMBAYARAN
========================================================== */
if (isset($_GET['aksi'], $_GET['id'])) {

    $id_pesanan = intval($_GET['id']);
    $aksi = $_GET['aksi'];

    // Ambil data pesanan
    $q = mysqli_query($conn, "
        SELECT id_laptop 
        FROM tb_pesanan 
        WHERE id_pesanan = $id_pesanan
    ");
    $p = mysqli_fetch_assoc($q);

    if ($aksi === "setuju") {

        mysqli_query($conn, "
            UPDATE tb_pesanan 
            SET status='Disetujui' 
            WHERE id_pesanan = $id_pesanan
        ");

        mysqli_query($conn, "
            UPDATE tb_laptop 
            SET status='Disewa' 
            WHERE id_laptop = {$p['id_laptop']}
        ");

        $_SESSION['pesan'] = "Pembayaran berhasil disetujui.";

    } elseif ($aksi === "tolak") {

        mysqli_query($conn, "
            UPDATE tb_pesanan 
            SET status='Ditolak Pembayaran',
                bukti=NULL
            WHERE id_pesanan = $id_pesanan
        ");

        $_SESSION['pesan'] = "Bukti pembayaran ditolak. User dapat upload ulang.";
    }

    header("Location: pesanan.php");
    exit;
}

/* ==========================================================
   FILTER STATUS
========================================================== */
$status_filter = $_GET['status'] ?? '';
$where = "";

if (!empty($status_filter)) {
    $status_filter = mysqli_real_escape_string($conn, $status_filter);
    $where = "WHERE tb_pesanan.status = '$status_filter'";
}

/* ==========================================================
   AMBIL DATA PESANAN
========================================================== */
$data = $conn->query("
    SELECT 
        tb_pesanan.*,
        tb_user.nama AS nama_user,
        tb_laptop.nama AS nama_laptop
    FROM tb_pesanan
    JOIN tb_user ON tb_pesanan.id_user = tb_user.id_user
    JOIN tb_laptop ON tb_pesanan.id_laptop = tb_laptop.id_laptop
    $where
    ORDER BY tb_pesanan.id_pesanan DESC
");
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
    <h3 class="mb-3">Kelola Pesanan User</h3>

    <!-- PESAN NOTIFIKASI -->
    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
        </div>
    <?php endif; ?>

    <!-- FILTER STATUS -->
    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-4">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">-- Semua Status --</option>
                <option value="Menunggu Pembayaran" <?= $status_filter=='Menunggu Pembayaran'?'selected':''; ?>>
                    Menunggu Pembayaran
                </option>
                <option value="Menunggu Konfirmasi" <?= $status_filter=='Menunggu Konfirmasi'?'selected':''; ?>>
                    Menunggu Konfirmasi
                </option>
                <option value="Disetujui" <?= $status_filter=='Disetujui'?'selected':''; ?>>
                    Disetujui
                </option>
                <option value="Ditolak Pembayaran" <?= $status_filter=='Ditolak Pembayaran'?'selected':''; ?>>
                    Ditolak Pembayaran
                </option>
                <option value="Menunggu Pengembalian" <?= $status_filter=='Menunggu Pengembalian'?'selected':''; ?>>
                    Menunggu Pengembalian
                </option>
                <option value="Selesai" <?= $status_filter=='Selesai'?'selected':''; ?>>
                    Selesai
                </option>
            </select>
        </div>
    </form>

    <?php if ($status_filter): ?>
        <div class="alert alert-secondary py-2">
            Menampilkan pesanan dengan status:
            <strong><?= $status_filter; ?></strong>
            <a href="pesanan.php" class="ms-2">Reset</a>
        </div>
    <?php endif; ?>

    <!-- TABEL -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Laptop</th>
                    <th>Tanggal</th>
                    <th>Durasi</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Bukti</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
            <?php $no=1; foreach ($data as $p): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $p['nama_user']; ?></td>
                    <td><?= $p['nama_laptop']; ?></td>
                    <td><?= $p['tanggal_sewa']; ?></td>
                    <td><?= $p['durasi']; ?> hari</td>
                    <td>Rp<?= number_format($p['total_harga']); ?></td>

                    <td>
                        <?php
                            $badge = [
                                'Menunggu Pembayaran' => 'warning text-dark',
                                'Menunggu Konfirmasi' => 'info text-dark',
                                'Ditolak Pembayaran'  => 'danger',
                                'Masa Sewa Berakhir'  => 'danger',
                                'Ditolak'  => 'danger',
                                'Dibatalkan'  => 'danger',
                                'Disetujui'           => 'success',
                                'Menunggu Pengembalian' => 'primary',
                                'Selesai'             => 'success'
                            ];
                            $class = $badge[$p['status']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $class; ?>">
                            <?= $p['status']; ?>
                        </span>
                    </td>

                    <td>
                        <?php if ($p['bukti']): ?>
                            <a href="../assets/uploads/<?= $p['bukti']; ?>" target="_blank"
                               class="btn btn-sm btn-primary">Lihat</a>
                        <?php else: ?>
                            <small class="text-danger">Belum Upload</small>
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($p['status'] == 'Menunggu Konfirmasi'): ?>
                            <a href="?id=<?= $p['id_pesanan']; ?>&aksi=setuju"
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Setujui pembayaran?')">
                                Setujui
                            </a>
                            <a href="?id=<?= $p['id_pesanan']; ?>&aksi=tolak"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Tolak bukti pembayaran?')">
                                Tolak
                            </a>
                        <?php else: ?>
                            <small>-</small>
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
