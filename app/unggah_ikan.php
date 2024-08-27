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
                $fotoDestination = 'dist/img/' . $newFotoName; // Save to the uploads folder outside the current directory
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
