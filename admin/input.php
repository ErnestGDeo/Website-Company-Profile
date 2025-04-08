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

    $stmt = $koneksi->prepare("INSERT INTO career_positions (judul_posisi, lokasi_provinsi, lokasi_kabupaten, deskripsi) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $judul_posisi, $lokasi_provinsi, $lokasi_kabupaten, $deskripsi);
    
    if ($stmt->execute()) {
        echo "<p>Data berhasil disimpan!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
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

					<li class="sidebar-item">
						<a class="sidebar-link" href="data.php">
              <i class="align-middle" data-feather="database"></i> <span class="align-middle">Data</span>
            </a>
					</li>

					<li class="sidebar-item active">
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
        <div style="text-align: right; color: white; padding-left:120px;">
            <span id="time"></span><br>
            <span><?php echo date('l, F j, Y'); // Format: Hari, Bulan Tanggal, Tahun ?></span>
        </div>
        </nav>

	<div class="main">
		

		<!-- Gambar Utama -->
			<div class="container mt-5">
					<h2 class="mb-4">Input Gambar Dashboard </h2>
					<form action="../Mayawana_Web/index.php" method="POST" enctype="multipart/form-data">
					<div class="mb-3">
						<label for="judul_dash" class="form-label">Judul</label>
						<textarea class="form-control" name="judul_dash" rows="1" placeholder="Masukkan judul"></textarea>
					</div>
					<div class="mb-3">
						<label for="description_dash" class="form-label">Sub Judul</label>
						<textarea class="form-control" name="description_dash" rows="1" placeholder="Masukkan Sub Judul"></textarea>
					</div>
					<div class="mb-3">
						<label for="image_dash" class="form-label">Upload Gambar</label>
						<input class="form-control" type="file" name="image_dash" accept="image/*">
					</div>
					<button type="submit" class="btn btn-primary">Simpan</button>
					</form>
			</div>

		<!-- End Gambar utama -->
		<!-- about  -->
					<div class="container mt-5">
					<h2 class="mb-4">Form Input About </h2>
					<form action="../Mayawana_Web/index.php" method="POST" enctype="multipart/form-data">
					<!-- input judul -->
					<div class="mb-3">
						<label for="judul_about" class="form-label">Judul</label>
						<textarea class="form-control" name="judul_about" rows="1" placeholder="Masukkan judul"></textarea>
					</div>

					<!-- Input Deskripsi -->
					<div class="mb-3">
						<label for="description_about" class="form-label">Deskripsi</label>
						<textarea class="form-control" name="description_about" rows="4" placeholder="Masukkan deskripsi"></textarea>
					</div>
					
					<!-- Input Gambar -->
					<div class="mb-3">
						<label for="image_about" class="form-label">Upload Gambar</label>
						<input class="form-control" type="file" name="image_about" accept="image/*">
					</div>

					<!-- Tombol Submit -->
					<button type="submit" class="btn btn-primary">Simpan</button>
					</form>
					</div>

				<hr> 
		<!-- end about -->
		<!-- program -->
			<div class="container mt-5">
			<h2 class="mb-4">Form Input Program</h2>
			<form method="POST" action="../Mayawana_Web/index.php" enctype="multipart/form-data">
				<!-- input Judul -->
				<div class="mb-3">
					<label for="judul_program" class="form-label">Judul</label>
					<textarea class="form-control" name="judul_program" id="judul_program" rows="1" placeholder="Masukkan judul"></textarea>
				</div>
				<!-- Input Deskripsi -->
				<div class="mb-3">
					<label for="description_program" class="form-label">Deskripsi</label>
					<textarea class="form-control" name="description_program" id="description_program" rows="4" placeholder="Masukkan deskripsi"></textarea>
				</div>
				
				<!-- Input Gambar -->
				<div class="mb-3">
					<label for="image_program" class="form-label">Upload Gambar</label>
					<input class="form-control" type="file" name="image_program" id="image_program" accept="image/*">
				</div>

				<!-- Tombol Submit -->
				<button type="submit" class="btn btn-primary">Simpan</button>
			</form>
			</div>
			<hr> 
		<!-- end program -->
		<!-- MEDIA -->


			<div class="container mt-5">
			<h2 class="mb-4">Form Input Media</h2>
			<form method="POST" action="../Mayawana_Web/index.php" enctype="multipart/form-data">
				<!-- Input Judul -->
				<div class="mb-3">
					<label for="judul_media" class="form-label">Judul</label>
					<textarea class="form-control" id="judul_media" name="judul_media" rows="1" placeholder="Masukkan judul"></textarea>
				</div>
				
				<!-- Input Deskripsi -->
				<div class="mb-3">
					<label for="description_media" class="form-label">Deskripsi</label>
					<textarea class="form-control" id="description_media" name="description_media" rows="4" placeholder="Masukkan deskripsi"></textarea>
				</div>
				
				<!-- Input Gambar -->
				<div class="mb-3">
					<label for="image_media" class="form-label">Upload Gambar</label>
					<input class="form-control" type="file" id="image_media" name="image_media" accept="image/*">
				</div>

				<!-- Tombol Submit -->
				<button type="submit" name="submit" class="btn btn-primary">Simpan</button>
			</form>
			</div>

			<hr> 
		<!-- END MEDIA -->
	
		<!-- career tentang -->
			<div class="container mt-5">
			<h2>Form Input Tentang Kami</h2>
			<form action="../Mayawana_Web/index.php" method="POST">
				<div class="mb-3">
					<label for="description_about_us" class="form-label">Deskripsi</label>
					<textarea class="form-control" id="description_about_us" name="description_about_us" rows="5" placeholder="Masukkan deskripsi tentang perusahaan"></textarea>
				</div>
				<button type="submit" class="btn btn-primary">Simpan</button>
			</form>
			</div>

			<hr> 
		<!-- end of tentang -->

		<!-- career posisi -->
			<div class="container mt-5">
			<h2 class="mb-4">Form Posisi yang Dilamar</h2>
			<form action="../Mayawana_Web/index.php" method="POST">
				<!-- Input Judul -->
				<div class="mb-3">
					<label for="judul_posisi" class="form-label">Judul</label>
					<textarea class="form-control" id="judul_posisi" name="judul_posisi" rows="1" placeholder="Masukkan judul"></textarea>
				</div>
				
			<!-- Input Lokasi -->
				<div class="mb-3">
					<label for="lokasi_posisi" class="form-label">Lokasi</label>
					<input type="text" class="form-control" id="lokasi_posisi" name="lokasi_posisi" placeholder="Masukkan Lokasi">
				</div>


				<!-- Input Deskripsi -->
				<div class="mb-3">
					<label for="description_posisi" class="form-label">Deskripsi</label>
					<textarea class="form-control" id="description_posisi" name="description_posisi" rows="4" placeholder="Masukkan deskripsi"></textarea>
				</div>  
				
				<!-- Tombol Submit -->
				<button type="submit" class="btn btn-primary">Simpan</button>
			</form>
			</div>

			<hr> 
		<!-- end of posisi -->
	<!-- Form Upload Logo -->
			<div class="container mt-5">
				<h2 class="mb-4">Upload Logo dan Teks Opsional</h2>
				<form action="../Mayawana_Web/index.php" method="POST" enctype="multipart/form-data">
					<!-- Input Logo -->
					<div class="mb-3">
						<label for="logo_image" class="form-label">Upload Logo</label>
						<input class="form-control" type="file" name="logo_image" accept="image/*" required>
					</div>
					<!-- Input Teks Opsional -->
					<div class="mb-3">
						<label for="logo_text" class="form-label">Teks Opsional</label>
						<textarea class="form-control" name="logo_text" rows="1" placeholder="Masukkan teks opsional"></textarea>
					</div>
					<!-- Tombol Submit -->
					<button type="submit" class="btn btn-primary">Simpan</button>
				</form>
			</div>
	<!-- End Form Upload Logo -->

	</div>
</div>
	<script>
		document.addEventListener("DOMContentLoaded", function () {
			// Tangkap semua form di halaman
			const forms = document.querySelectorAll("form");

			forms.forEach((form) => {
				form.addEventListener("submit", function () {
					// Tampilkan notifikasi
					alert("Berhasil diupload!");
				});
			});
		});
		// JavaScript untuk membuka dan menutup sidebar
		document.querySelector('.js-sidebar-toggle').addEventListener('click', function () {
		document.getElementById('sidebar').classList.toggle('active');
		});

	</script>

	<script src="js/app.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

</body>

</html>