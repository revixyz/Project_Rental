<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("ID pesanan tidak valid.");
}
$id_pesanan = intval($_GET['id']);

if (isset($_POST['upload'])) {

    $metode   = $_POST['metode'];
    $rekening = $_POST['rekening'];

    $file = $_FILES['bukti'];

    $allowed_ext  = ['jpg', 'jpeg', 'png'];
    $max_size     = 2 * 1024 * 1024; // 2MB

    $file_name = $file['name'];
    $file_tmp  = $file['tmp_name'];
    $file_size = $file['size'];
    $file_err  = $file['error'];

    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    //Error upload
    if ($file_err !== 0) {
        echo "<script>alert('Gagal upload file!'); window.history.back();</script>";
        exit;
    }

    //Bukan JPG / PNG
    if (!in_array($ext, $allowed_ext)) {
        echo "<script>
            alert('Bukti pembayaran hanya boleh JPG atau PNG!');
            window.history.back();
        </script>";
        exit;
    }

    //Ukuran terlalu besar
    if ($file_size > $max_size) {
        echo "<script>
            alert('Ukuran file maksimal 2MB!');
            window.history.back();
        </script>";
        exit;
    }

    $new_name = 'bukti_' . $id_pesanan . '_' . time() . '.' . $ext;
    $folder = "../assets/uploads/" . $new_name;

    move_uploaded_file($file_tmp, $folder);

    $status = "Menunggu Konfirmasi";

mysqli_query($conn, "
    UPDATE tb_pesanan SET 
        bukti='$new_name',
        metode_bayar='$metode',
        rekening_tujuan='$rekening',
        status='$status',
        is_locked=1
    WHERE id_pesanan=$id_pesanan
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

    <form method="POST" enctype="multipart/form-data">

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

        <div class="mb-3" id="rekeningBox" style="display:none;">
            <label class="form-label">Nomor Rekening Tujuan</label>
            <div class="input-group">
                <input type="text" id="rekening" name="rekening" class="form-control" readonly>
                <button type="button" class="btn btn-primary" onclick="copyRekening()">Copy</button>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Upload Bukti Pembayaran</label>
            <input type="file"
                   name="bukti"
                   class="form-control"
                   accept=".jpg,.jpeg,.png"
                   required>
            <small class="text-muted">Format JPG / PNG, maksimal 2MB</small>
        </div>

        <button type="submit" name="upload" class="btn btn-primary">Kirim</button>
        <a href="pesanan-saya.php" class="btn btn-secondary">Kembali</a>
    </form>

</div>

<script>
function showRekening() {
    const metode = document.getElementById("metode").value;
    const rekeningBox = document.getElementById("rekeningBox");
    const rekening = document.getElementById("rekening");

    const nomor = {
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
    const rekening = document.getElementById("rekening");
    navigator.clipboard.writeText(rekening.value);
    alert("Nomor rekening berhasil disalin!");
}
</script>

</body>
</html>
