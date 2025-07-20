<?php
// Memasukkan file konfigurasi database
require_once 'config.php';

// Memasukkan bagian header website
include 'header.php';

// Memasukkan bagian sidebar website
include 'sidebar.php';
?>

<!-- Konten Utama -->
<main class="main-content"> 
    <h1>Welcome To Secord Home</h1>
    <p>Bukan sekadar tempat berkumpul, ini adalah rumah kedua tempat tawa dibagi, cerita diputar ulang, dan pertemanan tumbuh tanpa batas.</p>
    <hr style="margin: 20px 0;">

    <!-- Pesan sederhana yang mengarahkan pengguna ke halaman artikel -->
    <p>Untuk melihat semua tulisan dan berita terbaru, silakan kunjungi halaman <a href="artikel.php" style="color: #1B8B8F; font-weight: bold;">Artikel</a> kami.</p>
</main>

<?php
// Memasukkan bagian footer website
include 'footer.php';
?>