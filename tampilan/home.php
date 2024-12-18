<?php include './koneksi/header.php'; ?>

<div class="container-fluid p-0">
    <!-- Hero Section -->
    <section class="hero d-flex align-items-center justify-content-center text-center" style="background-image: url('./images/hero.jpg'); background-size: cover; height: 80vh; background-position: center;">
        <div class="overlay d-flex flex-column align-items-center justify-content-center text-center p-4">
            <h1 class="text-white display-4 fw-bold mb-3">Selamat datang di ResepNusantara</h1>
            <p class="text-white fs-5 mb-4">Temukan beragam resep masakan khas Indonesia yang lezat dan mudah diikuti</p>
        </div>
    </section>

    <!-- Kategori Resep -->
    <section id="categories" class="py-5 bg-light">
        <h2 class="text-center mb-4">Kategori Resep</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded">
                        <img src="./images/kategori1.jpg" class="card-img-top" alt="Makanan Utama">
                        <div class="card-body text-center">
                            <h5 class="card-title">Makanan Utama</h5>
                            <p class="card-text">Cicipi resep masakan utama Indonesia yang menggugah selera.</p>
                            <a href="#" class="btn btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded">
                        <img src="./images/kategori2.jpg" class="card-img-top" alt="Makanan Penutup">
                        <div class="card-body text-center">
                            <h5 class="card-title">Makanan Penutup</h5>
                            <p class="card-text">Nikmati hidangan penutup manis.</p>
                            <a href="#" class="btn btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 rounded">
                        <img src="./images/kategori3.jpg" class="card-img-top" alt="Makanan Ringan">
                        <div class="card-body text-center">
                            <h5 class="card-title">Makanan Ringan</h5>
                            <p class="card-text">Temukan camilan sehat dan lezat untuk menemani harimu.</p>
                            <a href="#" class="btn btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Resep Populer -->
    <section id="popular-recipes" class="py-5">
        <h2 class="text-center mb-4">Resep Populer</h2>
        <div class="container">
            <div class="row">
                <?php
                // Koneksi ke database
                include './koneksi/db.php';

                // Query untuk mengambil 4 resep terbaru dari database
                $query = "SELECT * FROM recipes ORDER BY created_at DESC LIMIT 4";
                $result = $conn->query($query);

                // Menampilkan resep
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-3">';
                        echo '<div class="card shadow-sm border-0 rounded">';
                        echo '<img src="' . $row['image'] . '" class="card-img-top" alt="' . $row['name'] . '">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $row['name'] . '</h5>';
                        echo '<a href="recipe_detail.php?id=' . $row['id'] . '" class="btn btn-danger">Lihat Resep</a>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-center">Tidak ada resep yang tersedia</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include './koneksi/footer.php'; ?>
</div>
