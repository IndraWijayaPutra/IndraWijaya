<?php
include('../config/config.php');

// Get the start and end period from the query parameter
$periode_awal = $_GET['periode_awal'] ?? date('Y-m-01');
$periode_akhir = $_GET['periode_akhir'] ?? date('Y-m-t');

// Query to get financial report data from the 'laporan_keuangan' table
$sql = "SELECT * FROM laporan_keuangan WHERE tanggal BETWEEN ? AND ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("ss", $periode_awal, $periode_akhir);
$stmt->execute();
$result = $stmt->get_result();

// Initialize total variables
$total_pemasukan = 0;
$total_pengeluaran = 0;
$total_biaya_operasional = 0;
$total_biaya_non_operasional = 0;
$total_pendapatan_non_operasional = 0;
$total_laba_kotor = 0;
$total_laba_bersih = 0;
$total_total_harga = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }

        .header .company-logo {
            height: 50px;
        }

        .header .company-info {
            text-align: center;
            flex-grow: 1;
        }

        .header .company-info h1 {
            margin: 0;
        }

        .header .company-info p {
            margin: 0;
        }

        .header .company-info .phone {
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }

        .header .date {
            text-align: right;
        }

        .report-title {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: right;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
        }

        .footer-section {
            text-align: right;
            margin-top: 20px;
        }

        .signature {
            display: inline-block;
            width: 200px;
            border-bottom: 1px solid #000;
            text-align: center;
        }

        .signature-text {
            margin-top: 5px;
        }

        @media print {
            @page {
                size: A4;
                margin: 20mm;
            }
            body {
                margin: 0;
                padding: 0;
                font-size: 12pt;
            }
            .no-print {
                display: none !important;
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
        <div class="print-btn-container">
            <button type="button" class="btn btn-primary no-print" onclick="printPage()">Cetak</button>
            <a href="http://localhost/coba/app/" class="btn btn-secondary  no-print">Kembali</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="printableArea">
                    <div class="header">
                        <img src="dist/img/logo.jpg" alt="Logo Perusahaan" class="company-logo">
                        <div class="company-info">
                            <h1>PEMANCINGAN JORAN MARINDU</h1>
                            <p> Jl. Karang Anyar 1 No. 123, Kota Banjarbaru</p>
                            <p class="phone">Telp: (021) 12345678</p>
                        </div>
                        <div class="date">
                            <p><?php echo date('d F Y'); ?></p>
                        </div>
                    </div>
                    <h2 class="report-title">Laporan Keuangan</h2>
                    <?php
                    if ($result->num_rows > 0) {
                        echo "<table>
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Total Pemasukan</th>
                                        <th>Total Pengeluaran</th>
                                        <th>Biaya Operasional</th>
                                        <th>Biaya Non-Operasional</th>
                                        <th>Pendapatan Non-Operasional</th>
                                        <th>Laba Kotor</th>
                                        <th>Laba Bersih</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        while ($row = $result->fetch_assoc()) {
                            $total_pemasukan += $row['total_pemasukan'];
                            $total_pengeluaran += $row['total_pengeluaran'];
                            $total_biaya_operasional += $row['biaya_operasional'];
                            $total_biaya_non_operasional += $row['biaya_non_operasional'];
                            $total_pendapatan_non_operasional += $row['pendapatan_non_operasional'];
                            $total_laba_kotor += $row['laba_kotor'];
                            $total_laba_bersih += $row['laba_bersih'];
                            $total_total_harga += $row['total_harga'];

                            echo "<tr>
                                    <td>" . htmlspecialchars($row['tanggal']) . "</td>
                                    <td>Rp" . number_format($row['total_pemasukan'], 2, ',', '.') . "</td>
                                    <td>Rp" . number_format($row['total_pengeluaran'], 2, ',', '.') . "</td>
                                    <td>Rp" . number_format($row['biaya_operasional'], 2, ',', '.') . "</td>
                                    <td>Rp" . number_format($row['biaya_non_operasional'], 2, ',', '.') . "</td>
                                    <td>Rp" . number_format($row['pendapatan_non_operasional'], 2, ',', '.') . "</td>
                                    <td>Rp" . number_format($row['laba_kotor'], 2, ',', '.') . "</td>
                                    <td>Rp" . number_format($row['laba_bersih'], 2, ',', '.') . "</td>
                                  </tr>";
                        }
                        echo "<tr>
                                <td><strong>Total Keseluruhan</strong></td>
                                <td><strong>Rp" . number_format($total_pemasukan, 2, ',', '.') . "</strong></td>
                                <td><strong>Rp" . number_format($total_pengeluaran, 2, ',', '.') . "</strong></td>
                                <td><strong>Rp" . number_format($total_biaya_operasional, 2, ',', '.') . "</strong></td>
                                <td><strong>Rp" . number_format($total_biaya_non_operasional, 2, ',', '.') . "</strong></td>
                                <td><strong>Rp" . number_format($total_pendapatan_non_operasional, 2, ',', '.') . "</strong></td>
                                <td><strong>Rp" . number_format($total_laba_kotor, 2, ',', '.') . "</strong></td>
                                <td><strong>Rp" . number_format($total_laba_bersih, 2, ',', '.') . "</strong></td>
                              </tr>";
                        echo "</tbody></table>";
                    } else {
                        echo "<p>Tidak ada data laporan keuangan untuk periode ini.</p>";
                    }
                    ?>
                    <div class="footer-section">
                        <div class="signature">
                            <div class="signature-text">
                                Hormat Kami
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
