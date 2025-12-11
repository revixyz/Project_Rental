<?php
session_start();
require "../functions.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data admin
$admin_list = query("SELECT * FROM tb_user WHERE role='admin' ORDER BY id_user DESC");

// Ambil data user
$user_list  = query("SELECT * FROM tb_user WHERE role='user' ORDER BY id_user DESC");
?>

<?php require "../assets/header.php"; ?>

<div class="container mt-4">

    <h3 class="mb-4">Manajemen User & Admin</h3>

    <a href="tambah-user.php" class="btn btn-primary mb-3">Tambah User / Admin</a>

    <!-- ========================
         TABEL ADMIN
    ========================= -->
    <h4 class="mt-4">Daftar Admin</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no=1; foreach($admin_list as $a): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $a["nama"]; ?></td>
                    <td><?= $a["email"]; ?></td>
                    <td><?= $a["password"]; ?></td>
                    <td>
                        <a href="edit-user.php?id=<?= $a['id_user']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus-user.php?id=<?= $a['id_user']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus admin ini?');">
                           Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>


    <!-- ========================
         TABEL USER
    ========================= -->
    <h4 class="mt-4">Daftar User</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no=1; foreach($user_list as $u): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $u["nama"]; ?></td>
                    <td><?= $u["email"]; ?></td>
                    <td><?= $u["password"]; ?></td>
                    <td>
                        <a href="edit-user.php?id=<?= $u['id_user']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus-user.php?id=<?= $u['id_user']; ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Yakin ingin menghapus user ini?');">
                           Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>

</div>

<?php require "../assets/footer.php"; ?>
