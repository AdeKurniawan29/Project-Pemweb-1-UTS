<?php
require_once 'koneksi.php';
require_once 'sesi.php';
wajibLogin();

$judulHalaman = 'Daftar Menu';
$sql = "SELECT m.id, m.nama_menu, m.kategori, m.harga, m.tersedia, m.deskripsi,
               p.nama AS nama_penginput
        FROM menu_kantin m
        JOIN pengguna p ON m.pengguna_id = p.id
        ORDER BY m.id DESC";
$hasil = $koneksi->query($sql);

require 'header.php';
?>
<section class="page-heading">
    <div>
        <h1>Daftar Menu Kantin</h1>
        <p class="muted">Daftar makanan dan minuman yang tersedia di kantin.</p>
    </div>
    <?php if (($_SESSION['peran'] ?? '') === 'admin'): ?>
        <a class="button-link" href="tambah.php">+ Tambah Menu</a>
    <?php endif; ?>
</section>

<?php if (isset($_GET['tambah']) && $_GET['tambah'] === 'berhasil'): ?>
    <p class="alert success">Menu berhasil ditambahkan.</p>
<?php endif; ?>

<?php if ($hasil->num_rows === 0): ?>
    <section class="empty-state"><h2>Belum ada menu</h2><p>Data menu akan tampil di halaman ini.</p></section>
<?php else: ?>
    <div class="menu-grid">
        <?php while ($menu = $hasil->fetch_assoc()): ?>
            <article class="menu-card">
                <div class="menu-card-top">
                    <span class="category"><?= e($menu['kategori']) ?></span>
                    <span class="status <?= $menu['tersedia'] === 'Ya' ? 'available' : 'unavailable' ?>">
                        <?= e($menu['tersedia'] === 'Ya' ? 'Tersedia' : 'Tidak tersedia') ?>
                    </span>
                </div>
                <h2><?= e($menu['nama_menu']) ?></h2>
                <p class="price">Rp<?= number_format((int) $menu['harga'], 0, ',', '.') ?></p>
                <p class="muted small">Diinput oleh <?= e($menu['nama_penginput']) ?></p>
                <a class="detail-link" href="detail.php?id=<?= (int) $menu['id'] ?>">Lihat detail</a>
            </article>
        <?php endwhile; ?>
    </div>
<?php endif; ?>
<?php require 'footer.php'; ?>
