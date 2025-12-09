<?php
require "../functions.php";
require "../assets/header.php";

$data = query("SELECT * FROM laptop");
?>

<h3 class="mb-4">Data Laptop</h3>

<a href="tambah-laptop.php" class="btn btn-primary mb-3">+ Tambah Laptop</a>

<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>No</th>
      <th>Nama Laptop</th>
      <th>Stok</th>
      <th>Harga / Hari</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>

    <?php $no = 1; ?>
    <?php foreach ($data as $d): ?>
    <tr>
      <td><?= $no++; ?></td>
      <td><?= $d["nama"]; ?></td>
      <td><?= $d["stok"]; ?></td>
      <td>Rp <?= number_format($d["Harga"]); ?></td>
      <td>
        <a href="edit-laptop.php?id=<?= $d['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

        <a href="hapus-laptop.php?id=<?= $d['id']; ?>" 
           class="btn btn-danger btn-sm"
           onclick="return confirm('Yakin ingin menghapus data ini?');">
           Hapus
        </a>
      </td>
    </tr>
    <?php endforeach; ?>

  </tbody>
</table>

<?php require "../assets/footer.php"; ?>
