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

    // Update status pemesanan
    $sql = "UPDATE pemesanan_tiket SET status_pemesanan = 'Disetujui' WHERE kode_pesanan = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('s', $kode_pesanan);

    if ($stmt->execute()) {
        // Ambil data pemesanan
        $sql_get = "SELECT tanggal_pemesanan, kolam_id, jumlah_pengunjung, total_harga FROM pemesanan_tiket WHERE kode_pesanan = ?";
        $stmt_get = $koneksi->prepare($sql_get);
        $stmt_get->bind_param('s', $kode_pesanan);
        $stmt_get->execute();
        $stmt_get->bind_result($tanggal_pemesanan, $kolam_id, $jumlah_pengunjung, $total_harga);
        $stmt_get->fetch();
        $stmt_get->close();

        // Validasi tanggal_pemesanan
        if (empty($tanggal_pemesanan)) {
            // Atur tanggal default jika tanggal_pemesanan kosong
            $tanggal_pemesanan = date('Y-m-d'); // Atau sesuaikan dengan format yang diinginkan
        }

        // Masukkan total harga dan tanggal_pemesanan ke laporan_keuangan
        $sql_insert = "INSERT INTO laporan_keuangan (tanggal, total_pemasukan, kode_pesanan) 
                       VALUES (?, ?, ?) 
                       ON DUPLICATE KEY UPDATE total_pemasukan = total_pemasukan + VALUES(total_pemasukan)";
        $stmt_insert = $koneksi->prepare($sql_insert);
        $stmt_insert->bind_param('sds', $tanggal_pemesanan, $total_harga, $kode_pesanan);
        $stmt_insert->execute();
        $stmt_insert->close();

        // Perbarui jumlah pengunjung di tabel kolam
        $sql_update_kolam = "UPDATE kolam SET jumlah_pengunjung = jumlah_pengunjung + ? WHERE id = ?";
        $stmt_update_kolam = $koneksi->prepare($sql_update_kolam);
        $stmt_update_kolam->bind_param('ii', $jumlah_pengunjung, $kolam_id);
        $stmt_update_kolam->execute();
        $stmt_update_kolam->close();

        echo 'success';
    } else {
        echo 'Terjadi kesalahan saat memperbarui status pesanan.';
    }
    $stmt->close();
} else {
    echo 'Invalid request method.';
}
?>
