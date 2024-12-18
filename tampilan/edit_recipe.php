<?php
session_start();
include './koneksi/db.php';

// Cek apakah ada id yang diterima
if (!isset($_GET['id'])) {
    header("Location: index.php?page=manage_recipes");
    exit();
}

$id = intval($_GET['id']);

// Ambil data resep berdasarkan id
$stmt = $conn->prepare("SELECT * FROM recipes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Resep tidak ditemukan.";
    exit();
}

$row = $result->fetch_assoc();

// Proses update data resep
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $image = $_FILES['image'];

    // Validasi input
    if (empty($name) || empty($description)) {
        echo "Nama dan deskripsi wajib diisi.";
        exit();
    }

    // Proses upload gambar jika ada
    $imagePath = $row['image']; // Gunakan gambar lama jika tidak ada gambar baru
    if ($image['size'] > 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $imagePath = $targetDir . basename($image["name"]);

        // Validasi file gambar
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($image['type'], $allowedTypes)) {
            echo "Format file tidak valid. Hanya JPG, PNG, dan GIF yang diperbolehkan.";
            exit();
        }

        // Upload file
        if (!move_uploaded_file($image["tmp_name"], $imagePath)) {
            echo "Gagal mengupload gambar.";
            exit();
        }
    }

    // Update data resep
    $stmt = $conn->prepare("UPDATE recipes SET name = ?, description = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $description, $imagePath, $id);
    if ($stmt->execute()) {
        header("Location: index.php?page=manage_recipes");
        exit();
    } else {
        echo "Gagal memperbarui data.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resep</title>
    <style>
        /* Gaya CSS tetap sama seperti sebelumnya */
    </style>
</head>
<body>
    <?php include './koneksi/header.php'; ?>

    <div class="container">
        <h2 class="text-center my-4">Edit Resep</h2>

        <!-- Form Edit Resep -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Update Resep</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Resep</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama resep" value="<?php echo $row['name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Masukkan deskripsi atau langkah memasak" rows="5" required><?php echo $row['description']; ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Resep</label>
                        <input type="file" id="image" name="image" class="form-control">
                        <?php if ($row['image']) { ?>
                            <img src="<?php echo $row['image']; ?>" alt="Gambar Resep" style="width: 100px; height: 100px; margin-top: 10px;">
                        <?php } else { ?>
                            <span>-</span>
                        <?php } ?>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Resep</button>
                </form>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <button onclick="window.history.back();" class="btn btn-secondary mb-4" style="padding: 5px 15px; font-size: 0.875rem;">Kembali</button>

    </div>

    <?php include './koneksi/footer.php'; ?>
</body>
</html>
