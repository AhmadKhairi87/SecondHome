<?php
// Memanggil file konfigurasi, yang diasumsikan sudah menjalankan session_start()
// dan menyiapkan koneksi database ($conn).
require_once 'config.php';

// Memastikan script hanya berjalan jika diakses melalui metode POST dari sebuah form.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Inisialisasi sebuah array kosong untuk menampung semua pesan error yang mungkin terjadi.
    // Ini memungkinkan kita untuk memvalidasi semua input terlebih dahulu sebelum mengambil tindakan.
    $errors = [];

    // --- LANGKAH 1: Validasi Username ---
    $username = trim($_POST["username"]); // Ambil dan bersihkan input username.
    if (empty($username)) {
        $errors[] = "Username tidak boleh kosong."; // Tambahkan error ke array jika kosong.
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        // `preg_match` digunakan untuk validasi format dengan Regular Expression.
        // Pola '/^[a-zA-Z0-9_]+$/' berarti: string harus dimulai (^), hanya berisi huruf, angka, atau underscore (+), dan berakhir ($).
        $errors[] = "Username hanya boleh berisi huruf, angka, dan underscore.";
    } else {
        // Jika format valid, cek apakah username sudah ada di database untuk mencegah duplikasi.
        $sql = "SELECT id FROM users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result(); // Simpan hasil agar bisa dicek jumlah barisnya.
            if ($stmt->num_rows > 0) {
                $errors[] = "Username ini sudah digunakan."; // Tambahkan error jika sudah ada.
            }
            $stmt->close();
        }
    }

    // --- LANGKAH 2: Validasi Email ---
    $email = trim($_POST["email"]); // Ambil dan bersihkan input email.
    if (empty($email)) {
        $errors[] = "Email tidak boleh kosong.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // `filter_var` dengan `FILTER_VALIDATE_EMAIL` adalah cara standar dan paling andal di PHP untuk memvalidasi format email.
        $errors[] = "Format email tidak valid.";
    } else {
        // Jika format valid, cek juga apakah email sudah terdaftar.
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors[] = "Email ini sudah terdaftar.";
            }
            $stmt->close();
        }
    }

    // --- LANGKAH 3: Validasi Password ---
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    if (empty($password)) {
        $errors[] = "Password tidak boleh kosong.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password minimal harus 6 karakter.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Konfirmasi password tidak cocok.";
    }

    // --- LANGKAH 4: Proses Data (HANYA JIKA TIDAK ADA ERROR) ---
    // Ini adalah langkah penentu. Kode di dalam blok ini hanya akan berjalan jika array $errors masih kosong.
    if (empty($errors)) {
        // Enkripsi password menggunakan algoritma hashing yang kuat. INI SANGAT PENTING untuk keamanan.
        // `PASSWORD_DEFAULT` memastikan PHP menggunakan algoritma terbaik yang tersedia saat ini.
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Siapkan query untuk memasukkan data pengguna baru.
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        
        if ($stmt = $conn->prepare($sql)) {
            // Ikat parameter: username, email, dan password yang sudah di-hash.
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            // Coba eksekusi query.
            if ($stmt->execute()) {
                // Jika pendaftaran berhasil, langsung buat sesi agar pengguna tidak perlu login lagi.
                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $stmt->insert_id; // Ambil ID dari user yang baru saja dibuat.
                $_SESSION["username"] = $username;
                
                // Arahkan ke halaman utama dan hentikan eksekusi script.
                header("location: index.php");
                exit();
            } else {
                // Jika terjadi kegagalan di level database.
                $errors[] = "Terjadi kesalahan pada database. Silakan coba lagi.";
            }
            $stmt->close();
        }
    }

    // --- LANGKAH 5: Penanganan Error (JIKA ADA ERROR) ---
    // Jika array $errors tidak kosong (artinya ada satu atau lebih pesan error).
    if (!empty($errors)) {
        // `implode` akan menggabungkan semua elemen array $errors menjadi satu string,
        // dipisahkan oleh tag <br> agar setiap error tampil di baris baru.
        $_SESSION['register_message'] = implode("<br>", $errors);
        // Kembalikan pengguna ke halaman registrasi untuk melihat semua pesan error.
        header("location: register.php");
        exit();
    }

} else {
    // Jika halaman ini diakses langsung (bukan via POST), alihkan ke halaman register.
    header("location: register.php");
    exit();
}

// Menutup koneksi database di akhir script adalah praktik yang baik.
$conn->close();
?>