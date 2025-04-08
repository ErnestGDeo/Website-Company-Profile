<?php
// Menghubungkan ke database
include '../../koneksi.php';

// Memeriksa apakah parameter id ada di URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Query untuk mengambil data media berdasarkan id
    $query = "SELECT * FROM media WHERE id = $id";
    $result = mysqli_query($koneksi, $query);

    // Cek apakah data ditemukan
    if (mysqli_num_rows($result) > 0) {
        $media = mysqli_fetch_assoc($result);
    } else {
        echo "Media tidak ditemukan.";
        exit;
    }
} else {
    echo "ID media tidak valid.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($media['judul']); ?> - Detail Media</title>
    <link rel="shortcut icon" href="../images/logodaun.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <style>
        body{
            font-family: 'Times New Roman', Times, serif;
            background-color: rgb(184, 184, 184);
        }
        /* Styling opsional untuk konten media */
        .media-container {
            margin: 45px auto;
            max-width: 800px;
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .media-image {
            width: 100%;
            height: auto;
            object-fit: cover;
            margin-bottom: 20px;
            border: 5px solid white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        /* Styling untuk navbar */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: #f8f9fa;
            border-bottom: 2px solid #ddd;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand img {
            width: 40px;
            height: auto;
            margin-top: -9px;
        }

        .navbar-btn {
            margin-top: 9px;
        }

        .navbar-welcome {
            font-size: 25px;
            font-weight: bold;
            color: #333;
            text-align: center;
            flex-grow: 1;
        }
        .media-container h1 {
            font-size: 28px;
            text-align: left;
            margin-bottom: 20px;
        }
        .media-container p {
            text-align: left;
            font-size: 18px;
            margin-bottom: 15px;
        }
        @media (max-width: 768px) {
            .media-container {
                padding: 0 10px;
            }
            .navbar {
                flex-wrap: wrap;
                padding: 10px;
            }
            .navbar-btn{
                margin-top: 9px;
            }

            .navbar-brand {
                margin-bottom: -5px;
            }

            .navbar-right {
                text-align: right;
                margin-top: 5px;
            }

            .navbar-welcome {
                font-size: 20px;
                text-align: center;
                flex-grow: 1;
                margin-top: 10px;
            }

            .navbar-right .btn {
                margin-top: 5px;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<!-- Navbar dengan logo gambar dan tombol Kembali -->
<nav class="navbar navbar-default">
        <a class="navbar-brand" href="#">
            <img src="../images/logodaun.png" alt="Logo">
        </a>
        <div class="navbar-welcome">Media Mayawana</div>
        <div class="navbar-right">
            <a href="../index.php" class="btn btn-primary navbar-btn">Kembali</a>
        </div>
    </nav>

<div class="container media-container">
    <?php if (!empty($media['gambar'])): ?>
        <img class="media-image" src="../<?php echo htmlspecialchars($media['gambar']); ?>" alt="<?php echo htmlspecialchars($media['judul']); ?>">
    <?php endif; ?>
    <h1><b><?php echo htmlspecialchars($media['judul']); ?></b></h1>
    <p><span class="glyphicon glyphicon-calendar"></span> <?php echo date("d F Y", strtotime($media['tanggal'])); ?></p>
    <p><?php echo nl2br(htmlspecialchars($media['deskripsi'])); ?></p>
</div>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
