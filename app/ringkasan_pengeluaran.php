<?php
session_start();
include('../config/config.php');

// Ambil periode dari query string
$periode_awal = $_GET['periode_awal'] ?? date('Y-m') . '-01';
$periode_akhir = $_GET['periode_akhir'] ?? date('Y-m-t');

// Ambil data dari tabel laporan_keuangan
$sql = "SELECT * FROM laporan_keuangan WHERE tanggal BETWEEN ? AND ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("ss", $periode_awal, $periode_akhir);
$stmt->execute();
$result = $stmt->get_result();
$laporan = $result->fetch_assoc();
$stmt->close();

if (!$laporan) {
    echo "<div class='container mt-5'><div class='alert alert-warning' role='alert'>Tidak ada data untuk periode dari $periode_awal hingga $periode_akhir.</div></div>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ringkasan Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Ringkasan Pengeluaran untuk Periode <?php echo htmlspecialchars($periode_awal) . " hingga " . htmlspecialchars($periode_akhir); ?></h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Keterangan</th>
                    <th scope="col">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Pemasukan</td>
                    <td><?php echo number_format($laporan['total_pemasukan'], 2, ',', '.'); ?> IDR</td>
                </tr>
                <tr>
                    <td>Biaya Operasional</td>
                    <td><?php echo number_format($laporan['biaya_operasional'], 2, ',', '.'); ?> IDR</td>
                </tr>
                <tr>
                    <td>Biaya Non-Operasional</td>
                    <td><?php echo number_format($laporan['biaya_non_operasional'], 2, ',', '.'); ?> IDR</td>
                </tr>
                <tr>
                    <td>Pendapatan Non-Operasional</td>
                    <td><?php echo number_format($laporan['pendapatan_non_operasional'], 2, ',', '.'); ?> IDR</td>
                </tr>
                <tr>
                    <td>Laba Kotor</td>
                    <td><?php echo number_format($laporan['laba_kotor'], 2, ',', '.'); ?> IDR</td>
                </tr>
                <tr>
                    <td>Laba Bersih</td>
                    <td><?php echo number_format($laporan['laba_bersih'], 2, ',', '.'); ?> IDR</td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td><?php echo htmlspecialchars($laporan['keterangan']); ?></td>
                </tr>
            </tbody>
        </table>

        <a href="pengeluaran.php" class="btn btn-primary mt-4">Kembali</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
