<?php
include '../../config/config.php';

// Mengambil data pengunjung dengan filter
$filterName = isset($_GET['filter_name']) ? $_GET['filter_name'] : '';

// Query untuk mengambil data pengunjung dengan filter nama
$sql = "SELECT * FROM pengunjung WHERE username LIKE ? ORDER BY FIELD(role, 'Admin', 'Pengunjung')";

// Menyiapkan statement
$stmt = $koneksi->prepare($sql);

if ($stmt === false) {
    die("Gagal menyiapkan statement: " . $koneksi->error);
}

// Mengikat parameter dengan wildcard untuk LIKE
$filterNameParam = "%$filterName%";
$stmt->bind_param("s", $filterNameParam);

// Menjalankan statement
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Pengunjung</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            padding: 10px;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            position: relative;
        }
        .header img {
            height: 60px; /* Adjust logo size */
            position: absolute;
            left: 200px;
            top: 30px;
        }
        .header div {
            display: inline-block;
            text-align: center;
            width: 100%;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .date {
            position: absolute;
            right: 10px;
            top: 10px;
            font-size: 18px;
        }
        .container {
            padding: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .line {
            border-top: 2px solid #000;
            margin: 20px 0;
        }
        .buttons {
            text-align: center;
            margin: 20px 0;
        }
        .buttons button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 0 10px;
            cursor: pointer;
        }
        @media print {
            .header {
                border-bottom: none;
            }
            .container {
                padding: 10px;
            }
            .buttons {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../dist/img/ewin.jpg" alt="Logo">
        <div>
            <p class="date"> <?php echo date('d F Y'); ?></p>
            <h1>Data Pengunjung Pemancingan Joran Marindu</h1>
            <p>PEMANCINGAN JORAN MARINDU</p>
            <p> Jl. Karang Anyar 1 No. 123, Kota Banjarbaru</p>
            <p>Telp: (021) 12345678</p>
        </div>
    </div>

    <div class="line"></div>

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>No Telepon</th>
                    <th>Alamat</th>
                    <th>Umur</th>
                    <th>Role</th>
                    <th>Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['no_telepon']}</td>
                                <td>{$row['alamat']}</td>
                                <td>{$row['umur']}</td>
                                <td>{$row['role']}</td>
                                <td>{$row['tanggal_daftar']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada data.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="buttons">
        <button onclick="window.location.href='http://localhost/coba/app'">Kembali</button>
        <button onclick="window.print()">Cetak</button>
    </div>
</body>
</html>
