<?php
require_once 'koneksi.php';
require_once 'sesi.php';
wajibLogin();

$judulHalaman = 'Detail Menu';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('ID menu tidak valid.');
}

$stmt = $koneksi->prepare(
    "SELECT m.id, m.nama_menu, m.kategori, m.harga, m.tersedia, m.deskripsi,
            p.nama AS nama_penginput
     FROM menu_kantin m
     JOIN pengguna p ON m.pengguna_id = p.id
     WHERE m.id = ? LIMIT 1"
);
// Tipe i berarti nilai ID diikat sebagai integer.
$stmt->bind_param('i', $id);
$stmt->execute();
$menu = $stmt->get_result()->fetch_assoc();

if (!$menu) {
    http_response_code(404);
    exit('Menu tidak ditemukan.');
}

require 'header.php';
?>
<section class="detail-card">
    <a class="back-link" href="daftar.php">&larr; Kembali ke daftar</a>
    <span class="category"><?= e($menu['kategori']) ?></span>
    <h1><?= e($menu['nama_menu']) ?></h1>
    <p class="price large">Rp<?= number_format((int) $menu['harga'], 0, ',', '.') ?></p>
    <p><strong>Status:</strong>
        <span class="status <?= $menu['tersedia'] === 'Ya' ? 'available' : 'unavailable' ?>">
            <?= e($menu['tersedia'] === 'Ya' ? 'Tersedia' : 'Tidak tersedia') ?>
        </span>
    </p>
    <h2>Deskripsi</h2>
    <p class="description"><?= nl2br(e($menu['deskripsi'])) ?></p>
    <p class="muted small">Diinput oleh <?= e($menu['nama_penginput']) ?></p>
</section>
<?php require 'footer.php'; ?>
