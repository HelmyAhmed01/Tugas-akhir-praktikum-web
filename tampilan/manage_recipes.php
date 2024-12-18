<?php
session_start();
include './koneksi/db.php';

// Cek role admin
// Handle CREATE

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
            background-color: #f8f9fa;
        }

        /* Container untuk menambahkan margin dan padding yang sesuai */
        .container {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        /* Header Card */
        .card-header {
            background-color: #007bff;
            color: white;
            font-size: 1.25rem;
            font-weight: bold;
            text-align: center;
        }

        /* Card Body */
        .card-body {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            padding: 20px;
            border-radius: 8px;
        }

        /* Form styling */
        .form-label {
            font-weight: bold;
            color: #343a40;
        }

        /* Input dan Textarea styling */
        input[type="text"], textarea, input[type="file"], .form-control {
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #ced4da;
            width: 100%;
        }

        input[type="text"]:focus, textarea:focus, input[type="file"]:focus {
            border-color: #007bff;
            outline: none;
        }

        /* Button Styling */
        button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Tabel styling */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            text-align: center;
            padding: 12px;
            border: 1px solid #dee2e6;
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
        .btn-danger {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Responsif untuk ukuran kecil */
        @media (max-width: 768px) {
            .container {
                margin-top: 20px;
            }

            .card-body {
                padding: 15px;
            }

            .card-header {
                font-size: 1.1rem;
            }

            button[type="submit"] {
                width: auto;
            }

            .table th, .table td {
                font-size: 0.9rem;
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
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo substr($row['description'], 0, 50); ?>...</td>
                    <td>
                        <?php if ($row['image']) { ?>
                            <img src="<?php echo $row['image']; ?>" alt="Gambar Resep" style="width: 100px; height: 100px;">
                        <?php } else { ?>
                            <span>-</span>
                        <?php } ?>
                    </td>
                    <td>
                        <a href="index.php?page=manage_recipes&delete_id=<?php echo $row['id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Hapus resep ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
    </div>
    </br>
    <!-- Tombol Kembali -->
    <button onclick="window.history.back();" class="btn btn-secondary mb-4" style="padding: 5px 15px; font-size: 0.875rem;">Kembali</button>


    <?php include './koneksi/footer.php'; ?>
</body>
</html>
