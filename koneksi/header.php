<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResepNusantara</title>
    <style>
        /* General styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navbar styling */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #343a40; /* Mirip warna bg-dark Bootstrap */
            padding: 10px 20px;
            color: white;
        }

        .navbar-brand {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar-toggler {
            display: none;
            font-size: 1.5rem;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }

        .navbar-nav {
            list-style: none;
            display: flex;
            gap: 15px;
            margin: 0;
            padding: 0;
        }

        .navbar-nav li {
            display: inline;
        }

        .navbar-nav li a {
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            transition: background-color 0.3s ease;
        }

        .navbar-nav li a:hover {
            background-color: #495057; /* Mirip warna hover Bootstrap */
            border-radius: 5px;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .navbar-toggler {
                display: block;
            }

            .navbar-nav {
                display: none;
                flex-direction: column;
                background-color: #343a40;
                position: absolute;
                top: 50px;
                right: 0;
                width: 100%;
                padding: 10px 0;
            }

            .navbar-nav.active {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a class="navbar-brand" href="index.php">ResepNusantara</a>
        <button class="navbar-toggler" onclick="toggleNavbar()">&#9776;</button>
        <ul class="navbar-nav" id="navbarNav">
            <li><a href="index.php">Home</a></li>
            <li><a href="#categories">Kategori</a></li>
            <li><a href="#popular-recipes">Resep Populer</a></li>
            <li><a href="?page=login">Login</a></li>
        </ul>
    </nav>

    <script>
        function toggleNavbar() {
            const nav = document.getElementById('navbarNav');
            nav.classList.toggle('active');
        }
    </script>
</body>
</html>
