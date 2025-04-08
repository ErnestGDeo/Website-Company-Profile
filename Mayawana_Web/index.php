<?php
// Koneksi ke database
include '../koneksi.php';


// BAGIAN ABOUT
// Algoritma untuk menyimpan data About
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['judul_about']) && !empty($_POST['description_about']) && !empty($_FILES['image_about']['name'])) {
        $judul = htmlspecialchars($_POST['judul_about']);
        $deskripsi = htmlspecialchars($_POST['description_about']);
        $gambar = $_FILES['image_about']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($gambar);
        
        // Pindahkan file gambar ke folder uploads
        move_uploaded_file($_FILES['image_about']['tmp_name'], $target_file);
        
        // Simpan ke database
        $query = "INSERT INTO about (judul, deskripsi, gambar) VALUES ('$judul', '$deskripsi', '$target_file')";
        mysqli_query($koneksi, $query);
    }
}
// BAGIAN PROGRAM
// Algoritma untuk menyimpan data Program
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['judul_program'])) {
    $judul_program = mysqli_real_escape_string($koneksi, $_POST['judul_program']);
    $description_program = mysqli_real_escape_string($koneksi, $_POST['description_program']);
    $image_program = "";

    // Proses upload gambar
    if (!empty($_FILES['image_program']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image_program']['name']);
        
        if (move_uploaded_file($_FILES['image_program']['tmp_name'], $target_file)) {
            $image_program = $target_file;
        }
    }

    // Simpan data ke database
    $sql = "INSERT INTO program (judul, deskripsi, gambar) VALUES ('$judul_program', '$description_program', '$image_program')";
    mysqli_query($koneksi, $sql);
}

    // Fungsi untuk membatasi jumlah kata dalam teks
    function limit_words($text, $limit) {
        $words = explode(" ", $text);
        return (count($words) > $limit) ? implode(" ", array_slice($words, 0, $limit)) . "..." : $text;
    }



// BAGIAN MEDIA
// Algoritma untuk menyimpan data Media
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['judul_media'])) {
    $judul_media = mysqli_real_escape_string($koneksi, $_POST['judul_media']);
    $description_media = mysqli_real_escape_string($koneksi, $_POST['description_media']);
    $image_media = "";

    // Proses upload gambar
    if (!empty($_FILES['image_media']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image_media']['name']);
        
        // Buat folder uploads jika belum ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        if (move_uploaded_file($_FILES['image_media']['tmp_name'], $target_file)) {
            $image_media = $target_file;
        }
    }

    // Simpan data ke database
    $sql = "INSERT INTO media (judul, deskripsi, gambar, tanggal) VALUES ('$judul_media', '$description_media', '$image_media', NOW())";
    mysqli_query($koneksi, $sql);
}
// function limit_words($text, $limit) {
//     $words = explode(" ", $text);
//     return implode(" ", array_slice($words, 0, $limit)) . (count($words) > $limit ? "..." : "");
// }




// BAGIAN SLIDESHOW MEDIA
// Algoritma untuk paginasi data Media
$limit = 4;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM media ORDER BY tanggal DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);

// Total halaman
$total_result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM media");
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_page = ceil($total_data / $limit);

// BAGIAN KARIR
// Algoritma untuk menyimpan deskripsi About Us
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['description_about_us'])) {
    $deskripsi = htmlspecialchars($_POST['description_about_us']);

    // Periksa apakah data sudah ada di tabel
    $checkQuery = "SELECT * FROM about_us";
    $result = mysqli_query($koneksi, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        // Update data
        $updateQuery = "UPDATE about_us SET deskripsi = '$deskripsi' WHERE id = 1";
        mysqli_query($koneksi, $updateQuery);
    } else {
        // Insert data baru
        $insertQuery = "INSERT INTO about_us (deskripsi) VALUES ('$deskripsi')";
        mysqli_query($koneksi, $insertQuery);
    }
}

// BAGIAN LOWONGAN
// Algoritma untuk menyimpan data lowongan kerja
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['judul_posisi']) && !empty($_POST['lokasi_posisi']) && !empty($_POST['description_posisi'])) {
        $judul = htmlspecialchars($_POST['judul_posisi']);
        $lokasi = htmlspecialchars($_POST['lokasi_posisi']);
        $deskripsi = htmlspecialchars($_POST['description_posisi']);

        $query = "INSERT INTO career (judul, lokasi, deskripsi) VALUES ('$judul', '$lokasi', '$deskripsi')";
        mysqli_query($koneksi, $query);
    }
}

