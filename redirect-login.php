<?php
session_start();

if (!isset($_GET['id_laptop']) || !isset($_GET['from'])) {
    header("Location: index.php");
    exit;
}

$id   = intval($_GET['id_laptop']);
$from = $_GET['from']; // laptop / detail

// TENTUKAN TUJUAN SETELAH LOGIN
if ($from === 'detail') {
    // balik ke detail-laptop
    $_SESSION['redirect_to'] = "detail-laptop.php?id=$id";
} else {
    // dari laptop.php → langsung ke pesanan
    $_SESSION['redirect_to'] = "user/pesanan.php?id_laptop=$id";
}

// kalau belum login → login
if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

// kalau sudah login
header("Location: " . $_SESSION['redirect_to']);
exit;
