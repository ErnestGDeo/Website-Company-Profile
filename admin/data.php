<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../form login/login.php");
    exit();
}
include '../koneksi.php'; // Pastikan path ini sesuai dengan struktur folder Anda

// Query untuk mendapatkan daftar tabel
$tableList = mysqli_query($koneksi, "SHOW TABLES");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data dari Semua Tabel</title>
    <link rel="shortcut icon" href="../Mayawana_Web/images/logodaun.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="css/app.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
     <!-- Navbar -->
     <div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="index.php">
                <span class="navbar-text text-white">
                Selamat datang, <?php echo htmlspecialchars($_SESSION['admin']); ?>
            </span>
        </a>

				<ul class="sidebar-nav">

					<li class="sidebar-item">
						<a class="sidebar-link" href="index.php">
              <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
            </a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="../register.php">
              <i class="align-middle" data-feather="user"></i> <span class="align-middle">Admin</span>
            </a>
					</li>

					<li class="sidebar-item active">
						<a class="sidebar-link" href="data.php">
              <i class="align-middle" data-feather="database"></i> <span class="align-middle">Data</span>
            </a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="input.php">
              <i class="align-middle" data-feather="file-plus"></i> <span class="align-middle">Input</span>
            </a>
					</li>
                    
                    <li class="sidebar-item">
						<a class="sidebar-link" href="medsos.php">
              <i class="align-middle" data-feather="at-sign"></i> <span class="align-middle">Link</span>
            </a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="../form login/logout.php">
              <i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Logout</span>
            </a>
					</li>
                    <li class="sidebar-item">
						<a class="sidebar-link" href="../Mayawana_Web/index.php">
              <i class="align-middle" data-feather="globe"></i> <span class="align-middle">Web</span>
            </a>
					</li>
				</ul>

			</div>
		</nav>
    <!-- Main Content Section -->
    <div class="main" id="main-container">
    <!-- Navbar -->
        <nav 
        class="navbar navbar-expand-lg navbar-light navbar-bg" 
        style="
            width: 100%; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 0 2px; 
            background-color: #006600; 
            color: white;
        "
        >
        <!-- Hamburger Icon -->
        <button 
            class="btn btn-primary js-sidebar-toggle" 
            style="
            border: none; 
            background: none; 
            color: white;
            "
        >
            <i class="hamburger align-self-center"></i>
        </button>

        <!-- Welcome Title -->
        <span style="font-size: 1.5rem; font-weight: bold;">PT MAYAWANA PERSADA</span>

        <!-- Date and Time -->
        <div style="text-align: right; color: white;">
            <span id="time"></span><br>
            <span><?php echo date('l, F j, Y'); // Format: Hari, Bulan Tanggal, Tahun ?></span>
        </div>
        </nav>

	<div class="main">
		
    <div class="container mt-5">
        <h2>Data dari Semua Tabel</h2>

        <?php while ($table = mysqli_fetch_array($tableList)): ?>
            <h3>Tabel: <?php echo htmlspecialchars($table[0]); ?></h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <?php
                        // Ambil nama tabel
                        $tableName = $table[0];

                        // Ambil kolom dari tabel
                        $columns = mysqli_query($koneksi, "SHOW COLUMNS FROM $tableName");
                        while ($column = mysqli_fetch_array($columns)) {
                            echo "<th>" . htmlspecialchars($column['Field']) . "</th>";
                        }
                        ?>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ambil data dari tabel
                    $data = mysqli_query($koneksi, "SELECT * FROM $tableName");
                    while ($row = mysqli_fetch_assoc($data)) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            echo "<td>" . htmlspecialchars($value) . "</td>";
                        }
                        echo "<td>
                                <a href='edit.php?id={$row['id']}&table={$tableName}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete.php?id={$row['id']}&table={$tableName}' class='btn btn-danger btn-sm' onclick='return confirm(\"Anda yakin ingin menghapus?\");'>Delete</a>
                                </td>";
                        echo "</tr>";


                    }
                    ?>
                </tbody>
            </table>
        <?php endwhile; ?>


    </div>
    </div>
     </div>

    <script src="js/app.js"></script>
    <script>		
        // JavaScript untuk membuka dan menutup sidebar
		document.querySelector('.js-sidebar-toggle').addEventListener('click', function () {
		document.getElementById('sidebar').classList.toggle('active');
		});
    </script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Tutup koneksi
mysqli_close($koneksi);
?>
