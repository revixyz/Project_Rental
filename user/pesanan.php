<?php
require "../functions.php";

if(isset($_POST["sewa"])) {
  $total = $_POST["durasi"] * $_POST["harga"];

  mysqli_query($conn, "INSERT INTO pesanan VALUES (
    '', '$_POST[user]', '$_POST[laptop]',
    '$_POST[tanggal]', '$_POST[durasi]',
    '$total', 'Menunggu'
  )");

  echo "Pesanan berhasil dibuat!";
}
?>

<form method="post">
  ID User: <input type="number" name="user"><br>
  ID Laptop: <input type="number" name="laptop"><br>
  Harga per Hari: <input type="number" name="harga"><br>
  Tanggal Sewa: <input type="date" name="tanggal"><br>
  Durasi (hari): <input type="number" name="durasi"><br>

  <button name="sewa">Sewa Laptop</button>
</form>
