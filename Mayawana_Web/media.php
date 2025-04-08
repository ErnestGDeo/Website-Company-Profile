<?php
// Koneksi ke database
include '../koneksi.php';


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
function limit_words($text, $limit) {
    $words = explode(" ", $text);
    return implode(" ", array_slice($words, 0, $limit)) . (count($words) > $limit ? "..." : "");
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

// Ambil data media dari database
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 5; // misalnya 5 item per halaman
$offset = ($page - 1) * $limit;

$queryMedia = "SELECT * FROM media ORDER BY tanggal DESC LIMIT $limit OFFSET $offset";
$mediaResult = mysqli_query($koneksi, $queryMedia);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>PT Mayawana Persada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    
    <link rel="shortcut icon" href="images/logodaun.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="css/jquery.lightbox.css" rel="stylesheet">
    <link href="css/flexslider.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<header>
    <!-- Bagian Media Sosial -->
    <div class="medsos">
        <a href="<?= $links['facebook'] ?? '#' ?>" target="_blank" class="social-icon"><i class="fab fa-facebook"></i></a>
        <a href="<?= $links['youtube'] ?? '#' ?>" target="_blank" class="social-icon"><i class="fab fa-youtube"></i></a>
        <a href="<?= $links['instagram'] ?? '#' ?>" target="_blank" class="social-icon"><i class="fab fa-instagram"></i></a>
        <a href="<?= $links['linkedin'] ?? '#' ?>" target="_blank" class="social-icon"><i class="fab fa-linkedin"></i></a>
        <a href="../form login/login.php" class="social-icon"><i class="fa-regular fa-circle-user"></i></a>  
    </div>

    <!-- Bagian Logo dan Navigasi -->
    <div class="col-md-5 col-md-push-1 col-sm-6">
        <a href="index.php" id="logo">
        <img src="<?php echo isset($logoData['logo_image']) ? $logoData['logo_image'] : 'images/ptMWP.png'; ?>" alt="mayawana persada">
        </a>
        <?php if (!empty($logoData['logo_text'])) : ?>
            <p style="margin-top: 18px; font-size: 18px; font-family: 'Montserrat', sans-serif;"><?php echo htmlspecialchars($logoData['logo_text']); ?></p>
        <?php endif; ?>

        <div class="col-md-28 col-sm-16 hidden-xs" id="nav-bar">
            <ul class="nav navbar-right">
                <li><a href="/mayawana_web/Mayawana_Web/index.php">Home</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/about.php">About</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/program.php">Program</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/media.php">Media</a></li>
                <li><a href="/mayawana_web/Mayawana_Web/career.php">Career</a></li>
            </ul>
        </div>

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

<main>
    <div class="container">
        <div class="solidline"></div>
    </div>

    <section id="blog">
        <div class="container" id="bg-light-media">
            <div class="row">
                <h1><b>Media</b></h1>
            </div>
            <div class="content-container" id="article-container">
            <?php while ($row = mysqli_fetch_assoc($mediaResult)): 
                // Hitung total data
            $countQuery = "SELECT COUNT(*) AS total FROM media";
            $countResult = mysqli_query($koneksi, $countQuery);
            $totalRow = mysqli_fetch_assoc($countResult)['total'];

            $totalPages = ceil($totalRow / $limit);
            ?>

                <div class="row mb-4">
                    <div class="col-sm-9">
                        <?php
                        $imagePath = htmlspecialchars($row['gambar']);
                        echo '<img class="img-responsive article-image" src="' . $imagePath . '" alt="' . htmlspecialchars($row['judul']) . '" style="width: 100%; height: auto;">';
                        ?>
                    </div>
                    <div class="col-sm-15">
                        <h2><?php echo htmlspecialchars($row['judul']); ?></h2>
                        <p><span class="glyphicon glyphicon-calendar"></span> <?php echo date("d F Y", strtotime($row['tanggal'])); ?></p>
                        <p><?php echo htmlspecialchars(limit_words($row['deskripsi'], 15)); ?></p>
                        <a href="media/media.php?id=<?php echo $row['id']; ?>" class="btn btn-default margin_top">Baca Selengkapnya</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <!-- Pagination -->
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center" style="background-color: #557C56; padding: 10px 3px; border-radius: 5px;">
        <!-- Tombol Prev -->
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" style="color: #dc3545; background-color: transparent; border: none;" href="?page=<?= $page - 1 ?>">Prev</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <a class="page-link" style="color: #ffffff; background-color: transparent; border: none;">Prev</a>
            </li>
        <?php endif; ?>

        <!-- Tombol nomor halaman -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                <a class="page-link" style="color: <?= ($i == $page) ? '#557C56' : '#ffffff' ?>; background-color: transparent; border: none;" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Tombol Next -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" style="color: #ffffff; background-color: transparent; border: none;" href="?page=<?= $page + 1 ?>">Next</a>
            </li>
        <?php else: ?>
            <li class="page-item disabled">
                <a class="page-link" style="color: #ffffff; background-color: transparent; border: none;">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

        </div>
    </section>
</main>

<!-- Footer berada di luar main content -->
<footer id="footer">
    <div class="footer-content">
        <p>&copy; 2024 Mayawana - All Rights Reserved</p>
    </div>
    <div class="footer-icons">
        <a href="<?= $links['facebook'] ?? '#' ?>" target="_blank" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
        <a href="<?= $links['youtube'] ?? '#' ?>" target="_blank" class="social-icon" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        <a href="<?= $links['instagram'] ?? '#' ?>" target="_blank" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="<?= $links['linkedin'] ?? '#' ?>" target="_blank" class="social-icon" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
    </div>
</footer>

<!-- Scripts -->
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