<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil ID pesanan
if (!isset($_GET['id'])) {
    die("ID pesanan tidak valid.");
}
$id_pesanan = $_GET['id'];

// Proses upload
if (isset($_POST['upload'])) {

    $metode       = $_POST['metode'];
    $rekening     = $_POST['rekening'];

    // Upload file
    $namaFile = $_FILES['bukti']['name'];
    $tmp      = $_FILES['bukti']['tmp_name'];
    $folder   = "../assets/uploads/" . $namaFile;

    move_uploaded_file($tmp, $folder);

    // Simpan ke database
    $status = "Menunggu Konfirmasi";
    $status = trim($status); // hilangkan spasi tak terlihat

    mysqli_query($conn, "
        UPDATE tb_pesanan SET 
            bukti='$namaFile',
            metode_bayar='$metode',
            rekening_tujuan='$rekening',
            status='$status'
        WHERE id_pesanan='$id_pesanan'
    ");


    $_SESSION['pesan'] = "Bukti pembayaran berhasil diupload!";
    header("Location: pesanan-saya.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Upload Bukti Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php require "../assets/user-header.php"; ?>

<div class="container mt-5">

    <h3 class="mb-3">Upload Bukti Pembayaran</h3>

    <form action="" method="POST" enctype="multipart/form-data">
        
        <!-- METODE PEMBAYARAN -->
        <div class="mb-3">
            <label class="form-label">Metode Pembayaran</label>
            <select name="metode" id="metode" class="form-select" required onchange="showRekening()">
                <option value="">-- Pilih Metode --</option>
                <option value="BCA">Transfer BCA</option>
                <option value="BRI">Transfer BRI</option>
                <option value="Dana">Dana</option>
                <option value="Gopay">Gopay</option>
            </select>
        </div>

        <!-- REKENING TUJUAN -->
        <div class="mb-3" id="rekeningBox" style="display:none;">
            <label class="form-label">Nomor Rekening Tujuan</label>

            <div class="input-group">
                <input type="text" id="rekening" name="rekening" class="form-control" readonly>

                <button type="button" class="btn btn-primary" onclick="copyRekening()">
                    Copy
                </button>
            </div>

            <small class="text-muted">Silakan transfer sesuai metode yang dipilih.</small>
        </div>

        <!-- UPLOAD BUKTI -->
        <div class="mb-3">
            <label class="form-label">Upload Bukti Pembayaran</label>
            <input type="file" name="bukti" class="form-control" required>
        </div>

        <button type="submit" name="upload" class="btn btn-primary">Kirim</button>
        <a href="pesanan-saya.php" class="btn btn-secondary">Kembali</a>
    </form>

</div>

<script>
// Daftar nomor rekening
function showRekening() {
    let metode = document.getElementById("metode").value;
    let rekeningBox = document.getElementById("rekeningBox");
    let rekening = document.getElementById("rekening");

    let nomor = {
        "BCA": "1234567890 (a.n Rental Laptop)",
        "BRI": "9876543210 (a.n Rental Laptop)",
        "Dana": "082112223333 (a.n Rental Laptop)",
        "Gopay": "082112223333 (a.n Rental Laptop)"
    };

    if (metode !== "") {
        rekening.value = nomor[metode];
        rekeningBox.style.display = "block";
    } else {
        rekeningBox.style.display = "none";
    }
}

function copyRekening() {
    let rekening = document.getElementById("rekening");
    navigator.clipboard.writeText(rekening.value);
    alert("Nomor rekening berhasil disalin!");
}
</script>

</body>
</html>
