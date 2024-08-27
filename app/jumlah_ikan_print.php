<?php
include('../config/config.php');

// Query untuk mengambil data kapasitas ikan dari tabel 'penangkapan_ikan'
$sql = "SELECT p.jumlah AS Jumlah, i.nama AS nama_ikan, p.tanggal_penangkapan 
        FROM penangkapan_ikan p 
        JOIN ikan i ON p.ikan_id = i.id"; // Menyesuaikan join untuk mendapatkan nama ikan
$result = $koneksi->query($sql);

// Variabel untuk menyimpan total keseluruhan jumlah ikan
$totalKeseluruhan = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jumlah Ikan</title>
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
            text-align: right;
        }
        .print-btn-container {
            text-align: right;
            margin-bottom: 20px;
        }
        .line-under-telp {
            border-bottom: 2px solid #000;
            margin: 10px 0;
        }
        .signature {
            display: inline-block;
            width: 200px; /* Adjust the width as needed */
            border-bottom: 1px solid #000;
            text-align: center;
        }
        .signature-text {
            margin-top: 5px;
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
                text-align: right;
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
        <div class="print-btn-container">
            <button type="button" class="btn btn-primary no-print" onclick="printPage()">Cetak</button>
            <a href="http://localhost/coba/app/" class="btn btn-primary no-print">Kembali</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="printableArea">
                    <div class="header-section">
                        <img src="dist/img/logo.jpg" alt="Logo" class="logo">
                        <div>
                            <p class="centered-text">
                                <?php echo date('d F Y'); ?>
                            </p>
                        </div>
                    </div>
                    <h3 class="centered-text">Tangkapan Ikan</h3>
                    <div class="kop">
                        <p>
                            PEMANCINGAN JORAN MARINDU<br>
                            Jl. Karang Anyar 1 No. 123, Kota Banjarbaru<br>
                            Telp: (021) 12345678
                        </p>
                        <div class="line-under-telp"></div>
                    </div>
                    <?php
                    if ($result->num_rows > 0) {
                        echo "<table class='table table-bordered'>
                                <thead>
                                    <tr>
                                        <th>Nama Ikan</th>
                                        <th>Tanggal Penangkapan</th>
                                        <th>Total Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['nama_ikan']) . "</td>
                                    <td>" . htmlspecialchars($row['tanggal_penangkapan']) . "</td>
                                    <td>" . htmlspecialchars($row['Jumlah']) . "</td>
                                </tr>";
                            // Menambahkan jumlah ikan ke total keseluruhan
                            $totalKeseluruhan += $row['Jumlah'];
                        }
                        // Menambahkan baris total ke tabel
                        echo "<tr>
                                <td><strong>Total Keseluruhan</strong></td>
                                <td></td>
                                <td><strong>" . $totalKeseluruhan . "</strong></td>
                              </tr>";
                        echo "  </tbody>
                             </table>";
                    } else {
                        echo "<p>Tidak ada data penangkapan ikan.</p>";
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
