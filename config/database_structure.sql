CREATE DATABASE IF NOT EXISTS sistem_tiket;
USE sistem_tiket;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('admin', 'teknisi', 'reviewer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    teknisi_id INT,
    status ENUM('baru', 'diproses', 'selesai') NOT NULL DEFAULT 'baru',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teknisi_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS daily_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    nama_pelanggan VARCHAR(255) NOT NULL,
    no_layanan VARCHAR(50) NOT NULL,
    alamat TEXT NOT NULL,
    durasi_waktu VARCHAR(50) NOT NULL,
    teknisi VARCHAR(255) NOT NULL,
    foto_sn_ont VARCHAR(255),
    foto_ktp_pelanggan VARCHAR(255),
    foto_teknisi_pelanggan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teknisi', 'reviewer') NOT NULL;
