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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $role);
    $stmt->execute();
    header("Location: index.php?page=manage_users");
    exit();
}

// Handle UPDATE (username or password)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_user'])) {
    $id = $_POST['user_id'];
    
    // Update username if it's changed
    if (!empty($_POST['username'])) {
        $username = $_POST['username'];
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $username, $id);
        $stmt->execute();
    }
    
    // Update password if it's changed
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $password, $id);
        $stmt->execute();
    }
    
    header("Location: index.php?page=manage_users");
    exit();
}

// Fetch all users
$result = $conn->query("SELECT * FROM users");

// Fetch specific user for update
$user_to_edit = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user_to_edit = $user_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        main {
            margin: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        h4 {
            font-size: 1.5rem;
            color: #555;
            margin-bottom: 10px;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            width: 100%;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
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
            display: block;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
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

    <!-- Form Edit Username -->
    <?php if ($user_to_edit): ?>
        <h4>Edit Username</h4>
        <form method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user_to_edit['id']; ?>">
            <input type="text" name="username" value="<?php echo $user_to_edit['username']; ?>" placeholder="Username" required>
            <button type="submit" name="update_user">Perbarui Username</button>
        </form>
        
        <!-- Form Edit Password -->
        <h4>Edit Password</h4>
        <form method="POST">
            <input type="hidden" name="user_id" value="<?php echo $user_to_edit['id']; ?>">
            <input type="password" name="password" placeholder="Password Baru" required>
            <button type="submit" name="update_user">Perbarui Password</button>
        </form>
    <?php else: ?>
        <h4>Tambah Pengguna</h4>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
            <button type="submit" name="create_user">Tambah</button>
        </form>
    <?php endif; ?>

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
                <a href="index.php?page=manage_users&edit_id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="index.php?page=manage_users&delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>

    <br>
    <button class="btn-back" onclick="window.history.back();">Kembali</button>
</main>
<?php include './koneksi/footer.php'; ?>
</body>
</html>
