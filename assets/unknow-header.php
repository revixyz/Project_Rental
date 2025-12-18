<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLogin = isset($_SESSION['login']) && $_SESSION['login'] === true;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rental Laptop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .navbar-custom {
            background: linear-gradient(135deg, #4c6ef5, #5f3dc4);
            padding: 14px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand { font-size: 1.5rem; font-weight: 700; }
        .navbar-nav .nav-link { color: #fff !important; font-weight: 500; }
        .navbar-nav .nav-link:hover { color: #ffdd57 !important; }
        .btn-login {
            background: linear-gradient(135deg, #ffd43b, #fab005);
            font-weight: 700;
            border-radius: 12px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">

    <a class="navbar-brand text-white" href="/Project_Rental_copy/index.php">
      RENTAL LAPTOP
    </a>

    <div class="collapse navbar-collapse show">
      <ul class="navbar-nav ms-auto align-items-center">

        <li class="nav-item">
          <a class="nav-link" href="/Project_Rental_copy/index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="/Project_Rental_copy/laptop.php">Laptop</a>
        </li>

        <?php if ($isLogin): ?>
            <li class="nav-item">
              <a class="nav-link" href="/Project_Rental_copy/user/pesanan-saya.php">Riwayat</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/Project_Rental_copy/user/pengembalian.php">Pengembalian</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/Project_Rental_copy/user/profil.php">Profil</a>
            </li>
            <li class="nav-item ms-2">
              <a class="btn btn-login" href="/Project_Rental_copy/auth/logout.php">Logout</a>
            </li>
        <?php else: ?>
            <li class="nav-item ms-2">
              <a class="btn btn-login" href="/Project_Rental_copy/auth/login.php">Login</a>
            </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>
