<?php
session_start();
include('../config/config.php');

// Periksa apakah pengguna sudah login dan merupakan admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo 'Unauthorized';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_pesanan = isset($_POST['kode_pesanan']) ? $_POST['kode_pesanan'] : '';

    // Validasi input
    if (empty($kode_pesanan)) {
        echo 'Kode pemesanan tidak boleh kosong.';
        exit();
    }

    // Update status pemesanan menjadi 'Ditolak'
    $sql = "UPDATE pemesanan_tiket SET status_pemesanan = 'Ditolak' WHERE kode_pesanan = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $kode_pesanan);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Terjadi kesalahan saat memperbarui status pesanan.';
    }
    $stmt->close();
} else {
    echo 'Invalid request method.';
}
?>
