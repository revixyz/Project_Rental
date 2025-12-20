<?php
require "functions.php"; 
require_once "config/database.php";

$laptop = query("
    SELECT 
        id_laptop AS id,
        nama,
        foto,
        spesifikasi,
        Harga AS harga_per_hari,
        stok,
        COALESCE(status, 'Tersedia') AS status
    FROM tb_laptop
    ORDER BY stok DESC
");
?>

<?php require "assets/unknow-header.php"; ?>

<!DOCTYPE html>
<html lang="id">
<body>

<div class="container mt-5">
    <h2>Daftar Laptop</h2>

    <?php if (empty($laptop)): ?>
        <p class="alert alert-info">
            Maaf, saat ini belum ada data laptop.
        </p>
    <?php else: ?>
        <?php foreach($laptop as $l): ?>

        <div class="card mb-3 p-3 shadow-sm">
            <div class="row g-3 align-items-center">

                <div class="col-md-3">
                    <img src="assets/laptop/<?= htmlspecialchars($l['foto']); ?>"
                         class="img-fluid rounded"
                         style="height:180px;width:100%;object-fit:cover;">
                </div>

                <div class="col-md-9">

                    <h5 class="mb-1">
                        <strong><?= htmlspecialchars($l["nama"]); ?></strong>
                    </h5>

                    <p class="mb-1">
                        Harga:
                        <strong>Rp<?= number_format($l["harga_per_hari"]); ?></strong> / hari |

                        <?php if ($l['stok'] <= 0): ?>
                            <span class="badge bg-danger">Stok Tidak Tersedia</span>
                        <?php else: ?>
                            <span class="badge bg-success">Stok Tersedia</span>
                        <?php endif; ?>

                        | Stok: <strong><?= $l["stok"]; ?></strong> unit
                    </p>

                    <p class="text-muted small mb-2">
                        <strong>Spesifikasi:</strong>
                        <?= htmlspecialchars($l["spesifikasi"]); ?>
                    </p>

                    <?php if ($l['stok'] <= 0): ?>

                        <button class="btn btn-secondary btn-sm mt-2" disabled>
                            Tidak Bisa Disewa
                        </button>
                    <?php else: ?>
                        
                        <a href="redirect-login.php?id_laptop=<?= $l['id'] ?>&from=laptop"
                          class="btn btn-warning btn-sm">
                          Sewa Sekarang
                        </a>

                    <?php endif; ?>

                </div>

            </div>
        </div>

        <?php endforeach; ?>
    <?php endif; ?>

</div>

</body>

<div class="modal fade" id="loginAlert" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">

      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title">Login Diperlukan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <p class="fs-5 mb-3">
          Untuk menyewa laptop, kamu harus login terlebih dahulu.
        </p>
        <p class="text-muted">
          Silakan login atau daftar jika belum punya akun.
        </p>
      </div>

      <div class="modal-footer justify-content-center">
        <a href="auth/login.php" class="btn btn-primary px-4">
          Login Sekarang
        </a>
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
          Batal
        </button>
      </div>

    </div>
  </div>
</div>


</html>
