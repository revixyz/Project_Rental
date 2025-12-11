<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION["id_user"];
$today   = date('Y-m-d');

/* ============================================
   PROSES KIRIM FORM PENGEMBALIAN
============================================ */
if (isset($_POST['kembalikan'])) {

    $id_pesanan      = $_POST['id_pesanan'];
    $id_laptop       = $_POST['id_laptop'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $kondisi         = $_POST['kondisi'];
    $catatan         = $_POST['catatan'];
    $denda           = $_POST['denda_dihitung'];

    // Tambah data pengembalian
    mysqli_query($conn, "
        INSERT INTO tb_pengembalian (id_pesanan, tanggal_kembali, denda, kondisi_laptop, catatan_admin)
        VALUES ('$id_pesanan', '$tanggal_kembali', '$denda', '$kondisi', '$catatan')
    ");

    // Update status pesanan
    mysqli_query($conn, "
        UPDATE tb_pesanan 
        SET status='Selesai' 
        WHERE id_pesanan='$id_pesanan'
    ");

    // Menambah stok laptop kembali
    mysqli_query($conn, "
        UPDATE tb_laptop 
        SET status='Tersedia', stok = stok + 1 
        WHERE id_laptop='$id_laptop'
    ");

    $_SESSION['pesan'] = "Pengembalian berhasil diproses!";
    header("Location: pengembalian.php");
    exit;
}

/* ============================================
   AMBIL DATA PESANAN YANG BISA DIKEMBALIKAN
============================================ */
$sql = "
    SELECT p.*, l.nama AS nama_laptop
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
    <div class="alert alert-info"><?= $_SESSION['pesan']; unset($_SESSION['pesan']); ?></div>
<?php endif; ?>

<div class="table-responsive shadow-sm">
<table class="table table-bordered table-striped align-middle">
    <thead class="table-primary">
        <tr>
            <th>No</th>
            <th>Laptop</th>
            <th>Tgl Sewa</th>
            <th>Durasi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>

<?php 
$no = 1; 
$modal_list = "";

if ($data->num_rows == 0): ?>

    <tr>
        <td colspan="6" class="text-center py-4">
            <div class="text-muted">
                <strong>Tidak ada pengembalian yang perlu dilakukan.</strong><br>
                Anda belum memiliki pesanan yang sedang disewa atau masa sewa berakhir.
            </div>
        </td>
    </tr>

<?php else: ?>

    <?php foreach ($data as $p): ?>

        <?php
            $jatuh_tempo = date('Y-m-d', strtotime($p['tanggal_sewa'] . " + {$p['durasi']} days"));
            $hari_telat = 0;
            $tarif_denda = 20000;
            $denda = 0;

            if ($today > $jatuh_tempo) {
                $hari_telat = (strtotime($today) - strtotime($jatuh_tempo)) / 86400;
                $denda = $hari_telat * $tarif_denda;
            }

            $modal_id = "modalKembali" . $p['id_pesanan'];
        ?>

        <tr>
            <td><?= $no++; ?></td>
            <td><?= $p['nama_laptop']; ?></td>
            <td><?= $p['tanggal_sewa']; ?></td>
            <td><?= $p['durasi']; ?> hari</td>
            <td>
                <?php if ($p['status'] == 'Disetujui'): ?>
                    <span class="badge bg-primary">Sedang Disewa</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Masa Sewa Berakhir</span>
                <?php endif; ?>
            </td>
            <td>
                <button 
                    class="btn btn-success btn-sm"
                    data-bs-toggle="modal"
                    data-bs-target="#<?= $modal_id; ?>">
                    Ajukan Pengembalian
                </button>
            </td>
        </tr>

        <?php
        /* ============================================
           GENERATE MODAL UNTUK MASING-MASING PESANAN
        ============================================= */
        $modal_list .= '
        <div class="modal fade" id="'.$modal_id.'" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Form Pengembalian</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="id_pesanan" value="'.$p['id_pesanan'].'">
                        <input type="hidden" name="id_laptop" value="'.$p['id_laptop'].'">

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

                        <div class="alert alert-warning">
                            <strong>Denda Keterlambatan:</strong><br>'.
                            ($denda > 0
                                ? 'Terlambat <strong>'.$hari_telat.' hari</strong><br>Total Denda: <strong>Rp'.number_format($denda).'</strong>'
                                : 'Tidak ada denda.'
                            ).'
                        </div>

                        <input type="hidden" name="denda_dihitung" value="'.$denda.'">

                        <div class="mb-2">
                            <label class="form-label">Catatan</label>
                            <textarea name="catatan" class="form-control"></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" name="kembalikan" class="btn btn-success">
                            Konfirmasi Pengembalian
                        </button>
                    </div>

                </form>
            </div>
        </div>';
        ?>

    <?php endforeach; ?>

<?php endif; ?>

</tbody>

</table>
</div>

</div>

<!-- OUTPUT SEMUA MODAL -->
<?= $modal_list; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
