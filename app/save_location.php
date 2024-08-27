<?php
include('../config/config.php');

// Ambil data dari form
$nama = $_POST['nama'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

// Query untuk menyimpan lokasi
$sql = "INSERT INTO lokasi (nama, latitude, longitude) VALUES (?, ?, ?)";
if ($stmt = $koneksi->prepare($sql)) {
    $stmt->bind_param("sss", $nama, $latitude, $longitude);
    if ($stmt->execute()) {
        // Redirect ke peta dengan status sukses
        header("Location: pemetaan_lokasi.php?status=success");
    } else {
        // Redirect ke peta dengan status error
        header("Location: pemetaan_lokasi.php?status=error");
    }
    $stmt->close();
} else {
    // Redirect ke peta dengan status error
    header("Location: pemetaan_lokasi.php?status=error");
}

$koneksi->close();
?>
