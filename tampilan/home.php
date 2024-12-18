<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResepNusantara</title>
    <!-- Link ke AOS CSS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        /* Reset dan General Styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        h1, h2, h5 {
            margin: 0;
        }

        /* Hero Section */
        .hero {
            position: relative;
            background-image: url('./images/hero.jpg');
            background-size: cover;
            background-position: center;
            height: 80vh;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero .overlay {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
        }

        /* Section Umum */
        section {
            padding: 50px 20px;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        /* Kategori Resep */
        .categories-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background-color: white;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px;
            text-align: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #a71d2a;
        }

        /* Footer */
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero .overlay h1 {
                font-size: 1.8rem;
            }

            .hero .overlay p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include './koneksi/header.php';?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="overlay" data-aos="fade-up" data-aos-duration="1500">
            <h1 class="display-4 fw-bold mb-3">Selamat datang di ResepNusantara</h1>
            <p class="fs-5 mb-4">Temukan beragam resep masakan khas Indonesia yang lezat dan mudah diikuti</p>
        </div>
    </section>

    <!-- Kategori Resep -->
    <section id="categories" class="bg-light">
        <h2 class="text-center mb-4" data-aos="fade-down" data-aos-duration="1500">Kategori Resep</h2>
        <div class="categories-container">
            <div class="card" data-aos="zoom-in" data-aos-duration="1000">
                <img src="./images/kategori1.jpg" alt="Makanan Utama">
                <div class="card-body">
                    <h5 class="card-title">Makanan Utama</h5>
                    <p class="card-text">Cicipi resep masakan utama Indonesia yang menggugah selera.</p>
                    <a href="#" class="btn">Lihat Semua</a>
                </div>
            </div>
            <div class="card" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="200">
                <img src="./images/kategori2.jpg" alt="Makanan Penutup">
                <div class="card-body">
                    <h5 class="card-title">Makanan Penutup</h5>
                    <p class="card-text">Nikmati hidangan penutup manis.</p>
                    <a href="#" class="btn">Lihat Semua</a>
                </div>
            </div>
            <div class="card" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="400">
                <img src="./images/kategori3.jpg" alt="Makanan Ringan">
                <div class="card-body">
                    <h5 class="card-title">Makanan Ringan</h5>
                    <p class="card-text">Temukan camilan sehat dan lezat untuk menemani harimu.</p>
                    <a href="#" class="btn">Lihat Semua</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Resep Populer -->
    <section id="popular-recipes">
        <h2 class="text-center mb-4" data-aos="fade-down" data-aos-duration="1500">Resep </h2>
        <div class="categories-container">
            <?php
            include './koneksi/db.php';
            $query = "SELECT * FROM recipes ORDER BY created_at DESC LIMIT 4";
            $result = $conn->query($query);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="card" data-aos="flip-left" data-aos-duration="1200">';
                    echo '<img src="' . $row['image'] . '" alt="' . $row['name'] . '">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row['name'] . '</h5>';
                    echo '<a href="recipe_detail.php?id=' . $row['id'] . '" class="btn btn-danger">Lihat Resep</a>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="text-center">Tidak ada resep yang tersedia</p>';
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 ResepNusantara. Semua Hak Dilindungi</p>
    </footer>

    <!-- Script AOS -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>
