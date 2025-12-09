<?php
require "../functions.php";

$id = $_GET["id"];

// ambil data lama
$laptop = query("SELECT * FROM laptop WHERE id = $id")[0];

// proses update
if (isset($_POST["update"])) {

  $nama  = $_POST["nama"];
  $stok  = $_POST["stok"];
  $harga = $_POST["harga"];

  $query = "UPDATE laptop SET 
              nama = '$nama',
              stok = '$stok',
              harga = '$harga'
            WHERE id = $id";

  mysqli_query($conn, $query);

  echo "<script>
          alert('Data berhasil diupdate!');
          document.location.href = 'laptop.php';
        </script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Laptop</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

  <h2 class="mb-4">Edit Laptop</h2>

  <form method="post">

    <div class="mb-3">
      <label>Nama Laptop</label>
      <input type="text" name="nama" class="form-control" value="<?= $laptop["nama"]; ?>" required>
    </div>

    <div class="mb-3">
      <label>Stok</label>
      <input type="number" name="stok" class="form-control" value="<?= $laptop["stok"]; ?>" required>
    </div>

    <div class="mb-3">
      <label>Harga / Hari</label>
      <input type="number" name="harga" class="form-control" value="<?= $data["harga"]; ?>" required>
    </div>

    <button type="submit" name="update" class="btn btn-warning">Update</button>
    <a href="laptop.php" class="btn btn-secondary">Batal</a>

  </form>

</div>

</body>
</html>
