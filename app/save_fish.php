<?php
include('../config/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $habitat = $_POST['habitat'];
    $makanan = $_POST['makanan'];
    $berat_rata_rata = $_POST['berat_rata_rata'];
    $foto = '';

    // Proses unggahan foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "dist/img/";
        $foto = basename($_FILES["foto"]["name"]);
        $target_file = $target_dir . $foto;
        
        // Pindahkan file yang diunggah ke folder yang ditentukan
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
            // Simpan data ikan beserta nama file foto ke database
            $sql = "INSERT INTO ikan (nama, jenis, habitat, makanan, berat_rata_rata, foto) 
                    VALUES ('$nama', '$jenis', '$habitat', '$makanan', '$berat_rata_rata', '$foto')";
            if ($koneksi->query($sql) === TRUE) {
                echo "Data ikan berhasil disimpan.";
            } else {
                echo "Error: " . $sql . "<br>" . $koneksi->error;
            }
        } else {
            echo "Terjadi kesalahan saat mengunggah foto.";
        }
    } else {
        echo "Tidak ada foto yang diunggah atau terjadi kesalahan.";
    }
}

$koneksi->close();
?>