// BAGIAN HERO SECTION (DASHBOARD)
// Algoritma untuk menyimpan atau memperbarui data Hero Section
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul_dash'] ?? null;
    $subtitle = $_POST['description_dash'] ?? null;

    if (isset($_FILES['image_dash']) && $_FILES['image_dash']['error'] != UPLOAD_ERR_NO_FILE) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $imagePath = $uploadDir . basename($_FILES['image_dash']['name']);

        if (move_uploaded_file($_FILES['image_dash']['tmp_name'], $imagePath)) {
            $checkQuery = "SELECT * FROM hero_section LIMIT 1";
            $result = mysqli_query($koneksi, $checkQuery);

            if (mysqli_num_rows($result) > 0) {
                $updateQuery = "UPDATE hero_section SET title=?, subtitle=?, image_path=?";
                $stmt = mysqli_prepare($koneksi, $updateQuery);
                mysqli_stmt_bind_param($stmt, 'sss', $judul, $subtitle, $imagePath);
                mysqli_stmt_execute($stmt);
            } else {
                $insertQuery = "INSERT INTO hero_section (title, subtitle, image_path) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($koneksi, $insertQuery);
                mysqli_stmt_bind_param($stmt, 'sss', $judul, $subtitle, $imagePath);
                mysqli_stmt_execute($stmt);
            }
        }
    }
}

// BAGIAN MEDIA SOSIAL
// Algoritma untuk menyimpan data link media sosial
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $platform = $_POST['platform'];
    $link = $_POST['link'];

    if (!empty($platform) && !empty($link)) {
        $query = "INSERT INTO social_links (platform, link) VALUES ('$platform', '$link')";
        mysqli_query($koneksi, $query);
    }
}

// BAGIAN LOGO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['logo_image'])) {
        $logoPath = 'uploads/' . basename($_FILES['logo_image']['name']);
        if (move_uploaded_file($_FILES['logo_image']['tmp_name'], $logoPath)) {
            $logoText = mysqli_real_escape_string($koneksi, $_POST['logo_text'] ?? null);

            // GANTI 'logo_path' MENJADI 'logo_image'
            $query = "INSERT INTO logo (logo_image, logo_text) VALUES ('$logoPath', '$logoText')";
            mysqli_query($koneksi, $query);
        }
    }
}

// Ambil data logo terbaru
// GANTI 'logo_path' MENJADI 'logo_image' jika dipakai di bawah
$querySelect = "SELECT * FROM logo ORDER BY id DESC LIMIT 1";
$result = mysqli_query($koneksi, $querySelect);
$logoData = mysqli_fetch_assoc($result);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!-- Mengatur kompatibilitas dengan Internet Explorer -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>PT Mayawana Persada</title>
    <!-- Mengatur tampilan agar responsif di berbagai perangkat -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="images/logodaun.png">
    
    <!-- CSS Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Fancybox CSS -->
    <link href="css/jquery.lightbox.css" rel="stylesheet">
    
    <!-- Flexslider CSS -->
    <link href="css/flexslider.css" rel="stylesheet">
    
    <!-- Custom styles untuk template -->
    <link href="css/style.css" rel="stylesheet">
    
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Dukungan untuk HTML5 dan media query di IE8 -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<header>
    <?php
    // Mendapatkan semua link media sosial dari database
    $query = "SELECT platform, link FROM social_links";
    $result = mysqli_query($koneksi, $query);
    $links = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $links[$row['platform']] = $row['link'];
    }
    ?>

    <!-- Bagian untuk media sosial -->
    <div class="medsos">
        <a href="<?= $links['facebook'] ?? '#' ?>" target="_blank" class="social-icon"><i class="fab fa-facebook"></i></a>
        <a href="<?= $links['youtube'] ?? '#' ?>" target="_blank" class="social-icon"><i class="fab fa-youtube"></i></a>
        <a href="<?= $links['instagram'] ?? '#' ?>" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
        <a href="<?= $links['linkedin'] ?? '#' ?>" target="_blank" class="social-icon"><i class="fab fa-linkedin"></i></a>
        <a href="../form login/login.php" class="social-icon"><i class="fa-regular fa-circle-user"></i></a>  
    </div>

    <?php
    // Proses upload logo dan teks opsional
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['logo_image'])) {
            $logoPath = 'uploads/' . basename($_FILES['logo_image']['name']);
            move_uploaded_file($_FILES['logo_image']['tmp_name'], $logoPath);

            // Simpan teks opsional jika ada
            $logoText = isset($_POST['logo_text']) ? htmlspecialchars($_POST['logo_text']) : '';
        }
    }
    ?>

    <!-- Bagian logo dan navbar -->
    <div class="col-md-5 col-md-push-1 col-sm-6">
        <a href="index.php" id="logo">
        <img src="<?php echo isset($logoData['logo_image']) ? $logoData['logo_image'] : 'images/ptMWP.png'; ?>" alt="mayawana persada">
        </a>
        <?php if (!empty($logoData['logo_text'])) : ?>
            <!-- /* edit font  */ -->
            <p style="margin-top: 18px; font-size: 18px; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars($logoData['logo_text']); ?></p>
        <?php endif; ?>
        
        <!-- Navigasi utama -->
        <div class="col-md-28 col-sm-16 hidden-xs" id="nav-bar">
            <ul class="nav navbar-right">
            <li><a href="/mayawana_web/Mayawana_Web/index.php">Home</a></li>
            <li><a href="/mayawana_web/Mayawana_Web/about.php">About</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/program.php">Program</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/media.php">Media</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/career.php">Career</a></li>
            </ul>
        </div>

        <!-- Navigasi untuk perangkat mobile -->
        <div class="container">
            <div class="row">
                <div class="col-md-3 hidden-xs"></div>
                <div class="col-xs-3 col-xs-offset-20 visible-xs">
                    <a href="index.php" id="mobile_menu"><span class="glyphicon glyphicon-align-justify"></span></a>
                </div>
            </div>
            <div class="col-xs-24 visible-xs" id="mobile_menu_list">
                <ul>
                <li><a href="/mayawana_web/Mayawana_Web/index.php">Home</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/about.php">About</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/program.php">Program</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/media.php">Media</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/career.php">Career</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>

