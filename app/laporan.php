<?php
include('../config/config.php'); // Pastikan file ini menghubungkan ke database Anda

// Tangani pengiriman formulir
$reportType = filter_input(INPUT_POST, 'reportType', FILTER_SANITIZE_STRING);
$period = null;

if ($reportType === 'monthly') {
    $period = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_STRING);
} elseif ($reportType === 'quarterly') {
    $period = filter_input(INPUT_POST, 'quarter', FILTER_SANITIZE_STRING);
} elseif ($reportType === 'yearly') {
    $period = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT);
}

// Jika formulir telah dikirim, proses laporan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $reportType && $period) {
    // Validasi input
    if (!$reportType || !$period) {
        die("Input tidak valid.");
    }

    // Menentukan query berdasarkan jenis laporan
    $query = "";
    if ($reportType === 'monthly') {
        $month = date('m', strtotime($period)); // Extract bulan
        $year = date('Y', strtotime($period)); // Extract tahun
        $query = $koneksi->prepare("
            SELECT 'Tiket' AS jenis, id, tanggal_pemesanan, status_pemesanan, total_harga, kolam 
            FROM pemesanan_tiket 
            WHERE MONTH(tanggal_pemesanan) = ? AND YEAR(tanggal_pemesanan) = ?
            UNION ALL
            SELECT 'Kuliner' AS jenis, id, tanggal_pemesanan, status, harga AS total_harga, 'N/A' AS kolam 
            FROM pemesanan_kuliner 
            WHERE MONTH(tanggal_pemesanan) = ? AND YEAR(tanggal_pemesanan) = ?
        ");
        $query->bind_param("iiii", $month, $year, $month, $year);
    } elseif ($reportType === 'quarterly') {
        $year = date('Y'); // Atau ambil dari input jika tersedia
        switch($period) {
            case 'Q1': $startMonth = 1; $endMonth = 3; break;
            case 'Q2': $startMonth = 4; $endMonth = 6; break;
            case 'Q3': $startMonth = 7; $endMonth = 9; break;
            case 'Q4': $startMonth = 10; $endMonth = 12; break;
            default: die("Triwulan tidak valid."); // Tambahkan validasi untuk triwulan yang salah
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
    } elseif ($reportType === 'yearly') {
        $query = $koneksi->prepare("
            SELECT 'Tiket' AS jenis, id, tanggal_pemesanan, status_pemesanan, total_harga, kolam 
            FROM pemesanan_tiket 
            WHERE YEAR(tanggal_pemesanan) = ?
            UNION ALL
            SELECT 'Kuliner' AS jenis, id, tanggal_pemesanan, status, harga AS total_harga, 'N/A' AS kolam 
            FROM pemesanan_kuliner 
            WHERE YEAR(tanggal_pemesanan) = ?
        ");
        $query->bind_param("ii", $period, $period);
    }

    // Jalankan query dan proses hasilnya
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
    <title>Laporan Keuangan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Laporan Keuangan</h2>    
    <!-- Formulir untuk memilih laporan -->
    <form method="POST" action="laporan.php">
        <div class="form-group">
            <label for="reportType">Jenis Laporan</label>
            <select id="reportType" name="reportType" class="form-control" required>
                <option value="" disabled selected>Pilih Jenis Laporan</option>
                <option value="monthly" <?php if ($reportType === 'monthly') echo 'selected'; ?>>Bulanan</option>
                <option value="quarterly" <?php if ($reportType === 'quarterly') echo 'selected'; ?>>Triwulanan</option>
                <option value="yearly" <?php if ($reportType === 'yearly') echo 'selected'; ?>>Tahunan</option>
            </select>
        </div>
        <div class="form-group" id="periodGroup">
            <!-- Periode input akan ditampilkan berdasarkan jenis laporan yang dipilih -->
            <?php if ($reportType === 'monthly') { ?>
                <label for="month">Bulan</label>
                <input type="month" id="month" name="month" class="form-control" value="<?php echo htmlspecialchars($period); ?>" required>
            <?php } elseif ($reportType === 'quarterly') { ?>
                <label for="quarter">Triwulan</label>
                <select id="quarter" name="quarter" class="form-control" required>
                    <option value="Q1" <?php if ($period === 'Q1') echo 'selected'; ?>>Q1</option>
                    <option value="Q2" <?php if ($period === 'Q2') echo 'selected'; ?>>Q2</option>
                    <option value="Q3" <?php if ($period === 'Q3') echo 'selected'; ?>>Q3</option>
                    <option value="Q4" <?php if ($period === 'Q4') echo 'selected'; ?>>Q4</option>
                </select>
            <?php } elseif ($reportType === 'yearly') { ?>
                <label for="year">Tahun</label>
                <input type="number" id="year" name="year" class="form-control" min="2000" max="2100" value="<?php echo htmlspecialchars($period); ?>" required>
            <?php } ?>
        </div>
        <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
        <a href="http://localhost/coba/app/index.php?" class="btn btn-secondary">Kembali</a>
        <?php if (isset($result) && $result->num_rows > 0) { ?>
            <a href="cetak_triwulan.php?quarter=<?php echo urlencode($period); ?>" class="btn btn-success">Cetak Laporan</a>
        <?php } ?>
    </form>

    <!-- Tampilkan laporan jika ada data -->
    <?php if (isset($result) && $result->num_rows > 0) { ?>
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>Tanggal Pemesanan</th>
                    <th>Status</th>
                    <th>Total Harga</th>
                    <th>Total Keseluruhan</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['jenis']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_pemesanan']); ?></td>
                        <td><?php echo htmlspecialchars($row['status_pemesanan']); ?></td>
                        <td><?php echo number_format($row['total_harga'], 2, ',', '.'); ?></td>
                        <td></td>
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
        <p>Tidak ada data untuk periode ini.</p>
    <?php } ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.getElementById('reportType').addEventListener('change', function() {
        var reportType = this.value;
        var periodGroup = document.getElementById('periodGroup');
        periodGroup.innerHTML = '';

        if (reportType === 'monthly') {
            periodGroup.innerHTML = '<label for="month">Bulan</label>' +
                '<input type="month" id="month" name="month" class="form-control" required>';
        } else if (reportType === 'quarterly') {
            periodGroup.innerHTML = '<label for="quarter">Triwulan</label>' +
                '<select id="quarter" name="quarter" class="form-control" required>' +
                '<option value="Q1">Q1</option>' +
                '<option value="Q2">Q2</option>' +
                '<option value="Q3">Q3</option>' +
                '<option value="Q4">Q4</option>' +
                '</select>';
        } else if (reportType === 'yearly') {
            periodGroup.innerHTML = '<label for="year">Tahun</label>' +
                '<input type="number" id="year" name="year" class="form-control" min="2000" max="2100" required>';
        }
    });
</script>
</body>
</html>
