<?php
session_start();
include('../config/config.php');

// Ambil periode awal dan periode akhir dari input atau gunakan default
$periode_awal = $_GET['periode_awal'] ?? date('Y-m-01');
$periode_akhir = $_GET['periode_akhir'] ?? date('Y-m-t');

// Fungsi untuk mengambil data laporan dari tabel
function getLaporanData($koneksi, $periode_awal, $periode_akhir) {
    $sql = "SELECT SUM(total_pendapatan) AS total_pendapatan
            FROM laporan
            WHERE periode_awal BETWEEN ? AND ?";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $periode_awal, $periode_akhir);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    return [
        'total_pendapatan' => $data['total_pendapatan'] ?? 0
    ];
}

// Fungsi untuk menghitung total pengunjung kuliner
function getTotalPengunjung($koneksi, $periode_awal, $periode_akhir) {
    $sql = "SELECT COUNT(DISTINCT pengunjung_id) AS total_pengunjung
            FROM pengunjung_kuliner
            WHERE bulan_pemesanan BETWEEN ? AND ?";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $periode_awal, $periode_akhir);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    return $data['total_pengunjung'] ?? 0;
}

// Fungsi untuk menghitung total ikan yang ditangkap
function getTotalIkanDitangkap($koneksi, $periode_awal, $periode_akhir) {
    $sql = "SELECT SUM(jumlah) AS total_ikan
            FROM penangkapan_ikan
            WHERE tanggal_penangkapan BETWEEN ? AND ?";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ss", $periode_awal, $periode_akhir);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    return $data['total_ikan'] ?? 0;
}

// Fungsi untuk menghitung total biaya operasional jika ada tabel lain
function getBiayaOperasional($koneksi, $periode_awal, $periode_akhir) {
    // Ganti dengan query yang sesuai jika ada tabel biaya operasional
    return 0;
}

// Ambil data laporan, pengunjung, dan ikan
$data_keuangan = getLaporanData($koneksi, $periode_awal, $periode_akhir);
$total_pengunjung = getTotalPengunjung($koneksi, $periode_awal, $periode_akhir);
$total_ikan_ditangkap = getTotalIkanDitangkap($koneksi, $periode_awal, $periode_akhir);
$biaya_operasional = getBiayaOperasional($koneksi, $periode_awal, $periode_akhir);

// Hitung Laba Kotor dan Laba Bersih
$laba_kotor = $data_keuangan['total_pendapatan'];
$laba_operasi = $laba_kotor - $biaya_operasional;
$laba_bersih = $laba_operasi; // Jika tidak ada pendapatan non-operasional
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
            .btn, .no-print {
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
            color: white;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            border: none;
        }
        .btn-back:hover {
            background-color: #5a6268;
            color: white;
        }
        .table {
            margin-bottom: 1rem;
        }
        .text-blue {
            color: #007bff;
        }
        .text-red {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h1>Laporan Keuangan</h1>
        
        <!-- Form untuk memilih periode -->
        <form method="GET" action="laporan_keuangan.php">
            <div class="row mb-3">
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

        <h3>Laporan Keuangan</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th class="text-blue">Total Pemasukan</th>
                    <th class="text-blue">Total Pengunjung</th>
                    <th class="text-blue">Total Ikan Ditangkap</th>
                    <th class="text-blue">Laba Kotor</th>
                    <th class="text-blue">Laba Operasi</th>
                    <th class="text-blue">Laba Bersih</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Periode</td>
                    <td class="text-blue">Rp<?php echo number_format($data_keuangan['total_pendapatan'], 0, ',', '.'); ?></td>
                    <td class="text-blue"><?php echo number_format($total_pengunjung, 0, ',', '.'); ?></td>
                    <td class="text-blue"><?php echo number_format($total_ikan_ditangkap, 0, ',', '.'); ?></td>
                    <td class="text-blue">Rp<?php echo number_format($laba_kotor, 0, ',', '.'); ?></td>
                    <td class="text-blue">Rp<?php echo number_format($laba_operasi, 0, ',', '.'); ?></td>
                    <td class="text-blue">Rp<?php echo number_format($laba_bersih, 0, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>

        <button class="btn btn-print" onclick="window.print()">Cetak</button>
        <a href="index.php" class="btn btn-back no-print">Kembali</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
