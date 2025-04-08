<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: logina.php");
    exit();
}

echo "Selamat datang, " . $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
</head>
<body>
    <h2>Dashboard Admin</h2>
    <a href="logout.php">Logout</a>
</body>
</html>
