<?php
include('../config/config.php');

// Jika formulir dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kesan_pesan = $_POST['kesan_pesan'];

    // Proses upload gambar
    $target_dir = "../uploads/";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));

    // Pastikan direktori 'uploads' ada
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Cek apakah file gambar adalah gambar sebenarnya
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check === false) {
        echo "File yang diunggah bukan gambar.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["gambar"]["size"] > 5000000) { // 5MB
        echo "Maaf, ukuran gambar terlalu besar.";
        $uploadOk = 0;
    }

    // Cek jenis file gambar
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Maaf, hanya gambar JPG, JPEG, PNG & GIF yang diperbolehkan.";
        $uploadOk = 0;
    }

    // Cek apakah $uploadOk bernilai 0 karena kesalahan
    if ($uploadOk == 0) {
        echo "Maaf, gambar tidak diunggah.";
    } else {
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO momen (kesan_pesan, gambar) VALUES ('$kesan_pesan', '" . basename($_FILES["gambar"]["name"]) . "')";
            if ($koneksi->query($sql) === TRUE) {
                echo "Data berhasil disimpan.";
                header('Location: dashboard.php'); // Mengalihkan ke halaman dashboard
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $koneksi->error;
            }
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah gambar.";
        }
    }

    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bagikan Momen</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Bagikan Momen Anda di sini</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="kesan_pesan" class="form-label">Kesan dan Pesan</label>
                <textarea class="form-control" id="kesan_pesan" name="kesan_pesan" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Unggah Gambar</label>
                <input class="form-control" type="file" id="gambar" name="gambar" required>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
