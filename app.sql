CREATE DATABASE IF NOT EXISTS menu_kantin
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE menu_kantin;

DROP TABLE IF EXISTS menu_kantin;
DROP TABLE IF EXISTS pengguna;

CREATE TABLE pengguna (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    peran ENUM('admin', 'anggota') NOT NULL DEFAULT 'anggota',
    hash_sandi VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE menu_kantin (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pengguna_id INT UNSIGNED NOT NULL,
    nama_menu VARCHAR(150) NOT NULL,
    kategori ENUM('Makanan', 'Minuman', 'Camilan') NOT NULL,
    harga INT UNSIGNED NOT NULL,
    tersedia ENUM('Ya', 'Tidak') NOT NULL,
    deskripsi VARCHAR(2000) NOT NULL DEFAULT '',
    CONSTRAINT cek_harga_menu CHECK (harga BETWEEN 1000 AND 100000),
    CONSTRAINT fk_menu_pengguna FOREIGN KEY (pengguna_id)
        REFERENCES pengguna(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO pengguna (username, nama, peran, hash_sandi) VALUES
('admin', 'Admin Kantin', 'admin', '$2y$12$SwjpTd0CJH0bw1Lo6lGr.uK9J1Zn5pOp1fIk3knE18m5TyZg9nIR6'),
('anggota', 'Anggota Kantin', 'anggota', '$2y$12$ZVDLz.yvjqG9.1zkhzE00.F6enRUUZWbQtwW53rh4f0DRxFOdCI9e');

INSERT INTO menu_kantin (pengguna_id, nama_menu, kategori, harga, tersedia, deskripsi) VALUES
(1, 'Nasi Goreng', 'Makanan', 15000, 'Ya', 'Nasi goreng dengan telur.'),
(1, 'Ayam Geprek', 'Makanan', 18000, 'Ya', 'Ayam goreng dengan sambal.'),
(1, 'Mie Rebus', 'Makanan', 10000, 'Ya', 'Mie rebus hangat.'),
(1, 'Es Teh', 'Minuman', 5000, 'Ya', 'Teh manis dingin.'),
(1, 'Pisang Goreng', 'Camilan', 8000, 'Tidak', 'Stok pisang sedang habis.');
