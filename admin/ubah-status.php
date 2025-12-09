<?php
require "../functions.php";

// Pastikan data dikirim via POST
if(isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Update status pesanan
    $query = "UPDATE pesanan SET status = '$status' WHERE id = $id";
    $update = mysqli_query($conn, $query);

    if($update) {
        // Jika berhasil, redirect kembali ke halaman pesanan
        header("Location: pesanan.php");
        exit;
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal mengubah status: " . mysqli_error($conn);
    }
} else {
    // Jika form tidak dikirim dengan benar
    echo "Data tidak lengkap.";
}
