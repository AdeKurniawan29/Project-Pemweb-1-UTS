PROJECT MINI PROJECT PEMROGRAMAN WEB I
Tema: Menu Kantin

ISI FITUR
- Registrasi akun, login, dan logout.
- Dua peran: admin dan anggota.
- Admin dapat menambah menu kantin.
- Pengguna yang sudah login dapat melihat daftar dan detail menu.
- Daftar menu menampilkan nama penginput melalui JOIN.
- Validasi server, prepared statement, dan escape keluaran.

CARA MENJALANKAN
1. Pastikan XAMPP dengan PHP 8.1+ dan MySQL sudah terpasang.
2. Ekstrak folder MenuKantin_MiniProject ke C:\xampp\htdocs\menu_kantin.
3. Jalankan Apache dan MySQL dari XAMPP Control Panel.
4. Buka http://localhost/phpmyadmin/.
5. Pilih tab Import dan impor file app.sql.
   PERINGATAN: app.sql menghapus lalu membuat ulang tabel pengguna dan menu_kantin
   pada database menu_kantin. Jangan jalankan jika database tersebut berisi data penting.
6. Buka http://localhost/menu_kantin/.
7. Login dengan akun uji di bawah.

AKUN UJI
Admin:
Username: admin
Password: Admin123!

Anggota:
Username: anggota
Password: Anggota123!

CATATAN
- Akun uji disediakan untuk kebutuhan tugas lokal.
- Registrasi akun baru selalu menghasilkan peran anggota.
- Sesuaikan nama folder URL jika folder project diberi nama berbeda.
- Uji semua skenario dan ambil screenshot nyata sebelum mengumpulkan.
