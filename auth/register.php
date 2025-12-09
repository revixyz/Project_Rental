<?php
require "../functions.php";

if(isset($_POST["daftar"])) {
  mysqli_query($conn, "INSERT INTO user VALUES(
    '', '$_POST[nama]', '$_POST[email]', '$_POST[password]', 'user'
  )");
  echo "Pendaftaran berhasil!";
}
?>

<form method="post">
  Nama: <input type="text" name="nama"><br>
  Email: <input type="email" name="email"><br>
  Password: <input type="password" name="password"><br>
  <button name="daftar">Daftar</button>
</form>
