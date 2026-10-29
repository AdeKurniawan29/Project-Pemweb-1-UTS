<?php
require_once 'koneksi.php';
require_once 'sesi.php';
require_once 'validasi.php';

// Periksa peran dari sesi di server, bukan hanya menyembunyikan tombol di tampilan.
wajibAdmin();

$judulHalaman = 'Tambah Menu';
$nama_menu = '';
$kategori = '';
$harga = '';
$tersedia = '';
$deskripsi = '';
$error = [];
$kategoriDiizinkan = ['Makanan', 'Minuman', 'Camilan'];
$statusDiizinkan = ['Ya', 'Tidak'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekTokenCsrf();
    $nama_menu = trim($_POST['nama_menu'] ?? '');
    $kategori = $_POST['kategori'] ?? '';
    $harga = trim($_POST['harga'] ?? '');
    $tersedia = $_POST['tersedia'] ?? '';
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    // Validasi panjang teks utama mengikuti ketentuan umum tugas.
    if (panjangTeks($nama_menu) < 3 || panjangTeks($nama_menu) > 150) {
        $error['nama_menu'] = 'Nama menu harus berisi 3 sampai 150 karakter.';
    }
    // Validasi whitelist server diperlukan karena nilai form bisa dipalsukan.
    if (!validasiPilihan($kategori, $kategoriDiizinkan)) {
        $error['kategori'] = 'Kategori harus Makanan, Minuman, atau Camilan.';
    }
    if ($harga === '' || !ctype_digit($harga)) {
        $error['harga'] = 'Harga harus berupa bilangan bulat.';
    } elseif ((int) $harga < 1000 || (int) $harga > 100000) {
        $error['harga'] = 'Harga harus antara Rp1.000 dan Rp100.000.';
    }
    if (!validasiPilihan($tersedia, $statusDiizinkan)) {
        $error['tersedia'] = 'Pilihan tersedia harus Ya atau Tidak.';
    }
    if (panjangTeks($deskripsi) > 2000) {
        $error['deskripsi'] = 'Deskripsi maksimal 2.000 karakter.';
    }
    // Aturan khusus Menu Kantin: jika tidak tersedia, alasan harus dijelaskan.
    if ($tersedia === 'Tidak' && panjangTeks($deskripsi) < 10) {
        $error['deskripsi'] = 'Jika menu tidak tersedia, deskripsi alasan minimal 10 karakter.';
    }

    if (!$error) {
        // Pemilik diambil dari sesi login, bukan dari nilai yang dikirim form.
        $pengguna_id = (int) $_SESSION['pengguna_id'];
        $hargaAngka = (int) $harga;
        $stmt = $koneksi->prepare(
            'INSERT INTO menu_kantin (pengguna_id, nama_menu, kategori, harga, tersedia, deskripsi)
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param('ississ', $pengguna_id, $nama_menu, $kategori, $hargaAngka, $tersedia, $deskripsi);
        $stmt->execute();

        // PRG: redirect setelah berhasil supaya refresh tidak mengirim ulang form.
        header('Location: daftar.php?tambah=berhasil');
        exit;
    }
}

require 'header.php';
?>
<section class="form-card wide">
    <h1>Tambah Menu Kantin</h1>
    <p class="muted">Isi data menu dengan benar.</p>
    <form method="post" action="tambah.php" novalidate>
        <input type="hidden" name="token_csrf" value="<?= e(tokenCsrf()) ?>">

        <label for="nama_menu">Nama menu</label>
        <input id="nama_menu" name="nama_menu" value="<?= e($nama_menu) ?>" required minlength="3" maxlength="150">
        <?php if (isset($error['nama_menu'])): ?><small class="field-error"><?= e($error['nama_menu']) ?></small><?php endif; ?>

        <label for="kategori">Kategori</label>
        <select id="kategori" name="kategori" required>
            <option value="">Pilih kategori</option>
            <?php foreach ($kategoriDiizinkan as $opsi): ?>
                <option value="<?= e($opsi) ?>" <?= $kategori === $opsi ? 'selected' : '' ?>><?= e($opsi) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($error['kategori'])): ?><small class="field-error"><?= e($error['kategori']) ?></small><?php endif; ?>

        <label for="harga">Harga (Rp)</label>
        <input id="harga" type="number" name="harga" value="<?= e($harga) ?>" min="1000" max="100000" step="1" required>
        <?php if (isset($error['harga'])): ?><small class="field-error"><?= e($error['harga']) ?></small><?php endif; ?>

        <label for="tersedia">Tersedia?</label>
        <select id="tersedia" name="tersedia" required>
            <option value="">Pilih status</option>
            <?php foreach ($statusDiizinkan as $opsi): ?>
                <option value="<?= e($opsi) ?>" <?= $tersedia === $opsi ? 'selected' : '' ?>><?= e($opsi) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($error['tersedia'])): ?><small class="field-error"><?= e($error['tersedia']) ?></small><?php endif; ?>

        <label for="deskripsi">Deskripsi<?= $tersedia === 'Tidak' ? ' (isi alasan jika tidak tersedia)' : '' ?></label>
        <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="2000"><?= e($deskripsi) ?></textarea>
        <?php if (isset($error['deskripsi'])): ?><small class="field-error"><?= e($error['deskripsi']) ?></small><?php endif; ?>

        <button type="submit">Simpan Menu</button>
        <a class="cancel-link" href="daftar.php">Batal</a>
    </form>
</section>
<?php require 'footer.php'; ?>
