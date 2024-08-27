<?php
session_start();
include('../../../config/config.php'); // Pastikan path ini benar

// Cek jika parameter 'id' ada di POST
if (isset($_POST['id'])) {
    // Ambil data dari form dan sanitasi input
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $tanggal_pemesanan = mysqli_real_escape_string($koneksi, $_POST['tanggal_pemesanan']);
    $status_pemesanan = mysqli_real_escape_string($koneksi, $_POST['status_pemesanan']);
    $status_nota = mysqli_real_escape_string($koneksi, $_POST['status_nota']);
    $kode_pesanan = mysqli_real_escape_string($koneksi, $_POST['kode_pesanan']);
    $jumlah_pengunjung = mysqli_real_escape_string($koneksi, $_POST['jumlah_pengunjung']);

    // Query untuk update data
    $query = "UPDATE pemesanan_tiket 
              SET tanggal_pemesanan = '$tanggal_pemesanan', 
                  status_pemesanan = '$status_pemesanan', 
                  status_nota = '$status_nota', 
                  kode_pesanan = '$kode_pesanan', 
                  jumlah_pengunjung = '$jumlah_pengunjung'
              WHERE id = $id";

    // Eksekusi query
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => 'Data berhasil diperbarui!'
        ];
        header('Location: ../../Data_admin.php?status=updated');
    } else {
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'message' => 'Gagal memperbarui data: ' . mysqli_error($koneksi)
        ];
        header('Location: ../../Data_admin.php?status=error');
    }
    exit();
} else {
    die("ID tidak ditemukan.");
}
?>