<?php
// Ambil data untuk hero section
$heroQuery = "SELECT * FROM hero_section LIMIT 1";
$heroResult = mysqli_query($koneksi, $heroQuery);
$heroData = mysqli_fetch_assoc($heroResult);
?>

<!-- Hero Section -->
    <section id="hero-section">
        <div class="hero-section" style="background-image: url('<?php echo $heroData['image_path']; ?>');">
            <h1><?php echo htmlspecialchars($heroData['title']); ?></h1>
            <p><?php echo htmlspecialchars($heroData['subtitle']); ?></p>
        </div>
    </section>
<!-- End Hero Section -->


<!-- about -->
<section id="about">
    <div class="container">
        <div class="row">
            <h1><b>About</b></h1>
        </div>
        <?php
        // Fungsi untuk membatasi teks hingga sejumlah kata tertentu
        function batasi_kata($text, $jumlah_kata) {
            $words = explode(' ', $text);
            if (count($words) > $jumlah_kata) {
                return implode(' ', array_slice($words, 0, $jumlah_kata)) . '...';
            }
            return $text;
        }
        // Ambil data dari database
        $query = "SELECT * FROM about ORDER BY id ASC";
        $result = mysqli_query($koneksi, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="row">';
            echo '    <div class="col-md-1"></div>';
            echo '    <div class="col-md-5 col-sm-7 col-xs-24">';
            echo '        <img class="about-image" src="' . htmlspecialchars($row['gambar']) . '" alt="Uploaded image" />';
            echo '    </div>';
            echo '    <div class="col-md-1"></div>';
            echo '    <div class="col-md-16">';
            echo '        <h2>' . htmlspecialchars($row['judul']) . '</h2>';
            echo '        <p>' . htmlspecialchars(batasi_kata($row['deskripsi'], 50)) . '</p>';
            echo '    </div>';
            echo '</div>';
        }
        ?>
    </div>
</section>
<!-- end of about -->

<div class="container">
    <div class="solidline"></div>
</div><!-- solid line -->
<section id="services">
    <div class="container">
        <div class="row">
            <h1><b>Program</b></h1>
        </div>
        
        <?php
        // Mengambil data program dari database
        // PAGINATION SETTING
$limit = 6; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman aktif
$offset = ($page - 1) * $limit; // Hitung offset

// Hitung total data
$total_query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM program");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

