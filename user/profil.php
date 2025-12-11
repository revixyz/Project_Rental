<?php
session_start();
require "../functions.php";
require "../config/database.php";

// Proteksi halaman: hanya user yang login
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "user") {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil ID user dari session
$id_user = $_SESSION["id_user"];

// Ambil data user
$user = query("SELECT * FROM tb_user WHERE id_user = $id_user LIMIT 1");

if (!$user) {
    echo "<script>alert('Data user tidak ditemukan!'); window.location='../auth/login.php';</script>";
    exit;
}

$user = $user[0];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Profil Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .profile-card {
            max-width: 450px;
            margin: auto;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            animation: fadeIn .6s ease-out;
            background: white;
        }

        .profile-header {
            background: linear-gradient(135deg, #007bff, #0056d2);
            padding: 40px 20px;
            color: white;
            text-align: center;
        }

        .profile-header img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 4px solid white;
            margin-bottom: 15px;
            object-fit: cover;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body style="background: #f4f6f9;">

    <!-- Header User -->
    <?php require "../assets/user-header.php"; ?>

    <div class="container mt-5">

        <div class="profile-card">

            <!-- Header Profil -->
            <div class="profile-header">
                <i><h1>WELCOME</h1></i>
                <h4><?= htmlspecialchars($user["nama"]); ?></h4>
                <span class="badge bg-primary"><?= htmlspecialchars($user["role"]); ?></span>
            </div>

            <!-- Detail User -->
            <div class="p-4 bg-white">

                <h5 class="mb-3">Informasi Akun</h5>

                <table class="table">
                    <tr>
                        <th>Nama</th>
                        <td><?= htmlspecialchars($user["nama"]); ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= htmlspecialchars($user["email"]); ?></td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td><i><?= htmlspecialchars($user["password"]); ?></i></td>
                    </tr>
                </table>

                <div class="text-center mt-4">
                    <a href="edit-profil.php" class="btn btn-warning w-100 mb-2">Edit Profil</a>
                    <a href="../auth/logout.php" class="btn btn-danger w-100">Logout</a>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
