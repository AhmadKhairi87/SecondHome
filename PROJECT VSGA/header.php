<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secord Home - Perusahaan Anda</title>
    
    <!-- 
    PASTIKAN PATH INI BENAR! 
    'assets/css/style.css' berarti:
    1. Cari folder bernama 'assets' di level yang sama dengan index.php.
    2. Di dalam 'assets', cari folder 'css'.
    3. Di dalam 'css', cari file 'style.css'.
    JANGAN gunakan path absolut seperti 'C:/xampp/htdocs/...'
    -->
    <link rel="stylesheet" href="assets/css/style.css">

    <!-- Font dari Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="main-container">
        <header class="site-header">
            <div class="logo-container">
                <!-- 
                PASTIKAN PATH INI JUGA BENAR!
                'assets/images/logo.png' berarti:
                1. Cari folder 'assets'.
                2. Di dalamnya, cari folder 'images'.
                3. Di dalamnya, cari file 'logo.png'.
                Pastikan nama file logo Anda persis 'logo.png' dan ekstensinya .png
                -->
                <img src="assets/images/logo.png" alt="Logo Secord Home" class="logo">
                <span class="company-name">Secord Home</span>
            </div>
            <nav class="top-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="visi-misi.php">Visi dan Misi</a></li>
                    <li><a href="produk.php">Produk Kami</a></li>
                    <li><a href="kontak.php">Kontak</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </nav>
        </header>
        <div class="content-wrapper">
