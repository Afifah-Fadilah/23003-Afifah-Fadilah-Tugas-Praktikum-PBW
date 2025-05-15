
<?php
include_once 'config/db.php';

// Hitung total data
$mahasiswa_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM mahasiswa"))['count'];
$matakuliah_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM matakuliah"))['count'];
$krs_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM krs"))['count'];

// Ambil data KRS
$krs_data = mysqli_query($conn, "
    SELECT k.id, m.npm, m.nama AS nama_mahasiswa, mk.kodemk, mk.nama AS nama_matkul, mk.jumlah_sks 
    FROM krs k
    JOIN mahasiswa m ON k.mahasiswa_npm = m.npm
    JOIN matakuliah mk ON k.matakuliah_kodemk = mk.kodemk
    ORDER BY k.id DESC
");

// Mahasiswa dengan total SKS terbanyak
$top_students = mysqli_query($conn, "
    SELECT m.npm, m.nama, SUM(mk.jumlah_sks) as total_sks 
    FROM mahasiswa m
    LEFT JOIN krs k ON m.npm = k.mahasiswa_npm
    LEFT JOIN matakuliah mk ON k.matakuliah_kodemk = mk.kodemk
    GROUP BY m.npm
    ORDER BY total_sks DESC
    LIMIT 5
");

// Mata kuliah terpopuler
$popular_courses = mysqli_query($conn, "
    SELECT mk.kodemk, mk.nama, COUNT(k.matakuliah_kodemk) as jumlah_mahasiswa 
    FROM matakuliah mk
    LEFT JOIN krs k ON mk.kodemk = k.matakuliah_kodemk
    GROUP BY mk.kodemk
    ORDER BY jumlah_mahasiswa DESC
    LIMIT 5
");
?>


<div class="dashboard">
    <div class="dashboard-header">
        <h2>Dashboard</h2>
        <p>Selamat datang di Sistem Informasi Akademik</p>
    </div>
    
    <div class="dashboard-stats">
        <div class="stat-card primary">
            <h3>Total Mahasiswa</h3>
            <div class="value"><?php echo $mahasiswa_count; ?></div>
        </div>
        
        <div class="stat-card success">
            <h3>Total Mata Kuliah</h3>
            <div class="value"><?php echo $matakuliah_count; ?></div>
        </div>
        
        <div class="stat-card warning">
            <h3>Total KRS</h3>
            <div class="value"><?php echo $krs_count; ?></div>
        </div>
    </div>
    
    <div class="data-table">
        <div class="data-table-header">
            <h2>Data KRS Terbaru</h2>
            <a href="index.php?page=krs" class="btn btn-primary">Lihat Semua</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Mata Kuliah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if (mysqli_num_rows($krs_data) > 0) {
                    while ($row = mysqli_fetch_assoc($krs_data)):
                        if ($no <= 5): // Batasi hanya 10 data
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['nama_mahasiswa']; ?></td>
                    <td><?php echo $row['nama_matkul']; ?></td>
                    <td>
                        <span class="nama-highlight"><?php echo $row['nama_mahasiswa']; ?></span>
                        Mengambil Mata Kuliah 
                        <span class="matkul-highlight"><?php echo $row['nama_matkul']; ?></span>
                        (<?php echo $row['jumlah_sks']; ?> SKS)
                    </td>
                </tr>
                <?php
                        endif;
                    endwhile;
                } else {
                    echo "<tr><td colspan='4' class='text-center'>Tidak ada data</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    
    <div style="display: flex; gap: 20px;">
        <div class="data-table" style="flex: 1;">
            <div class="data-table-header">
                <h2>Mahasiswa dengan SKS Terbanyak</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Nama Mahasiswa</th>
                        <th>Total SKS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($top_students) > 0) {
                        while ($row = mysqli_fetch_assoc($top_students)):
                    ?>
                    <tr>
                        <td><?php echo $row['nama']; ?></td>
                        <td>
                            <span class="badge badge-primary"><?php echo $row['total_sks'] ? $row['total_sks'] : '0'; ?> SKS</span>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    } else {
                        echo "<tr><td colspan='2' class='text-center'>Tidak ada data</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="data-table" style="flex: 1;">
            <div class="data-table-header">
                <h2>Mata Kuliah Terpopuler</h2>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Mata Kuliah</th>
                        <th>Jumlah Mahasiswa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($popular_courses) > 0) {
                        while ($row = mysqli_fetch_assoc($popular_courses)):
                    ?>
                    <tr>
                        <td><?php echo $row['nama']; ?></td>
                        <td>
                            <span class="badge badge-success"><?php echo $row['jumlah_mahasiswa']; ?> Mahasiswa</span>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    } else {
                        echo "<tr><td colspan='2' class='text-center'>Tidak ada data</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>