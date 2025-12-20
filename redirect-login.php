<?php
session_start();

if (!isset($_GET['id_laptop']) || !isset($_GET['from'])) {
    header("Location: index.php");
    exit;
}

$id   = intval($_GET['id_laptop']);
$from = $_GET['from']; // laptop / detail


if ($from === 'detail') {
    $_SESSION['redirect_to'] = "detail-laptop.php?id=$id";
} else {
    $_SESSION['redirect_to'] = "user/pesanan.php?id_laptop=$id";
}

if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

header("Location: " . $_SESSION['redirect_to']);
exit;
