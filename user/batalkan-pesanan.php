<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION["id_user"];

if (!isset($_GET['id'])) {
    header("Location: pesanan-saya.php");
    exit;
}

$id_pesanan = intval($_GET['id']);

$data = mysqli_query($conn, "
    SELECT id_laptop, status 
    FROM tb_pesanan 
    WHERE id_pesanan = $id_pesanan 
    AND id_user = $id_user
");

$p = mysqli_fetch_assoc($data);

if (!$p) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location='pesanan-saya.php';</script>";
    exit;
}

if ($p['status'] != 'Menunggu Pembayaran') {
    echo "<script>alert('Pesanan tidak bisa dibatalkan!'); window.location='pesanan-saya.php';</script>";
    exit;
}

mysqli_query($conn, "
    UPDATE tb_pesanan 
    SET status = 'Dibatalkan' 
    WHERE id_pesanan = $id_pesanan
");

mysqli_query($conn, "
    UPDATE tb_laptop 
    SET stok = stok + 1 
    WHERE id_laptop = {$p['id_laptop']}
");

echo "<script>
    alert('Pesanan berhasil dibatalkan.');
    window.location='pesanan-saya.php';
</script>";
exit;
