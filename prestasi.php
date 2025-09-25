<?php
include 'config.php';
include 'templates/header.php';

// Tambah prestasi
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_prestasi = $_POST['nama_prestasi'];
    $tingkat = $_POST['tingkat'];
    $juara = $_POST['juara'];
    $tanggal = $_POST['tanggal'];
    $anggota = $_POST['anggota']; // array of siswa_id

    $stmt = $conn->prepare("INSERT INTO prestasi (nama_prestasi, tingkat, juara, tanggal) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama_prestasi, $tingkat, $juara, $tanggal);
    $stmt->execute();
    $prestasi_id = $conn->insert_id;

    foreach ($anggota as $siswa_id) {
        $conn->query("INSERT INTO prestasi_anggota (prestasi_id, siswa_id) VALUES ($prestasi_id, $siswa_id)");
    }
}

// Hapus prestasi
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM prestasi WHERE id=$id");
}

$siswa = $conn->query("SELECT * FROM siswa");
$prestasi = $conn->query("SELECT * FROM prestasi ORDER BY tanggal DESC");
?>

<h2>Data Prestasi</h2>
<form method="post" class="mb-3">
    <div class="mb-2"><input type="text" name="nama_prestasi" class="form-control" placeholder="Nama Prestasi" required></div>
    <div class="mb-2">
        <select name="tingkat" class="form-control" required>
            <option value="">Pilih Tingkat</option>
            <option>Sekolah</option><option>Kecamatan</option>
            <option>Kabupaten</option><option>Provinsi</option><option>Nasional</option>
        </select>
    </div>
    <div class="mb-2">
        <select name="juara" class="form-control" required>
            <option value="">Pilih Juara</option>
            <option>Juara 1</option><option>Juara 2</option>
            <option>Juara 3</option><option>Harapan</option><option>Peserta</option>
        </select>
    </div>
    <div class="mb-2"><input type="date" name="tanggal" class="form-control" required></div>
    <div class="mb-2">
        <label>Pilih Nama Siswa (bisa lebih dari satu)</label>
        <select name="anggota[]" class="form-control" multiple required>
            <?php while($row = $siswa->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?> (<?= htmlspecialchars($row['nis']) ?>)</option>
            <?php endwhile; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Tambah Prestasi</button>
</form>
<table class="table table-bordered">
    <tr><th>No</th><th>Nama Prestasi</th><th>Tingkat</th><th>Juara</th><th>Tanggal</th><th>Anggota</th><th>Action</th></tr>
    <?php $no=1; while($p = $prestasi->fetch_assoc()):
        $anggota = $conn->query("SELECT s.nama FROM prestasi_anggota pa JOIN siswa s ON pa.siswa_id=s.id WHERE pa.prestasi_id={$p['id']}");
        $nama_anggota = [];
        while($a = $anggota->fetch_assoc()) $nama_anggota[] = $a['nama'];
    ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($p['nama_prestasi']) ?></td>
        <td><?= htmlspecialchars($p['tingkat']) ?></td>
        <td><?= htmlspecialchars($p['juara']) ?></td>
        <td><?= htmlspecialchars($p['tanggal']) ?></td>
        <td><?= implode(', ', $nama_anggota) ?></td>
        <td><a href="?delete=<?= $p['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus prestasi ini?')">Hapus</a></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php include 'templates/footer.php'; ?>