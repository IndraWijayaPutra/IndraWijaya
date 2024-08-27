<?php
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $sql = "INSERT INTO lokasi (nama, latitude, longitude) VALUES (?, ?, ?)";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("sdd", $nama, $latitude, $longitude);
        if ($stmt->execute()) {
            // Redirect ke halaman peta setelah berhasil menambahkan lokasi
            header("Location: pemetaan_lokasi.php?status=success");
            exit;
        } else {
            echo "Gagal menambahkan lokasi.";
        }
        $stmt->close();
    } else {
        echo "Gagal mempersiapkan query.";
    }
}
?>
