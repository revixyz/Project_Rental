<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>User - Rental Laptop</title>
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

        /* Logout button */
        .btn-logout {
            background: #e03131;
            border: none;
            font-weight: 600;
            padding: 6px 16px;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            transition: .2s;
        }

        .btn-logout:hover {
            background: #c92a2a;
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container">

    <a class="navbar-brand text-white" href="../user/index.php">RENTAL LAPTOP</a>

    <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarUser">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarUser">
      <ul class="navbar-nav ms-auto align-items-center">

        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active-custom' : '' ?>" 
             href="../user/index.php">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'laptop.php' ? 'active-custom' : '' ?>" 
             href="../user/laptop.php">Laptop</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pesanan-saya.php' ? 'active-custom' : '' ?>" 
             href="../user/pesanan-saya.php">Riwayat</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pengembalian.php' ? 'active-custom' : '' ?>" 
             href="../user/pengembalian.php">Pengembalian</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active-custom' : '' ?>" 
             href="../user/profil.php">Profil</a>
        </li>

        <li class="nav-item ms-2">
          <a class="btn btn-logout" href="../auth/logout.php">Logout</a>
        </li>

      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">
