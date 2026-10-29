<?php
require_once 'koneksi.php';
require_once 'sesi.php';
require_once 'validasi.php';

$judulHalaman = 'Registrasi';
$nama = '';
$username = '';
$error = [];
$berhasil = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekTokenCsrf();
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $sandi = $_POST['sandi'] ?? '';

    if (panjangTeks($nama) < 3 || panjangTeks($nama) > 100) {
        $error['nama'] = 'Nama harus berisi 3 sampai 100 karakter.';
    }
    if (panjangTeks($username) < 3 || panjangTeks($username) > 30) {
        $error['username'] = 'Username harus berisi 3 sampai 30 karakter.';
    } elseif (!preg_match('/^[A-Za-z0-9_.]+$/', $username)) {
        $error['username'] = 'Username hanya boleh berisi huruf, angka, titik, dan garis bawah.';
    }
    if (!is_string($sandi) || strlen($sandi) < 8 || strlen($sandi) > 72) {
        $error['sandi'] = 'Kata sandi harus berisi 8 sampai 72 karakter.';
    }

    if (!$error) {
        $hashSandi = password_hash($sandi, PASSWORD_DEFAULT);
        try {
            // Registrasi publik selalu menjadi anggota, bukan admin.
            $stmt = $koneksi->prepare(
                "INSERT INTO pengguna (username, nama, peran, hash_sandi) VALUES (?, ?, 'anggota', ?)"
            );
            $stmt->bind_param('sss', $username, $nama, $hashSandi);
            $stmt->execute();
            header('Location: login.php?daftar=berhasil');
            exit;
        } catch (mysqli_sql_exception $e) {
            // Kode 1062 berarti username sudah dipakai karena kolomnya UNIQUE.
            if ((int) $e->getCode() === 1062) {
                $error['username'] = 'Username sudah digunakan. Silakan pilih username lain.';
            } else {
                error_log('Registrasi gagal: ' . $e->getMessage());
                $error['umum'] = 'Registrasi belum berhasil. Silakan coba lagi.';
            }
        }
    }
}

require 'header.php';
?>
<section class="form-card">
    <h1>Buat Akun</h1>
    <p class="muted">Daftar untuk melihat menu kantin.</p>
    <?php if (isset($error['umum'])): ?><p class="alert error"><?= e($error['umum']) ?></p><?php endif; ?>
    <form method="post" action="registrasi.php" novalidate>
        <input type="hidden" name="token_csrf" value="<?= e(tokenCsrf()) ?>">
        <label for="nama">Nama</label>
        <input id="nama" name="nama" value="<?= e($nama) ?>" required maxlength="100">
        <?php if (isset($error['nama'])): ?><small class="field-error"><?= e($error['nama']) ?></small><?php endif; ?>

        <label for="username">Username</label>
        <input id="username" name="username" value="<?= e($username) ?>" required maxlength="30">
        <?php if (isset($error['username'])): ?><small class="field-error"><?= e($error['username']) ?></small><?php endif; ?>

        <label for="sandi">Kata sandi</label>
        <input id="sandi" type="password" name="sandi" required minlength="8" maxlength="72">
        <?php if (isset($error['sandi'])): ?><small class="field-error"><?= e($error['sandi']) ?></small><?php endif; ?>

        <button type="submit">Daftar</button>
    </form>
    <p class="form-note">Sudah punya akun? <a href="login.php">Masuk</a></p>
</section>
<?php require 'footer.php'; ?>
