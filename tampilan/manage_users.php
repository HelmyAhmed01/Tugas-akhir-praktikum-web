<?php
session_start();
include './koneksi/db.php';

// Cek role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php?page=login");
    exit();
}

// Handle DELETE
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: index.php?page=manage_users");    
    exit();
}

// Handle CREATE
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
    header("Location: index.php?page=manage_users");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- CSS tambahan langsung di sini -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        main {
            margin: 20px;
        }

        h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        h4 {
            font-size: 1.5rem;
            color: #555;
            margin-bottom: 10px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            width: 100%;
            max-width: 500px;
        }

        form input, form select, form button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        form button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table td a {
            color: #dc3545;
            text-decoration: none;
        }

        table td a:hover {
            text-decoration: underline;
        }
        .btn-back {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }

        .btn-back:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
            form {
                width: 100%;
                padding: 15px;
            }

            table {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
<?php include './koneksi/header.php'; ?>
<main>
    
    <h2>Kelola Pengguna</h2>

    <!-- Form Tambah User -->
    <h4>Tambah Pengguna</h4>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <button type="submit">Tambah</button>
    </form>

    <!-- Tabel User -->
    <h4>Daftar Pengguna</h4>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['username']; ?></td>
            <td><?php echo $row['role']; ?></td>
            <td>
                <a href="index.php?page=manage_users&delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
        </br>
    <button class="btn-back" onclick="window.history.back();">Kembali</button>
</main>
<?php include './koneksi/footer.php'; ?>
</body>
</html>
