<?php
require "../functions.php";

session_start();
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST["simpan"])) {

    $nama     = htmlspecialchars($_POST["nama"]);
    $email    = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $role     = $_POST["role"]; // admin atau user

    // Cek email sudah dipakai atau belum
    $cek = mysqli_query($conn, "SELECT * FROM tb_user WHERE email='$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Email sudah digunakan! Silakan gunakan email lain.');
                document.location.href = 'tambah-user.php';
              </script>";
        exit;
    }

    $query = "
        INSERT INTO tb_user (nama, email, password, role)
        VALUES ('$nama', '$email', '$password', '$role')
    ";

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('User/Admin berhasil ditambahkan!');
                document.location.href = 'user.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan user!');
              </script>";
    }
}
?>

<?php require "../assets/header.php"; ?>

<div class="container mt-4" style="max-width: 700px;">

    <h3 class="mb-4">Tambah User / Admin</h3>

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-body">

            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama lengkap">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Masukkan email">
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                </div>

                <div class="mb-3">
                    <label class="form-label">Role / Level</label>
                    <select name="role" class="form-control" required>
                        <option value="user">User Biasa</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                <a href="user.php" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>

</div>

<?php require "../assets/footer.php"; ?>
