<?php
include 'config/db.php'; // asumsi koneksi db

$alert = "";

// Tambah Mahasiswa
if (isset($_POST['tambah_mahasiswa'])) {
    $npm     = mysqli_real_escape_string($conn, trim($_POST['npm']));
    $nama    = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $jurusan = mysqli_real_escape_string($conn, trim($_POST['jurusan']));
    $alamat  = mysqli_real_escape_string($conn, trim($_POST['alamat']));

    if (empty($npm) || empty($nama) || empty($jurusan)) {
        $alert = "<div class='alert alert-danger'>NPM, Nama, dan Jurusan harus diisi!</div>";
    } else {
        $cek = mysqli_query($conn, "SELECT npm FROM mahasiswa WHERE npm = '$npm'");
        if (mysqli_num_rows($cek) > 0) {
            $alert = "<div class='alert alert-danger'>NPM sudah terdaftar!</div>";
        } else {
            $query = "INSERT INTO mahasiswa (npm, nama, jurusan, alamat) VALUES ('$npm', '$nama', '$jurusan', '$alamat')";
            if (mysqli_query($conn, $query)) {
                $alert = "<div class='alert alert-success'>Mahasiswa berhasil ditambahkan!</div>";
            } else {
                $alert = "<div class='alert alert-danger'>Gagal tambah data: " . mysqli_error($conn) . "</div>";
            }
        }
    }
}

// Edit Mahasiswa
if (isset($_POST['edit_mahasiswa'])) {
    $npm     = mysqli_real_escape_string($conn, trim($_POST['npm']));
    $nama    = mysqli_real_escape_string($conn, trim($_POST['nama']));
    $jurusan = mysqli_real_escape_string($conn, trim($_POST['jurusan']));
    $alamat  = mysqli_real_escape_string($conn, trim($_POST['alamat']));

    if (empty($nama) || empty($jurusan)) {
        $alert = "<div class='alert alert-danger'>Nama dan Jurusan harus diisi!</div>";
    } else {
        $query = "UPDATE mahasiswa SET nama='$nama', jurusan='$jurusan', alamat='$alamat' WHERE npm='$npm'";
        if (mysqli_query($conn, $query)) {
            $alert = "<div class='alert alert-success'>Data berhasil diperbarui!</div>";
        } else {
            $alert = "<div class='alert alert-danger'>Gagal update data: " . mysqli_error($conn) . "</div>";
        }
    }
}

// Hapus Mahasiswa
if (isset($_GET['hapus'])) {
    $npm = mysqli_real_escape_string($conn, $_GET['hapus']);
    $query = "DELETE FROM mahasiswa WHERE npm = '$npm'";
    if (mysqli_query($conn, $query)) {
        $alert = "<div class='alert alert-success'>Data berhasil dihapus!</div>";
    } else {
        $alert = "<div class='alert alert-danger'>Gagal hapus data: " . mysqli_error($conn) . "</div>";
    }
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit'])) {
    $npm = mysqli_real_escape_string($conn, $_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE npm = '$npm'");
    $edit_data = mysqli_fetch_assoc($result);
}

// Ambil semua data mahasiswa
$mahasiswa = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY nama ASC");

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
if (!empty($keyword)) {
    $keyword = mysqli_real_escape_string($conn, $keyword);
    $mahasiswa = mysqli_query($conn, "SELECT * FROM mahasiswa WHERE npm LIKE '%$keyword%' OR nama LIKE '%$keyword%'");
} else {
    $mahasiswa = mysqli_query($conn, "SELECT * FROM mahasiswa");
}
?>

<!-- HTML UI -->
<div class="dashboard">
    <div class="dashboard-header">
        <h2>Data Mahasiswa</h2>
        <p>Kelola data mahasiswa</p>
    </div>

    <?php echo $alert; ?>

    <div class="form-container">
    <h3><?php echo $edit_data ? 'Edit Mahasiswa' : 'Tambah Mahasiswa'; ?></h3>
    <form method="post" action="">
        <?php if ($edit_data): ?>
            <input type="hidden" name="npm" value="<?php echo $edit_data['npm']; ?>">
        <?php else: ?>
            <div class="form-group">
                <label for="npm">NPM</label>
                <input type="text" class="form-control" id="npm" name="npm" maxlength="13" required
                       placeholder="Masukkan NPM Anda">
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama" name="nama"
                   value="<?php echo $edit_data ? $edit_data['nama'] : ''; ?>" maxlength="50" required
                   placeholder="Masukkan Nama Lengkap">
        </div>

        <div class="form-group">
            <label for="jurusan">Jurusan</label>
            <select class="form-control" id="jurusan" name="jurusan" required>
                <option value="">-- Pilih Jurusan --</option>
                <option value="Teknik Informatika" <?php echo ($edit_data && $edit_data['jurusan'] == 'Teknik Informatika') ? 'selected' : ''; ?>>Teknik Informatika</option>
                <option value="Sistem Operasi" <?php echo ($edit_data && $edit_data['jurusan'] == 'Sistem Operasi') ? 'selected' : ''; ?>>Sistem Operasi</option>
            </select>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3"
                      placeholder="Masukkan Alamat Lengkap"><?php echo $edit_data ? $edit_data['alamat'] : ''; ?></textarea>
        </div>

        <div class="btn-save">
            <button type="submit" name="<?php echo $edit_data ? 'edit_mahasiswa' : 'tambah_mahasiswa'; ?>" class="btn btn-primary">
                <?php echo $edit_data ? 'Update' : 'Simpan'; ?>
            </button>
        </div>

        <?php if ($edit_data): ?>
            <a href="index.php?page=mahasiswa" class="btn btn-warning">Batal</a>
        <?php endif; ?>
    </form>
</div>


    <div class="data-table">
        <div class="data-table-header">
            <h2>Daftar Mahasiswa</h2>
        </div>
        <div class="search-form">
            <input type="text" id="searchInput" placeholder="Cari NPM atau Nama...">
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NPM</th>
                    <th>Nama</th>
                    <th>Jurusan</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($mahasiswa) > 0) {
                    while ($row = mysqli_fetch_assoc($mahasiswa)) {
                        echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>{$row['npm']}</td>
                            <td>{$row['nama']}</td>
                            <td>{$row['jurusan']}</td>
                            <td>{$row['alamat']}</td>
                            <td class='actions'>
                                <a href='index.php?page=mahasiswa&edit={$row['npm']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='index.php?page=mahasiswa&hapus={$row['npm']}' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus data ini?')\">Hapus</a>
                            </td>
                        </tr>";
                    }
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
            let npm = row.cells[1].textContent.toUpperCase();
            let nama = row.cells[2].textContent.toUpperCase();
            if (npm.includes(filter) || nama.includes(filter)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });
</script>
