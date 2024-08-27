<?php
session_start();
include('../config/config.php');

// Cek jika parameter pemesanan_id ada
if (!isset($_POST['pemesanan_id']) || !filter_var($_POST['pemesanan_id'], FILTER_VALIDATE_INT)) {
    die('Parameter pemesanan_id tidak valid.');
}

$pemesanan_id = $_POST['pemesanan_id'];

// Ambil data pesanan
$sql = "SELECT pt.id, pt.tanggal_pemesanan, pt.status_pemesanan, pt.total_harga, p.username
        FROM pemesanan_tiket pt
        LEFT JOIN pengunjung p ON pt.pengunjung_id = p.id
        WHERE pt.id = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param('i', $pemesanan_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    echo '<!DOCTYPE html>';
    echo '<html lang="id">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>Nota Pemesanan</title>';
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">';
    echo '<style>';
    echo 'body { font-family: Arial, sans-serif; margin: 0; padding: 0; }';
    echo '.container { width: 80%; max-width: 800px; margin: auto; padding: 20px; background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }';
    echo '.header { background-color: #28a745; color: #fff; padding: 10px; border-radius: 8px 8px 0 0; text-align: center; }';
    echo '.content { padding: 20px; }';
    echo 'h1 { font-size: 24px; margin-bottom: 10px; }';
    echo 'p { font-size: 16px; margin: 5px 0; }';
    echo '.status { font-weight: bold; }';
    echo '.status-pending { color: orange; }';
    echo '.status-confirmed { color: green; }';
    echo '.status-cancelled { color: red; }';
    echo '</style>';
    echo '</head>';
    echo '<body>';
    echo '<div class="container">';
    echo '<div class="header">';
    echo '<h1>Nota Pemesanan</h1>';
    echo '</div>';
    echo '<div class="content">';
    echo '<p>ID Pesanan: <strong>' . htmlspecialchars($data['id']) . '</strong></p>';
    echo '<p>Username: <strong>' . htmlspecialchars($data['username']) . '</strong></p>';
    echo '<p>Tanggal Pemesanan: <strong>' . htmlspecialchars($data['tanggal_pemesanan']) . '</strong></p>';
    echo '<p>Status: <strong class="status status-' . strtolower(htmlspecialchars($data['status_pemesanan'])) . '">' . ucfirst(htmlspecialchars($data['status_pemesanan'])) . '</strong></p>';
    echo '<p>Total Harga: <strong>Rp' . number_format($data['total_harga'], 2, ',', '.') . '</strong></p>';
    echo '</div>';
    echo '</div>';
    echo '</body>';
    echo '</html>';
} else {
    echo 'Data tidak ditemukan.';
}

$stmt->close();
$koneksi->close();
?>
