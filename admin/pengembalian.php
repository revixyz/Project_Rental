<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

/* =======================================================
   PROSES SETUJUI / TOLAK PENGEMBALIAN
======================================================= */
if (isset($_POST['setujui'])) {

    $id_pengembalian = $_POST['id_pengembalian'];
    $id_pesanan      = $_POST['id_pesanan'];
    $id_laptop       = $_POST['id_laptop'];
    $denda           = $_POST['denda'];
    $catatan_admin   = $_POST['catatan_admin'];

    // Update data pengembalian
    mysqli_query($conn, "
        UPDATE tb_pengembalian
        SET denda='$denda', catatan_admin='$catatan_admin', status='Disetujui'
        WHERE id_pengembalian='$id_pengembalian'
    ");

    // Ubah status pesanan
    mysqli_query($conn, "
        UPDATE tb_pesanan SET status='Selesai'
        WHERE id_pesanan='$id_pesanan'
    ");

    // Kembalikan stok laptop
    mysqli_query($conn, "
        UPDATE tb_laptop SET status='Tersedia', stok = stok + 1
        WHERE id_laptop='$id_laptop'
    ");

    $_SESSION['pesan'] = "Pengembalian telah disetujui!";
    header("Location: pengembalian.php");
    exit;

}

if (isset($_POST['tolak'])) {

    $id_pengembalian = $_POST['id_pengembalian'];
    $catatan_admin   = $_POST['catatan_admin'];

    mysqli_query($conn, "
        UPDATE tb_pengembalian
        SET status='Ditolak', catatan_admin='$catatan_admin'
        WHERE id_pengembalian='$id_pengembalian'
    ");

    $_SESSION['pesan'] = "Pengembalian telah ditolak!";
    header("Location: pengembalian.php");
    exit;
}

/* =======================================================
   AMBIL DATA
======================================================= */

// Ambil semua pengembalian
$sql = "
    SELECT 
        k.*,
        p.id_laptop,
        p.tanggal_sewa,
        p.durasi,
        l.nama AS nama_laptop,
        u.nama AS nama_user
    FROM tb_pengembalian k
    JOIN tb_pesanan p ON k.id_pesanan = p.id_pesanan
    JOIN tb_laptop l ON p.id_laptop = l.id_laptop
    JOIN tb_user u ON p.id_user = u.id_user
    ORDER BY k.id_pengembalian DESC
";
$data = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Data Pengembalian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require "../assets/header.php"; ?>

<div class="container mt-5">

    <h3 class="mb-4">Data Pengembalian Laptop</h3>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-info"><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></div>
    <?php endif; ?>

    <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Laptop</th>
                <th>Tgl Kembali</th>
                <th>Denda (User)</th>
                <th>Kondisi</th>
                <th>Catatan User</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        <?php 
            $no = 1; 
            $modal_list = "";
        ?>
        <?php foreach ($data as $k): ?>

            <?php
                $modal_id = "modalAksi" . $k['id_pengembalian'];
            ?>

            <tr>
                <td><?= $no++; ?></td>
                <td><?= $k['nama_user']; ?></td>
                <td><?= $k['nama_laptop']; ?></td>
                <td><?= $k['tanggal_kembali']; ?></td>
                <td>Rp<?= number_format($k['denda']); ?></td>
                <td><?= $k['kondisi_laptop']; ?></td>
                <td><?= $k['catatan_admin']; ?></td>
                <td>
                    <?php if ($k['status'] == "Pending"): ?>
                        <span class="badge bg-warning text-dark">Menunggu</span>
                    <?php elseif ($k['status'] == "Disetujui"): ?>
                        <span class="badge bg-success">Disetujui</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Ditolak</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button class="btn btn-primary btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#<?= $modal_id; ?>">
                        Proses
                    </button>
                </td>
            </tr>

            <?php
            // MODAL
            $modal_list .= '
            <div class="modal fade" id="'.$modal_id.'" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Proses Pengembalian</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <input type="hidden" name="id_pengembalian" value="'.$k['id_pengembalian'].'">
                            <input type="hidden" name="id_pesanan" value="'.$k['id_pesanan'].'">
                            <input type="hidden" name="id_laptop" value="'.$k['id_laptop'].'">

                            <strong>User:</strong> '.$k['nama_user'].'<br>
                            <strong>Laptop:</strong> '.$k['nama_laptop'].'<br>
                            <strong>Tanggal Kembali:</strong> '.$k['tanggal_kembali'].'<br>
                            <hr>

                            <label class="form-label fw-bold">Denda</label>
                            <input type="number" name="denda" class="form-control mb-2" value="'.$k['denda'].'" required>

                            <label class="form-label fw-bold">Catatan Admin</label>
                            <textarea name="catatan_admin" class="form-control">'.$k['catatan_admin'].'</textarea>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-danger" name="tolak">Tolak</button>
                            <button class="btn btn-success" name="setujui">Setujui</button>
                        </div>

                    </form>
                </div>
            </div>
            ';
            ?>

        <?php endforeach; ?>
        </tbody>

    </table>
    </div>

</div>

<?= $modal_list; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
