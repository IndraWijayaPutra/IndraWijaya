<?php
session_start();
include('../config/config.php');

if (!isset($_SESSION['pengunjung_id'])) {
    die("Pengunjung ID belum diatur. Pastikan pengguna sudah login.");
}

$pengunjung_id = $_SESSION['pengunjung_id'];
$pesan = "";
$show_pesan = false; // Flag untuk menampilkan pesan

// Periksa apakah formulir dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : [];
    $jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : [];
    $tanggal_penangkapan = isset($_POST['tanggal_penangkapan']) ? $_POST['tanggal_penangkapan'] : '';

    $success = true;
    $valid = false; // Flag untuk memeriksa setidaknya satu input jumlah diisi

    foreach ($id as $index => $ikan_id) {
        $jumlah_ikan = intval($jumlah[$index]);

        if ($jumlah_ikan > 0) {
            $valid = true;

            // Periksa apakah data sudah ada
            $sql_check = "SELECT * FROM penangkapan_ikan WHERE pengunjung_id = ? AND ikan_id = ? AND tanggal_penangkapan = ?";
            if ($stmt_check = $koneksi->prepare($sql_check)) {
                $stmt_check->bind_param("iis", $pengunjung_id, $ikan_id, $tanggal_penangkapan);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();

                if ($result_check->num_rows > 0) {
                    $pesan = "Data sudah ada untuk ikan ID $ikan_id pada tanggal $tanggal_penangkapan.";
                    $success = false;
                    $stmt_check->close();
                    break;
                }
                $stmt_check->close();
            } else {
                $pesan = "Gagal mempersiapkan query untuk memeriksa data.";
                $success = false;
                break;
            }

            // Cek jumlah ikan yang tersedia
            $sql_check_ikan = "SELECT jumlah FROM ikan WHERE id = ?";
            if ($stmt_check_ikan = $koneksi->prepare($sql_check_ikan)) {
                $stmt_check_ikan->bind_param("i", $ikan_id);
                $stmt_check_ikan->execute();
                $result_check_ikan = $stmt_check_ikan->get_result();
                $row_check_ikan = $result_check_ikan->fetch_assoc();

                if ($row_check_ikan['jumlah'] < $jumlah_ikan) {
                    $pesan = "Jumlah ikan yang ditangkap melebihi stok yang tersedia untuk ikan ID $ikan_id.";
                    $success = false;
                    $stmt_check_ikan->close();
                    break;
                }
                $stmt_check_ikan->close();
            } else {
                $pesan = "Gagal mempersiapkan query untuk memeriksa jumlah ikan.";
                $success = false;
                break;
            }

            // Insert into penangkapan_ikan (tanpa menyertakan kolom 'id' jika auto-increment)
            $sql_insert = "INSERT INTO penangkapan_ikan (pengunjung_id, ikan_id, tanggal_penangkapan, jumlah) VALUES (?, ?, ?, ?)";
            if ($stmt_insert = $koneksi->prepare($sql_insert)) {
                $stmt_insert->bind_param("iisi", $pengunjung_id, $ikan_id, $tanggal_penangkapan, $jumlah_ikan);
                if (!$stmt_insert->execute()) {
                    $pesan = "Gagal memasukkan data penangkapan ikan: " . $koneksi->error;
                    $success = false;
                    $stmt_insert->close();
                    break;
                }
                $stmt_insert->close();
            } else {
                $pesan = "Gagal mempersiapkan query untuk memasukkan data.";
                $success = false;
                break;
            }

            // Update jumlah ikan di tabel 'ikan'
            $sql_update_ikan = "UPDATE ikan SET kapasitas = kapasitas - ? WHERE id = ?";
            if ($stmt_update_ikan = $koneksi->prepare($sql_update_ikan)) {
                $stmt_update_ikan->bind_param("ii", $jumlah_ikan, $ikan_id);
                if (!$stmt_update_ikan->execute()) {
                    $pesan = "Gagal memperbarui jumlah ikan.";
                    $success = false;
                    $stmt_update_ikan->close();
                    break;
                }
                $stmt_update_ikan->close();
            } else {
                $pesan = "Gagal mempersiapkan query untuk memperbarui jumlah ikan.";
                $success = false;
                break;
            }
        }
    }

    if (!$valid) {
        $pesan = "Minimal satu jumlah ikan harus diisi.";
        $success = false;
    } elseif ($success) {
        $pesan = "Terima kasih telah melaporkan penangkapan ikan.";
        $show_pesan = true;
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Penangkapan Ikan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <style>
        .fade-out {
            transition: opacity 3s ease-out;
        }
        .fade-out.hidden {
            opacity: 0;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h3>Form Penangkapan Ikan</h3>
            </div>
            <div class="card-body">
                <?php if ($show_pesan): ?>
                    <div id="pesan" class="alert <?php echo $success ? 'alert-success' : 'alert-danger'; ?> fade-out">
                        <?php echo $pesan; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post">
                    <div id="ikan-container">
                        <?php
                        $sql_ikan = "SELECT id, nama FROM ikan";
                        $result_ikan = $koneksi->query($sql_ikan);

                        while ($row = $result_ikan->fetch_assoc()) {
                            echo "<div class='mb-3'>";
                            echo "<label for='ikan_" . $row['id'] . "' class='form-label'>" . $row['nama'] . "</label>";
                            echo "<input type='number' class='form-control' id='ikan_" . $row['id'] . "' name='id[]' value='" . $row['id'] . "' hidden>";
                            echo "<input type='number' class='form-control' name='jumlah[]' min='0' placeholder='Jumlah'>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_penangkapan" class="form-label">Tanggal Penangkapan</label>
                        <input type="date" class="form-control" id="tanggal_penangkapan" name="tanggal_penangkapan" required>
                    </div>
                    <button type="submit" class="btn btn-secondary">Kirim Data Penangkapan</button>
                    <a href="Jumlah_ikan_tangkapan.php" class="btn btn-secondary">Lihat Ikan apa saja yang paling banyak di tangkap</a>
                    <a href="http://localhost/coba/app" class="btn btn-secondary">Kembali ke Dashboard</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var pesan = document.getElementById('pesan');
            if (pesan) {
                setTimeout(function () {
                    pesan.classList.add('hidden');
                }, 3000); // Tampilkan pesan selama 3 detik
            }
        });
    </script>
</body>
</html>
