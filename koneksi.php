<?php
$host = "localhost";
$user = "root"; // Sesuaikan dengan username MySQL
$pass = ""; // Sesuaikan dengan password MySQL
$db = "mayawana";

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
