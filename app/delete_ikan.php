<?php
include('../config/config.php');

// Ambil nama ikan dari query string
$nama = isset($_GET['nama']) ? $_GET['nama'] : '';

if ($nama) {
    // Hapus ikan berdasarkan nama
    $stmt = $koneksi->prepare("DELETE FROM ikan WHERE nama = ?");
    $stmt->bind_param('s', $nama);

    if ($stmt->execute()) {
        // Redirect dengan status berhasil
        header("Location: view_ikan.php?status=deleted");
    } else {
        // Redirect dengan status error
        header("Location: view_ikan.php?status=error");
    }
    $stmt->close();
} else {
    // Redirect dengan status error jika nama tidak ditemukan
    header("Location: view_ikan.php?status=error");
}

$koneksi->close();
?>
