<?php
session_start();
include('../config/config.php');

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Fungsi untuk menghasilkan kode unik
function generateUniqueCode($length = 32) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $charactersLength = strlen($characters);
    $randomCode = '';
    for ($i = 0; $i < $length; $i++) {
        $randomCode .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomCode;
}

// Periksa apakah formulir dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari formulir dengan pemeriksaan keberadaan
    $jumlah_pengunjung = isset($_POST['jumlah_pengunjung']) ? intval($_POST['jumlah_pengunjung']) : 0;
    $tanggal_pemesanan = isset($_POST['tanggal_pemesanan']) ? $_POST['tanggal_pemesanan'] : '';
    $status_pemesanan = ($_SESSION['role'] === 'admin') ? (isset($_POST['status_pemesanan']) ? $_POST['status_pemesanan'] : 'Dalam Proses') : 'Dalam Proses';

    // Validasi input
    if ($jumlah_pengunjung <= 0 || !$tanggal_pemesanan) {
        die("Input tidak valid.");
    }

    // Ambil ID pengunjung berdasarkan username
    $username = $_SESSION['username'];
    $sql_pengunjung = "SELECT id FROM pengunjung WHERE username = ?";
    $stmt_pengunjung = $koneksi->prepare($sql_pengunjung);
    $stmt_pengunjung->bind_param("s", $username);
    $stmt_pengunjung->execute();
    $stmt_pengunjung->bind_result($pengunjung_id);
    $stmt_pengunjung->fetch();
    $stmt_pengunjung->close();

    if (!$pengunjung_id) {
        die("Pengunjung tidak ditemukan.");
    }

    // Pilih kolam dengan jumlah pengunjung paling sedikit
    $sql_kolam = "SELECT id FROM kolam ORDER BY jumlah_pengunjung ASC LIMIT 1";
    $result_kolam = $koneksi->query($sql_kolam);
    if ($row_kolam = $result_kolam->fetch_assoc()) {
        $kolam_id = $row_kolam['id'];
    } else {
        die("Tidak ada kolam tersedia.");
    }

    // Generate unique code
    $kode_pesanan = generateUniqueCode();

    // Simpan pemesanan tiket
    $harga_tiket = 10000;
    $total_harga_tiket = $harga_tiket * $jumlah_pengunjung;

    $sql = "INSERT INTO pemesanan_tiket (pengunjung_id, tanggal_pemesanan, status_pemesanan, total_harga, kolam_id, kode_pesanan, status_nota)
            VALUES (?, ?, ?, ?, ?, ?, 'Belum Dikirim')";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("issdss", $pengunjung_id, $tanggal_pemesanan, $status_pemesanan, $total_harga_tiket, $kolam_id, $kode_pesanan);
    if ($stmt->execute()) {
        $pemesanan_id = $stmt->insert_id;
    } else {
        die("Terjadi kesalahan saat menambahkan pemesanan: " . $stmt->error);
    }
    $stmt->close();

    // Set harga tetap kuliner
    $harga_kuliner = 15000;

    // Hitung total harga kuliner
    $total_harga_kuliner = 0;

    // Siapkan query untuk memasukkan data kuliner
    $sql_kuliner = "INSERT INTO pemesanan_kuliner (pemesanan_id, kuliner_id, jumlah, harga) VALUES (?, ?, ?, ?)";
    $stmt_kuliner = $koneksi->prepare($sql_kuliner);

    if (isset($_POST['kuliner_id']) && is_array($_POST['kuliner_id'])) {
        foreach ($_POST['kuliner_id'] as $id => $jumlah) {
            $jumlah = intval($jumlah);
            if ($jumlah > 0) {
                $total_harga_kuliner += $harga_kuliner * $jumlah;
                $stmt_kuliner->bind_param("iiid", $pemesanan_id, $id, $jumlah, $harga_kuliner);
                if (!$stmt_kuliner->execute()) {
                    die("Terjadi kesalahan saat menambahkan data kuliner: " . $stmt_kuliner->error);
                }
            }
        }
    }
    $stmt_kuliner->close();

    // Simpan total harga keseluruhan (tiket + kuliner) di tabel pemesanan tiket
    $total_harga = $total_harga_tiket + $total_harga_kuliner;

    $sql_update_total = "UPDATE pemesanan_tiket SET total_harga = ? WHERE id = ?";
    $stmt_update_total = $koneksi->prepare($sql_update_total);
    $stmt_update_total->bind_param("di", $total_harga, $pemesanan_id);
    if (!$stmt_update_total->execute()) {
        die("Terjadi kesalahan saat memperbarui total harga: " . $stmt_update_total->error);
    }
    $stmt_update_total->close();

    // Redirect ke halaman konfirmasi pemesanan
    header("Location: konfirmasi_pemesanan.php?pemesanan_id=" . $pemesanan_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Kuliner dan Tiket</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Pemesanan Kuliner dan Tiket</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username Pengunjung</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pemesanan" class="form-label">Tanggal Pemesanan</label>
                        <input type="date" class="form-control" id="tanggal_pemesanan" name="tanggal_pemesanan" required>
                    </div>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                    <div class="mb-3">
                        <label for="status_pemesanan" class="form-label">Status</label>
                        <select class="form-select" id="status_pemesanan" name="status_pemesanan" required>
                            <option value="Dalam Proses">Dalam Proses</option>
                            <option value="Disetujui">Disetujui</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="kuliner" class="form-label">Pilih Kuliner dan Jumlah</label>
                        <div id="kuliner-container">
                            <?php
                            // Set harga tetap
                            $harga_kuliner = 15000;

                            // Ambil data kuliner dari database
                            $sql_kuliner = "SELECT id, nama FROM wisata_kuliner";
                            $result_kuliner = $koneksi->query($sql_kuliner);

                            while ($row = $result_kuliner->fetch_assoc()) {
                                echo "<div class='mb-3'>";
                                echo "<label for='kuliner_" . $row['id'] . "' class='form-label'>" . $row['nama'] . " - Rp" . number_format($harga_kuliner, 0, ',', '.') . "</label>";
                                echo "<input type='number' class='form-control' id='kuliner_" . $row['id'] . "' name='kuliner_id[" . $row['id'] . "]' min='0' placeholder='Jumlah'>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah_pengunjung" class="form-label">Jumlah Pengunjung</label>
                        <input type="number" class="form-control" id="jumlah_pengunjung" name="jumlah_pengunjung" min="1" required>
                    </div>
                    <a href="http://localhost/coba/app" class="btn btn-secondary">Kembali ke Dashboard</a>
                    <button type="submit" class="btn btn-primary">Kirim Pemesanan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
