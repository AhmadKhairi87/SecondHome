<?php
// Memuat file konfigurasi database dan pengaturan penting lainnya.
// 'require_once' memastikan file ini hanya dimuat sekali dan akan menghentikan eksekusi jika file tidak ditemukan.
require_once 'config.php';

// --- VALIDASI INPUT ---
// Memeriksa apakah parameter 'id' ada di URL (misal: detail_artikel.php?id=123).
// Jika 'id' tidak ada atau nilainya kosong, pengguna akan dialihkan ke halaman daftar artikel.
// 'exit' digunakan untuk menghentikan eksekusi script lebih lanjut setelah pengalihan.
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("location: artikel.php");
    exit;
}

// Menyimpan ID artikel dari URL ke dalam variabel untuk digunakan dalam query database.
$article_id = $_GET['id'];

// --- PENGAMBILAN DATA DARI DATABASE ---
// Menyiapkan query SQL untuk mengambil data artikel spesifik.
// Menggunakan JOIN untuk menggabungkan tabel 'articles' dengan 'users' agar bisa mendapatkan nama penulis (username).
// Tanda tanya (?) adalah placeholder untuk 'prepared statement', sebuah praktik keamanan untuk mencegah SQL Injection.
$sql = "SELECT articles.title, articles.content, articles.created_at, users.username 
        FROM articles 
        JOIN users ON articles.user_id = users.id 
        WHERE articles.id = ?";

// Inisialisasi variabel $article dengan null. Variabel ini akan diisi dengan data jika artikel ditemukan.
$article = null;

// Mempersiapkan dan mengeksekusi query dengan cara yang aman.
if($stmt = $conn->prepare($sql)){
    // Mengikat (bind) variabel $article_id ke placeholder (?) dalam query.
    // "i" menandakan bahwa tipe data yang diikat adalah integer.
    $stmt->bind_param("i", $article_id);
    
    // Menjalankan query yang sudah disiapkan.
    $stmt->execute();
    
    // Mengambil hasil dari query.
    $result = $stmt->get_result();
    
    // Memeriksa apakah query menghasilkan tepat satu baris data.
    if($result->num_rows == 1){
        // Jika ditemukan, ambil data artikel dan simpan sebagai array asosiatif ke dalam variabel $article.
        $article = $result->fetch_assoc();
    } else {
        // Jika tidak ada artikel dengan ID tersebut, alihkan pengguna kembali ke halaman daftar artikel.
        header("location: artikel.php");
        exit;
    }
    // Menutup statement untuk melepaskan sumber daya server.
    $stmt->close();
}

// Memuat file 'header.php' (bagian atas halaman) dan 'sidebar.php' (panel samping).
include 'header.php';
include 'sidebar.php';
?>

<main class="main-content">
    <?php // Memulai blok PHP untuk menampilkan konten secara dinamis. ?>
    <?php if($article): // Kondisi ini memeriksa apakah variabel $article berhasil diisi (artinya artikel ditemukan). ?>
        <article class="full-article">
            <h1><?php echo htmlspecialchars($article['title']); ?></h1>
            
            <p class="meta" style="font-size: 0.9em; color: #555; margin-bottom: 20px; border-bottom: 1px solid #e0dcd1; padding-bottom: 15px;">
                Ditulis oleh: <strong><?php echo htmlspecialchars($article['username']); ?></strong> 
                pada <?php echo date('d F Y', strtotime($article['created_at'])); // Mengubah format tanggal dari database menjadi format yang mudah dibaca (misal: 20 Juli 2025) ?>
            </p>
            
            <div class="article-content">
                <?php 
                // Menampilkan konten artikel.
                // htmlspecialchars() digunakan untuk keamanan (mencegah XSS).
                // nl2br() mengubah karakter baris baru (\n) dari database menjadi tag HTML <br>,
                // sehingga format paragraf dan spasi antar baris tetap terjaga seperti saat ditulis.
                echo nl2br(htmlspecialchars($article['content'])); 
                ?>
            </div>
        </article>
    <?php endif; // Menutup blok kondisi 'if'. Jika $article null, tidak ada yang akan ditampilkan di dalam <main>. ?>
</main>

<?php 
// Memuat file 'footer.php' untuk menampilkan bagian bawah halaman.
include 'footer.php'; 
?>