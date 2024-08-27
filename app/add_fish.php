<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ikan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Tambah Ikan</h1>
        <a href="http://localhost/coba/app/" class="btn btn-secondary mb-3">Kembali</a>
        <form action="save_fish.php" method="post">
            <div class="form-group">
                <label for="nama">Nama Ikan</label>
                <input type="text" class="form-control" id="nama" name="nama" required>
            </div>
            <div class="form-group">
                <label for="jenis">Jenis</label>
                <input type="text" class="form-control" id="jenis" name="jenis" required>
            </div>
            <div class="form-group">
                <label for="habitat">Habitat</label>
                <input type="text" class="form-control" id="habitat" name="habitat" required>
            </div>
            <div class="form-group">
                <label for="makanan">Makanan</label>
                <input type="text" class="form-control" id="makanan" name="makanan" required>
            </div>
            <div class="form-group">
                <label for="berat_rata_rata">Berat Rata-Rata (kg)</label>
                <input type="number" class="form-control" id="berat_rata_rata" name="berat_rata_rata" step="0.01" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
