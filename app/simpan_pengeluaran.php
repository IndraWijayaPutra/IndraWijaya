<?php
session_start();
include('../config/config.php');

// Ambil data dari form
$tanggal = $_POST['tanggal'] ?? '';
$pemasukan = str_replace(['Rp', '.', ','], ['', '', '.'], $_POST['pemasukan'] ?? '0');
$biaya_operasional = str_replace(['Rp', '.', ','], ['', '', '.'], $_POST['biaya_operasional'] ?? '0');
$biaya_non_operasional = str_replace(['Rp', '.', ','], ['', '', '.'], $_POST['biaya_non_operasional'] ?? '0');
$pendapatan_non_operasional = str_replace(['Rp', '.', ','], ['', '', '.'], $_POST['pendapatan_non_operasional'] ?? '0');
$keterangan = $_POST['keterangan'] ?? '';

// Validasi data
if (!$tanggal || !$pemasukan || !$biaya_operasional || !$biaya_non_operasional || !$pendapatan_non_operasional) {
    echo "Semua field harus diisi.";
    exit;
}

// Siapkan pernyataan SQL
$sql = "INSERT INTO pengeluaran (tanggal, pemasukan, biaya_operasional, biaya_non_operasional, pendapatan_non_operasional, keterangan) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $koneksi->prepare($sql);

if ($stmt === false) {
    die('Gagal menyiapkan pernyataan SQL: ' . $koneksi->error);
}

// Bind parameter
$stmt->bind_param("ddddss", $tanggal, $pemasukan, $biaya_operasional, $biaya_non_operasional, $pendapatan_non_operasional, $keterangan);

if (!$stmt->execute()) {
    die('Gagal mengeksekusi pernyataan SQL: ' . $stmt->error);
}

$stmt->close();
$koneksi->close();

// Redirect ke halaman utama setelah menyimpan
header('Location: http://localhost/coba/app/index.php');
exit;
?>
