<?php
require "../functions.php";

session_start();
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "<script>
            alert('ID user tidak ditemukan!');
            document.location.href = 'user.php';
          </script>";
    exit;
}

$id = $_GET['id'];

// Cegah admin menghapus dirinya sendiri
if ($id == $_SESSION["id_user"]) {
    echo "<script>
            alert('Anda tidak dapat menghapus akun Anda sendiri!');
            document.location.href = 'user.php';
          </script>";
    exit;
}

$query = "DELETE FROM tb_user WHERE id_user = '$id'";

if (mysqli_query($conn, $query)) {
    echo "<script>
            alert('User berhasil dihapus!');
            document.location.href = 'user.php';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus user!');
            document.location.href = 'user.php';
          </script>";
}
?>
