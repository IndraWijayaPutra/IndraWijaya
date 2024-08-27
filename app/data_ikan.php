<?php
include('../config/config.php');

// Jika formulir dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul_laporan = $_POST['judul_laporan'];
    $nama_pengunjung = $_POST['nama_pengunjung'];
    $tanggal = $_POST['tanggal'];
    $kesan_pesan = $_POST['kesan_pesan'];

    // Proses upload gambar
    $target_dir = "uploads/";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));

    // Cek apakah file gambar adalah gambar sebenarnya
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check === false) {
        echo "<script>alert('File yang diunggah bukan gambar.');</script>";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["gambar"]["size"] > 5000000) { // 5MB
        echo "<script>alert('Maaf, ukuran gambar terlalu besar.');</script>";
        $uploadOk = 0;
    }

    // Cek jenis file gambar
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "<script>alert('Maaf, hanya gambar JPG, JPEG, PNG & GIF yang diperbolehkan.');</script>";
        $uploadOk = 0;
    }

    // Cek apakah $uploadOk bernilai 0 karena kesalahan
    if ($uploadOk == 0) {
        echo "<script>alert('Maaf, gambar tidak diunggah.');</script>";
    } else {
        $target_file = $target_dir . basename($_FILES["gambar"]["name"]);
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO momen (judul_laporan, nama_pengunjung, tanggal, kesan_pesan, gambar) 
                    VALUES ('$judul_laporan', '$nama_pengunjung', '$tanggal', '$kesan_pesan', '" . basename($_FILES["gambar"]["name"]) . "')";
            if ($koneksi->query($sql) === TRUE) {
                echo "<script>
                    alert('Terima kasih sudah membagikan momen Anda.');
                    window.location.href = 'http://localhost/coba/app/index.php?page=dashboard';
                </script>";
                exit();
            } else {
                echo "<script>alert('Error: " . $sql . "<br>" . $koneksi->error . "');</script>";
            }
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah gambar.');</script>";
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
                <label for="judul_laporan" class="form-label">Judul</label>
                <input type="text" class="form-control" id="judul_laporan" name="judul_laporan" required>
            </div>
            <div class="mb-3">
                <label for="nama_pengunjung" class="form-label">Nama Pengunjung</label>
                <input type="text" class="form-control" id="nama_pengunjung" name="nama_pengunjung" required>
            </div>
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label for="kesan_pesan" class="form-label">Berikan Pendapat Anda</label>
                <textarea class="form-control" id="kesan_pesan" name="kesan_pesan" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="gambar" class="form-label">Unggah Gambar</label>
                <input class="form-control" type="file" id="gambar" name="gambar" required>
            </div>
            <button type="submit" class="btn btn-primary">Kirim</button>
            <a href="http://localhost/coba/app/" class="btn btn-primary">Kembali</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
