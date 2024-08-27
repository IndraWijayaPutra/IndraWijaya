<?php
session_start();
include('../config/config.php');

// Ambil data dari formulir jika sudah di-submit
$periode_awal = $_POST['periode_awal'] ?? '';
$periode_akhir = $_POST['periode_akhir'] ?? '';

// Mengubah nilai input menjadi angka
$biaya_operasional = isset($_POST['biaya_operasional']) ? floatval(preg_replace('/[^\d.]/', '', $_POST['biaya_operasional'])) : 0;
$biaya_non_operasional = isset($_POST['biaya_non_operasional']) ? floatval(preg_replace('/[^\d.]/', '', $_POST['biaya_non_operasional'])) : 0;
$pendapatan_non_operasional = isset($_POST['pendapatan_non_operasional']) ? floatval(preg_replace('/[^\d.]/', '', $_POST['pendapatan_non_operasional'])) : 0;
$keterangan = $_POST['keterangan'] ?? '';

// Jika data sudah di-submit, tampilkan ringkasan pengeluaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total_pengeluaran = $biaya_operasional + $biaya_non_operasional;
}

$sql = "INSERT INTO laporan_keuangan (tanggal, total_pemasukan, total_pengeluaran, keterangan, laba, laba_kotor, laba_bersih, biaya_operasional, biaya_non_operasional, pendapatan_non_operasional)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Fungsi untuk format Rupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 2, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Pengeluaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Menambahkan CSS untuk memastikan teks dimulai dari kiri */
        input[type="text"], textarea {
            text-align: left;
            direction: ltr;
        }

        /* Memastikan input kotak sesuai dengan lebar halaman */
        input[type="text"], textarea, input[type="number"] {
            width: 100%;
            box-sizing: border-box;
        }

        /* Menambahkan CSS untuk format Rupiah pada input */
        .currency-input {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Input Pengeluaran</h1>
        <form action="pengeluaran.php" method="post" onsubmit="formatFormInputs()">
            <div class="mb-3">
                <label for="periode_awal" class="form-label">Periode Awal:</label>
                <input type="date" class="form-control" id="periode_awal" name="periode_awal" value="<?php echo htmlspecialchars($periode_awal); ?>">
            </div>

            <div class="mb-3">
                <label for="periode_akhir" class="form-label">Periode Akhir:</label>
                <input type="date" class="form-control" id="periode_akhir" name="periode_akhir" value="<?php echo htmlspecialchars($periode_akhir); ?>">
            </div>

            <div class="mb-3">
                <label for="biaya_operasional" class="form-label">Biaya Operasional:</label>
                <input type="text" class="form-control currency-input" id="biaya_operasional" name="biaya_operasional" value="<?php echo formatRupiah($biaya_operasional); ?>" oninput="formatRupiahInput(this)">
            </div>

            <div class="mb-3">
                <label for="biaya_non_operasional" class="form-label">Biaya Non-Operasional:</label>
                <input type="text" class="form-control currency-input" id="biaya_non_operasional" name="biaya_non_operasional" value="<?php echo formatRupiah($biaya_non_operasional); ?>" oninput="formatRupiahInput(this)">
            </div>

            <div class="mb-3">
                <label for="pendapatan_non_operasional" class="form-label">Pendapatan Non-Operasional:</label>
                <input type="text" class="form-control currency-input" id="pendapatan_non_operasional" name="pendapatan_non_operasional" value="<?php echo formatRupiah($pendapatan_non_operasional); ?>" oninput="formatRupiahInput(this)">
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan:</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="4"><?php echo htmlspecialchars($keterangan); ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Tampilkan Rincian</button>
            <a href="http://localhost/coba/app/index.php?" class="btn btn-secondary">Kembali</a>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <h2 class="mt-5 mb-4">Rincian Pengeluaran</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Periode Awal</th>
                        <th>Periode Akhir</th>
                        <th>Biaya Operasional</th>
                        <th>Biaya Non-Operasional</th>
                        <th>Pendapatan Non-Operasional</th>
                        <th>Total Pengeluaran</th>
                        <th>Keterangan</th>
                        <th>Konfirmasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo htmlspecialchars($periode_awal); ?></td>
                        <td><?php echo htmlspecialchars($periode_akhir); ?></td>
                        <td><?php echo formatRupiah($biaya_operasional); ?></td>
                        <td><?php echo formatRupiah($biaya_non_operasional); ?></td>
                        <td><?php echo formatRupiah($pendapatan_non_operasional); ?></td>
                        <td><?php echo formatRupiah($total_pengeluaran); ?></td>
                        <td><?php echo htmlspecialchars($keterangan); ?></td>
                        <td>
                            <form action="proses_pengeluaran.php" method="post">
                                <input type="hidden" name="periode_awal" value="<?php echo htmlspecialchars($periode_awal); ?>">
                                <input type="hidden" name="periode_akhir" value="<?php echo htmlspecialchars($periode_akhir); ?>">
                                <input type="hidden" name="biaya_operasional" value="<?php echo htmlspecialchars($biaya_operasional); ?>">
                                <input type="hidden" name="biaya_non_operasional" value="<?php echo htmlspecialchars($biaya_non_operasional); ?>">
                                <input type="hidden" name="pendapatan_non_operasional" value="<?php echo htmlspecialchars($pendapatan_non_operasional); ?>">
                                <input type="hidden" name="keterangan" value="<?php echo htmlspecialchars($keterangan); ?>">
                                <button type="submit" class="btn btn-success">Simpan ke Laporan Keuangan</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fungsi untuk memformat input sebagai Rupiah
        function formatRupiahInput(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            let formattedValue = 'Rp ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = formattedValue;
        }

        // Fungsi untuk menghapus format Rupiah dari input
        function removeRupiahFormat(value) {
            return value.replace(/[^0-9]/g, '');
        }

        // Fungsi untuk menghapus format Rupiah sebelum mengirim formulir
        function formatFormInputs() {
            document.querySelectorAll('.currency-input').forEach(input => {
                input.value = removeRupiahFormat(input.value);
            });
        }

        // Menyimpan nilai numerik yang diformat sebagai Rupiah
        document.querySelectorAll('.currency-input').forEach(input => {
            input.addEventListener('input', () => formatRupiahInput(input));
        });
    </script>
</body>
</html>
