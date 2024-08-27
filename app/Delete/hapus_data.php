<?php
session_start();
include('../../config/config.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Cek apakah data pemesanan dengan ID ini ada
    $checkQuery = "SELECT pt.*, p.username 
                   FROM pemesanan_tiket pt
                   JOIN pengunjung p ON pt.pengunjung_id = p.id
                   WHERE pt.id = $id";
    $checkResult = mysqli_query($koneksi, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Jika ada, lakukan penghapusan
        $deleteQuery = "DELETE FROM pemesanan_tiket WHERE id = $id";

        if (mysqli_query($koneksi, $deleteQuery)) {
            $_SESSION['flash_message'] = [
                'type' => 'success',
                'message' => 'Data pemesanan berhasil dihapus!'
            ];
            header('Location: ../Data_admin.php?status=deleted');
        } else {
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'message' => 'Gagal menghapus data: ' . mysqli_error($koneksi)
            ];
            header('Location: ../Data_admin.php?status=error');
        }
    } else {
        // Jika data tidak ditemukan
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'message' => 'Data pemesanan tidak ditemukan atau sudah dihapus!'
        ];
        header('Location: ../Data_admin.php?status=error');
    }
} else {
    // Jika ID tidak disediakan
    $_SESSION['flash_message'] = [
        'type' => 'danger',
        'message' => 'ID tidak valid!'
    ];
    header('Location: ../Data_admin.php?status=error');
}
exit();
?>
