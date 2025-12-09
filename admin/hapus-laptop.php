<?php
require "../functions.php";

$id = $_GET["id"];

mysqli_query($conn, "DELETE FROM laptop WHERE id = $id");

echo "<script>
        alert('Data berhasil dihapus!');
        document.location.href = 'laptop.php';
      </script>";
