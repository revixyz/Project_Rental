<?php
require "../functions.php"; 
require_once "../config/database.php";

// Ambil data laptop (menggunakan kolom Harga sebagai harga_per_hari)
$laptop = query( "
    SELECT 
        id_laptop AS id,
        nama,
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
<head>
    <meta charset="UTF-8">
    <title>Daftar Laptop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="adminlte.css">
</head>
<body>

<div class="container mt-5">
    <h2>Daftar Laptop</h2>

    <?php if (empty($laptop)): ?>
        <p class="alert alert-info">Maaf, saat ini tidak ada laptop yang tersedia untuk disewa.</p>
    <?php else: ?>
        <?php foreach($laptop as $l): ?>
            <div class="card mb-3 p-3 shadow-sm">
                <p>
                    <strong><?= htmlspecialchars($l["nama"]); ?></strong> | 
                    Harga: <strong>Rp<?= number_format($l["harga_per_hari"]); ?>/hari</strong> |
                    Status: <span class="badge bg-success"><?= htmlspecialchars($l["status"]); ?></span> |
                    Stok: <strong><?= $l["stok"]; ?></strong> unit
                </p>

                <p class="text-muted small">
                    Spesifikasi: <?= $l["spesifikasi"]; ?>
                </p>
                
                <a href="pesanan.php?id_laptop=<?= $l['id']; ?>" 
                   class="btn btn-primary btn-sm mt-2" style="width: 150px;">
                    Sewa Sekarang
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>
</html>
