<?php
include '../../config/config.php';

// Proses pembaruan data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    // ... (proses pembaruan data lainnya)

    $sql = "UPDATE pengunjung SET username = ?, email = ? WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("ssi", $username, $email, $id);

    if ($stmt->execute()) {
        $updateSuccess = true; // Indikator bahwa pembaruan berhasil
    } else {
        $updateSuccess = false; // Indikator bahwa pembaruan gagal
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <style>
        /* Modal container */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        /* Modal content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            text-align: center;
        }
        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<!-- Form untuk pembaruan data -->
<form method="POST" action="update_user.php">
    <!-- Input fields for user data -->
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    <!-- ... (form fields lainnya) -->
    <button type="submit">Update</button>
</form>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <p id="modal-message">Data berhasil diperbarui.</p>
        <button id="modal-ok" class="btn-ok">OK</button>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan modal
    function showModal(message, redirectUrl) {
        var modal = document.getElementById("myModal");
        var modalMessage = document.getElementById("modal-message");
        var span = document.getElementsByClassName("close")[0];
        var okButton = document.getElementById("modal-ok");

        // Set pesan
        modalMessage.textContent = message;

        // Tampilkan modal
        modal.style.display = "block";

        // Tutup modal saat klik tombol close
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Tutup modal dan redirect saat klik OK
        okButton.onclick = function() {
            modal.style.display = "none";
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        }

        // Tutup modal saat klik di luar modal
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }

    // Menampilkan modal jika pembaruan berhasil
    <?php if (isset($updateSuccess) && $updateSuccess): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showModal('Data berhasil diperbarui.', 'http://localhost/coba/app/view/user.php');
        });
    <?php endif; ?>
</script>

</body>
</html>
