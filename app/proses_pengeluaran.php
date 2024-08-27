<?php
session_start();
include('../config/config.php');

// Ambil data dari formulir
$periode_awal = $_POST['periode_awal'] ?? '';
$periode_akhir = $_POST['periode_akhir'] ?? '';
$biaya_operasional = $_POST['biaya_operasional'] ?? 0;
$biaya_non_operasional = $_POST['biaya_non_operasional'] ?? 0;
$pendapatan_non_operasional = $_POST['pendapatan_non_operasional'] ?? 0;
$keterangan = $_POST['keterangan'] ?? '';

// Validasi input
if (empty($periode_awal) || empty($periode_akhir)) {
    die("Periode awal dan periode akhir harus diisi.");
}

// Menghitung total pengeluaran
$total_pengeluaran = $biaya_operasional + $biaya_non_operasional;
$total_pemasukan = $pendapatan_non_operasional;
$laba = $total_pemasukan - $total_pengeluaran;
$laba_kotor = $laba; // Sesuaikan jika diperlukan
$laba_bersih = $laba; // Sesuaikan jika diperlukan

// Simpan data ke tabel laporan_keuangan
$tanggal_sekarang = date('Y-m-d');

$sql = "INSERT INTO laporan_keuangan (
            tanggal, total_pemasukan, total_pengeluaran, keterangan, laba, laba_kotor, laba_bersih,
            biaya_operasional, biaya_non_operasional, pendapatan_non_operasional
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

$stmt->bind_param(
    "ssssdddddd",
    $tanggal_sekarang,
    $total_pemasukan,
    $total_pengeluaran,
    $keterangan,
    $laba,
    $laba_kotor,
    $laba_bersih,
    $biaya_operasional,
    $biaya_non_operasional,
    $pendapatan_non_operasional
);

$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Disimpan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function redirectToIndex() {
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 2000); // Delay 2 detik
        }
    </script>
</head>
<body onload="redirectToIndex()">
    <div class="container mt-5">
        <div class="alert alert-success" role="alert">
            Data berhasil disimpan ke laporan keuangan.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
