<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION["id_user"];

// Ambil data user
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tb_user WHERE id_user='$id_user'"));

/* ===========================================================
   PROSES UPDATE PROFIL
=========================================================== */
if (isset($_POST["simpan"])) {

    $nama   = $_POST["nama"];
    $email  = $_POST["email"];
    $pass   = $_POST["password"];

    // Jika password baru kosong → tetap gunakan password lama
    if ($pass == "") {
        $passBaru = $user["password"];
    } else {
        // Enkripsi password baru
        $passBaru = password_hash($pass, PASSWORD_DEFAULT);
    }

    // Update ke database
    mysqli_query($conn, "
        UPDATE tb_user 
        SET nama='$nama', email='$email', password='$passBaru'
        WHERE id_user='$id_user'
    ");

    $_SESSION["nama"] = $nama;

    echo "<script>
            alert('Profil berhasil diperbarui!');
            document.location.href = 'profil.php';
          </script>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Profil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css\" rel=\"stylesheet\">
</head>

<body>

<?php require "../assets/user-header.php"; ?>

<div class="container mt-5">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Profil</h4>
                </div>

                <div class="card-body">

                    <form method="post">

                        <!-- NAMA -->
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?= $user['nama']; ?>" required>
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= $user['email']; ?>" required>
                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">
                            <label class="form-label">Password Baru (Opsional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ganti password">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="profil.php" class="btn btn-secondary">Kembali</a>
                            <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