$query = "SELECT * FROM program LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);

        $programs = [];

        // Mengumpulkan semua program ke dalam array
        while ($row = mysqli_fetch_assoc($result)) {
            $programs[] = $row;
        }

        // Menampilkan program dengan layout horizontal
        for ($i = 0; $i < count($programs); $i += 2) {
            echo '<div class="row">';

            // Program pertama
            if (isset($programs[$i])) {
                echo '<div class="col-md-5 col-sm-5 col-xs-24">';
                echo '<img class="img-responsive program-image" src="' . htmlspecialchars($programs[$i]['gambar']) . '" alt="Program ' . ($i + 1) . ' Image" />';
                echo '</div>';
                echo '<div class="col-sm-6">';
                echo '<h2><a href="program/program.php?id=' . htmlspecialchars($programs[$i]['id']) . '">' . htmlspecialchars($programs[$i]['judul']) . '</a></h2>';
                echo '<p class="program-description">' . htmlspecialchars(limit_words($programs[$i]['deskripsi'], 13)) . '</p>';
                echo '</div>';
            }
            echo '<div class="col-md-1 col-sm-1"></div>';

             // Program kedua
            if (isset($programs[$i + 1])) {
                echo '<div class="col-md-5 col-sm-5 col-xs-24">';
                echo '<img class="img-responsive program-image" src="' . htmlspecialchars($programs[$i + 1]['gambar']) . '" alt="Program ' . ($i + 2) . ' Image" />';
                echo '</div>';
                echo '<div class="col-sm-6">';
                echo '<h2><a href="program/program.php?id=' . htmlspecialchars($programs[$i + 1]['id']) . '">' . htmlspecialchars($programs[$i + 1]['judul']) . '</a></h2>';
                echo '<p class="program-description">' . htmlspecialchars(limit_words($programs[$i + 1]['deskripsi'], 13)) . '</p>';
                echo '</div>';
            }
            echo '</div>'; // Tutup div.row
        }
        ?>
    </div>
</section>
<!-- Pagination -->
<div class="text-center">
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li><a href="?page=<?= $page - 1 ?>">&laquo; Prev</a></li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="<?= ($i == $page) ? 'active' : '' ?>">
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $total_pages): ?>
            <li><a href="?page=<?= $page + 1 ?>">Next &raquo;</a></li>
        <?php endif; ?>
    </ul>
</div>

<style>
    /* CSS untuk membatasi panjang teks dan menambah pembatas */
    .program-description {
        max-height: 200px; /* Batasi tinggi maksimal */
        overflow: hidden; /* Sembunyikan teks yang melampaui batas */
        text-overflow: ellipsis; /* Tambahkan ellipsis (...) di akhir teks yang terputus */
        display: -webkit-box;
        -webkit-line-clamp: 3; /* Tampilkan hanya 3 baris teks */
        -webkit-box-orient: vertical;
    }
</style>

<!-- solid line -->
<div class="container">
    <div class="solidline"></div>
</div>
<?php
// Ambil page dari query string, jika tidak ada default ke 1
// Pagination setup
    $limit = 4;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Ambil data dari database
    $query = "SELECT * FROM media ORDER BY tanggal DESC LIMIT $limit OFFSET $offset";
    $result = mysqli_query($koneksi, $query);


    // Hitung total halaman
    $total_result = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM media");
    $total_data = mysqli_fetch_assoc($total_result)['total'];
    $total_page = ceil($total_data / $limit);
?>

<section id="blog">
<div class="container" id="bg-light-media">
        <div class="row">
        <h1><b>Media</b></h1>
        </div>
        <div class="content-container" id="article-container">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="row mb-4">
                <div class="col-sm-9">
                    <?php
                    $imagePath = htmlspecialchars($row['gambar']);
                    echo '<img class="img-responsive article-image" src="' . $imagePath . '" alt="' . htmlspecialchars($row['judul']) . '" style="width: 100%; height: auto;">';
                    ?>
                </div>
                 <div class="col-sm-15">
                <h2><?php echo htmlspecialchars($row['judul']); ?></h2>
                <p>
                    <span class="glyphicon glyphicon-calendar"></span> <?php echo date("d F Y", strtotime($row['tanggal'])); ?>
                </p>
                <p><?php echo htmlspecialchars(limit_words($row['deskripsi'], 15)); ?></p> <!-- Menampilkan maksimal 15 kata -->
                <a href="media/media.php?id=<?php echo $row['id']; ?>" class="btn btn-default margin_top">Baca Selengkapnya</a>
            </div>
            </div>
            <?php endwhile; ?>
        </div>
