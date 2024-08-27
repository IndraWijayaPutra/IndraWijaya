<?php
session_start();
include('../config/config.php');

// Periksa apakah pengguna sudah login dan memiliki hak akses
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil pemesanan_id dari permintaan POST
    $pemesanan_id = isset($_POST['pemesanan_id']) ? intval($_POST['pemesanan_id']) : 0;

    // Validasi pemesanan_id
    if ($pemesanan_id <= 0) {
        echo 'error';
        exit();
    }

    // Update status nota menjadi 'Dikirim'
    $sql = "UPDATE pemesanan_tiket SET status_nota = 'Dikirim' WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $pemesanan_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Ambil detail pesanan untuk notifikasi
        $sql = "SELECT p.username, pt.total_harga FROM pemesanan_tiket pt
                LEFT JOIN pengunjung p ON pt.pengunjung_id = p.id
                WHERE pt.id = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $pemesanan_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        // Kirim email atau notifikasi (fungsi ini harus diimplementasikan)
        // sendEmailNotification($data['username'], $data['total_harga']);

        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    $koneksi->close();
}
?>
