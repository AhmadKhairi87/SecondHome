<?php
// Memuat file konfigurasi, yang seharusnya sudah berisi koneksi database ($conn)
// dan mungkin juga sudah memulai sesi (session_start).
require_once 'config.php';

// --- PENGAMBILAN KATEGORI DARI URL ---
// Menggunakan operator ternary untuk mengambil nama kategori dari parameter URL '?k=...'.
// `isset($_GET['k'])`: Cek apakah parameter 'k' ada di URL.
// `htmlspecialchars($_GET['k'])`: Jika ada, ambil nilainya dan bersihkan dengan htmlspecialchars untuk mencegah serangan XSS.
// `'Umum'`: Jika parameter 'k' tidak ada, gunakan 'Umum' sebagai kategori default.
$category = isset($_GET['k']) ? htmlspecialchars($_GET['k']) : 'Umum';

// Menyertakan file-file tata letak standar untuk bagian atas dan samping halaman.
include 'header.php';
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Artikel Kategori: <?php echo ucfirst($category); ?></h1>
    <hr style="margin: 20px 0;"> <?php
    // --- LOGIKA PENGAMBILAN DAN PENAMPILAN ARTIKEL ---
    
    // Menyiapkan query SQL untuk mengambil artikel yang sesuai dengan kategori yang dipilih.
    // JOIN digunakan untuk mendapatkan username penulis dari tabel 'users'.
    // `WHERE articles.category = ?` adalah placeholder untuk prepared statement.
    // `ORDER BY articles.created_at DESC` mengurutkan artikel dari yang paling baru.
    $sql = "SELECT articles.id, articles.title, articles.content, articles.created_at, users.username 
            FROM articles 
            JOIN users ON articles.user_id = users.id 
            WHERE articles.category = ?
            ORDER BY articles.created_at DESC";
    
    // Mempersiapkan statement SQL untuk eksekusi yang aman (mencegah SQL Injection).
    if($stmt = $conn->prepare($sql)){
        // Mengikat (bind) variabel $category ke placeholder (?) sebagai string ("s").
        $stmt->bind_param("s", $category);
        // Menjalankan query.
        $stmt->execute();
        // Mengambil hasil dari query yang sudah dijalankan.
        $result = $stmt->get_result();

        // Cek apakah query berhasil dan mengembalikan setidaknya satu baris artikel.
        if ($result && $result->num_rows > 0) {
            // Melakukan perulangan untuk setiap artikel yang ditemukan.
            while($row = $result->fetch_assoc()) {
                // Mencetak (echo) struktur HTML untuk setiap ringkasan artikel.
                echo '<article class="article">';
                // Judul artikel sebagai tautan ke halaman detail artikel (view_article.php).
                echo '<h2><a href="view_article.php?id=' . $row["id"] . '">' . htmlspecialchars($row["title"]) . '</a></h2>';
                // Menampilkan metadata: penulis dan tanggal publikasi yang sudah diformat.
                echo '<p style="font-size: 0.9em; color: #555;">Ditulis oleh: <strong>' . htmlspecialchars($row['username']) . '</strong> pada ' . date('d M Y', strtotime($row['created_at'])) . '</p>';
                // Menampilkan cuplikan konten. `strip_tags` menghapus semua HTML dari konten agar tidak merusak layout,
                // lalu `substr` memotongnya menjadi 250 karakter pertama.
                echo '<p>' . substr(strip_tags($row["content"]), 0, 250) . '...</p>';
                // Tautan "Baca Selengkapnya" yang juga mengarah ke halaman detail artikel.
                echo '<a href="view_article.php?id=' . $row["id"] . '" class="read-more">Baca Selengkapnya</a>';
                echo '</article>';
            }
        } else {
            // Jika tidak ada artikel yang ditemukan dalam kategori ini, tampilkan pesan.
            echo "<p>Belum ada artikel dalam kategori ini.</p>";
        }
        // Menutup statement untuk melepaskan sumber daya.
        $stmt->close();
    }
    ?>
</main>

<?php
// Menyertakan file footer untuk bagian bawah halaman.
include 'footer.php';
?>