<?php
// Memuat file konfigurasi, yang biasanya berisi koneksi database ($conn).
require_once 'config.php';
// Menyertakan file-file tata letak standar untuk bagian atas (header) dan samping (sidebar) halaman.
include 'header.php';
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Semua Artikel</h1>
    <p>Temukan berita, tutorial, dan wawasan terbaru dari kami.</p>
    <hr style="margin: 20px 0;"> <?php
    // --- LOGIKA PENGAMBILAN DAN PENAMPILAN SEMUA ARTIKEL ---
    
    // Menyiapkan query SQL untuk mengambil semua artikel.
    // JOIN digunakan untuk menggabungkan tabel 'articles' dengan tabel 'users' berdasarkan 'user_id'
    // agar kita bisa mendapatkan nama penulis (username) untuk setiap artikel.
    // 'ORDER BY articles.created_at DESC' mengurutkan hasilnya dari yang paling baru.
    $sql = "SELECT articles.id, articles.title, articles.content, articles.created_at, users.username 
            FROM articles 
            JOIN users ON articles.user_id = users.id 
            ORDER BY articles.created_at DESC";
    
    // Menjalankan query pada database dan menyimpan hasilnya di variabel $result.
    // Untuk query sederhana tanpa input pengguna seperti ini, $conn->query() dapat digunakan.
    $result = $conn->query($sql);

    // Memeriksa apakah query berhasil dieksekusi DAN ada setidaknya satu baris hasil (artikel).
    if ($result && $result->num_rows > 0) {
        // Melakukan perulangan (loop) untuk setiap baris data artikel yang ditemukan.
        // fetch_assoc() mengambil satu baris sebagai array asosiatif (misal: $row['title']).
        while($row = $result->fetch_assoc()) {
            // Mencetak (echo) struktur HTML untuk setiap ringkasan artikel.
            echo '<article class="article">';
            // Menampilkan judul artikel sebagai tautan ke halaman detailnya (view_article.php?id=...).
            // htmlspecialchars() digunakan untuk keamanan, mencegah serangan XSS dari judul artikel.
            echo '<h2><a href="view_article.php?id=' . $row["id"] . '">' . htmlspecialchars($row["title"]) . '</a></h2>';
            // Menampilkan metadata: nama penulis dan tanggal publikasi yang sudah diformat agar mudah dibaca.
            echo '<p style="font-size: 0.9em; color: #555;">Ditulis oleh: <strong>' . htmlspecialchars($row['username']) . '</strong> pada ' . date('d M Y', strtotime($row['created_at'])) . '</p>';
            // Menampilkan cuplikan konten:
            // 1. strip_tags() menghapus semua tag HTML dari konten agar tidak merusak layout.
            // 2. substr() memotong teks yang sudah bersih itu menjadi 250 karakter pertama.
            echo '<p>' . substr(strip_tags($row["content"]), 0, 250) . '...</p>';
            // Menampilkan tautan "Baca Selengkapnya" yang juga mengarah ke halaman detail artikel.
            echo '<a href="view_article.php?id=' . $row["id"] . '" class="read-more">Baca Selengkapnya</a>';
            echo '</article>';
        }
    } else {
        // Jika tidak ada artikel yang ditemukan di database, tampilkan pesan ini.
        echo "<p>Belum ada artikel yang dipublikasikan.</p>";
    }
    ?>
</main>

<?php
// Menyertakan file footer untuk bagian bawah halaman.
include 'footer.php';
?>