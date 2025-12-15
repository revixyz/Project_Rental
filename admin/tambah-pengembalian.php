<?php
session_start();
require "../config/database.php";

if (!isset($_SESSION["login"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit;
}

$pemesanan = mysqli_query($conn, "
    SELECT p.*, u.nama AS nama_user, l.nama AS nama_laptop
    FROM tb_pesanan p
    JOIN tb_user u ON p.id_user = u.id_user
    JOIN tb_laptop l ON p.id_laptop = l.id_laptop
    WHERE p.status = 'Dipinjam'
");
?>

<?php
if (isset($_POST['simpan'])) {

    mysqli_query($conn, "
        INSERT INTO tb_pengembalian
        (id_pesanan, tanggal_kembali, kondisi_laptop, denda, catatan_admin, status)
        VALUES (
            '$_POST[id_pesanan]',
            '$_POST[tanggal_kembali]',
            '$_POST[kondisi]',
            '$_POST[denda]',
            '$_POST[catatan_admin]',
            'Disetujui'
        )
    ");

    // Update pesanan
    mysqli_query($conn, "
        UPDATE tb_pesanan SET status='Selesai'
        WHERE id_pesanan='$_POST[id_pesanan]'
    ");

    header("Location: pengembalian.php");
}

?>

<?php require "../assets/header.php"; ?>

<h2 class="mb-4">Tambah Pengembalian</h2>
<form method="POST">
    <label>Pilih Pesanan</label>
    <select name="id_pesanan" class="form-control" required>
        <?php while ($p = mysqli_fetch_assoc($pemesanan)) : ?>
            <option value="<?= $p['id_pesanan']; ?>">
                <?= $p['nama_user']; ?> - <?= $p['nama_laptop']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Tanggal Kembali</label>
    <input type="date" name="tanggal_kembali" class="form-control" required>

    <label>Kondisi Laptop</label>
    <select name="kondisi" class="form-control">
        <option>Baik</option>
        <option>Rusak</option>
    </select>

    <label>Denda</label>
    <input type="number" name="denda" class="form-control">

    <label>Catatan Admin</label>
    <textarea name="catatan_admin" class="form-control"></textarea>

    <button class="btn btn-primary mt-3" name="simpan">Simpan</button>
</form>
