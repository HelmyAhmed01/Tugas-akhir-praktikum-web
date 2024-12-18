<?php
include './koneksi/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi form
    if (empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom harus diisi.";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Cek apakah username sudah ada
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $error = "Username sudah digunakan.";
        } else {
            // Enkripsi password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Simpan pengguna baru
            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt->bind_param("ss", $username, $hashed_password);
            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                $error = "Gagal mendaftar, coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - ResepNusantara</title>
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
    <!-- Header -->
    <?php include './koneksi/header.php'; ?>

    <main>
        <section class="register-container">
            <div class="register-card">
                <h1>Daftar Akun</h1>
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
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn">Daftar</button>
                </form>
                <p>Sudah memiliki akun? <a href="index.php?page=login">Login di sini</a></p>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?php include './koneksi/footer.php'; ?>
</body>
</html>
