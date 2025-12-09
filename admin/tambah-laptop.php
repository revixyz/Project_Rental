<?php
require "../functions.php";

if (isset($_POST["simpan"])) {

    $nama   = $_POST["nama"];
    $stok   = $_POST["stok"];
    $harga  = $_POST["harga"];

    $query = "INSERT INTO laptop (nama, stok, harga)
            VALUES ('$nama', '$stok', '$harga')";

    mysqli_query($conn, $query);

    echo "<script>
          alert('Laptop berhasil ditambahkan!');
          document.location.href = 'laptop.php';
        </script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Tambah Laptop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">

        <h2 class="mb-4">Tambah Laptop</h2>

        <form method="post">

            <div class="mb-3">
                <label>Nama Laptop</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Harga / Hari</label>
                <input type="number" name="harga" class="form-control" required>
            </div>

            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="laptop.php" class="btn btn-secondary">Kembali</a>

        </form>

    </div>

</body>

</html>