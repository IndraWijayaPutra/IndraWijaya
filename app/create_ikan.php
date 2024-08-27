<?php
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $habitat = $_POST['habitat'];
    $makanan = $_POST['makanan'];
    $berat_rata_rata = $_POST['berat_rata_rata'];

    // Handling the file upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];
        $fotoName = basename($foto['name']);
        $fotoSize = $foto['size'];
        $fotoTmpName = $foto['tmp_name'];
        $fotoError = $foto['error'];
        $fotoType = $foto['type'];

        $fotoExt = strtolower(pathinfo($fotoName, PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array($fotoExt, $allowed)) {
            if ($fotoSize < 5000000) { // Limit to 5MB
                $newFotoName = uniqid('', true) . "." . $fotoExt;
                $fotoDestination = 'uploads/' . $newFotoName;
                move_uploaded_file($fotoTmpName, $fotoDestination);
            } else {
                header('Location: view_ikan.php?status=filesizeerror');
                exit();
            }
        } else {
            header('Location: view_ikan.php?status=filetypeerror');
            exit();
        }
    } else {
        header('Location: view_ikan.php?status=nophotoerror');
        exit();
    }

    // Query untuk menambahkan data ikan, termasuk kolom foto
    $sql = "INSERT INTO ikan (nama, jenis, habitat, makanan, berat_rata_rata, foto) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('ssssds', $nama, $jenis, $habitat, $makanan, $berat_rata_rata, $newFotoName);

    if ($stmt->execute()) {
        // Redirect dengan status success
        header('Location: view_ikan.php?status=added');
    } else {
        // Redirect dengan status error
        header('Location: view_ikan.php?status=error');
    }

    $stmt->close();
    $koneksi->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unggah Ikan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Unggah Data Ikan</h1>
        <form action="unggah_ikan.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nama">Nama Ikan</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="jenis">Jenis Ikan</label>
                <input type="text" class="form-control" id="jenis" name="jenis" required>
            </div>
            <div class="form-group">
                <label for="habitat">Habitat</label>
                <input type="text" class="form-control" id="habitat" name="habitat" required>
            </div>
            <div class="form-group">
                <label for="makanan">Makanan</label>
                <input type="text" class="form-control" id="makanan" name="makanan" required>
            </div>
            <div class="form-group">
                <label for="berat_rata_rata">Berat Rata-Rata (kg)</label>
                <input type="number" step="0.01" class="form-control" id="berat_rata_rata" name="berat_rata_rata" required>
            </div>
            <div class="form-group">
                <label for="foto">Foto Ikan</label>
                <input type="file" class="form-control-file" id="foto" name="foto" required>
            </div>
            <button type="submit" class="btn btn-primary">Unggah</button>
        </form>
    </div>
</body>
</html>
