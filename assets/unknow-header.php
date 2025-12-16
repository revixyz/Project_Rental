<!DOCTYPE html>
<html lang="id">
<?php
$isLogin = isset($_SESSION['login']) && $_SESSION['login'] === true;

?>

<head>
    <meta charset="UTF-8">
    <title>Rental Laptop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* === NAVBAR MODERN === */
        .navbar-custom {
            background: linear-gradient(135deg, #4c6ef5, #5f3dc4);
            padding: 14px 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 1000;
            
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: .5px;
        }

        .navbar-nav .nav-link {
            color: #ffffffd9 !important;
            font-weight: 500;
            padding-left: 16px !important;
            padding-right: 16px !important;
            transition: .3s;
        }

        .navbar-nav .nav-link:hover {
            color: #ffdd57 !important;
            transform: translateY(-1px);
        }

        /* Active link highlight */
        .navbar-nav .nav-link.active-custom {
            color: #ffdd57 !important;
            font-weight: 700;
        }

        /* Login button */
        .btn-login {
            background: linear-gradient(135deg, #ffd43b, #fab005);
            color: #212529;
            border: none;
            font-weight: 700;
            padding: 7px 18px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(250, 176, 5, 0.45);
            transition: all 0.25s ease;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #fcc419, #f59f00);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 6px 18px rgba(250, 176, 5, 0.6);
        }

    </style>
</head>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">

    <a class="navbar-brand text-white" href="index.php">RENTAL LAPTOP</a>

    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarUser">
      <ul class="navbar-nav ms-auto align-items-center">

        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active-custom' : '' ?>" 
             href="index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'laptop.php' ? 'active-custom' : '' ?>" 
             href="laptop.php">Laptop</a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pesanan-saya.php' ? 'active-custom' : '' ?>"
                href="<?= $isLogin ? '../user/pesanan-saya.php' : '#' ?>"
                onclick="<?= !$isLogin ? 'showLoginAlert(); return false;' : '' ?>">
                Riwayat
            </a>

        </li>

        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pengembalian.php' ? 'active-custom' : '' ?>"
                href="<?= $isLogin ? '../user/pengembalian.php' : '#' ?>"
                onclick="<?= !$isLogin ? 'showLoginAlert(); return false;' : '' ?>">
                Pengembalian
            </a>

        </li>

        <li class="nav-item ms-2">
          <a class="btn btn-login" href="auth/login.php">Login</a>
        </li>

      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">

<!-- MODAL LOGIN ALERT -->
<div class="modal fade" id="loginAlert" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-danger">
          Akses Terbatas
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">
        <p class="mb-3">
          Kamu harus <strong>login terlebih dahulu</strong><br>
        </p>
        <a href="auth/login.php" class="btn btn-primary px-4">
          Login Sekarang
        </a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function showLoginAlert() {
    const modal = new bootstrap.Modal(document.getElementById('loginAlert'));
    modal.show();
}
</script>


