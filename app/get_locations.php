<?php
include('../config/config.php');

// Mengatur header untuk output JSON
header('Content-Type: application/json');

try {
    // Query untuk mengambil data lokasi termasuk kolom tambahan
    $sql = "SELECT id, nama, latitude, longitude, jumlah_pengunjung, rekomendasi FROM lokasi";
    $result = $koneksi->query($sql);

    if ($result === false) {
        throw new Exception('Query failed: ' . $koneksi->error);
    }

    $locations = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $lat = floatval($row['latitude']);
            $lon = floatval($row['longitude']);

            // Validasi koordinat
            if (($lat >= -90 && $lat <= 90) && ($lon >= -180 && $lon <= 180)) {
                $locations[] = array(
                    'id' => $row['id'], // Menambahkan ID lokasi
                    'nama' => $row['nama'],
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'jumlah_pengunjung' => intval($row['jumlah_pengunjung']), // Menambahkan jumlah pengunjung
                    'rekomendasi' => $row['rekomendasi'] // Menambahkan rekomendasi
                );
            }
        }
    }

    // Mengembalikan data dalam format JSON
    echo json_encode($locations);
} catch (Exception $e) {
    // Mengembalikan pesan kesalahan dalam format JSON
    echo json_encode(array('error' => $e->getMessage()));
} finally {
    // Menutup koneksi
    $koneksi->close();
}
?>