<!-- Pagination Controls -->
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center" style="background-color: #557C56; padding: 10px 3px; border-radius: 5px;"> <!-- Menggunakan warna latar belakang biru -->
        <!-- Tombol "Previous" -->
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" style="color: #dc3545; background-color: transparent; border: none;" href="?page=<?php echo $page - 1; ?>">Prev</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <a class="page-link" style="color: #ffffff; background-color: transparent; border: none;">Prev</a>
            </li>
        <?php endif; ?>

        <!-- Pagination links -->
        <li class="page-item">
            <a class="page-link" style="color: #ffffff; background-color: transparent; border: none;" href="?page=1">1</a>
        </li>
        <li class="page-item">
            <a class="page-link" style="color: #ffffff; background-color: transparent; border: none;" href="?page=2">2</a>
        </li>

        <li class="page-item">
            <a class="page-link" style="color: #ffffff; background-color: transparent; border: none;" href="?page=<?php echo $page + 1; ?>">Next</a>
        </li>
        </ul>
    </nav>


</div>
</section>
 <!-- end of Career -->
<!-- solid line -->
<div class="container">
    <div class="solidline"></div>
</div>

<section id="contact">
    <div class="container">
        <div class="row" id="contact_gray_wap">
            <div class="col-md-24 col-sm-24">
                <h1 class="margin_top">Career</h1>
            </div>

            <div class="col-md-9 col-sm-18 col-sm-push-3">
                <h2>Tentang Kami</h2>
                <?php
                // Ambil deskripsi dari tabel `about_us`
                $query = "SELECT deskripsi FROM about_us LIMIT 1";
                $result = mysqli_query($koneksi, $query);
                $about = mysqli_fetch_assoc($result);

                if ($about) {
                    echo '<p>' . htmlspecialchars($about['deskripsi']) . '</p>';
                } else {
                    echo '<p>Belum ada deskripsi yang tersedia. Silakan tambahkan deskripsi di halaman input.</p>';
                }
                ?>
                <br>

            </div>
 
            
            <div class="col-md-1"></div>
            
            <div class="col-md-10 col-sm-18 col-sm-push-3">
                <!-- Form Aplikasi Karir -->         
                <h2>Lowongan Tersedia</h2>
                <ul class="job-list">
                    <?php
                    $query = "SELECT * FROM career ORDER BY id DESC";
                    $result = mysqli_query($koneksi, $query);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<li>';
                        echo '<h3>Posisi: ' . htmlspecialchars($row['judul']) . '</h3>';
                        echo '<p>Lokasi: ' . htmlspecialchars($row['lokasi']) . '</p>';
                        echo '<p>Deskripsi Pekerjaan: ' . htmlspecialchars($row['deskripsi']) . '</p>';
                        ?>
                        <a href="<?= $links['lamar'] ?? '#' ?>" class="btn btn-default" aria-label="Lamar Sekarang">
                            Lamar Sekarang
                        </a>
                        <?php
                        echo '</li>';
                        
                    }
                    ?>
                </ul> 
            </div>
        </div>
    </div>
</section>


<!-- Footter -->
<div id="footer">
    <div class="footer-content">
        <!-- untuk mengedit tulisan di footer -->
        <p>&copy; 2024 Mayawana - All Rights Reserved</p>
    </div>
    <div class="footer-icons">
        <a href="<?= $links['facebook'] ?? '#' ?>" target="_blank" class="social-icon" aria-label="Facebook">
            <i class="fab fa-facebook"></i>
        </a>
        <a href="<?= $links['youtube'] ?? '#' ?>" target="_blank" class="social-icon" aria-label="YouTube">
            <i class="fab fa-youtube"></i>
        </a>
        <a href="<?= $links['instagram'] ?? '#' ?>" target="_blank" class="social-icon" aria-label="Instagram">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="<?= $links['linkedin'] ?? '#' ?>" target="_blank" class="social-icon" aria-label="LinkedIn">
            <i class="fab fa-linkedin"></i>
        </a>
    </div>
</div>



            
        <!-- end of footer  -->
<script src="js/jquery.min.js"></script>
<script src="js/jquery.scrollto.min.js"></script>
<script src="js/jquery.easing.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.lightbox.min.js"></script>
<script src="js/jquery.flexslider.js"></script>
<script src="js/jquery.singlePageNav.min.js"></script>
<script src="js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/mayawana_web/Mayawana_Web/js/script.js"></script>

 
</body>
</html>