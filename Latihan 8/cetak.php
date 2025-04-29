<?php
session_start(); // <<< WAJIB untuk mengakses data di $_SESSION

$data = isset($_SESSION['data']) ? $_SESSION['data'] : []; // Ambil data pendaftaran dari session, jika tidak ada inisialisasi sebagai array kosong

$id = isset($_GET['id']) ? (int)$_GET['id'] : null; // Ambil nilai 'id' dari parameter URL dan konversi ke integer
if ($id !== null && isset($data[$id])) { // Jika ID valid dan data pendaftaran dengan ID tersebut ada
    $pendaftaran = $data[$id]; // Simpan data pendaftaran ke variabel
} else {
    die('Data tidak ditemukan.'); // Jika data tidak ditemukan, hentikan eksekusi dan tampilkan pesan
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Tiket</title> <!-- Judul halaman -->
    <link rel="stylesheet" href="style.css"> <!-- Menghubungkan file CSS eksternal -->
</head>
<body class="tiket-style"> <!-- Tambahkan kelas khusus untuk styling halaman tiket -->
<div class="tiket-container"> <!-- Container utama tiket -->
    <div class="tiket-header"> <!-- Header tiket -->
        <h1>Tiket Penerbangan</h1>
        <p>ID Penerbangan: <?= htmlspecialchars($pendaftaran['id_penerbangan']) ?></p> <!-- Tampilkan ID penerbangan dengan filter HTML aman -->
    </div>

    <table class="tiket-details"> <!-- Tabel berisi detail tiket -->
        <tr>
            <th>Nama Penumpang</th>
            <td><?= htmlspecialchars($pendaftaran['penumpang']) ?></td>
        </tr>
        <tr>
            <th>Maskapai</th>
            <td><?= htmlspecialchars($pendaftaran['maskapai']) ?></td>
        </tr>
        <tr>
            <th>Tanggal penerbangan</th>
            <td><?= htmlspecialchars($pendaftaran['tanggal']) ?></td>
        </tr>
        <tr>
            <th>Asal</th>
            <td><?= htmlspecialchars($pendaftaran['asal']) ?></td>
        </tr>
        <tr>
            <th>Tujuan</th>
            <td><?= htmlspecialchars($pendaftaran['tujuan']) ?></td>
        </tr>
        <tr>
            <th>Kelas</th>
            <td><?= htmlspecialchars($pendaftaran['kelas']) ?></td>
        </tr>
        <tr>
            <th>No Kursi</th>
            <td><?= htmlspecialchars($pendaftaran['nomor_kursi']) ?></td>
        </tr>
        <tr>
            <th>Total Harga</th>
            <td>Rp <?= number_format($pendaftaran['total'], 0, ',', '.') ?></td>
        </tr>
    </table>

    <?php if (isset($pendaftaran['created_at'])): ?> <!-- Jika data tanggal input tersedia -->
    <div class="tgl-input-cetak">
        Tanggal Input: <?= date('d-m-Y H:i', strtotime($pendaftaran['created_at'])) ?> <!-- Tampilkan tanggal input dalam format lokal -->
    </div> 
    <?php endif; ?>

    <div class="tiket-footer"> <!-- Footer tiket -->
        <p>Terima kasih telah memilih penerbangan kami! <br>
            Tunjukkan tiket ini saat Anda tiba di loket penerbangan kami untuk proses check-in dan verifikasi.</p>
        <a href="hasil.php" class="btn-secondary">Kembali</a> <!-- Tombol kembali ke halaman hasil -->
        <button onclick="window.print()" class="btn">Print Tiket</button> <!-- Tombol cetak tiket menggunakan fungsi browser -->
    </div>

</div>

</body>
</html>
