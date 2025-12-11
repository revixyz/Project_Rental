<?php
require "../functions.php";

if (isset($_POST["simpan"])) {

    $nama   = $_POST["nama"];
    $stok   = $_POST["stok"];
    $spesifikasi = $_POST["spesifikasi"];
    $harga  = $_POST["harga"];

    // === Upload Foto ===
    $namaFile = $_FILES['foto']['name'];
    $tmpName  = $_FILES['foto']['tmp_name'];
    $error    = $_FILES['foto']['error'];

    // Folder untuk menyimpan gambar laptop
    $folder = "../assets/laptop/";

    // Buat folder jika belum ada
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    // Jika foto diupload
    if ($error === 0) {
        // Beri nama unik agar tidak bentrok
        $namaBaru = time() . "_" . $namaFile;

        move_uploaded_file($tmpName, $folder . $namaBaru);
    } else {
        $namaBaru = null; // jika tidak ada foto
    }

    // === Simpan ke Database ===
    $query = "INSERT INTO tb_laptop (nama, foto, spesifikasi, harga, stok)
          VALUES ('$nama', '$namaBaru', '$spesifikasi', '$harga', '$stok')";


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

        <form method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Nama Laptop</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Spesifikasi Laptop</label>
                <textarea name="spesifikasi" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Harga / Hari</label>
                <input type="number" name="harga" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Laptop</label>
                <input type="file" name="foto" class="form-control" accept="image/*" required>
            </div>

            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            <a href="laptop.php" class="btn btn-secondary">Kembali</a>

        </form>

    </div>

</body>
</html>
