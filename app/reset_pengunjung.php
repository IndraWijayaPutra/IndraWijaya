<?php
include(__DIR__ . '/../config/config.php'); // Path yang benar ke config.php

// Reset jumlah pengunjung di semua kolam menjadi 0
$sql_reset = "UPDATE kolam SET jumlah_pengunjung = 0";
if ($koneksi->query($sql_reset) === TRUE) {
    echo "Jumlah pengunjung berhasil direset.";
} else {
    echo "Error updating record: " . $koneksi->error;
}

// Tutup koneksi
$koneksi->close();
?>
