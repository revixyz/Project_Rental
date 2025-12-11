<?php
require "../functions.php";

// Ambil ID
$id = $_GET["id"];

// Ambil data lama laptop
$laptop = query("SELECT * FROM tb_laptop WHERE id_laptop = $id")[0];

// Proses update
if (isset($_POST["update"])) {

    $nama   = $_POST["nama"];
    $stok   = $_POST["stok"];
    $spesifikasi = $_POST["spesifikasi"];
    $harga  = $_POST["harga"];
    $fotoLama = $_POST["foto_lama"];

    // === Upload Foto ===
    $namaFile = $_FILES['foto']['name'];
    $tmpName  = $_FILES['foto']['tmp_name'];
    $error    = $_FILES['foto']['error'];

    $folder = "../assets/laptop/";

    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
    }

    // Jika user upload foto baru
    if ($error === 0) {
        $namaBaru = time() . "_" . $namaFile;
        move_uploaded_file($tmpName, $folder . $namaBaru);

        // Hapus foto lama jika ada
        if ($fotoLama != "" && file_exists($folder . $fotoLama)) {
            unlink($folder . $fotoLama);
        }
    } 
    else {
        $namaBaru = $fotoLama; // tetap pakai foto lama
    }

    // Update DB
    $query = "
        UPDATE tb_laptop SET 
            nama = '$nama',
            foto = '$namaBaru',
            spesifikasi = '$spesifikasi',
            harga = '$harga',
            stok = '$stok'
        WHERE id_laptop = '$id'
    ";

    mysqli_query($conn, $query);

    echo "
        <script>
            alert('Data laptop berhasil diperbarui!');
            document.location.href = 'laptop.php';
        </script>
    ";
}
?>



<?php require "../assets/header.php"; ?>

<body>

<div class="container mt-5">

    <h2 class="mb-4">Edit Data Laptop</h2>

    <form method="post" enctype="multipart/form-data">

        <input type="hidden" name="foto_lama" value="<?= $laptop['foto']; ?>">

        <div class="mb-3">
            <label class="form-label">Nama Laptop</label>
            <input type="text" 
                   name="nama" 
                   class="form-control" 
                   value="<?= $laptop['nama']; ?>" 
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Spesifikasi Laptop</label>
            <textarea name="spesifikasi" 
                      class="form-control" 
                      required><?= $laptop['spesifikasi']; ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Harga / Hari</label>
            <input type="number" 
                   name="harga" 
                   class="form-control" 
                   value="<?= $laptop['harga']; ?>" 
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Stok</label>
            <input type="number" 
                   name="stok" 
                   class="form-control" 
                   value="<?= $laptop['stok']; ?>" 
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Laptop Saat Ini:</label><br>
            <img src="../assets/laptop/<?= $laptop['foto']; ?>" 
                 width="200" 
                 class="rounded mb-2">
        </div>

        <div class="mb-3">
            <label class="form-label">Ganti Foto Laptop (opsional)</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>

        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="laptop.php" class="btn btn-secondary">Kembali</a>

    </form>

</div>

</body>