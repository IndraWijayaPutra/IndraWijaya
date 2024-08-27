<?php
include('../config/config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM lokasi WHERE id = ?";
    if ($stmt = $koneksi->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $location = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "Gagal mempersiapkan query.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lokasi</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Edit Lokasi</h1>
        <form action="process_location.php" method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($location['id']); ?>">
            <div class="form-group">
                <label for="nama">Nama Lokasi:</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($location['nama']); ?>" required>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude:</label>
                <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($location['latitude']); ?>" required>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude:</label>
                <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($location['longitude']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="view_locations.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
