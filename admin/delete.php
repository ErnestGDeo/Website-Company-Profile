<?php
include '../koneksi.php'; // Pastikan path ini sesuai dengan struktur folder Anda

// Pastikan ID diterima dari query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $tableName = $_GET['table']; // Ambil nama tabel

    // Menghapus data dari tabel
    $deleteQuery = "DELETE FROM $tableName WHERE id = '$id'";
    mysqli_query($koneksi, $deleteQuery);
    
    // Redirect setelah hapus
    header("Location: data.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Data</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Data Berhasil Dihapus</h2>
        <a href="data.php" class="btn btn-primary">Kembali</a>
    </div>
</body>
</html>

<?php
// Tutup koneksi
mysqli_close($koneksi);
?>
