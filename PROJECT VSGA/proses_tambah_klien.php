<?php
// Memulai atau melanjutkan sesi PHP. Ini wajib ada di paling atas
// jika kita ingin menggunakan variabel session seperti $_SESSION["loggedin"].
session_start();
// Memuat file konfigurasi yang berisi detail koneksi database ($conn)
// dan mungkin pengaturan penting lainnya.
require_once 'config.php';

// --- KEAMANAN TINGKAT 1: OTENTIKASI ---
// Memeriksa apakah pengguna sudah login. Ini adalah gerbang keamanan utama.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Jika tidak ada session login yang valid, hentikan eksekusi script sepenuhnya
    // dan tampilkan pesan error. `die()` adalah cara langsung untuk menghentikan script.
    die("Akses ditolak. Silakan login terlebih dahulu.");
}

// --- KEAMANAN TINGKAT 2: METODE REQUEST ---
// Memastikan bahwa script ini hanya dapat dijalankan melalui metode POST (dari sebuah form).
// Ini mencegah pengguna mengakses file ini secara langsung melalui URL (metode GET).
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- LANGKAH 1: Ambil data dari Form ---
    // Mengambil nilai dari input 'nama_klien' yang dikirim melalui metode POST.
    $nama_klien = $_POST['nama_klien'];

    // --- LANGKAH 2: Proses Upload File ---
    // Cek apakah ada file yang dikirim dengan nama 'foto_klien' DAN tidak ada error saat upload (error code 0).
    if (isset($_FILES['foto_klien']) && $_FILES['foto_klien']['error'] == 0) {
        $target_dir = "uploads/"; // Menentukan nama folder tujuan. Pastikan folder ini sudah ada dan memiliki izin tulis (writable).
        
        // Membuat nama file baru yang unik untuk menghindari konflik/penimpaan file.
        // Ini adalah praktik keamanan yang sangat baik.
        $file_extension = pathinfo($_FILES["foto_klien"]["name"], PATHINFO_EXTENSION); // Ambil ekstensi asli file (misal: "jpg").
        $nama_file_baru = uniqid() . '_' . time() . '.' . $file_extension; // Gabungkan ID unik, timestamp, dan ekstensi.
        $target_file = $target_dir . $nama_file_baru; // Path lengkap file tujuan (misal: "uploads/5f1fABC_1626789.jpg").

        // Memindahkan file dari lokasi sementara server ke folder tujuan yang sudah ditentukan.
        if (move_uploaded_file($_FILES["foto_klien"]["tmp_name"], $target_file)) {
            // Jika file berhasil dipindahkan, lanjutkan proses penyimpanan ke database.
            
            // --- LANGKAH 3: Simpan Data ke Database (Cara Aman dengan Prepared Statements) ---
            // Menyiapkan template query SQL dengan placeholder (?) untuk mencegah SQL Injection.
            $sql = "INSERT INTO klien (nama_klien, foto_klien) VALUES (?, ?)";
            
            // Mempersiapkan statement SQL.
            if ($stmt = $conn->prepare($sql)) {
                // Mengikat (bind) variabel PHP ke placeholder dalam statement.
                // "ss" berarti kedua parameter yang diikat adalah tipe data String.
                $stmt->bind_param("ss", $param_nama, $param_foto);
                
                // Menetapkan nilai ke variabel yang akan diikat.
                $param_nama = $nama_klien;
                $param_foto = $nama_file_baru; // PENTING: Yang disimpan ke DB adalah nama file baru yang unik, bukan path lengkapnya.
                
                // Mencoba mengeksekusi statement yang sudah disiapkan.
                if ($stmt->execute()) {
                    // Jika berhasil, alihkan pengguna kembali ke halaman daftar klien.
                    header("location: klien.php");
                    exit(); // Hentikan script setelah redirect.
                } else {
                    echo "Terjadi kesalahan saat menyimpan ke database.";
                }

                // Selalu tutup statement setelah selesai digunakan.
                $stmt->close();
            }
        } else {
            // Jika `move_uploaded_file` gagal.
            echo "Maaf, terjadi kesalahan saat meng-upload file Anda.";
        }
    } else {
        // Jika tidak ada file yang di-upload atau terjadi error pada file.
        echo "Maaf, tidak ada file yang di-upload atau terjadi kesalahan.";
    }
    
    // Selalu tutup koneksi database setelah semua operasi selesai.
    $conn->close();
} else {
    // Jika script diakses dengan metode selain POST, alihkan ke halaman utama.
    header("location: index.php");
    exit();
}
?>