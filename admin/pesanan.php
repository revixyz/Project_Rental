<?php
require "../functions.php";
require "../assets/header.php";

// Ambil semua data pesanan dengan LEFT JOIN dan COALESCE agar teks default langsung dari SQL
$pesanan = query("
    SELECT 
        p.id, 
        COALESCE(u.nama, 'User tidak ada') AS user, 
        COALESCE(l.nama, 'Laptop tidak ada') AS laptop, 
        p.tanggal_sewa, 
        p.durasi,
        p.total_harga,
        p.status,
        p.bukti
    FROM pesanan p
    LEFT JOIN user u ON p.id_user = u.id
    LEFT JOIN laptop l ON p.id_laptop = l.id
    ORDER BY p.id DESC
");
?>

<h3>Kelola Pesanan</h3>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Laptop</th>
            <th>Tanggal Sewa</th>
            <th>Durasi (hari)</th>
            <th>Total Harga</th>
            <th>Status</th>
            <th>bukti</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($pesanan) > 0): ?>
            <?php $no = 1;
            foreach ($pesanan as $p): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $p['user']; ?></td>
                    <td><?= $p['laptop']; ?></td>
                    <td><?= $p['tanggal_sewa']; ?></td>
                    <td><?= $p['durasi']; ?></td>
                    <td>Rp <?= number_format($p['total_harga']); ?></td>
                    <td><?= $p['status']; ?></td>

                    <td class="text-center">
                        <?php if (!empty($p['bukti'])): ?>
                            <a href="../assets/bukti/<?= $p['bukti']; ?>" target="_blank">
                                <img src="../assets/bukti/<?= $p['bukti']; ?>" width="60" class="rounded shadow">
                            </a>
                        <?php else: ?>
                            <span class="badge bg-secondary">Belum Ada</span>
                        <?php endif; ?>
                    </td>

                    <td>

                        <a href="ubah-status.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-warning">
                            Ubah Status
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9" class="text-center">Belum ada pesanan</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php require "../assets/footer.php"; ?>