-- SIMAS BERKAH v1.1.0 initial schema (trimmed sample)
CREATE DATABASE IF NOT EXISTS simas DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE simas;

CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) UNIQUE, password VARCHAR(255), fullname VARCHAR(100), created_at DATETIME DEFAULT CURRENT_TIMESTAMP);
INSERT INTO users (username,password,fullname) VALUES ('admin','$2y$12$e9u8w0HkF1f1XvPz5q0m.Ou0yFh7H2KjzYQ1mVqvKkz8G1r5s6z1O','Administrator');

CREATE TABLE setting_system (id INT AUTO_INCREMENT PRIMARY KEY, `key` VARCHAR(100), `value` TEXT);
INSERT INTO setting_system (`key`,`value`) VALUES ('tema_aktif','al-barokah'),('nama_masjid','Masjid Nurul Ikhlas'),('tagline','Makmur dengan Iman dan Ilmu'),('deskripsi','Masjid Nurul Ikhlas - edit via admin');

CREATE TABLE pengurus (id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(100),jabatan VARCHAR(100),wa VARCHAR(30),status_aktif TINYINT(1) DEFAULT 1);
INSERT INTO pengurus (nama,jabatan,wa) VALUES ('Ust. Budi','Ketua','6281234567890'),('Sdr. Imam','Bendahara','6281987654321');

CREATE TABLE galeri (id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(150), deskripsi TEXT, file VARCHAR(255), tanggal DATE, uploaded_by VARCHAR(100), created_at DATETIME DEFAULT CURRENT_TIMESTAMP);
INSERT INTO galeri (judul,deskripsi,file,tanggal,uploaded_by) VALUES ('Pengajian Jumat','Pengajian rutin','/simas/assets/images/sample1.jpg','2025-10-10','admin');

CREATE TABLE quotes (id INT AUTO_INCREMENT PRIMARY KEY, teks TEXT NOT NULL, penulis VARCHAR(100), aktif TINYINT(1) DEFAULT 1);
INSERT INTO quotes (teks,penulis) VALUES ('Barang siapa memberi manfaat kepada kaum muslimin, Allah akan memberinya kebaikan','HR. Bukhari'),('Sesungguhnya shalat itu adalah tiang agama','Anonim');

CREATE TABLE kas (id INT AUTO_INCREMENT PRIMARY KEY, tanggal DATE, jenis ENUM('masuk','keluar'), sumber ENUM('manual','qris'), kategori VARCHAR(100), deskripsi TEXT, jumlah DECIMAL(15,2) DEFAULT 0, created_by VARCHAR(100), created_at DATETIME DEFAULT CURRENT_TIMESTAMP);
INSERT INTO kas (tanggal,jenis,sumber,kategori,deskripsi,jumlah,created_by) VALUES (CURDATE(),'masuk','manual','Infaq','Infaq Jumat',500000,'admin');

CREATE TABLE log_system (id INT AUTO_INCREMENT PRIMARY KEY, tanggal DATETIME, sumber VARCHAR(50), level VARCHAR(20), deskripsi TEXT, detail TEXT, user VARCHAR(100));

CREATE TABLE gateway_setting (id INT AUTO_INCREMENT PRIMARY KEY, provider VARCHAR(50), url_api TEXT, token TEXT, aktif TINYINT(1) DEFAULT 0);
INSERT INTO gateway_setting (provider,url_api,token,aktif) VALUES ('fonnte','https://api.fonnte.com/send','TOKEN_PASTE_HERE',1);

CREATE TABLE jadwal_salat (id INT AUTO_INCREMENT PRIMARY KEY, kota VARCHAR(100), kode_kota VARCHAR(20), tanggal DATE, subuh TIME, dzuhur TIME, ashar TIME, maghrib TIME, isya TIME, terbit TIME, created_at DATETIME DEFAULT CURRENT_TIMESTAMP);

CREATE TABLE notifikasi_setting (id INT AUTO_INCREMENT PRIMARY KEY, aktif ENUM('Y','N') DEFAULT 'Y', menit_sebelum INT DEFAULT 10, kirim_wa ENUM('Y','N') DEFAULT 'N');

-- end
