<?php
// Koneksi database (asumsi session sudah simpan koneksi di $conn)
include 'config/db.php'; // pastikan ini ada koneksi $conn

$alert = "";

// Proses Tambah Mata Kuliah
if (isset($_POST['tambah_matakuliah'])) {
    $kodemk = mysqli_real_escape_string($conn, $_POST['kodemk']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jumlah_sks = mysqli_real_escape_string($conn, $_POST['jumlah_sks']);

    if (empty($kodemk) || empty($nama) || empty($jumlah_sks)) {
        $alert = "<div class='alert alert-danger'>Kode MK, Nama, dan Jumlah SKS harus diisi!</div>";
    } else {
        $check = mysqli_query($conn, "SELECT kodemk FROM matakuliah WHERE kodemk = '$kodemk'");
        if (mysqli_num_rows($check) > 0) {
            $alert = "<div class='alert alert-danger'>Kode MK sudah terdaftar!</div>";
        } else {
            $sql = "INSERT INTO matakuliah (kodemk, nama, jumlah_sks) VALUES ('$kodemk', '$nama', '$jumlah_sks')";
            if (mysqli_query($conn, $sql)) {
                $alert = "<div class='alert alert-success'>Mata Kuliah berhasil ditambahkan!</div>";
            } else {
                $alert = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}

// Proses Edit
if (isset($_POST['edit_matakuliah'])) {
    $kodemk = mysqli_real_escape_string($conn, $_POST['kodemk']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $jumlah_sks = mysqli_real_escape_string($conn, $_POST['jumlah_sks']);

    if (empty($nama) || empty($jumlah_sks)) {
        $alert = "<div class='alert alert-danger'>Nama dan Jumlah SKS harus diisi!</div>";
    } else {
        $sql = "UPDATE matakuliah SET nama = '$nama', jumlah_sks = '$jumlah_sks' WHERE kodemk = '$kodemk'";
        if (mysqli_query($conn, $sql)) {
            $alert = "<div class='alert alert-success'>Data berhasil diperbarui!</div>";
        } else {
            $alert = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $kodemk = mysqli_real_escape_string($conn, $_GET['hapus']);
    $sql = "DELETE FROM matakuliah WHERE kodemk = '$kodemk'";
    if (mysqli_query($conn, $sql)) {
        $alert = "<div class='alert alert-success'>Mata Kuliah berhasil dihapus!</div>";
    } else {
        $alert = "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $kodemk = mysqli_real_escape_string($conn, $_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM matakuliah WHERE kodemk = '$kodemk'");
    $edit_data = mysqli_fetch_assoc($result);
}

// Ambil semua data
$matakuliah = mysqli_query($conn, "SELECT * FROM matakuliah ORDER BY kodemk ASC");
?>


<div class="dashboard">
    <div class="dashboard-header">
        <h2>Data Mata Kuliah</h2>
        <p>Kelola data mata kuliah</p>
    </div>
    
    <?php echo $alert; ?>
    
    <div class="form-container">
    <h3><?php echo $edit_data ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah'; ?></h3>
    <form method="post" action="">
        <?php if ($edit_data): ?>
            <input type="hidden" name="kodemk" value="<?php echo $edit_data['kodemk']; ?>">
        <?php else: ?>
            <div class="form-group">
                <label for="kodemk">Kode Mata Kuliah</label>
                <input type="text" class="form-control" id="kodemk" name="kodemk" maxlength="6" required
                       placeholder="Masukkan Kode Mata Kuliah">
            </div>
        <?php endif; ?>
        
        <div class="form-group">
            <label for="nama">Nama Mata Kuliah</label>
            <input type="text" class="form-control" id="nama" name="nama"
                   value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>" maxlength="50" required
                   placeholder="Masukkan Nama Mata Kuliah">
        </div>
        
        <div class="form-group">
            <label for="jumlah_sks">Jumlah SKS</label>
            <input type="number" class="form-control" id="jumlah_sks" name="jumlah_sks"
                   value="<?php echo $edit_data ? $edit_data['jumlah_sks'] : ''; ?>" min="1" max="6" required
                   placeholder="Masukkan Jumlah SKS">
        </div>

        <div class="btn-save">
            <button type="submit" name="<?php echo $edit_data ? 'edit_matakuliah' : 'tambah_matakuliah'; ?>" class="btn btn-primary">
                <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
            </button>
        </div>
        
        <?php if ($edit_data): ?>
            <a href="index.php?page=matakuliah" class="btn btn-warning">Batal</a>
        <?php endif; ?>
    </form>
</div>

    
    <div class="data-table">
        <div class="data-table-header">
            <h2>Daftar Mata Kuliah</h2>
        </div>
        <div class="search-form">
            <input type="text" id="searchMatkulInput" placeholder="Cari Kode MK atau Nama Mata Kuliah...">
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th>Jumlah SKS</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($matakuliah) > 0) {
                    while ($row = mysqli_fetch_assoc($matakuliah)):
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['kodemk']; ?></td>
                    <td><?php echo $row['nama']; ?></td>
                    <td><?php echo $row['jumlah_sks']; ?></td>
                    <td class="actions">
                        <a href="index.php?page=matakuliah&edit=<?php echo $row['kodemk']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="index.php?page=matakuliah&hapus=<?php echo $row['kodemk']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php
                    endwhile;
                } else {
                    echo "<tr><td colspan='5' class='text-center'>Tidak ada data</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    document.getElementById('searchMatkulInput').addEventListener('keyup', function () {
        let filter = this.value.toUpperCase();
        let rows = document.querySelectorAll("table tbody tr");

        rows.forEach(function (row) {
            let kodeMK = row.cells[1].textContent.toUpperCase();
            let namaMK = row.cells[2].textContent.toUpperCase();

            if (kodeMK.includes(filter) || namaMK.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
