<?php
require 'koneksi.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Hash password sebelum menyimpannya ke database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan data ke dalam tabel admin
    $query = "INSERT INTO admin (username, password) VALUES ('$username', '$hashed_password')";

    if (mysqli_query($koneksi, $query)) {
        echo "<div class='alert alert-success' role='alert'>Pendaftaran berhasil!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error: " . mysqli_error($koneksi) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <link rel="shortcut icon" href="Mayawana_Web/images/logodaun.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <h2 class="text-center mb-4">Register Admin</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="Masukkan username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Masukkan password" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                <a href="form login/login.php   " class="btn btn-secondary w-100 mt-2">Back</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
