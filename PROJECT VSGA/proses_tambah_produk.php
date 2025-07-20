<?php
// Memuat file konfigurasi (yang seharusnya sudah menjalankan session_start())
// dan koneksi database ($conn).
require_once 'config.php';

// --- BAGIAN KEAMANAN 1: OTENTIKASI PENGGUNA ---
// Memastikan hanya pengguna yang sudah login yang dapat menjalankan script ini.
// Ini mencegah pengguna anonim mencoba mengirim data ke endpoint ini.
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    // Jika belum login, alihkan ke halaman login.
    header("location: login.php");
    exit; // Hentikan eksekusi script.
}

// --- BAGIAN KEAMANAN 2: METODE REQUEST ---
// Memeriksa apakah halaman ini diakses menggunakan metode POST, yang merupakan
// metode standar untuk mengirim data formulir.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- LANGKAH 1: VALIDASI INPUT TEKS ---
    // Membersihkan input dari spasi yang tidak perlu di awal dan akhir menggunakan trim().
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Memeriksa apakah input nama atau deskripsi kosong setelah dibersihkan.
    if (empty($name) || empty($description)) {
        // Jika kosong, simpan pesan error di session.
        $_SESSION['product_error'] = "Nama dan deskripsi produk tidak boleh kosong.";
        // Kembalikan pengguna ke formulir untuk memperbaiki input.
        header("location: tambah_produk.php");
        exit;
    }

    // --- LANGKAH 2: VALIDASI DAN PEMROSESAN UPLOAD GAMBAR ---
    // Cek apakah file diunggah (`isset`) dan tidak ada error saat proses upload (`error == 0`).
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
        $target_dir = "assets/uploads/products/"; // Tentukan folder tujuan upload.

        // Cek jika direktori tujuan belum ada, maka buat secara otomatis.
        // `is_dir()` mengecek keberadaan direktori.
        // `mkdir()` membuat direktori. `0755` adalah izin akses, `true` memungkinkan pembuatan direktori secara rekursif.
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Membuat nama file yang unik untuk menghindari penimpaan file dengan nama yang sama.
        // `uniqid()` menghasilkan ID unik berdasarkan waktu, digabung dengan nama file asli (`basename`).
        $file_name = uniqid() . '-' . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $file_name; // Path lengkap file yang akan disimpan.
        // Mengambil ekstensi file (jpg, png, dll) dan mengubahnya ke huruf kecil untuk validasi.
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi #1: Cek apakah file benar-benar sebuah gambar.
        // `getimagesize()` akan mengembalikan `false` jika file bukan gambar.
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check === false) {
            $_SESSION['product_error'] = "File yang diunggah bukan gambar.";
            header("location: tambah_produk.php");
            exit;
        }

        // Validasi #2: Cek ukuran file (misal: maks 2MB atau 2,000,000 bytes).
        if ($_FILES["product_image"]["size"] > 2000000) {
            $_SESSION['product_error'] = "Maaf, ukuran file terlalu besar. Maksimal 2MB.";
            header("location: tambah_produk.php");
            exit;
        }

        // Validasi #3: Izinkan hanya format file tertentu.
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $_SESSION['product_error'] = "Maaf, hanya format JPG, JPEG, & PNG yang diizinkan.";
            header("location: tambah_produk.php");
            exit;
        }

        // --- LANGKAH 3: PEMINDAHAN FILE DAN PENYIMPANAN KE DATABASE ---
        // Jika semua validasi di atas lolos, coba pindahkan file dari lokasi sementara ke folder tujuan.
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            // Jika file berhasil diunggah, lanjutkan untuk menyimpan info ke database.
            // Menggunakan 'prepared statement' (?) untuk mencegah SQL Injection.
            $sql = "INSERT INTO products (name, description, image_path) VALUES (?, ?, ?)";

            if ($stmt = $conn->prepare($sql)) {
                // Mengikat variabel PHP ke placeholder dalam query SQL.
                // "sss" berarti ketiga variabel adalah tipe data string.
                $stmt->bind_param("sss", $name, $description, $target_file);

                // Jalankan query yang sudah disiapkan.
                if ($stmt->execute()) {
                    // Jika eksekusi berhasil, arahkan ke halaman daftar produk.
                    header("location: produk.php");
                    exit();
                } else {
                    // Jika gagal menyimpan ke DB, berikan pesan error.
                    $_SESSION['product_error'] = "Gagal menyimpan data produk ke database.";
                    header("location: tambah_produk.php");
                    exit;
                }
                // Tutup statement.
                $stmt->close();
            }
        } else {
            // Jika `move_uploaded_file` gagal.
            $_SESSION['product_error'] = "Terjadi kesalahan saat mengunggah file.";
            header("location: tambah_produk.php");
            exit;
        }
    } else {
        // Jika tidak ada file yang diunggah atau ada error dari sisi server.
        $_SESSION['product_error'] = "Tidak ada file yang diunggah atau terjadi error.";
        header("location: tambah_produk.php");
        exit;
    }

    // Tutup koneksi database.
    $conn->close();

} else {
    // Jika halaman ini diakses langsung (bukan via metode POST),
    // kembalikan pengguna ke halaman formulir.
    header("location: tambah_produk.php");
    exit;
}
?>