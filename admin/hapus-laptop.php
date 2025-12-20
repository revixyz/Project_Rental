<?php
require "../functions.php";

$id = $_GET["id"];

$laptop = query("SELECT * FROM tb_laptop WHERE id_laptop = $id")[0];

$folder = "../assets/laptop/";

if (!empty($laptop['foto']) && file_exists($folder . $laptop['foto'])) {
    unlink($folder . $laptop['foto']);
}

mysqli_query($conn, "DELETE FROM tb_laptop WHERE id_laptop = $id");

echo "
    <script>
        alert('Laptop berhasil dihapus!');
        document.location.href = 'laptop.php';
    </script>
";
?>
