<?php
include('../../config/config.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID adalah integer

    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // Query untuk menghapus data dari tabel pengunjung_kuliner
        $query1 = "DELETE FROM pengunjung_kuliner WHERE pengunjung_id = ?";
        $stmt1 = $koneksi->prepare($query1);
        if ($stmt1 === false) {
            throw new Exception("Gagal menyiapkan statement: " . $koneksi->error);
        }
        $stmt1->bind_param("i", $id);
        $stmt1->execute();
        $stmt1->close();

        // Query untuk menghapus data dari tabel pengunjung
        $query2 = "DELETE FROM pengunjung WHERE id = ?";
        $stmt2 = $koneksi->prepare($query2);
        if ($stmt2 === false) {
            throw new Exception("Gagal menyiapkan statement: " . $koneksi->error);
        }
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->close();

        // Commit transaksi
        $koneksi->commit();

        // Tampilkan pesan sukses dan alihkan setelah 2 detik
        echo "<!DOCTYPE html>
              <html lang='en'>
              <head>
                  <meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>Data Dihapus</title>
                  <style>
                      body {
                          display: flex;
                          justify-content: center;
                          align-items: center;
                          height: 100vh;
                          margin: 0;
                          background-color: #f8f9fa;
                          text-align: center;
                      }
                      .message {
                          background-color: #28a745;
                          color: white;
                          padding: 20px;
                          border-radius: 5px;
                          font-size: 24px;
                      }
                  </style>
              </head>
              <body>
                  <div class='message'>
                      Data berhasil dihapus.
                  </div>
                  <script>
                      document.addEventListener('DOMContentLoaded', function() {
                          setTimeout(function() {
                              window.location.href = '../index.php'; // Redirect ke halaman dashboard
                          }, 2000); // Tampilkan selama 2 detik
                      });
                  </script>
              </body>
              </html>";
    } catch (Exception $e) {
        $koneksi->rollback();
        // Tampilkan pesan error jika terjadi kesalahan
        echo "<!DOCTYPE html>
              <html lang='en'>
              <head>
                  <meta charset='UTF-8'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                  <title>Gagal Menghapus</title>
              </head>
              <body>
                  <script>
                      alert('Gagal menghapus data: " . $e->getMessage() . "');
                      window.location.href = '../index.php'; // Redirect ke halaman dashboard
                  </script>
              </body>
              </html>";
    }

    // Menutup koneksi
    $koneksi->close();
    exit();
} else {
    // Redirect dengan pesan error jika ID tidak ditemukan
    echo "<!DOCTYPE html>
          <html lang='en'>
          <head>
              <meta charset='UTF-8'>
              <meta name='viewport' content='width=device-width, initial-scale=1.0'>
              <title>ID Tidak Ditemukan</title>
          </head>
          <body>
              <script>
                  alert('ID tidak ditemukan.');
                  window.location.href = '../index.php'; // Redirect ke halaman dashboard
              </script>
          </body>
          </html>";
    exit();
}
?>
