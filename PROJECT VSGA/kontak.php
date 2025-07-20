<?php
// Memuat file konfigurasi (`config.php`). 'require_once' memastikan file ini
// hanya dimuat sekali dan penting untuk pengaturan dasar situs.
require_once 'config.php';

// Menyertakan file `header.php`. File ini biasanya berisi bagian atas dari
// halaman HTML, termasuk tag <head>, judul, dan navigasi utama.
include 'header.php';

// Menyertakan file `sidebar.php`, yang berisi panel navigasi samping.
// Menggunakan 'include' memungkinkan penggunaan ulang komponen di berbagai halaman.
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Hubungi Kami</h1>
    
    <p>Jangan ragu untuk menghubungi kami melalui informasi di bawah ini:</p>
    
    <ul>
        <li><strong>Email:</strong> secondhome12@gmail.com</li>
        <li><strong>Telepon:</strong> 089629668124</li>
        <li><strong>Alamat:</strong> JL.Melati Bati-Bati Tanah Laut kalimantan Selatan</li>
    </ul>
</main>

<?php
// Menyertakan file `footer.php`. File ini berisi bagian bawah halaman,
// seperti informasi hak cipta atau tautan tambahan.
include 'footer.php'; 
?>