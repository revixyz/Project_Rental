<?php
require "../functions.php"; 

// HANYA ambil laptop yang memiliki stok > 0, karena kolom status adalah NULL
// MENGGUNAKAN KOLOM HARGA (H KAPITAL)
$laptop = query("SELECT id, nama, spesifikasi, Harga, stok FROM laptop WHERE stok > 0");
?>

<p>
    Rp<?= number_format($l["Harga"]); ?>/hari | 
    </p>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Laptop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    **<?= htmlspecialchars($l["nama"]); ?>** | 
                    Harga: **Rp<?= number_format($l["harga_per_hari"]); ?>/hari** | 
                    Status: <span class="badge bg-success"><?= $l["status"]; ?></span> |
                    Stok: **<?= $l["stok"]; ?>** unit
                </p>
                <p class="text-muted small">
                    Spesifikasi: <?= nl2br(htmlspecialchars($l["spesifikasi"])); ?>
                </p>
                
                <a href="pesanan.php?id_laptop=<?= $l['id']; ?>" class="btn btn-primary btn-sm mt-2" style="width: 150px;">
                    Sewa Sekarang
                </a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>
</body>
</html>