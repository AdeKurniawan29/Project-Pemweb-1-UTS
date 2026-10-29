<?php
require_once 'koneksi.php';
require_once 'sesi.php';

$judulHalaman = 'Masuk';
$username = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    cekTokenCsrf();
    $username = trim($_POST['username'] ?? '');
    $sandi = $_POST['sandi'] ?? '';

    if ($username === '' || !is_string($sandi) || $sandi === '') {
        $error = 'Username atau kata sandi salah.';
    } else {
        $stmt = $koneksi->prepare(
            'SELECT id, username, nama, peran, hash_sandi FROM pengguna WHERE username = ? LIMIT 1'
        );
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $pengguna = $stmt->get_result()->fetch_assoc();

        // Pesan dibuat sama untuk username yang tidak ada dan kata sandi yang salah.
        if ($pengguna && password_verify($sandi, $pengguna['hash_sandi'])) {
            session_regenerate_id(true);
            $_SESSION['pengguna_id'] = (int) $pengguna['id'];
            $_SESSION['username'] = $pengguna['username'];
            $_SESSION['nama'] = $pengguna['nama'];
            $_SESSION['peran'] = $pengguna['peran'];
            header('Location: daftar.php');
            exit;
        }
        $error = 'Username atau kata sandi salah.';
    }
}

require 'header.php';
?>
<section class="form-card">
    <h1>Masuk</h1>
    <p class="muted">Masuk untuk melihat daftar menu kantin.</p>
    <?php if (isset($_GET['daftar']) && $_GET['daftar'] === 'berhasil'): ?>
        <p class="alert success">Registrasi berhasil. Silakan masuk.</p>
    <?php endif; ?>
    <?php if ($error !== ''): ?><p class="alert error"><?= e($error) ?></p><?php endif; ?>
    <form method="post" action="login.php">
        <input type="hidden" name="token_csrf" value="<?= e(tokenCsrf()) ?>">
        <label for="username">Username</label>
        <input id="username" name="username" value="<?= e($username) ?>" required maxlength="30">
        <label for="sandi">Kata sandi</label>
        <input id="sandi" type="password" name="sandi" required>
        <button type="submit">Masuk</button>
    </form>
    <p class="form-note">Belum punya akun? <a href="registrasi.php">Daftar</a></p>
</section>
<?php require 'footer.php'; ?>
