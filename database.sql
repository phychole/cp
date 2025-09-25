CREATE TABLE siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nis VARCHAR(20) NOT NULL
);

CREATE TABLE prestasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_prestasi VARCHAR(100) NOT NULL,
    tingkat VARCHAR(50),
    juara VARCHAR(50),
    tanggal DATE
);

CREATE TABLE prestasi_anggota (
    id INT AUTO_INCREMENT PRIMARY KEY,
    prestasi_id INT,
    siswa_id INT,
    FOREIGN KEY (prestasi_id) REFERENCES prestasi(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
);