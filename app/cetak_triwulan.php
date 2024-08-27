<?php
include('../config/config.php'); // Pastikan file ini menghubungkan ke database Anda

// Ambil parameter triwulan dari URL
$quarter = filter_input(INPUT_GET, 'quarter', FILTER_SANITIZE_STRING);
$year = date('Y'); // Atau ambil dari parameter jika ada

if ($quarter) {
    switch($quarter) {
        case 'Q1': $startMonth = 1; $endMonth = 3; break;
        case 'Q2': $startMonth = 4; $endMonth = 6; break;
        case 'Q3': $startMonth = 7; $endMonth = 9; break;
        case 'Q4': $startMonth = 10; $endMonth = 12; break;
        default: die("Triwulan tidak valid."); // Tambahkan validasi untuk triwulan yang salah
    }

    // Query data berdasarkan triwulan
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
    $query->execute();
    $result = $query->get_result();

    if (!$result) {
        die("Query gagal: " . mysqli_error($koneksi));
    }

    // Hitung total keseluruhan
    $totalHarga = 0;
    while ($row = $result->fetch_assoc()) {
        $totalHarga += $row['total_harga'];
    }

    // Reset pointer hasil query untuk menampilkan data
    $result->data_seek(0);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Triwulan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .kop-surat {
                display: block;
            }
        }
        .kop-surat {
            display: flex;
            justify-content: space-betwee   n;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat .logo {
            flex: 1;
            text-align: left;
        }
        .kop-surat .info {
            flex: 2;
            text-align: center;
            line-height: 1; /* Spasi antar baris */
            margin: 0; /* Atur margin jika diperlukan */
        }
        .kop-surat .tanggal {
            flex: 1;
            text-align: right;
            line-height: 1; /* Spasi antar baris */
            margin: 0; /* Atur margin jika diperlukan */
        }
        .kop-surat img {
            height: 50px; /* Sesuaikan dengan ukuran logo */
        }
        h2 {
            text-align: center;
            margin-top: 20px; /* Margin atas untuk jarak dari header */
            margin-bottom: 20px; /* Margin bawah untuk jarak dari tabel */
            line-height: 1; /* Spasi antar baris */
        }
        table {
            margin-top: 20px; /* Jarak tabel dari teks sebelumnya */
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="kop-surat">
        <div class="logo">
            <img src="dist/img/logo.jpg" alt="Logo"> <!-- Ganti dengan path ke logo Anda -->
        </div>
        <div class="info">
            <h5>Data Pengunjung Pemancingan Joran Marindu</h5>
            <p>PEMANCINGAN JORAN MARINDU</p>
            <p>Jl. Raya Pemancingan No. 123, Kota Wisata, 12345</p>
            <p>Telp: (021) 12345678</p>
        </div>
        <div class="tanggal">
            <?php echo date('d F Y'); ?> <!-- Tanggal laporan -->
        </div>
    </div>

    <h2>Laporan Triwulan</h2>
    
    <!-- Tampilkan laporan jika ada data -->
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
                    <td colspan="4" class="text-right">Total:</td>
                    <td><?php echo number_format($totalHarga, 2, ',', '.'); ?></td>
                </tr>
            </tfoot>
        </table>
    <?php } else { ?>
        <p>Tidak ada data untuk triwulan ini.</p>
    <?php } ?>
    
    <a href="laporan.php" class="btn btn-secondary no-print">Kembali</a>
    <button class="btn btn-primary no-print" onclick="window.print()">Cetak</button>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
