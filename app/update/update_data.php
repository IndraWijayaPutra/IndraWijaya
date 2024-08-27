<?php
session_start();
include('../../config/config.php');

// Pastikan data dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $no_telepon = mysqli_real_escape_string($koneksi, $_POST['no_telepon']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $umur = mysqli_real_escape_string($koneksi, $_POST['umur']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    $tanggal_daftar = mysqli_real_escape_string($koneksi, $_POST['tanggal_daftar']);
    $status_pemesanan = mysqli_real_escape_string($koneksi, $_POST['status_pemesanan']); // Status pemesanan

    // Query untuk melakukan update data pengunjung dan status pemesanan
    $query = "
        UPDATE pengunjung 
        SET 
            username = '$username', 
            email = '$email', 
            no_telepon = '$no_telepon', 
            alamat = '$alamat', 
            umur = '$umur', 
            role = '$role', 
            tanggal_daftar = '$tanggal_daftar',
            status_pemesanan = '$status_pemesanan'  -- Menambahkan status pemesanan
        WHERE id = '$id'
    ";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['flash_message'] = [
            'type' => 'success',
            'message' => 'Data berhasil diperbarui!'
        ];
        header('Location: ../Data_admin.php?status=updated');
    } else {
        $_SESSION['flash_message'] = [
            'type' => 'danger',
            'message' => 'Gagal memperbarui data: ' . mysqli_error($koneksi)
        ];
        header('Location: ../Data_admin.php?status=error');
    }
    exit();
} else {
    $_SESSION['flash_message'] = [
        'type' => 'danger',
        'message' => 'Metode pengiriman tidak valid.'
    ];
    header('Location: ../Data_admin.php?status=error');
    exit();
}
