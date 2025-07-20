<?php
// Memuat file konfigurasi, yang seharusnya sudah memulai sesi dan menyiapkan koneksi DB.
require_once 'config.php';

// --- KEAMANAN TINGKAT 1: OTENTIKASI PENGGUNA ---
// Cek apakah pengguna sudah login. Jika belum, mereka tidak boleh mengakses halaman ini.
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php"); // Alihkan ke halaman login.
    exit;
}

// --- VALIDASI AWAL: MEMASTIKAN ID ARTIKEL ADA ---
// Halaman ini memerlukan ID artikel dari URL (misal: edit_article.php?id=123) untuk tahu artikel mana yang harus diedit.
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("location: dashboard.php"); // Jika tidak ada ID, kembali ke dashboard.
    exit;
}

// --- INISIALISASI VARIABEL ---
// Variabel untuk menampung data dari URL, Sesi, dan Form.
$article_id = $_GET['id']; // ID artikel yang akan diedit, dari URL.
$user_id = $_SESSION['id']; // ID pengguna yang sedang login, dari sesi.
$title = $content = ""; // Variabel untuk menampung judul dan konten, diinisialisasi kosong.
$title_err = $content_err = ""; // Variabel untuk menampung pesan error validasi, diinisialisasi kosong.


// --- LOGIKA #1: MEMPROSES FORM SAAT DISUBMIT (METODE POST) ---
// Blok ini hanya akan berjalan ketika pengguna menekan tombol "Simpan Perubahan" (method="post").
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Validasi input judul dari form.
    if(empty(trim($_POST["title"]))){
        $title_err = "Judul tidak boleh kosong."; // Jika kosong, set pesan error.
    } else{
        $title = trim($_POST["title"]); // Jika ada, bersihkan dan simpan.
    }
    
    // Validasi input konten dari form.
    if(empty(trim($_POST["content"]))){
        $content_err = "Konten tidak boleh kosong."; // Jika kosong, set pesan error.
    } else{
        $content = trim($_POST["content"]); // Jika ada, bersihkan dan simpan.
    }
    
    // Cek apakah tidak ada error validasi sebelum melanjutkan ke database.
    if(empty($title_err) && empty($content_err)){
        // Menyiapkan query UPDATE dengan placeholder (?) untuk keamanan (mencegah SQL Injection).
        // PENTING: Klausa `AND user_id = ?` adalah langkah keamanan krusial untuk memastikan
        // seorang pengguna hanya bisa mengedit artikel miliknya sendiri.
        $sql = "UPDATE articles SET title = ?, content = ? WHERE id = ? AND user_id = ?";
        
        if($stmt = $conn->prepare($sql)){
            // Mengikat (bind) variabel PHP ke placeholder. "ssii" mendefinisikan tipe datanya:
            // s = string ($title), s = string ($content), i = integer ($article_id), i = integer ($user_id)
            $stmt->bind_param("ssii", $title, $content, $article_id, $user_id);
            
            // Mencoba mengeksekusi statement.
            if($stmt->execute()){
                // Jika berhasil, set pesan sukses di session dan arahkan ke dashboard.
                $_SESSION['success_message'] = "Artikel berhasil diperbarui!";
                header("location: dashboard.php");
                exit();
            } else{
                echo "Terjadi kesalahan. Silakan coba lagi nanti.";
            }
            // Selalu tutup statement.
            $stmt->close();
        }
    }
}


// --- LOGIKA #2: MENGAMBIL DATA AWAL ARTIKEL (UNTUK MENGISI FORM) ---
// Blok ini berjalan saat halaman pertama kali dimuat (metode GET) untuk mengambil
// data artikel yang ada dan menampilkannya di dalam form.
$sql_fetch = "SELECT title, content FROM articles WHERE id = ? AND user_id = ?";
if($stmt_fetch = $conn->prepare($sql_fetch)){
    // Mengikat ID artikel dan ID pengguna untuk memastikan hanya pemilik yang bisa mengambil data.
    $stmt_fetch->bind_param("ii", $article_id, $user_id);
    if($stmt_fetch->execute()){
        $result = $stmt_fetch->get_result();
        // Cek apakah artikel ditemukan dan benar milik pengguna ini (hasil harus tepat 1 baris).
        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            // Isi variabel $title dan $content dengan data dari database.
            // Variabel ini akan digunakan untuk mengisi 'value' pada form di bawah.
            $title = $row['title'];
            $content = $row['content'];
        } else {
            // Jika artikel tidak ditemukan atau bukan milik user, tendang kembali ke dashboard.
            header("location: dashboard.php");
            exit;
        }
    }
    // Selalu tutup statement.
    $stmt_fetch->close();
}


// Menyertakan file-file tata letak standar.
include 'header.php';
include 'sidebar.php';
?>

<main class="main-content">
    <h1>Edit Artikel</h1>
    <p>Perbarui judul atau isi konten artikel Anda.</p>

    <form action="edit_article.php?id=<?php echo $article_id; ?>" method="post" class="article-form">
        <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
            <label>Judul Artikel</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($title); ?>">
            <span class="help-block" style="color:red;"><?php echo $title_err; ?></span>
        </div>    
        <div class="form-group <?php echo (!empty($content_err)) ? 'has-error' : ''; ?>">
            <label>Konten</label>
            <textarea name="content" class="form-control" rows="10"><?php echo htmlspecialchars($content); ?></textarea>
            <span class="help-block" style="color:red;"><?php echo $content_err; ?></span>
        </div>
        <div class="form-group" style="display: flex; gap: 10px;">
            <input type="submit" class="btn btn-primary" value="Simpan Perubahan">
            <a href="dashboard.php" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</main>

<?php 
// Menyertakan file footer.
include 'footer.php'; 
?>