<?php
session_start();
include './koneksi/auth.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: index.php?page=admin');
        exit;
    } else {
        header('Location: index.php?page=user');
        exit;
    }
}

// Proses Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include './koneksi/db.php';

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Pastikan tidak ada input kosong
    if (empty($username) || empty($password)) {
        $error = "Username atau password tidak boleh kosong.";
    } else {
        // Cek jika yang login adalah admin menggunakan username dan password default
        if ($username === 'admin' && $password === 'admin123') {
            $_SESSION['user_id'] = 1;  // ID admin
            $_SESSION['role'] = 'admin';
            header('Location: index.php?page=admin');
            exit;
        }

        // Jika bukan admin, cari pengguna biasa di database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];  // Menyimpan username pengguna
            header('Location: index.php?page=user');
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ResepNusantara</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include './koneksi/header.php'; ?>

    <main>
        <section class="login-container">
            <div class="login-card">
                <h1>Login</h1>
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn">Masuk</button>
                </form>
                <p>Belum memiliki akun? <a href="index.php?page=register">Daftar di sini</a></p>
            </div>
        </section>
    </main>

    <?php include './koneksi/footer.php'; ?>
</body>
</html>
