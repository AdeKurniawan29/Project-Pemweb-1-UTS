<?php
// Pengaturan cookie sesi dibuat sebelum session_start().
if (session_status() === PHP_SESSION_NONE) {
    $httpsAktif = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'httponly' => true,
        'secure' => $httpsAktif,
        'samesite' => 'Lax'
    ]);
    session_start();
}

// Escape setiap nilai dinamis sebelum dicetak ke HTML untuk mencegah XSS.
function e($nilai) {
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}

function wajibLogin() {
    if (empty($_SESSION['pengguna_id'])) {
        header('Location: login.php');
        exit;
    }
}

function wajibAdmin() {
    wajibLogin();
    if (($_SESSION['peran'] ?? '') !== 'admin') {
        http_response_code(403);
        exit('Akses ditolak. Halaman ini hanya untuk admin.');
    }
}

// Token ini membantu menolak pengiriman form yang bukan berasal dari sesi saat ini.
function tokenCsrf() {
    if (empty($_SESSION['token_csrf'])) {
        $_SESSION['token_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['token_csrf'];
}

function cekTokenCsrf() {
    $tokenForm = $_POST['token_csrf'] ?? '';
    $tokenSesi = $_SESSION['token_csrf'] ?? '';
    if (!is_string($tokenForm) || !is_string($tokenSesi) ||
        $tokenSesi === '' || !hash_equals($tokenSesi, $tokenForm)) {
        http_response_code(400);
        exit('Permintaan tidak valid. Silakan muat ulang halaman.');
    }
}
?>
