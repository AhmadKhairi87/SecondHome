<?php
// Memuat file konfigurasi yang biasanya berisi koneksi database ($conn)
// dan juga harus sudah menjalankan session_start() di dalamnya.
require_once 'config.php';

// --- KEAMANAN TINGKAT 1: OTENTIKASI PENGGUNA ---
// Cek apakah pengguna sudah login. Ini adalah langkah krusial untuk memastikan
// hanya pengguna terdaftar yang bisa menambah artikel.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Jika pengguna mencoba mengakses file ini tanpa login,
    // mereka akan secara otomatis dialihkan ke halaman login.
    header("location: login.php");
    exit; // Hentikan eksekusi script lebih lanjut.
}

// --- KEAMANAN TINGKAT 2: VALIDASI METODE REQUEST ---
// Script ini dirancang untuk memproses data yang dikirim dari sebuah form.
// Oleh karena itu, kita hanya mengizinkan request dengan metode POST.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- LANGKAH 1: PENGAMBILAN DAN VALIDASI INPUT ---
    // Mengambil data 'title' dan 'content' dari form, lalu membersihkan spasi
    // yang tidak perlu di awal dan akhir string menggunakan fungsi trim().
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    // Mengambil ID pengguna dari variabel session yang sudah tersimpan saat login.
    // Ini lebih aman daripada mengirim user ID melalui form, karena mencegah pemalsuan data.
    $user_id = $_SESSION['id']; 

    // Validasi dasar: memastikan judul dan konten tidak kosong.
    if (empty($title) || empty($content)) {
        // Jika ada yang kosong, buat pesan error di session.
        $_SESSION['article_error'] = "Judul dan konten artikel tidak boleh kosong.";
        // Kembalikan pengguna ke halaman formulir untuk mengisi ulang.
        header("location: tambah_artikel.php");
        exit;
    }

    // --- LANGKAH 2: PENYIMPANAN KE DATABASE (METODE AMAN) ---
    // Menyiapkan template SQL untuk memasukkan data. Menggunakan placeholder (?)
    // adalah langkah pertama untuk mencegah serangan SQL Injection.
    $sql = "INSERT INTO articles (title, content, user_id) VALUES (?, ?, ?)";

    // Mempersiapkan statement SQL menggunakan koneksi database.
    if ($stmt = $conn->prepare($sql)) {
        // Mengikat (bind) variabel PHP ke placeholder dalam statement SQL.
        // "ssi" adalah string format yang mendefinisikan tipe data dari setiap variabel:
        // s = string (untuk $title)
        // s = string (untuk $content)
        // i = integer (untuk $user_id)
        $stmt->bind_param("ssi", $title, $content, $user_id);

        // Mencoba untuk mengeksekusi statement yang sudah disiapkan.
        if ($stmt->execute()) {
            // Jika eksekusi berhasil (data berhasil masuk ke database).
            // Alihkan pengguna ke halaman dashboard.
            // Anda bisa menambahkan pesan sukses di session sebelum redirect jika diperlukan.
            header("location: dashboard.php");
            exit();
        } else {
            // Jika terjadi error saat eksekusi (misalnya, koneksi DB putus).
            // Buat pesan error dan kembalikan pengguna ke form.
            $_SESSION['article_error'] = "Terjadi kesalahan. Gagal mempublikasikan artikel.";
            header("location: tambah_artikel.php");
            exit;
        }

        // Menutup statement untuk melepaskan sumber daya.
        $stmt->close();
    }
    
    // Menutup koneksi database setelah semua operasi selesai.
    $conn->close();

} else {
    // Jika script ini diakses langsung (misal, via URL/GET) dan bukan melalui metode POST,
    // maka alihkan pengguna ke dashboard.
    header("location: dashboard.php");
    exit;
}
?>