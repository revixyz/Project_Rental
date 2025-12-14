<?php
require "../functions.php";
require "../assets/header.php";

$data = query("SELECT * FROM tb_laptop");
?>

<h3 class="mb-4">Data Laptop</h3>

<table class="table table-bordered table-striped align-middle">
  <thead class="table-dark text-center">
    <tr>
      <th>No</th>
      <th>Nama Laptop</th>
      <th>Spesifikasi</th>
      <th>Stok</th>
      <th>Harga / Hari</th>
    </tr>
  </thead>
  <tbody>

  <?php $no = 1; foreach ($data as $d): ?>
    <tr>
      <td class="text-center"><?= $no++; ?></td>
      <td><?= $d['nama']; ?></td>
      <td><?= $d['spesifikasi']; ?></td>

      <!-- STOK -->
      <td class="text-center">
        <?php if ($d['stok'] > 0): ?>
            <?= $d['stok']; ?>
        <?php else: ?>
            <span class="badge bg-danger">
                Stok tidak tersedia
            </span>
        <?php endif; ?>
      </td>

      <td>Rp <?= number_format($d['harga']); ?></td>
    </tr>
  <?php endforeach; ?>

  </tbody>
</table>

<?php require "../assets/footer.php"; ?>