<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: ../form login/login.php");
    exit();
}

// echo "Selamat datang, " . $_SESSION['admin'];


if (isset($_POST['submit'])) {
    $judul_posisi = $_POST['judul_posisi'];
    $lokasi_provinsi = $_POST['lokasi_provinsi'];
    $lokasi_kabupaten = $_POST['lokasi_kabupaten'];
    $deskripsi = $_POST['description_posisi'];

    $query = "INSERT INTO career_positions (judul_posisi, lokasi_provinsi, lokasi_kabupaten, deskripsi) 
              VALUES ('$judul_posisi', '$lokasi_provinsi', '$lokasi_kabupaten', '$deskripsi')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<p>Data berhasil disimpan!</p>";
    } else {
        echo "<p>Error: " . mysqli_error($koneksi) . "</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN MWP</title>
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

					<li class="sidebar-item active">
						<a class="sidebar-link" href="index.php">
              <i class="align-middle" data-feather="sliders"></i> <span class="align-middle">Dashboard</span>
            </a>
					</li>

					<li class="sidebar-item">
						<a class="sidebar-link" href="../register.php">
              <i class="align-middle" data-feather="user"></i> <span class="align-middle">Admin</span>
            </a>
					</li>

					<li class="sidebar-item">
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

        <!-- Content Area -->
        <div 
        style="
            height: calc(100vh - 60px); 
            text-align: center; 
            background-size: cover; 
            background-position: center; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            justify-content: center; 
            color: white;
        "
        >
        <h1>Welcome to PT MAYAWANA PERSADA</h1>
        <div style="position: absolute; bottom: 20px;">
            <label 
            for="backgroundInput" 
            style="
                cursor: pointer; 
                padding: 5px 15px; 
                font-size: 14px; 
                background-color: #0056b3; 
                color: white; 
                border-radius: 5px; 
                border: none;
            "
            >
            Background
            </label>
            <input 
            type="file" 
            id="backgroundInput" 
            accept="image/*" 
            style="display: none;"
            >
        </div>
        </div>
    </div>
</div>
    <script src="js/app.js"></script>
 
    <script>
// JavaScript untuk membuka dan menutup sidebar
document.querySelector('.js-sidebar-toggle').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('active');
});



    // JavaScript untuk memperbarui waktu secara dinamis
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('time').innerText = timeString;
    }

    setInterval(updateTime, 1000); // Perbarui setiap detik
    updateTime(); // Panggil segera saat halaman dimuat

    // Event Listener untuk input file
    document.getElementById('backgroundInput').addEventListener('change', function (event) {
    const file = event.target.files[0]; // Ambil file yang dipilih
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            // Terapkan gambar sebagai background
            const backgroundImage = `url(${e.target.result})`;
            document.getElementById('main-container').style.backgroundImage = backgroundImage;

            // Mengatur ukuran gambar agar tidak melebihi batas
            const image = new Image();
            image.src = e.target.result;

            image.onload = function() {
                // Menghitung proporsi gambar untuk menjaga ukuran sesuai kontainer
                const width = image.width;
                const height = image.height;
                if (width > height) {
                    document.getElementById('main-container').style.backgroundSize = 'cover';
                } else {
                    document.getElementById('main-container').style.backgroundSize = 'contain';
                }
            }
        };
        reader.readAsDataURL(file); // Baca file sebagai data URL
    }
});

</script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
