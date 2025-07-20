<?php
// Memuat file konfigurasi (yang juga harusnya sudah memulai sesi) dan koneksi database.
require_once 'config.php';
// Menyertakan file-file tata letak standar untuk bagian atas dan samping halaman.
include 'header.php';
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Produk & Layanan Kami</h1>
    <p>Berikut adalah produk-produk unggulan yang kami tawarkan.</p>
    
    <?php 
    // --- TOMBOL KONDISIONAL UNTUK ADMIN/PENGGUNA LOGIN ---
    // Blok ini memeriksa apakah pengguna sudah login.
    // Jika ya, tombol "+ Tambah Produk Baru" akan ditampilkan.
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): 
    ?>
        <a href="tambah_produk.php" class="btn" style="display:inline-block; width:auto; margin-bottom: 20px;">+ Tambah Produk Baru</a>
    <?php endif; // Akhir dari blok kondisional if. ?>

    <div class="product-grid">
        <?php
        // --- LOGIKA PENGAMBILAN DAN PENAMPILAN PRODUK ---
        
        // Menyiapkan query SQL untuk mengambil semua data dari tabel 'products'.
        // 'ORDER BY created_at DESC' mengurutkan produk dari yang paling baru ditambahkan.
        $sql = "SELECT id, name, description, image_path FROM products ORDER BY created_at DESC";
        // Menjalankan query pada database.
        $result = $conn->query($sql);

        // Memeriksa apakah query berhasil dieksekusi DAN ada setidaknya satu baris hasil.
        if ($result && $result->num_rows > 0) {
            // Melakukan perulangan (loop) untuk setiap baris data produk yang ditemukan.
            // 'fetch_assoc()' mengambil satu baris sebagai array asosiatif (misal: $row['name']).
            while($row = $result->fetch_assoc()) {
                // Mencetak (echo) struktur HTML untuk setiap kartu produk.
                echo '<div class="product-card">';
                // Menampilkan gambar produk. `htmlspecialchars` digunakan untuk keamanan, mencegah serangan XSS dari path atau nama file.
                echo '    <img src="' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['name']) . '">';
                echo '    <div class="product-info">';
                // Menampilkan nama produk.
                echo '        <h3>' . htmlspecialchars($row['name']) . '</h3>';
                // Menampilkan ringkasan deskripsi. `substr()` digunakan untuk memotong deskripsi hanya 100 karakter pertama.
                echo '        <p>' . htmlspecialchars(substr($row['description'], 0, 100)) . '...</p>';
                echo '    </div>';
                echo '</div>';
            }
        } else {
            // Jika tidak ada produk yang ditemukan di database, tampilkan pesan ini.
            echo "<p>Belum ada produk yang ditambahkan.</p>";
        }
        ?>
    </div>
</main>

<style>
/* Membuat layout grid yang responsif untuk produk */
.product-grid {
    display: grid; /* Mengaktifkan layout grid */
    /* Kolom akan dibuat secara otomatis, dengan lebar minimal 280px dan akan mengisi ruang yang tersedia */
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px; /* Jarak antar kartu produk */
    margin-top: 25px;
}
/* Styling untuk setiap kartu produk */
.product-card {
    border: 1px solid #e0dcd1;
    border-radius: 8px; /* Sudut yang melengkung */
    overflow: hidden; /* Memastikan gambar tidak keluar dari border-radius */
    box-shadow: 0 4px 15px rgba(18, 44, 52, 0.08); /* Efek bayangan lembut */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Animasi transisi saat hover */
    background-color: #fff;
}
/* Efek saat kursor mouse berada di atas kartu produk */
.product-card:hover {
    transform: translateY(-5px); /* Mengangkat kartu sedikit ke atas */
    box-shadow: 0 8px 25px rgba(18, 44, 52, 0.12); /* Bayangan menjadi lebih jelas */
}
/* Styling untuk gambar produk */
.product-card img {
    width: 100%;
    height: 220px;
    object-fit: cover; /* Memastikan gambar mengisi area tanpa distorsi, memotong jika perlu */
    display: block;
    background-color: #f0f0f0; /* Warna latar jika gambar gagal dimuat */
}
/* Area informasi di bawah gambar */
.product-card .product-info {
    padding: 20px;
}
.product-card h3 {
    font-size: 1.25em;
    margin-top: 0;
    margin-bottom: 10px;
    color: #122C34;
}
.product-card p {
    font-size: 0.95em;
    line-height: 1.5;
    color: #555;
    margin-bottom: 0;
}
</style>

<?php 
// Menyertakan file footer untuk bagian bawah halaman.
include 'footer.php'; 
?>