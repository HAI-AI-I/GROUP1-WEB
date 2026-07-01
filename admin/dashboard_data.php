<?php
session_start();

if (!isset($_SESSION['admin_name'])) {
    $_SESSION['admin_name'] = "Super Admin";
}

/* ===========================
   DỮ LIỆU DASHBOARD (Tạm thời)
   Sau này sẽ lấy từ Database
=========================== */

$totalUsers = 24831;
$totalSongs = 1024;
$totalSingers = 384;
$totalGenres = 28;
$totalPlays = "4.2M";
