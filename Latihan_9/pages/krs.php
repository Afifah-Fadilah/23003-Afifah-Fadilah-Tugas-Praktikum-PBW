<?php
// Koneksi ke database
include 'config/db.php'; // asumsi koneksi db

$alert = "";

// Tambah KRS
if (isset($_POST['tambah_krs'])) {
    $mahasiswa_npm = htmlspecialchars(trim($_POST['mahasiswa_npm']));
    $matakuliah_kodemk = htmlspecialchars(trim($_POST['matakuliah_kodemk']));

    if (empty($mahasiswa_npm) || empty($matakuliah_kodemk)) {
        $alert = "<div class='alert alert-danger'>Mahasiswa dan Mata Kuliah harus dipilih!</div>";
    } else {
        $check = $conn->query("SELECT id FROM krs WHERE mahasiswa_npm='$mahasiswa_npm' AND matakuliah_kodemk='$matakuliah_kodemk'");
        if ($check->num_rows > 0) {
            $alert = "<div class='alert alert-danger'>KRS sudah terdaftar!</div>";
        } else {
            if ($conn->query("INSERT INTO krs (mahasiswa_npm, matakuliah_kodemk) VALUES ('$mahasiswa_npm', '$matakuliah_kodemk')")) {
                $alert = "<div class='alert alert-success'>KRS berhasil ditambahkan!</div>";
            } else {
                $alert = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        }
    }
}

// Edit KRS
if (isset($_POST['edit_krs'])) {
    $id = intval($_POST['id']);
    $mahasiswa_npm = htmlspecialchars(trim($_POST['mahasiswa_npm']));
    $matakuliah_kodemk = htmlspecialchars(trim($_POST['matakuliah_kodemk']));

    if (empty($mahasiswa_npm) || empty($matakuliah_kodemk)) {
        $alert = "<div class='alert alert-danger'>Mahasiswa dan Mata Kuliah harus dipilih!</div>";
    } else {
        $check = $conn->query("SELECT id FROM krs WHERE mahasiswa_npm='$mahasiswa_npm' AND matakuliah_kodemk='$matakuliah_kodemk' AND id != '$id'");
        if ($check->num_rows > 0) {
            $alert = "<div class='alert alert-danger'>KRS sudah terdaftar!</div>";
        } else {
            if ($conn->query("UPDATE krs SET mahasiswa_npm='$mahasiswa_npm', matakuliah_kodemk='$matakuliah_kodemk' WHERE id='$id'")) {
                $alert = "<div class='alert alert-success'>Data KRS berhasil diperbarui!</div>";
            } else {
                $alert = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        }
    }
}

// Hapus KRS
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    if ($conn->query("DELETE FROM krs WHERE id='$id'")) {
        $alert = "<div class='alert alert-success'>KRS berhasil dihapus!</div>";
    } else {
        $alert = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM krs WHERE id = '$id'");
    $edit_data = $result->fetch_assoc();
}

// Ambil semua data KRS dengan JOIN
$krs_data = $conn->query("
    SELECT krs.id, m.nama AS nama_mahasiswa, mk.nama AS nama_matkul, mk.jumlah_sks 
    FROM krs 
    JOIN mahasiswa m ON m.npm = krs.mahasiswa_npm 
    JOIN matakuliah mk ON mk.kodemk = krs.matakuliah_kodemk
");

// Ambil semua mahasiswa
$mahasiswa_options = $conn->query("SELECT npm, nama FROM mahasiswa");

// Ambil semua mata kuliah
$matakuliah_options = $conn->query("SELECT kodemk, nama, jumlah_sks FROM matakuliah");
?>


<div class="dashboard">
    <div class="dashboard-header">
        <h2>Data KRS</h2>
        <p>Kelola Kartu Rencana Studi</p>
    </div>
    
    <?php echo $alert; ?>
    
    <div class="form-container">
        <h3><?php echo $edit_data ? 'Edit KRS' : 'Tambah KRS'; ?></h3>
        <form method="post" action="">
            <?php if ($edit_data): ?>
                <input type="hidden" name="id" value="<?php echo $edit_data['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
            <label for="mahasiswa_npm">Mahasiswa</label>
            <select class="form-control select2" id="mahasiswa_npm" name="mahasiswa_npm" required>
                <option value="">-- Pilih Mahasiswa --</option>
                <?php
                mysqli_data_seek($mahasiswa_options, 0);
                while ($row = mysqli_fetch_assoc($mahasiswa_options)): 
                ?>
                <option value="<?php echo $row['npm']; ?>" <?php echo $edit_data && $edit_data['mahasiswa_npm'] == $row['npm'] ? 'selected' : ''; ?>>
                    <?php echo $row['npm'] . ' - ' . $row['nama']; ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>

            
            <div class="form-group">
                <label for="matakuliah_kodemk">Mata Kuliah</label>
                <select class="form-control" id="matakuliah_kodemk" name="matakuliah_kodemk" required>
                    <option value="">-- Pilih Mata Kuliah --</option>
                    <?php
                    mysqli_data_seek($matakuliah_options, 0);
                    while ($row = mysqli_fetch_assoc($matakuliah_options)): 
                    ?>
                    <option value="<?php echo $row['kodemk']; ?>" <?php echo $edit_data && $edit_data['matakuliah_kodemk'] == $row['kodemk'] ? 'selected' : ''; ?>>
                        <?php echo $row['kodemk'] . ' - ' . $row['nama'] . ' (' . $row['jumlah_sks'] . ' SKS)'; ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="btn-save">
            <button type="submit" name="<?php echo $edit_data ? 'edit_krs' : 'tambah_krs'; ?>" class="btn btn-primary">
                <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
            </button>
            </div>
            <?php if ($edit_data): ?>
                <a href="index.php?page=krs" class="btn btn-warning">Batal</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="data-table">
        <div class="data-table-header">
            <h2>Daftar KRS</h2>
        </div>
        <div class="search-form">
            <input type="text" id="searchInput" placeholder="Cari berdasarkan Nama atau Mata Kuliah...">
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($krs_data) > 0) {
                    mysqli_data_seek($krs_data, 0);
                    while ($row = mysqli_fetch_assoc($krs_data)):
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['nama_mahasiswa']; ?></td>
                    <td><?php echo $row['nama_matkul']; ?></td>
                    <td><?php echo $row['jumlah_sks']; ?></td>
                    <td>
                        <span class="nama-highlight"><?php echo $row['nama_mahasiswa']; ?></span>
                        Mengambil Mata Kuliah 
                        <span class="matkul-highlight"><?php echo $row['nama_matkul']; ?></span>
                        (<?php echo $row['jumlah_sks']; ?> SKS)
                    </td>
                    <td class="actions">
                        <a href="index.php?page=krs&edit=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="index.php?page=krs&hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php
                    endwhile;
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Tidak ada data</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    document.getElementById('searchInput').addEventListener('keyup', function () {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("table tbody tr");

        rows.forEach(function (row) {
            let nama = row.cells[1].textContent.toUpperCase();
            let matkul = row.cells[2].textContent.toUpperCase();

            if (nama.includes(filter) || matkul.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- jQuery (wajib untuk Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

