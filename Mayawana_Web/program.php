<?php
// Koneksi ke database
include '../koneksi.php';


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
            <li><a href="index.php">Home</a></li>
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


<div class="container">
    <div class="solidline"></div>
</div><!-- solid line -->
<section id="services">
    <div class="container">
        <div class="row">
            <h1><b>Program</b></h1>
        </div>
        
        <?php
        // PAGINATION SETTING
$limit = 6; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman aktif
$offset = ($page - 1) * $limit; // Hitung offset

// Hitung total data
$total_query = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM program");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

        // Mengambil data program dari database
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