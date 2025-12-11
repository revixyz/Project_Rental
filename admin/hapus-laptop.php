<?php
require "../functions.php";

// Ambil ID laptop
$id = $_GET["id"];

// Ambil data laptop untuk mendapatkan nama file foto
$laptop = query("SELECT * FROM tb_laptop WHERE id_laptop = $id")[0];

// Lokasi folder foto
$folder = "../assets/laptop/";

// Hapus foto jika ada
if (!empty($laptop['foto']) && file_exists($folder . $laptop['foto'])) {
    unlink($folder . $laptop['foto']);
}

// Hapus data dari database
mysqli_query($conn, "DELETE FROM tb_laptop WHERE id_laptop = $id");

// Kembali ke halaman laptop
echo "
    <script>
        alert('Laptop berhasil dihapus!');
        document.location.href = 'laptop.php';
    </script>
";
?>
