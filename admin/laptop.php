<?php
require "../functions.php";
require "../assets/header.php";

$data = query("SELECT * FROM tb_laptop");
?>

<h3 class="mb-4">Data Laptop</h3>

<a href="tambah-laptop.php" class="btn btn-primary mb-3">Tambah Laptop</a>

<table class="table table-bordered table-striped align-middle">
  <thead class="table-dark text-center">
    <tr>
      <th>No</th>
      <th>Gambar</th>
      <th>Nama Laptop</th>
      <th>Spesifikasi</th>
      <th>Stok</th>
      <th>Harga / Hari</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>

    <?php $no = 1; ?>
    <?php foreach ($data as $d): ?>
    <tr>
      <td class="text-center"><?= $no++; ?></td>

      <!-- Gambar Laptop -->
      <td class="text-center">
        <img src="../assets/laptop/<?= $d['foto']; ?>"
             alt="<?= $d['nama']; ?>"
             width="180"
             class="rounded">
      </td>

      <td><?= $d["nama"]; ?></td>

      <!-- Spesifikasi -->
      <td style="white-space: pre-line;"><?= $d["spesifikasi"]; ?></td>

      <td class="text-center"><?= $d["stok"]; ?></td>
      <td>Rp <?= number_format($d["harga"]); ?></td>

      <!-- Tombol Aksi -->
      <td class="text-center">

    <div class="d-flex justify-content-center gap-2">

        <a href="edit-laptop.php?id=<?= $d['id_laptop']; ?>" 
           class="btn btn-sm btn-warning">
           Edit
        </a>

        <a href="hapus-laptop.php?id=<?= $d['id_laptop']; ?>" 
           class="btn btn-sm btn-danger"
           onclick="return confirm('Yakin ingin menghapus laptop ini?');">
           Hapus
        </a>

    </div>

</td>

    </tr>
    <?php endforeach; ?>

  </tbody>
</table>

<?php require "../assets/footer.php"; ?>
