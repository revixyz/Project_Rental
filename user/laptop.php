<?php
require "../functions.php"; 
require_once "../config/database.php";

// Ambil data laptop (menggunakan kolom Harga sebagai harga_per_hari)
$laptop = query( "
    SELECT 
        id_laptop AS id,
        nama,
        foto,
        spesifikasi,
        Harga AS harga_per_hari,
        stok,
        COALESCE(status, 'Tersedia') AS status
    FROM tb_laptop
    WHERE stok > 0
");
?>

<?php require "../assets/user-header.php"; ?>

<!DOCTYPE html>
<html lang="id">
<body>

<div class="container mt-5">
    <h2>Daftar Laptop</h2>

    <?php if (empty($laptop)): ?>
        <p class="alert alert-info">Maaf, saat ini tidak ada laptop yang tersedia untuk disewa.</p>
    <?php else: ?>
        <?php foreach($laptop as $l): ?>

           <div class="card mb-3 p-3 shadow-sm">
    <div class="row g-3 align-items-center">

        <!-- FOTO LAPTOP -->
        <div class="col-md-3">
            <img src="../assets/laptop/<?= $l['foto']; ?>"
                 class="img-fluid rounded"
                 style="height: 180px; width: 100%; object-fit: cover;">
        </div>

        <!-- DETAIL LAPTOP -->
        <div class="col-md-9">

            <h5 class="mb-1"><strong><?= htmlspecialchars($l["nama"]); ?></strong></h5>

            <p class="mb-1">
                Harga: <strong>Rp<?= number_format($l["harga_per_hari"]); ?></strong> / hari |
                Status: <span class="badge bg-success"><?= htmlspecialchars($l["status"]); ?></span> |
                Stok: <strong><?= $l["stok"]; ?></strong> unit
            </p>

            <p class="text-muted small mb-2">
                <strong>Spesifikasi:</strong> <?= $l["spesifikasi"]; ?>
            </p>

            <a href="pesanan.php?id_laptop=<?= $l['id']; ?>" 
               class="btn btn-primary btn-sm mt-2"
               style="width: 150px;">
                Sewa Sekarang
            </a> 

        </div>

    </div>
</div>

        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>
</html>
