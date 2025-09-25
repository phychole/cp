<?php
include 'config.php';
include 'templates/header.php';

// Tambah siswa
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nis = $_POST['nis'];
    $stmt = $conn->prepare("INSERT INTO siswa (nama, nis) VALUES (?, ?)");
    $stmt->bind_param("ss", $nama, $nis);
    $stmt->execute();
}

// Hapus siswa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM siswa WHERE id=$id");
}

$siswa = $conn->query("SELECT * FROM siswa");
?>

<h2>Data Siswa</h2>
<form method="post" class="mb-3">
    <div class="mb-2"><input type="text" name="nama" class="form-control" placeholder="Nama Siswa" required></div>
    <div class="mb-2"><input type="text" name="nis" class="form-control" placeholder="NIS" required></div>
    <button type="submit" class="btn btn-primary">Tambah Siswa</button>
</form>
<table class="table table-bordered">
    <tr><th>No</th><th>Nama</th><th>NIS</th><th>Action</th></tr>
    <?php $no=1; while($row = $siswa->fetch_assoc()): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['nis']) ?></td>
        <td><a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Hapus siswa ini?')">Hapus</a></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php include 'templates/footer.php'; ?>
