<?php
include '../../config/config.php';

// Ambil ID dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Query untuk mengambil data pengunjung berdasarkan ID
    $sql = "SELECT * FROM pengunjung WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    
    if ($stmt === false) {
        die("Gagal menyiapkan statement: " . $koneksi->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Data tidak ditemukan.");
    }
} else {
    die("ID tidak valid.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengunjung</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        .form-group {
            margin-bottom: 1rem;
        }
        .btn-save {
            background-color: #28a745; /* Warna hijau */
            color: white;
            border: none;
        }
        .btn-save:hover {
            background-color: #218838; /* Warna hijau gelap */
            color: white;
        }
        .btn-back {
            background-color: #007bff; /* Warna biru */
            color: white;
            border: none;
        }
        .btn-back:hover {
            background-color: #0056b3; /* Warna biru gelap */
            color: white;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Edit Pengunjung</h2>
    <form action="update_user.php" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="no_telepon">No Telepon</label>
            <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?php echo htmlspecialchars($row['no_telepon']); ?>" required>
        </div>
        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?php echo htmlspecialchars($row['alamat']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="umur">Umur</label>
            <input type="number" class="form-control" id="umur" name="umur" value="<?php echo htmlspecialchars($row['umur']); ?>" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="Admin" <?php echo $row['role'] == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="Pengunjung" <?php echo $row['role'] == 'Pengunjung' ? 'selected' : ''; ?>>Pengunjung</option>
            </select>
        </div>
        <button type="submit" class="btn btn-save">Simpan</button>
        <a href="user.php" class="btn btn-back">Kembali</a>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
