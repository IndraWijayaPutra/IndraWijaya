<?php
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan ID lokasi dari parameter POST
    parse_str(file_get_contents("php://input"), $_POST);
    $id = $_POST['id'];

    // Query untuk menghapus lokasi
    $sql = "DELETE FROM lokasi WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        // Mengembalikan hasil sebagai teks 'success'
        echo 'success';
    } else {
        // Mengembalikan hasil sebagai teks 'error'
        echo 'error';
    }

    $stmt->close();
    $koneksi->close();
}
?>
