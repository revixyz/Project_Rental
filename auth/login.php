<?php
session_start();
require "../functions.php";
require "../config/database.php"; 

// Jika user sudah login, cegah akses login
if (isset($_SESSION["login"])) {
    if ($_SESSION["role"] == "admin") {
        header("Location: ../admin/index.php");
        exit;
    } else {
        header("Location: ../user/index.php");
        exit;
    }
}

if (isset($_POST["login"])) {

    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $pass  = mysqli_real_escape_string($conn, $_POST["password"]);

    // Ambil user berdasarkan email
    $data = query("SELECT * FROM tb_user WHERE email = '$email'");

    // Cek email ada atau tidak
    if ($data) {

        $user = $data[0];

        // Cek password (jika sudah pakai hash, gunakan password_verify)
        if ($pass === $user["password"]) {

            // Simpan session
            $_SESSION["login"]   = true;
            $_SESSION["id_user"] = $user["id_user"];
            $_SESSION["nama"]    = $user["nama"];
            $_SESSION["role"]    = $user["role"];

            // Redirect sesuai role
            if ($user["role"] == "admin") {
                header("Location: ../admin/index.php");
                exit;
            } else {
                header("Location: ../user/index.php");
                exit;
            }

        } else {
            echo "<script>alert('Password salah!');</script>";
        }

    } else {
        echo "<script>alert('Email tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #4a90e2, #007bff);
        height: 100vh;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI';
    }

    .login-card {
        width: 380px;
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        animation: fadeIn .6s ease-out;
    }
    .brand-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: blue;
        margin-bottom: 5px;
        letter-spacing: 1px;
        text-transform: uppercase;
        justify-content: center;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .login-title {
        font-weight: 700;
        font-size: 1.6rem;
    }

    .register-link:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>

<div class="login-card">
    <div class="text-center brand-title">RENTAL LAPTOP</div>

    <h3 class="text-center login-title mb-4">Login</h3>

    <form method="post">

        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control mb-3" placeholder="Masukkan Email" required>

        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control mb-3" placeholder="Masukkan Password" required>

        <button type="submit" name="login" class="btn btn-primary w-100 mb-3">
            Login
        </button>

        <div class="text-center">
            <span>Belum punya akun?</span>
            <a href="register.php" class="register-link">Daftar di sini</a>
        </div>

    </form>

</div>

</body>
</html>
