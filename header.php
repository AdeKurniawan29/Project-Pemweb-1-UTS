<?php
// Header bersama agar tampilan halaman tetap konsisten.
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($judulHalaman ?? 'Menu Kantin') ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="header">
    <div class="nav-wrap">
        <a class="brand" href="daftar.php">Menu Kantin</a>
        <nav>
            <a href="daftar.php">Daftar Menu</a>
            <?php if (!empty($_SESSION['pengguna_id'])): ?>
                <?php if (($_SESSION['peran'] ?? '') === 'admin'): ?>
                    <a href="tambah.php">Tambah Menu</a>
                <?php endif; ?>
                <span class="nav-user"><?= e($_SESSION['nama'] ?? '') ?></span>
                <a href="logout.php">Keluar</a>
            <?php else: ?>
                <a href="login.php">Masuk</a>
                <a href="registrasi.php">Daftar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="container">
