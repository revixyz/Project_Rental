<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Admin - Rental Laptop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* === NAVBAR ATAS === */
    .navbar-dark {
        background: linear-gradient(135deg, #212529, #343a40);
        box-shadow: 0 3px 10px rgba(0,0,0,0.25);
    }

    .navbar-brand {
        font-size: 1.25rem;
        letter-spacing: .5px;
    }

    .btn-logout {
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 8px;
        transition: .2s;
    }

    .btn-logout:hover {
        transform: scale(1.05);
    }

    /* === SIDEBAR === */
    .sidebar {
        background: #1e1e1e;
        min-height: 100vh;
        padding-top: 70px;
        box-shadow: inset -1px 0 0 rgba(255,255,255,0.1);
    }

    .sidebar .nav-link {
        color: #fff !important;
        padding: 12px 20px;
        font-size: 15px;
        border-left: 3px solid transparent;
        transition: 0.25s;
    }

    .sidebar .nav-link:hover {
        background: #343a40;
        border-left: 3px solid #ffc107;
        color: #ffc107 !important;
    }

    /* === LINK AKTIF (AUTO DETECT HALAMAN) === */
    .active-menu {
        background: #343a40 !important;
        border-left: 3px solid #ffc107 !important;
        color: #ffc107 !important;
        font-weight: 600;
    }

  </style>
</head>

<body>

<!-- NAVBAR ATAS -->
<nav class="navbar navbar-dark fixed-top">
  <div class="container-fluid d-flex justify-content-between">
    <a class="navbar-brand fw-bold" href="../admin/index.php">ADMIN RENTAL LAPTOP</a>

    <a href="../auth/logout.php" class="btn btn-danger btn-logout btn-sm">
      Logout
    </a>
  </div>
</nav>

<!-- SIDEBAR + KONTEN -->
<div class="container-fluid">
  <div class="row">

    <!-- SIDEBAR -->
    <div class="col-md-2 sidebar">

      <ul class="nav flex-column">

        <li class="nav-item">
          <a class="nav-link 
            <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active-menu' : '' ?>" 
            href="../admin/index.php">Dashboard</a>
        </li>

        <li class="nav-item">
          <a class="nav-link 
            <?= basename($_SERVER['PHP_SELF']) == 'laptop.php' ? 'active-menu' : '' ?>" 
            href="../admin/laptop.php">Data Laptop</a>
        </li>

        <li class="nav-item">
          <a class="nav-link 
            <?= basename($_SERVER['PHP_SELF']) == 'pesanan.php' ? 'active-menu' : '' ?>" 
            href="../admin/pesanan.php">Pesanan</a>
        </li>

        <li class="nav-item">
          <a class="nav-link 
            <?= basename($_SERVER['PHP_SELF']) == 'pengembalian.php' ? 'active-menu' : '' ?>" 
            href="../admin/pengembalian.php">Pengembalian</a>
        </li>

        <li class="nav-item">
          <a class="nav-link 
            <?= basename($_SERVER['PHP_SELF']) == 'user.php' ? 'active-menu' : '' ?>" 
            href="../admin/user.php">User</a>
        </li>

      </ul>

    </div>

    <!-- KONTEN UTAMA -->
    <div class="col-md-10 pt-5">
      <div class="container mt-4">
