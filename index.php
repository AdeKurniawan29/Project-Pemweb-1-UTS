<?php
require_once 'sesi.php';
header('Location: ' . (!empty($_SESSION['pengguna_id']) ? 'daftar.php' : 'login.php'));
exit;
?>
