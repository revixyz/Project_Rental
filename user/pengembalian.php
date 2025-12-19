<?php
session_start();
require "../config/database.php";

/* =========================================
   CEK LOGIN USER
========================================= */
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION["id_user"];

/* =========================================
   UPDATE STATUS JIKA SUDAH JATUH TEMPO
========================================= */
mysqli_query($conn, "
    UPDATE tb_pesanan
    SET status = 'Masa Sewa Berakhir'
    WHERE id_user = '$id_user'
    AND status = 'Disetujui'
    AND DATE_ADD(tanggal_sewa, INTERVAL durasi DAY) < CURDATE()
");

/* =========================================
   PROSES AJUKAN PENGEMBALIAN
========================================= */
if (isset($_POST['ajukan'])) {

    $id_pesanan = $_POST['id_pesanan'];
    $tanggal_kembali = date('Y-m-d');

    // Cegah pengajuan ganda
    $cek = mysqli_query($conn, "
        SELECT * FROM tb_pengembalian 
        WHERE id_pesanan='$id_pesanan'
    ");

    if (mysqli_num_rows($cek) == 0) {

        // Insert pengembalian (TANPA denda & kondisi)
        mysqli_query($conn, "
            INSERT INTO tb_pengembalian (id_pesanan, tanggal_kembali, status)
            VALUES ('$id_pesanan', '$tanggal_kembali', 'Pending')
        ");

        // Update status pesanan
        mysqli_query($conn, "
            UPDATE tb_pesanan 
            SET status='Menunggu Pengembalian'
            WHERE id_pesanan='$id_pesanan'
        ");

        $_SESSION['pesan'] = "Pengembalian berhasil diajukan. Silakan kembalikan laptop ke admin.";
    } else {
        $_SESSION['pesan'] = "Pengembalian sudah diajukan sebelumnya.";
    }

    header("Location: pengembalian.php");
    exit;
}

/* =========================================
   AMBIL PESANAN AKTIF USER
========================================= */
$sql = "
    SELECT 
        p.id_pesanan,
        p.tanggal_sewa,
        p.durasi,
        p.status,
        l.nama AS nama_laptop
    FROM tb_pesanan p
    JOIN tb_laptop l ON p.id_laptop = l.id_laptop
    WHERE p.id_user='$id_user'
    AND p.status IN ('Disetujui', 'Masa Sewa Berakhir')
    ORDER BY p.id_pesanan DESC
";
$data = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengembalian Laptop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require "../assets/user-header.php"; ?>

<div class="container mt-5">

    <h3 class="mb-4">Pengembalian Laptop</h3>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="alert alert-info">
            <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
        </div>
    <?php endif; ?>

    <div class="table-responsive shadow-sm">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Laptop</th>
                    <th>Tanggal Sewa</th>
                    <th>Durasi</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

            <?php
            $no = 1;

            if ($data->num_rows == 0): ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        Tidak ada laptop yang sedang disewa.
                    </td>
                </tr>
            <?php else: ?>

                <?php foreach ($data as $p): ?>
                    <?php
                        $jatuh_tempo = date('Y-m-d', strtotime($p['tanggal_sewa'] . " + {$p['durasi']} days"));
                    ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $p['nama_laptop']; ?></td>
                        <td><?= $p['tanggal_sewa']; ?></td>
                        <td><?= $p['durasi']; ?> hari</td>
                        <td><?= $jatuh_tempo; ?></td>
                        <td class="text-center">
                            <?php if ($p['status'] == 'Masa Sewa Berakhir'): ?>
                                <span class="badge bg-danger">Masa Sewa Berakhir</span>
                            <?php else: ?>
                                <span class="badge bg-success">Sedang Disewa</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Yakin ingin mengajukan pengembalian?');">
                                <input type="hidden" name="id_pesanan" value="<?= $p['id_pesanan']; ?>">

                                <?php if ($p['status'] == 'Masa Sewa Berakhir'): ?>
                                    <div class="text-danger small mb-1">
                                        ⚠ Sudah jatuh tempo, denda akan dihitung admin
                                    </div>
                                <?php endif; ?>

                                <button type="submit" name="ajukan" class="btn btn-warning btn-sm">
                                    Ajukan Pengembalian
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php endif; ?>

            </tbody>
        </table>
    </div>

    <div class="alert alert-secondary mt-4">
        <strong>Catatan:</strong><br>
        Pengajuan pengembalian dapat dilakukan kapan saja. 
        Denda hanya akan dikenakan jika pengembalian melebihi tanggal jatuh tempo 
        dan akan dihitung oleh admin setelah laptop diterima secara fisik.
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
