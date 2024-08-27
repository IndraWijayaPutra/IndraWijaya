<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Usia Pengunjung</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" onerror="this.onerror=null;this.href='https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css';">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .btn-custom {
            background-color: #007bff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .btn-print {
            background-color: #28a745;
            color: white;
        }
        .btn-print:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header text-center">
                <h3>Grafik Usia Pengunjung</h3>
            </div>
            <div class="card-body">
                <!-- Tombol Kembali dan Cetak -->
                <div class="d-flex justify-content-between mb-3">
                    <a href="http://localhost/coba/app/" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button class="btn btn-print" onclick="printPage()">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
                <!-- Canvas untuk grafik -->
                <canvas id="usiaChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Ambil data dari data_usia.php
        fetch('data_usia.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const ctx = document.getElementById('usiaChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Jumlah Pengunjung',
                            data: data.data,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });

        // Fungsi untuk mencetak halaman
        function printPage() {
            window.print();
        }
    </script>
</body>
</html>
