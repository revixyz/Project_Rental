<?php
session_start();
require "../functions.php";

// proteksi halaman admin
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

//baru boleh panggil header setelah lolos cek
require "../assets/header.php"; 

$totalLaptop = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM laptop"));
?>

<h3 class="mb-4">Dashboard Admin</h3>

<div class="row">

  <div class="col-md-4">
    <div class="card bg-primary text-white shadow mb-3">
      <div class="card-body text-center">
        <h6>Total Laptop</h6>
        <h2><?= $totalLaptop; ?></h2>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card bg-success text-white shadow mb-3">
      <div class="card-body text-center">
        <h6>Pesanan</h6>
        <h2><?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pesanan")); ?></h2>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card bg-warning text-dark shadow mb-3">
      <div class="card-body text-center">
        <h6>Total User</h6>
        <h2><?= mysqli_num_rows(mysqli_query($conn, "SELECT * FROM user")); ?></h2>
      </div>
    </div>
  </div>

</div>

<a href="laptop.php" class="btn btn-primary mt-3">Kelola Laptop</a>

<?php require "../assets/footer.php"; ?>
