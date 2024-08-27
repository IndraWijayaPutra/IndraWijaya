<?php
session_start();
include('../config/config.php');

// Ambil periode awal dan periode akhir dari input atau gunakan default
$periode_awal = $_GET['periode_awal'] ?? date('Y-m-01');
$periode_akhir = $_GET['periode_akhir'] ?? date('Y-m-t');

// Query untuk mengambil data laporan keuangan dari tabel 'laporan_keuangan'
$sql = "SELECT * FROM laporan_keuangan WHERE tanggal BETWEEN ? AND ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("ss", $periode_awal, $periode_akhir);
$stmt->execute();
$result = $stmt->get_result();

// Inisialisasi variabel total
$total_pemasukan = 0;
$total_pengeluaran = 0;
$total_biaya_operasional = 0;
$total_biaya_non_operasional = 0;
$total_pendapatan_non_operasional = 0;
$total_laba_kotor = 0;
$total_laba_bersih = 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        .btn-print {
            background-color: #007bff;
            color: white;
            border: none;
        }
        .btn-print:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }
        .table thead th {
            background-color: #f8f9fa;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table tbody tr:hover {
            background-color: #e9ecef;
        }
        .table td {
            vertical-align: middle;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Laporan Keuangan</h1>
        
        <!-- Form untuk memilih periode -->
        <form method="GET" action="laporan_keuangan.php" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="periode_awal" class="form-label">Periode Awal:</label>
                    <input type="date" class="form-control" id="periode_awal" name="periode_awal" value="<?php echo htmlspecialchars($periode_awal); ?>">
                </div>
                <div class="col-md-4">
                    <label for="periode_akhir" class="form-label">Periode Akhir:</label>
                    <input type="date" class="form-control" id="periode_akhir" name="periode_akhir" value="<?php echo htmlspecialchars($periode_akhir); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </div>
        </form>

        <h3 class="mb-4">Laporan Keuangan dari <?php echo htmlspecialchars($periode_awal); ?> hingga <?php echo htmlspecialchars($periode_akhir); ?></h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Total Pemasukan</th>
                    <th>Total Pengeluaran</th>
                    <th>Biaya Operasional</th>
                    <th>Biaya Non-Operasional</th>
                    <th>Pendapatan Non-Operasional</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    // Menghitung Laba Kotor dan Laba Bersih berdasarkan data yang ada
                    $laba_kotor = $row['total_pemasukan'] - $row['total_pengeluaran'];
                    $laba_bersih = $laba_kotor - ($row['biaya_operasional'] + $row['biaya_non_operasional']) + $row['pendapatan_non_operasional'];

                    // Menambahkan ke total keseluruhan
                    $total_pemasukan += $row['total_pemasukan'];
                    $total_pengeluaran += $row['total_pengeluaran'];
                    $total_biaya_operasional += $row['biaya_operasional'];
                    $total_biaya_non_operasional += $row['biaya_non_operasional'];
                    $total_pendapatan_non_operasional += $row['pendapatan_non_operasional'];
                    echo "<tr>
                            <td>" . htmlspecialchars($row['tanggal']) . "</td>
                            <td>Rp" . number_format($row['total_pemasukan'], 2, ',', '.') . "</td>
                            <td>Rp" . number_format($row['total_pengeluaran'], 2, ',', '.') . "</td>
                            <td>Rp" . number_format($row['biaya_operasional'], 2, ',', '.') . "</td>
                            <td>Rp" . number_format($row['biaya_non_operasional'], 2, ',', '.') . "</td>
                            <td>Rp" . number_format($row['pendapatan_non_operasional'], 2, ',', '.') . "</td>
                        </tr>";
                }
                ?>
                <tr class="total-row">
                    <td>Total Keseluruhan</td>
                    <td><strong>Rp<?php echo number_format($total_pemasukan, 2, ',', '.'); ?></strong></td>
                    <td><strong>Rp<?php echo number_format($total_pengeluaran, 2, ',', '.'); ?></strong></td>
                    <td><strong>Rp<?php echo number_format($total_biaya_operasional, 2, ',', '.'); ?></strong></td>
                    <td><strong>Rp<?php echo number_format($total_biaya_non_operasional, 2, ',', '.'); ?></strong></td>
                    <td><strong>Rp<?php echo number_format($total_pendapatan_non_operasional, 2, ',', '.'); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <div class="mt-4">
            <a href="cetak_laporan_keuangan.php?periode_awal=<?php echo urlencode($periode_awal); ?>&periode_akhir=<?php echo urlencode($periode_akhir); ?>" class="btn btn-print no-print">Cetak</a>
            <a href="index.php" class="btn btn-back">Kembali</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
