<?php
include '../koneksi.php'; // Ensure this path is correct

// Check if ID and table name are passed in the query string
if (isset($_GET['id']) && isset($_GET['table'])) {
    $id = $_GET['id'];
    $tableName = $_GET['table'];

    // Fetch current data from the table
    $result = mysqli_query($koneksi, "SELECT * FROM $tableName WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);
}

// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = ''; // Untuk menyimpan field yang akan diupdate
    foreach ($_POST as $key => $value) {
        if ($key !== 'id') { // Jangan sertakan ID dalam update
            $fields .= "$key='" . mysqli_real_escape_string($koneksi, $value) . "', ";
        }
    }

        // Periksa apakah file gambar diunggah
        if (!empty($_FILES['gambar']['name'])) {
            $gambarName = $_FILES['gambar']['name'];
            $gambarTmpName = $_FILES['gambar']['tmp_name'];
            $gambarPath = "../Mayawana_Web/uploads/" . $gambarName;
    
            if (move_uploaded_file($gambarTmpName, $gambarPath)) {
                // Simpan path lengkap 'uploads/namagambar.jpg' ke database
                $fields .= "gambar='" . mysqli_real_escape_string($koneksi, 'uploads/' . $gambarName) . "', ";
            }
        }

    $fields = rtrim($fields, ', '); // Menghapus koma terakhir

    // Update data di tabel
    $updateQuery = "UPDATE $tableName SET $fields WHERE id = '$id'";
    mysqli_query($koneksi, $updateQuery);   

    // Redirect setelah update
    header("Location: data.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Data</h2>
        <form method="post" enctype="multipart/form-data">
            <?php foreach ($row as $key => $value): ?>
                <?php if ($key !== 'id' && $key !== 'image'): // Exclude the ID and image field from display ?>
                    <div class="form-group">
                        <label><?php echo htmlspecialchars($key); ?></label>
                        <input type="text" class="form-control" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>" required>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <!-- Display current image and file input to replace it -->
            <?php if (!empty($row['image'])): ?>
                <div class="form-group">
                    <label>Current Image:</label><br>
                    <img src="../Mayawana_Web/uploads/ echo htmlspecialchars($row['image']); ?>" alt="Current Image" style="max-width: 150px; height: auto;">
                </div>
            <?php endif; ?>
            
            <div class="mb-3">
    <label for="gambar" class="form-label">Upload Gambar</label>
    <input class="form-control" type="file" name="gambar" accept="image/*">
</div>

            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="data.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($koneksi);
?>
