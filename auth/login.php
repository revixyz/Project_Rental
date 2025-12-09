<?php
session_start();
require "../functions.php";
require "../assets/auth-header.php"; 

if (isset($_POST["login"])) {

    $email = $_POST["email"];
    $pass  = $_POST["password"];

    $data = query("SELECT * FROM user WHERE email='$email' AND password='$pass'");

    if ($data) {

        $_SESSION["login"]   = true;
        $_SESSION["id_user"] = $data[0]["id"];
        $_SESSION["nama"]    = $data[0]["nama"];
        $_SESSION["role"]    = $data[0]["role"];

        if ($data[0]["role"] == "admin") {
            header("Location: ../admin/index.php");
            exit;
        } else {
            header("Location: ../user/index.php");
            exit;
        }

    } else {
        echo "<script>alert('Email atau Password salah!');</script>";
    }
}
?>

<div class="container mt-5">
    <h3 class="text-center">Login</h3>

    <form method="post" class="col-md-4 mx-auto mt-3">
        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>

        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>

        <button type="submit" name="login" class="btn btn-primary w-100">
            Login
        </button>
    </form>
</div>

<?php require "../assets/footer.php"; ?>
