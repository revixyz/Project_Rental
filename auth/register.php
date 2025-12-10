<?php
session_start();
require "../functions.php";
require "../config/database.php"; 
require "../assets/auth-header.php";

$success = "";
$error = "";

if (isset($_POST["register"])) {

    $nama     = htmlspecialchars($_POST["nama"]);
    $email    = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $confirm  = htmlspecialchars($_POST["confirm"]);

    // ---- VALIDASI ----
    if ($password !== $confirm) {
        $error = "Password dan Konfirmasi Password tidak sama!";
    } else {

        // Cek apakah email sudah digunakan
        $cek = query("SELECT * FROM tb_user WHERE email='$email'");
        if ($cek) {
            $error = "Email sudah terdaftar! Silakan gunakan email lain.";
        } else {

            // Insert user baru
            $insert = mysqli_query($conn, "
                INSERT INTO user (nama, email, password, role)
                VALUES ('$nama', '$email', '$password', 'user')
            ");

            if ($insert) {
                $success = "Akun berhasil dibuat! Silakan login.";
            } else {
                $error = "Gagal membuat akun.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #4a90e2, #007bff);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI';
        }

        .register-card {
            width: 420px;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            animation: fadeIn .6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .register-title {
            font-weight: 700;
            font-size: 1.6rem;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="register-card">

    <h3 class="text-center register-title mb-4">Buat Akun Baru</h3>

    <!-- Pesan Sukses -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php endif; ?>

    <!-- Pesan Error -->
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="post">

        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control mb-3" placeholder="Nama lengkap" required>

        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control mb-3" placeholder="Alamat email" required>

        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control mb-3" placeholder="Buat password" required>

        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="confirm" class="form-control mb-3" placeholder="Ulangi password" required>

        <button type="submit" name="register" class="btn btn-success w-100 mb-3">
            Daftar Sekarang
        </button>

        <div class="text-center">
            <span>Sudah punya akun?</span>
            <a href="login.php" class="login-link">Login di sini</a>
        </div>

    </form>

</div>

</body>
</html>
