<?php
include('../../config/config.php');

// Mulai transaksi
$koneksi->begin_transaction();

try {
    // Ambil semua data dari tabel dan urutkan berdasarkan ID
    $result = $koneksi->query("SELECT id FROM pengunjung ORDER BY id");

    if ($result) {
        $newId = 1;
        while ($row = $result->fetch_assoc()) {
            $oldId = $row['id'];
            if ($oldId != $newId) {
                // Update ID untuk membuat ID berurutan
                $updateQuery = "UPDATE pengunjung SET id = ? WHERE id = ?";
                $stmt = $koneksi->prepare($updateQuery);
                $stmt->bind_param("ii", $newId, $oldId);
                $stmt->execute();
                $stmt->close();
            }
            $newId++;
        }
        
        // Atur ulang AUTO_INCREMENT untuk ID yang baru
        $resetQuery = "ALTER TABLE pengunjung AUTO_INCREMENT = ?";
        $stmt = $koneksi->prepare($resetQuery);
        $stmt->bind_param("i", $newId);
        $stmt->execute();
        $stmt->close();

        // Commit transaksi
        $koneksi->commit();
        echo "ID berhasil diatur ulang.";
    } else {
        throw new Exception("Gagal mengambil data.");
    }
} catch (Exception $e) {
    // Rollback jika terjadi kesalahan
    $koneksi->rollback();
    echo "Terjadi kesalahan: " . $e->getMessage();
}

// Menutup koneksi
$koneksi->close();
?>
