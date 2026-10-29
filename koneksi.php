<?php
// Satu tempat untuk mengatur koneksi database.
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $koneksi = new mysqli('localhost', 'root', '', 'menu_kantin');
    $koneksi->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    // Detail error dicatat di log, bukan ditampilkan kepada pengunjung.
    error_log('Koneksi database gagal: ' . $e->getMessage());
    http_response_code(500);
    exit('Terjadi gangguan pada sistem. Silakan coba lagi nanti.');
}
?>
