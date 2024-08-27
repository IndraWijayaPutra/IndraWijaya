<?php
include('../config/config.php'); // Ensure this file connects to your database

// Get report type and period from URL parameters
$reportType = isset($_GET['reportType']) ? $_GET['reportType'] : null;
$period = isset($_GET['period']) ? $_GET['period'] : null;

// Validate inputs
if (!$reportType || !$period) {
    die("Invalid input.");
}

// Determine query based on report type
$query = "";
if ($reportType === 'quarterly') {
    $year = date('Y'); // Adjust as necessary to get the correct year
    switch($period) {
        case 'Q1': $startMonth = 1; $endMonth = 3; break;
        case 'Q2': $startMonth = 4; $endMonth = 6; break;
        case 'Q3': $startMonth = 7; $endMonth = 9; break;
        case 'Q4': $startMonth = 10; $endMonth = 12; break;
        default: die("Invalid quarter."); // Add validation for incorrect quarters
    }
    $query = $koneksi->prepare("
        SELECT 'Tiket' AS jenis, id, tanggal_pemesanan, status_pemesanan, total_harga, kolam 
        FROM pemesanan_tiket 
        WHERE MONTH(tanggal_pemesanan) BETWEEN ? AND ? AND YEAR(tanggal_pemesanan) = ?
        UNION ALL
        SELECT 'Kuliner' AS jenis, id, tanggal_pemesanan, status, harga AS total_harga, 'N/A' AS kolam 
        FROM pemesanan_kuliner 
        WHERE MONTH(tanggal_pemesanan) BETWEEN ? AND ? AND YEAR(tanggal_pemesanan) = ?
    ");
    $query->bind_param("iiiiii", $startMonth, $endMonth, $year, $startMonth, $endMonth, $year);
}

// Execute query and process results
$query->execute();
$result = $query->get_result();

if (!$result) {
    die("Query failed: " . mysqli_error($koneksi));
}

// Calculate total amount
$totalHarga = 0;
while ($row = $result->fetch_assoc()) {
    $totalHarga += $row['total_harga'];
}

// Reset query result pointer for data display
$result->data_seek(0);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Triwulanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Laporan Triwulanan</h2>
    <p>Periode: <?php echo htmlspecialchars($period) . " " . $year; ?></p>
    <!-- Display report if data is available -->
    <?php if (isset($result) && $result->num_rows > 0) { ?>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>Tanggal Pemesanan</th>
                    <th>Status</th>
                    <th>Total Harga</th>
                    <th>Kolam</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['jenis']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_pemesanan']); ?></td>
                        <td><?php echo htmlspecialchars($row['status_pemesanan']); ?></td>
                        <td><?php echo number_format($row['total_harga'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($row['kolam']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Total:</td>
                    <td colspan="2"><?php echo number_format($totalHarga, 2, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>
    <?php } else { ?>
        <p>Tidak ada data untuk laporan yang dipilih.</p>
    <?php } ?>
    <button onclick="window.print()" class="btn btn-primary no-print">Cetak Laporan</button>
    <a href="http://localhost/coba/app/index.php?" class="btn btn-secondary no-print">Kembali</a>
</div>
</body>
</html>
