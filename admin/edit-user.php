<?php
require "../functions.php";

session_start();
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil ID user
$id = $_GET["id"];

// Ambil data user
$user = query("SELECT * FROM tb_user WHERE id_user = $id")[0];

// Proses update
if (isset($_POST["simpan"])) {

    $nama  = htmlspecialchars($_POST["nama"]);
    $email = htmlspecialchars($_POST["email"]);
    $role  = $_POST["role"];
    $password = $_POST["password"];

    // cek email duplikat kecuali email miliknya sendiri
    $cek = mysqli_query($conn, "SELECT * FROM tb_user WHERE email='$email' AND id_user != $id");

    if (mysqli_num_rows($cek) > 0) {
        echo "<script>
                alert('Email sudah digunakan pengguna lain!');
                document.location.href = 'edit-user.php?id=$id';
              </script>";
        exit;
    }

    // Jika password diisi → update password
    if (!empty($password)) {
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "
            UPDATE tb_user SET 
                nama='$nama',
                email='$email',
                role='$role',
                password='$pass_hash'
            WHERE id_user=$id
        ";
    } else {
        // Jika password kosong → tidak update password
        $query = "
            UPDATE tb_user SET 
                nama='$nama',
                email='$email',
                role='$role'
            WHERE id_user=$id
        ";
    }

    if (mysqli_query($conn, $query)) {
        echo "<script>
                alert('Data user berhasil diperbarui!');
                document.location.href = 'user.php';
              </script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<?php require "../assets/header.php"; ?>

<div class="container mt-4" style="max-width: 700px;">

    <h3 class="mb-4">Edit User / Admin</h3>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="post">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $user['nama']; ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="<?= $user['email']; ?>" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="user"  <?= $user['role'] == "user" ? "selected" : ""; ?>>User</option>
                        <option value="admin" <?= $user['role'] == "admin" ? "selected" : ""; ?>>Admin</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Password Baru 
                        <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>
                    </label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">Simpan Perubahan</button>
                <a href="user.php" class="btn btn-secondary">Kembali</a>

            </form>

        </div>
    </div>

</div>

<?php require "../assets/footer.php"; ?>
