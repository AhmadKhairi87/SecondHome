/*
File: script.js
Deskripsi: File ini menangani interaktivitas pada sisi klien,
seperti menu dropdown dan konfirmasi hapus data.
*/

document.addEventListener('DOMContentLoaded', function() {
    
    // --- Fungsionalitas Dropdown Menu di Sidebar ---
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    if (dropdownToggle) {
        dropdownToggle.addEventListener('click', function(event) {
            // Mencegah link berpindah halaman
            event.preventDefault(); 
            
            // Cari menu dropdown yang berhubungan
            const dropdownMenu = this.nextElementSibling;
            
            // Toggle class 'show' untuk menampilkan atau menyembunyikan menu
            if (dropdownMenu) {
                dropdownMenu.classList.toggle('show');
            }
        });
    }

    // --- Fungsionalitas Konfirmasi Hapus ---
    // Cari semua tombol hapus
    const deleteButtons = document.querySelectorAll('.btn-delete');
    
    // Tambahkan event listener ke setiap tombol
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            // Tampilkan dialog konfirmasi bawaan browser
            const confirmation = confirm('Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.');
            
            // Jika pengguna membatalkan, hentikan aksi default (pindah ke link hapus)
            if (!confirmation) {
                event.preventDefault();
            }
        });
    });

});
