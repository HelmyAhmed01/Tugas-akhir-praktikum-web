<?php
session_start();
include './koneksi/db.php';

// Logika Hapus Resep
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']); // Pastikan ID adalah integer

    // Dapatkan path gambar dari database sebelum menghapus data
    $stmt = $conn->prepare("SELECT image FROM recipes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $stmt->fetch();
    $stmt->close();

    // Hapus file gambar jika ada
    if ($imagePath && file_exists($imagePath)) {
        unlink($imagePath);
    }

    // Hapus data dari database
    $stmt = $conn->prepare("DELETE FROM recipes WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: index.php?page=manage_recipes");
        exit();
    } else {
        echo "Gagal menghapus data.";
    }
}

// Handle CREATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $image = $_FILES['image'];

    // Validasi input
    if (empty($name) || empty($description)) {
        echo "Nama dan deskripsi wajib diisi.";
        exit();
    }

    // Proses upload gambar
    $imagePath = null;
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

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO recipes (name, description, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $description, $imagePath);
    if ($stmt->execute()) {
        header("Location: index.php?page=manage_recipes");
        exit();
    } else {
        echo "Gagal menyimpan data.";
    }
}

// Fetch all recipes
$result = $conn->query("SELECT * FROM recipes");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Resep Makanan</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #343a40;
            margin: 0;
            padding: 0;
        }

        /* Container for general padding */
        .container {
            margin-top: 40px;
            margin-bottom: 60px;
            padding: 20px;
        }

        /* Header Card */
        .card-header {
            background-color: #17a2b8;
            color: white;
            font-size: 1.6rem;
            font-weight: bold;
            text-align: center;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }

        /* Card Body */
        .card-body {
            background-color: #ffffff;
            border: 1px solid #e3e3e3;
            border-radius: 0 0 8px 8px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Form styling */
        .form-label {
            font-weight: bold;
            color: #495057;
        }

        /* Input dan Textarea styling */
        input[type="text"], textarea, input[type="file"], .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #ced4da;
            width: 100%;
            font-size: 1.1rem;
        }

        input[type="text"]:focus, textarea:focus, input[type="file"]:focus {
            border-color: #17a2b8;
            outline: none;
        }

        /* Button Styling */
        button[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 14px 22px;
            border-radius: 8px;
            font-size: 1.1rem;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #218838;
        }

        /* Table styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .table th, .table td {
            text-align: center;
            padding: 15px;
            border: 1px solid #dee2e6;
            font-size: 1.1rem;
        }

        .table th {
            background-color: #343a40;
            color: white;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .table img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        /* Tabel tombol aksi */
        .btn-danger, .btn-warning {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: black;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        /* Responsive styling for small screens */
        @media (max-width: 768px) {
            .container {
                margin-top: 20px;
            }

            .card-body {
                padding: 15px;
            }

            .card-header {
                font-size: 1.4rem;
            }

            button[type="submit"] {
                width: auto;
            }

            .table th, .table td {
                font-size: 1rem;
            }

            .table img {
                width: 90px;
                height: 90px;
            }
        }

    </style>
</head>
<body>
    <?php include './koneksi/header.php'; ?>

    <div class="container">
        <h2 class="text-center my-4">Kelola Resep Makanan</h2>

        <!-- Form Tambah Resep -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Tambah Resep Baru</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Resep</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama resep" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" placeholder="Masukkan deskripsi atau langkah memasak" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Resep</label>
                        <input type="file" id="image" name="image" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Resep</button>
                </form>
            </div>
        </div>

        <!-- Tabel Resep -->
        <div class="card">
            <div class="card-header">
                <h4>Daftar Resep</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Resep</th>
                            <th>Gambar</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><img src="<?php echo $row['image']; ?>" alt="gambar resep"></td>
                                <td><?php echo substr($row['description'], 0, 100) . '...'; ?></td>
                                <td>
                                    <a href="index.php?page=manage_recipes&delete_id=<?php echo $row['id']; ?>" class="btn btn-danger">Hapus</a>
                                    <a href="index.php?page=edit_recipe&id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php include './koneksi/footer.php'; ?>
</body>
</html>
