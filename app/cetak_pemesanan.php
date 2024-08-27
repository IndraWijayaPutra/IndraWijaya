<?php
include('../config/config.php');

// Ambil ID pemesanan dari URL
$pemesanan_id = isset($_GET['pemesanan_id']) ? $_GET['pemesanan_id'] : '';

if (empty($pemesanan_id)) {
    die("Kode pemesanan tidak valid.");
}

// Ambil data pemesanan berdasarkan ID
$sql = "SELECT tanggal_pemesanan, status_pemesanan, total_harga FROM pemesanan_tiket WHERE kode_pesanan = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $pemesanan_id);
$stmt->execute();
$stmt->bind_result($tanggal_pemesanan, $status_pemesanan, $total_harga);
if (!$stmt->fetch()) {
    die("Kode pemesanan tidak ditemukan.");
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemesanan - Cetak</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        .status-approved {
            background-color: #28a745;
            color: white;
        }
        .status-default {
            background-color: #007bff;
            color: white;
        }
        .centered-text {
            text-align: center;
        }
        .logo {
            width: 100px; /* Adjust the width as needed */
        }
        .kop {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .footer-section {
            margin-top: 20px;
            text-align: center;
        }
        .print-btn-container {
            text-align: right;
            margin-bottom: 20px;
        }
        .line-under-telp {
            border-bottom: 2px solid #000;
            margin: 10px 0;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .table thead th, .table tbody td {
                border: 1px solid #000 !important;
            }
            .logo, .kop {
                display: block !important;
            }
            .header-section {
                display: flex;
                justify-content: space-between;
            }
            .footer-section {
                margin-top: 20px;
                text-align: center;
            }
            .line-under-telp {
                border-bottom: 2px solid #000;
            }
        }
    </style>
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body onload="printPage()">
    <div class="container mt-4">
        <div class="kop-surat">
        <div class="container mt-4">
        <div class="print-btn-container">
            <button type="button" class="btn btn-primary no-print" onclick="printData()">Cetak</button>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="printableArea">
                    <div class="header-section">
                        <img src="../dist/img/ewin.jpg" alt="Logo" class="logo">
                        <div>
                            <p class="centered-text">
                                <?php echo date('d F Y'); ?>
                            </p>
                        </div>
                    </div>
                    <h3 class="centered-text">Data Pengunjung Pemancingan Joran Marindu</h3>
                    <div class="kop">
                        <p>
                            PEMANCINGAN JORAN MARINDU<br>
                            Jl. Karang Anyar 1 No. 123, Kota Banjarbaru<br>
                            Telp: (021) 12345678
                        </p>
                        <div class="line-under-telp"></div>
                    </div>
                    <table class="table table-bordered">
            <table>
    
            </table>
            <hr>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>Detail Pemesanan</h3>
            </div>
            <div class="card-body">
                <p><strong>Tanggal Pemesanan:</strong> <?php echo htmlspecialchars($tanggal_pemesanan); ?></p>
                <p>
                    <strong>Status:</strong> 
                    <span class="badge <?php echo ($status_pemesanan === 'Disetujui') ? 'status-approved' : 'status-default'; ?>">
                        <?php echo htmlspecialchars($status_pemesanan); ?>
                    </span>
                </p>
                <p><strong>Total Harga:</strong> Rp<?php echo number_format($total_harga, 0, ',', '.'); ?></p>
                <button class="btn btn-secondary" onclick="window.print()">Cetak</button>
                <a href="http://localhost/coba/app/" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
