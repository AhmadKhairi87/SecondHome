</div> <footer class="site-footer-bottom">
            <p>Design by : [Ahmad khairi]</p>
        </footer>
    </div> <script src="assets/js/script.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cari semua elemen dengan class .dropdown-toggle
        var dropdownToggles = document.querySelectorAll('.dropdown-toggle');

        // Loop melalui setiap elemen tersebut
        dropdownToggles.forEach(function(toggle) {
            // Tambahkan event listener untuk 'click'
            toggle.addEventListener('click', function(event) {
                // Mencegah link default (agar halaman tidak pindah)
                event.preventDefault(); 
                
                // Cari elemen menu dropdown yang berada tepat setelah tombol
                var dropdownMenu = this.nextElementSibling;
                
                // Tambah atau hapus class 'show' untuk menampilkan/menyembunyikan menu
                dropdownMenu.classList.toggle('show');
            });
        });
    });
    </script>

</body>
</html>