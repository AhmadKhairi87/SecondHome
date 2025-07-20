<?php
// Memuat file konfigurasi (`config.php`). Ini penting untuk pengaturan dasar situs,
// seperti koneksi database. `require_once` memastikan file hanya dimuat sekali.
require_once 'config.php';

// Menyertakan file `header.php`. File ini biasanya berisi bagian atas dari
// halaman HTML, termasuk tag <head>, judul, dan navigasi.
include 'header.php';

// Menyertakan file `sidebar.php`, yang berisi panel navigasi samping.
// Menggunakan 'include' memudahkan pengelolaan kode agar tidak berulang.
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Tentang Secord Home</h1>
    
    <h2>Second Home: Tempat Kembali, Tempat Berbagi</h2>
    
    <h3>cerita dibawah hanyalah karangan AI</h3>
    
    <p>Di sebuah sudut desa kecil (bati-bati) yang tidak terlalu ramai, berdirilah sebuah rumah tua yang tidak terlalu besar, namun penuh cerita. Bukan rumah mewah, bukan pula kafe hits dengan desain estetik. Tapi di sanalah tawa bergema paling keras, cerita mengalir tanpa batas, dan kenangan tercipta tanpa sengaja. Mereka menamakannya Second Home.

Second Home bukan sekadar bangunan. Ia adalah rumah kedua—tempat pulang setelah lelah menghadapi dunia luar. Di dalamnya, berkumpul sekelompok pemuda dengan karakter dan latar belakang yang berbeda, namun dipersatukan oleh satu hal: rasa nyaman saat bersama.

Ada Raka, si penggila motor yang selalu datang dengan cerita touring-nya. Lalu Dani, anak sastra yang puitis tapi hobi bikin lelucon receh. Gibran, si pendiam yang hobi fotografi, tapi kalau sudah bercerita bisa bikin semua orang terdiam mendengarkan. Ada juga Wahyu si gamer, Rizal si penyuka kopi, dan Zidan si juru masak yang selalu siap menyiapkan mie rebus di jam berapa pun.

Mereka menyebut pertemanan mereka sebagai sircle—bukan cuma sekadar geng atau tongkrongan, tapi keluarga kedua. Di Second Home, tidak ada yang dihakimi, tidak ada yang merasa asing. Di sana, mereka tumbuh bersama. Bercerita soal cinta, saling curhat tentang tekanan hidup, gagal dan bangkit bersama, serta berbagi tawa tanpa perlu alasan.

Kadang mereka hanya duduk di lantai, bersila sambil memainkan gitar butut yang senarnya tinggal tiga. Di lain waktu, mereka bisa berdiskusi seru soal masa depan, saling dorong untuk lebih baik, sambil ngemil keripik dan menonton film lawas di proyektor seadanya.

Second Home adalah tempat di mana air mata bisa tumpah tanpa malu. Tempat di mana candaan konyol menjadi obat dari penatnya dunia. Tempat di mana kejujuran tidak ditolak, dan kehadiran selalu diterima.

Malam-malam panjang diisi dengan mimpi dan tawa. Pagi hari penuh dengan sisa cerita semalam yang belum habis dibahas. Mereka sadar, waktu terus berjalan. Masing-masing akan punya jalan hidup sendiri. Tapi selama ada Second Home, akan selalu ada tempat untuk pulang.

Karena second home bukan cuma rumah kedua. Ia adalah perasaan—bahwa di tengah ribuan manusia di luar sana, masih ada lingkaran kecil yang benar-benar mengerti. Tempat berbagi, tertawa, tumbuh, dan pulih.
#Info
Second Home bukan cuma tempat, tapi perasaan. Dan circle seperti ini layak dijaga, karena tak semua orang punya tempat untuk menjadi dirinya sendiri.

Kalau kamu punya Second Home versimu sendiri—jaga baik-baik. Itu bukan sekadar tempat nongkrong. Itu rumah, di mana tawa dan cerita akan selalu punya tempat untuk tinggal.</p>

</main>

<?php
// Menyertakan file `footer.php`. File ini berisi bagian bawah halaman,
// seperti informasi hak cipta atau tautan tambahan.
include 'footer.php'; 
?>