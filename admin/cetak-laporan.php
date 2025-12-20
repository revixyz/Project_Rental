<?php
session_start();
require "../functions.php";


if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$bulan_filter = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

//detail transaksi bulanan
$sql_detail = "SELECT 
                p.tanggal_sewa, 
                u.nama AS nama_user, 
                l.nama AS nama_laptop, 
                p.total_harga AS sewa, 
                IFNULL(pg.denda, 0) AS denda
               FROM tb_pesanan p
               JOIN tb_user u ON p.id_user = u.id_user
               JOIN tb_laptop l ON p.id_laptop = l.id_laptop
               LEFT JOIN tb_pengembalian pg ON p.id_pesanan = pg.id_pesanan
               WHERE p.status = 'Selesai' 
               AND MONTH(p.tanggal_sewa) = '$bulan_filter' 
               AND YEAR(p.tanggal_sewa) = '$tahun_filter'
               ORDER BY p.tanggal_sewa ASC";
$result_detail = $conn->query($sql_detail);

//ringkasan tahunan
$sql_tahunan = "SELECT 
                SUM(p.total_harga) AS total_sewa_tahun, 
                SUM(IFNULL(pg.denda, 0)) AS total_denda_tahun
                FROM tb_pesanan p
                LEFT JOIN tb_pengembalian pg ON p.id_pesanan = pg.id_pesanan
                WHERE p.status = 'Selesai' 
                AND YEAR(p.tanggal_sewa) = '$tahun_filter'";
$result_tahunan = $conn->query($sql_tahunan)->fetch_assoc();

$total_sewa_thn  = $result_tahunan['total_sewa_tahun'] ?? 0;
$total_denda_thn = $result_tahunan['total_denda_tahun'] ?? 0;
$grand_total_thn = $total_sewa_thn + $total_denda_thn;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Keuangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 12px; }
        .table-container, .summary-card {
            border: 1px solid #dee2e6;
            border-radius: 0;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,.03);
        }
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">

<div class="container mt-4 mb-4">
    <h4 class="text-center mb-1">LAPORAN KEUANGAN RENTAL LAPTOP</h4>
    <p class="text-center mb-4">
        Periode: <strong><?= date('F', mktime(0,0,0,$bulan_filter,1)); ?> <?= $tahun_filter ?></strong>
    </p>

    <div class="table-responsive table-container mb-4">
        <table class="table table-bordered table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Tanggal Sewa</th>
                    <th>User</th>
                    <th>Laptop</th>
                    <th>Pendapatan Sewa</th>
                    <th>Pendapatan Denda</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $total_sewa_bln = 0;
                $total_denda_bln = 0;

                if ($result_detail->num_rows > 0):
                    while($row = $result_detail->fetch_assoc()):
                        $total_sewa_bln += $row['sewa'];
                        $total_denda_bln += $row['denda'];
                        $subtotal = $row['sewa'] + $row['denda'];
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal_sewa'])); ?></td>
                    <td><?= $row['nama_user']; ?></td>
                    <td><?= $row['nama_laptop']; ?></td>
                    <td>Rp<?= number_format($row['sewa'], 0, ',', '.'); ?></td>
                    <td class="<?= ($row['denda'] > 0) ? 'text-danger fw-bold' : ''; ?>">
                        Rp<?= number_format($row['denda'], 0, ',', '.'); ?>
                    </td>
                    <td class="fw-bold">Rp<?= number_format($subtotal, 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
                <tr class="table-secondary fw-bold">
                    <td colspan="4" class="text-end">TOTAL BULAN INI</td>
                    <td>Rp<?= number_format($total_sewa_bln, 0, ',', '.'); ?></td>
                    <td class="text-danger">Rp<?= number_format($total_denda_bln, 0, ',', '.'); ?></td>
                    <td class="table-dark text-white">Rp<?= number_format($total_sewa_bln + $total_denda_bln, 0, ',', '.'); ?></td>
                </tr>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">Tidak ada transaksi.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="row g-3">
        <div class="col-4">
            <div class="card summary-card border-primary h-100">
                <div class="card-header bg-primary text-white text-center">Total Sewa <?= $tahun_filter ?></div>
                <div class="card-body text-center">
                    <strong>Rp<?= number_format($total_sewa_thn, 0, ',', '.'); ?></strong>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card summary-card border-danger h-100">
                <div class="card-header bg-danger text-white text-center">Total Denda <?= $tahun_filter ?></div>
                <div class="card-body text-center">
                    <strong>Rp<?= number_format($total_denda_thn, 0, ',', '.'); ?></strong>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card summary-card border-success h-100">
                <div class="card-header bg-success text-white text-center">Total Pendapatan</div>
                <div class="card-body text-center">
                    <strong>Rp<?= number_format($grand_total_thn, 0, ',', '.'); ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-end">
        <p>Dicetak pada: <?= date('d-m-Y'); ?></p>
    </div>
</div>

</body>
</html>
