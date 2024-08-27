<?php
session_start();
include('../config/config.php');

// Cek apakah pengguna adalah pengunjung
if (!isset($_SESSION['pengunjung_id'])) {
    die("Pengunjung ID belum diatur. Pastikan pengguna sudah login.");
}

$pengunjung_id = $_SESSION['pengunjung_id'];
$pesan = "";

// Query untuk menghitung jumlah penangkapan per jenis ikan
$frequentCatchQuery = "SELECT i.nama AS nama_ikan, i.foto AS foto_ikan, SUM(p.jumlah) AS total_jumlah
                       FROM penangkapan_ikan p
                       JOIN ikan i ON p.ikan_id = i.id
                       GROUP BY i.nama, i.foto
                       ORDER BY total_jumlah DESC";
$frequentCatchResult = $koneksi->query($frequentCatchQuery);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ikan Paling Banyak Ditangkap</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
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
        .fish-photo {
            width: 100px; /* Adjust the width as needed */
            height: auto;
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
                    <h3 class="centered-text">Ikan Paling Banyak Ditangkap</h3>
                    <div class="kop">
                        <p>
                            PEMANCINGAN JORAN MARINDU<br>
                            Jl. Karang Anyar 1 No. 123, Kota Banjarbaru<br>
                            Telp: (021) 12345678
                        </p>
                        <div class="line-under-telp"></div>
                    </div>
                    <?php
                    if ($frequentCatchResult->num_rows > 0) {
                        echo "<table class='table table-bordered'>
                                <thead>
                                    <tr>
                                        <th>Nama Ikan</th>
                                        <th>Foto Ikan</th>
                                        <th>Total Penangkapan</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        while ($row = $frequentCatchResult->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['nama_ikan']) . "</td>
                                    <td><img src='dist/img/" . htmlspecialchars($row['foto_ikan']) . "' alt='Foto Ikan' class='fish-photo'></td>
                                    <td>" . htmlspecialchars($row['total_jumlah']) . "</td>
                                </tr>";
                        }
                        echo "  </tbody>
                             </table>";
                    } else {
                        echo "<p>Tidak ada data penangkapan ikan yang cukup untuk laporan ini.</p>";
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
