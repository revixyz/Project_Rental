<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$tarif_denda = 20000; // denda per hari

/* =====================================================
   PROSES SETUJUI PENGEMBALIAN (AUTO HITUNG DENDA)
===================================================== */
if (isset($_POST['setujui'])) {

    $id_pesanan      = $_POST['id_pesanan'];
    $id_laptop       = $_POST['id_laptop'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $kondisi         = $_POST['kondisi'];
    $catatan_admin   = $_POST['catatan_admin'];

    // Ambil data pesanan
    $q = mysqli_query($conn, "
        SELECT tanggal_sewa, durasi 
        FROM tb_pesanan 
        WHERE id_pesanan='$id_pesanan'
    ");
    $p = mysqli_fetch_assoc($q);

    $jatuh_tempo = date('Y-m-d', strtotime($p['tanggal_sewa'] . " + {$p['durasi']} days"));

    // Hitung denda otomatis
    $hari_telat = 0;
    $denda = 0;

    if ($tanggal_kembali > $jatuh_tempo) {
        $hari_telat = (strtotime($tanggal_kembali) - strtotime($jatuh_tempo)) / 86400;
        $denda = $hari_telat * $tarif_denda;
    }

    // Simpan pengembalian
    mysqli_query($conn, "
        INSERT INTO tb_pengembalian
        (id_pesanan, tanggal_kembali, denda, kondisi_laptop, catatan_admin, status)
        VALUES
        ('$id_pesanan', '$tanggal_kembali', '$denda', '$kondisi', '$catatan_admin', 'Disetujui')
    ");

    // Update pesanan
    mysqli_query($conn, "
        UPDATE tb_pesanan 
        SET status='Selesai'
        WHERE id_pesanan='$id_pesanan'
    ");

    // Update laptop
    mysqli_query($conn, "
        UPDATE tb_laptop 
        SET status='Tersedia', stok = stok + 1
        WHERE id_laptop='$id_laptop'
    ");

    $_SESSION['pesan'] = "Pengembalian berhasil diproses. Denda: Rp" . number_format($denda);
    header("Location: pengembalian.php");
    exit;
}

/* =====================================================
   AMBIL DATA PESANAN MENUNGGU PENGEMBALIAN
===================================================== */
$sql = "
    SELECT 
        p.id_pesanan,
        p.tanggal_sewa,
        p.durasi,
        l.id_laptop,
        l.nama AS nama_laptop,
        u.nama AS nama_user,
        DATE_ADD(p.tanggal_sewa, INTERVAL p.durasi DAY) AS jatuh_tempo
    FROM tb_pesanan p
    JOIN tb_laptop l ON p.id_laptop = l.id_laptop
    JOIN tb_user u ON p.id_user = u.id_user
    WHERE p.status = 'Menunggu Pengembalian'
    ORDER BY p.id_pesanan DESC
";
$data = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - Pengembalian Laptop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require "../assets/header.php"; ?>

<div class="container mt-5">

<h3 class="mb-4">Data Pengembalian Laptop</h3>

<?php if (isset($_SESSION['pesan'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
    </div>
<?php endif; ?>

<div class="table-responsive shadow-sm">
<table class="table table-bordered align-middle">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Laptop</th>
            <th>Tgl Sewa</th>
            <th>Jatuh Tempo</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>

<?php
$no = 1;
$modal_list = "";

if ($data->num_rows == 0): ?>
<tr>
    <td colspan="6" class="text-center text-muted py-4">
        Tidak ada pengembalian yang menunggu proses.
    </td>
</tr>
<?php else: ?>

<?php foreach ($data as $p):
    $modal_id = "modalProses" . $p['id_pesanan'];
?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= $p['nama_user']; ?></td>
    <td><?= $p['nama_laptop']; ?></td>
    <td><?= $p['tanggal_sewa']; ?></td>
    <td><?= $p['jatuh_tempo']; ?></td>
    <td>
        <button class="btn btn-primary btn-sm"
                data-bs-toggle="modal"
                data-bs-target="#<?= $modal_id; ?>">
            Proses
        </button>
    </td>
</tr>

<?php
/* =====================================================
   MODAL PROSES PENGEMBALIAN (ADMIN)
===================================================== */
$modal_list .= '
<div class="modal fade" id="'.$modal_id.'" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Proses Pengembalian</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" name="id_pesanan" value="'.$p['id_pesanan'].'">
                <input type="hidden" name="id_laptop" value="'.$p['id_laptop'].'">

                <p><strong>User:</strong> '.$p['nama_user'].'</p>
                <p><strong>Laptop:</strong> '.$p['nama_laptop'].'</p>
                <p><strong>Jatuh Tempo:</strong> '.$p['jatuh_tempo'].'</p>

                <div class="mb-2">
                    <label class="form-label">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" class="form-control" required>
                </div>

                <div class="mb-2">
                    <label class="form-label">Kondisi Laptop</label>
                    <select name="kondisi" class="form-control" required>
                        <option value="Baik">Baik</option>
                        <option value="Lecet Ringan">Lecet Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>

                <div class="alert alert-info">
                    Denda keterlambatan dihitung otomatis oleh sistem
                    (Rp<?= number_format($tarif_denda); ?>/hari).
                </div>

                <div class="mb-2">
                    <label class="form-label">Catatan Admin</label>
                    <textarea name="catatan_admin" class="form-control"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" name="setujui" class="btn btn-success">
                    Setujui Pengembalian
                </button>
            </div>

        </form>
    </div>
</div>
';
?>

<?php endforeach; ?>
<?php endif; ?>

    </tbody>
</table>
</div>

</div>

<?= $modal_list; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
