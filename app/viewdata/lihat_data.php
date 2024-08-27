<?php
// Menyesuaikan path config.php
include('../../config/config.php');

// Periksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Query untuk mengambil data dari tabel pemesanan_tiket dan nama pengunjung
$query = "
    SELECT 
        pt.id, 
        p.username AS nama_pengunjung,
        pt.tanggal_pemesanan, 
        pt.total_harga, 
        pt.status_pemesanan,  
        pt.kode_pesanan
    FROM pemesanan_tiket pt
    JOIN pengunjung p ON pt.pengunjung_id = p.id
";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemesanan Tiket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
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
</head>
<body>
    <div class="container mt-4">
        <div class="print-btn-container">
            <button type="button" class="btn btn-primary no-print" onclick="printData()">Cetak</button>
            <a href="http://localhost/coba/app/" class="btn btn-secondary no-print">Kembali</a>
        </div>
        <div class="card">
            <div class="card-body">
                <div id="printableArea">
                    <div class="header-section">
                        <img src="../dist/img/logo.jpg" alt="Logo" class="logo">
                        <div>
                            <p class="centered-text">
                                <?php echo date('d F Y'); ?>
                            </p>
                        </div>
                    </div>
                    <h3 class="centered-text">Data Pemesanan Tiket Pemancingan Joran Marindu</h3>
                    <div class="kop">
                        <p>
                            PEMANCINGAN JORAN MARINDU<br>
                            Jl. Karang Anyar 1 No. 123, Kota Banjarbaru<br>
                            Telp: (021) 12345678
                        </p>
                        <div class="line-under-telp"></div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pengunjung</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Total Harga</th>
                                <th>Status Pemesanan</th>
                                <th>Kode Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1; // Inisialisasi nomor urut
                            while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_pengunjung']); ?></td>
                                <td><?php echo htmlspecialchars($row['tanggal_pemesanan']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_harga']); ?></td>
                                <td><?php echo htmlspecialchars($row['status_pemesanan']); ?></td>
                                <td><?php echo htmlspecialchars($row['kode_pesanan']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <div class="footer-section">
                        <p>
                            : _____________________<br>
                            (Hormat Kami)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printData() {
            printJS({
                printable: 'printableArea',
                type: 'html',
                targetStyles: ['*'],
                documentTitle: 'Data Pemesanan Tiket'
            });
        }
    </script>
</body>
</html>
