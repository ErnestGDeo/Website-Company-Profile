<?php
session_start();
require '../koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Redirect to newRegister.php if username is "register"
    if (strtolower($username) === 'register') {
        header("Location: ../newRegister.php");
        exit();
    }

    $query = "SELECT * FROM admin WHERE username='$username'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['admin'] = $username;
        header("Location: ../admin/index.php");
        exit();
    } else {  
        echo "Username atau password salah!";
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="shortcut icon" href="../Mayawana_Web/images/logodaun.png">
    <link rel="stylesheet" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  </head>
  <body>
    <div class="login-container" id="loginContainer">
      <!-- bagian bawah ini untuk mengganti logo -->
      <img src="../Mayawana_Web/images/logodaun.png" alt="Company Logo" width="80" style="margin-bottom: 20px;">
      <h1>Login</h1>
      <form method="POST" action="">
    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" name="username" class="form-control" id="username" placeholder="Enter your username" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password" required>
    </div>
    <br>
    <button type="submit" name="login" class="btn btn-primary">Login</button>
</form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="login.js"></script>
  </body>
</html>
