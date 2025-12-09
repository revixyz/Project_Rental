<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Admin - Rental Laptop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom Admin CSS -->
  <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<!-- NAVBAR ATAS -->
<nav class="navbar navbar-dark bg-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="../admin/index.php">ADMIN RENTAL LAPTOP</a>
    <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
  </div>
</nav>

<!-- SIDEBAR + KONTEN -->
<div class="container-fluid">
  <div class="row">

    <!-- SIDEBAR -->
    <div class="col-md-2 bg-dark text-white min-vh-100 pt-5">
      <ul class="nav flex-column mt-4">

        <li class="nav-item">
          <a class="nav-link text-white" href="../admin/index.php">Dashboard</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="../admin/laptop.php">Data Laptop</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="../admin/pesanan.php">Pesanan</a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="../admin/user.php">User</a>
        </li>
      </ul>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="col-md-10 pt-5">
      <div class="container mt-4">
