<?php
include 'config.php';

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="laporan_prestasi.csv"');

$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : ''; 
tahun = isset($_GET['tahun']) ? $_GET['tahun'] : ''; 
$nama = isset($_GET['nama']) ? $_GET['nama'] : ''; 

$sql = "SELECT p.*, GROUP_CONCAT(s.nama SEPARATOR ', ') anggota FROM prestasi p
        JOIN prestasi_anggota pa ON p.id = pa.prestasi_id
        JOIN siswa s ON pa.siswa_id = s.id
        WHERE 1";
if ($bulan) $sql .= " AND MONTH(p.tanggal) = $bulan";
if ($tahun) $sql .= " AND YEAR(p.tanggal) = $tahun";
if ($nama) $sql .= " AND s.nama LIKE '%$nama%'";
$sql .= " GROUP BY p.id ORDER BY p.tanggal DESC";
$data = $conn->query($sql);

// Output header
echo "Prestasi,Tingkat,Juara,Tanggal,Anggota\n";

while($p = $data->fetch_assoc()) {
    echo "\"{$p['nama_prestasi']}\",\"{$p['tingkat']}\",\"{$p['juara']}\",\"{$p['tanggal']}\",\"{$p['anggota']}\"\n";
} 
?>