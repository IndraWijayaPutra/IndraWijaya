<?php
$servername = "localhost"; // Hostname database
$username = "root";        // Username database
$password = "";            // Password database (kosong jika tidak ada password)
$dbname = "pemancingan";   // Nama database

// Membuat koneksi
$koneksi = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi database gagal: " . $koneksi->connect_error);
}
?>
