<?php
session_start();
include('../config/config.php');

// Ambil data umur pengunjung
$sql = "SELECT umur, COUNT(*) AS jumlah FROM pengunjung GROUP BY umur";
$result = $koneksi->query($sql);

$data = [
    'labels' => [],
    'data' => []
];

while ($row = $result->fetch_assoc()) {
    $umur = $row['umur'];
    $data['labels'][] = $umur . ' tahun';
    $data['data'][] = $row['jumlah'];
}

header('Content-Type: application/json');
echo json_encode($data);
?>
