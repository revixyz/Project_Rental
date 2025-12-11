<?php
session_start();
require "../functions.php";

// Proteksi halaman admin
if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

//baru boleh panggil header setelah lolos cek
require "../assets/header.php"; 

// Query data
$totalLaptop = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_laptop"));
$totalPesanan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_pesanan"));
$totalUser    = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_user"));
?>

<style>
.dashboard-header {
    background: linear-gradient(135deg, #4c6ef5, #7950f2);
    color: white;
    padding: 40px 25px;
    border-radius: 15px;
    margin-bottom: 40px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.card-custom {
    border: none;
    border-radius: 15px;
    color: white;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transition: transform .2s, box-shadow .2s;
}

.card-custom:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.25);
}

.bg-laptop { background: linear-gradient(135deg, #4dabf7, #1c7ed6); }
.bg-pesanan { background: linear-gradient(135deg, #51cf66, #37b24d); }
.bg-user { background: linear-gradient(135deg, #fcc419, #f08c00); }

.quick-actions .btn {
    border-radius: 12px;
    padding: 12px 18px;
    font-size: 15px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.12);
}
.quick-actions .btn:hover {
    transform: scale(1.03);
}
</style>

<div class="dashboard-header">
    <h2 class="fw-bold mb-2">Selamat Datang, Admin</h2>
    <p>Kelola semua aktivitas rental laptop melalui dashboard ini.</p>
</div>

<!-- STATISTIC CARDS -->
<div class="row mb-4">
  
  <div class="col-md-4 mb-3">
      <div class="card-custom bg-laptop text-center">
          <h5>Total Laptop</h5>
          <h1 class="fw-bold"><?= $totalLaptop ?></h1>
      </div>
  </div>

  <div class="col-md-4 mb-3">
      <div class="card-custom bg-pesanan text-center">
          <h5>Total Pesanan</h5>
          <h1 class="fw-bold"><?= $totalPesanan ?></h1>
      </div>
  </div>

  <div class="col-md-4 mb-3">
      <div class="card-custom bg-user text-center">
          <h5>Total User</h5>
          <h1 class="fw-bold"><?= $totalUser ?></h1>
      </div>
  </div>

</div>

<!-- QUICK ACTIONS -->
<div class="card shadow p-4 mb-5">
  <h4 class="mb-3 fw-bold">Aksi Cepat</h4>
  <div class="row quick-actions g-3">

      <div class="col-md-4">
          <a href="laptop.php" class="btn btn-primary w-100">
              Kelola Laptop
          </a>
      </div>

      <div class="col-md-4">
          <a href="pesanan.php" class="btn btn-success w-100">
              Kelola Pesanan
          </a>
      </div>

      <div class="col-md-4">
          <a href="user.php" class="btn btn-warning w-100 text-dark">
              Kelola User
          </a>
      </div>

  </div>
</div>

<!-- FOOTER -->
<?php require "../assets/footer.php"; ?>
