<?php
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan data dari form
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $habitat = $_POST['habitat'];
    $makanan = $_POST['makanan'];
    $berat_rata_rata = $_POST['berat_rata_rata'];
    $foto = ''; // Kosongkan jika tidak ada file foto yang diunggah

    // Proses unggahan foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "dist/img/";
        $foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        
        // Pindahkan file yang diunggah ke folder yang ditentukan
        if (!move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            echo "Terjadi kesalahan saat mengunggah foto.";
            exit();
        }
    }

    // Query untuk memperbarui data ikan berdasarkan nama
    $sql = "UPDATE ikan 
            SET jenis = ?, habitat = ?, makanan = ?, berat_rata_rata = ?, foto = ? 
            WHERE nama = ?";
    $stmt = $koneksi->prepare($sql);

    // Menggunakan bind_param untuk memasukkan data ke dalam query
    $stmt->bind_param('ssssss', $jenis, $habitat, $makanan, $berat_rata_rata, $foto, $nama);

    if ($stmt->execute()) {
        // Simpan pesan sukses dalam session
        header('Location: view_ikan.php?status=updated');
    } else {
        // Simpan pesan error dalam session
        header('Location: view_ikan.php?status=error');
    }

    $stmt->close();
    $koneksi->close();
}
?>
