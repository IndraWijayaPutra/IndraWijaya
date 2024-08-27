<?php
include('../config/config.php');

// Periksa apakah aksi tersedia
if (isset($_POST['aksi']) && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $aksi = $_POST['aksi'];

    if ($aksi === 'setujui') {
        $sql = "UPDATE pemesanan_tiket SET status = 'Disetujui' WHERE id = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Tambahkan ke laporan keuangan (kode untuk itu)
        // ...

        // Kirim notifikasi ke pengunjung (kode untuk itu)
        // ...

    } elseif ($aksi === 'tolak') {
        $sql = "UPDATE pemesanan_tiket SET status = 'Ditolak' WHERE id = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();

        // Kirim notifikasi ke pengunjung (kode untuk itu)
        // ...
    }
}
?>
