<?php
include '../../koneksi.php'; // Memanggil koneksi database

// Periksa apakah ada ID yang dikirimkan melalui URL
if (isset($_GET['id'])) {
    $id_program = mysqli_real_escape_string($koneksi, $_GET['id']);
    
    // Query untuk mengambil data program berdasarkan ID
    $query = "SELECT * FROM program WHERE id = '$id_program'";
    $result = mysqli_query($koneksi, $query);

    // Periksa apakah data ditemukan
    if (mysqli_num_rows($result) > 0) {
        $program = mysqli_fetch_assoc($result); // Ambil data program
    } else {
        echo "Program tidak ditemukan.";
        exit; // Hentikan script jika program tidak ditemukan
    }
} else {
    echo "ID program tidak diberikan.";
    exit; // Hentikan script jika ID tidak ada
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($program['judul']); ?></title>
    <link rel="shortcut icon" href="../images/logodaun.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }

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
            margin-top: -8px;
        }

        .navbar-btn {
            margin-top: 5px;
        }

        .navbar-welcome {
            font-size: 25px;
            font-weight: bold;
            color: #333;
            text-align: center;
            flex-grow: 1;
        }

        .program-container {
            margin: 45px auto;
            max-width: 100%;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
        }

        .program-image {
            width: 100%;
            max-width: 700px;
            margin: 20px auto;
            border: 5px solid #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: block;
        }

        .bg-color {
            background-color: rgb(184, 184, 184);
            color: rgb(0, 0, 0);
            padding: 20px;
        }

        .program-font {
            text-align: left;
            margin: 20px 0;
        }

        .program-font h1 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .program-font p {
            font-size: 18px;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-wrap: wrap;
                padding: 10px;
            }

            .navbar-brand {
                margin-bottom: 10px;
            }
            .navbar-brand img{
                margin-top: 0px;
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

            .program-font h1 {
                font-size: 28px;
            }

            .program-font p {
                font-size: 16px;
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
        <div class="navbar-welcome">Program Mayawana</div>
        <div class="navbar-right">
            <a href="../index.php" class="btn btn-primary navbar-btn">Kembali</a>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="bg-color">
        <div class="container program-container">
            <?php if (!empty($program['gambar'])): ?>
                <!-- Gambar Program -->
                <img class="program-image" src="../<?php echo htmlspecialchars($program['gambar']); ?>" alt="Gambar Program">
            <?php endif; ?>

            <!-- Teks Program -->
            <div class="program-font">
                <h1><?php echo htmlspecialchars($program['judul']); ?></h1>
                <p><?php echo nl2br(htmlspecialchars($program['deskripsi'])); ?></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>