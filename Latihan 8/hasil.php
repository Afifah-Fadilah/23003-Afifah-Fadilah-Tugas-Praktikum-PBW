<?php
session_start(); // Memulai sesi PHP untuk mengakses/memanipulasi data $_SESSION

$data = isset($_SESSION['data']) ? $_SESSION['data'] : []; // Ambil data pendaftaran dari session jika tersedia


$id = isset($_GET['id']) ? (int)$_GET['id'] : null;  // Ambil ID dari parameter URL jika ada, cast ke integer


// Handle AJAX pembayaran
if (isset($_POST['ajax']) && $_POST['ajax'] == 'bayar' && isset($_SESSION['data'][$id])) {
    $_SESSION['data'][$id]['payment'] = true; // Jika Pembayaran telah dilakukan Set status pembayaran menjadi true
    echo 'success'; // Kirim respon sukses ke client (AJAX)
    exit; // Hentikan eksekusi lebih lanjut
}

// Fungsi untuk mendapatkan pajak bandara asal
function getPajakAsal($bandara) {
    $pajak_asal = [
        "Soekarno Hatta (CGK)" => 65000,
        "Husein Sastranegara (BDO)" => 50000,
        "Abdul Rachman Saleh (MLG)" => 40000,
        "Juanda (SUB)" => 30000
    ];
    return $pajak_asal[$bandara] ?? 0;
}

// Fungsi untuk mendapatkan pajak bandara tujuan
function getPajakTujuan($bandara) {
    $pajak_tujuan = [
        "Ngurah Rai (DPS)" => 85000,
        "Hasanuddin (UPG)" => 70000,
        "Inanwatan (INX)" => 90000,
        "Sultan Iskandar Muda (BTJ)" => 60000
    ];
    return $pajak_tujuan[$bandara] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Pendaftaran</title>
    <link rel="stylesheet" href="style.css"> <!-- Menghubungkan ke file CSS eksternal -->

</head>
<body>

<div class="container-hasil"> <!-- Kontainer utama halaman hasil -->
    <h1>Hasil Pendaftaran Rute Penerbangan</h1> <!-- judul halaman hasil -->

    <?php if (!empty($data)): ?> <!-- Jika ada data pendaftaran -->
        <?php if ($id === null): ?> <!-- Jika belum memilih ID pendaftaran -->
            <h3>Belum Bayar</h3> <!-- judul kategori data pendaftaran bagi yang belum bayar -->
            <ul class="list-pendaftaran">
                <?php
                ksort($data); // Mengurutkan data berdasarkan key
                $adaBelumBayar = false; // Flag untuk mengecek apakah ada yang belum bayar
                foreach ($data as $index => $pendaftaran):
                    if (!isset($pendaftaran['payment']) || !$pendaftaran['payment']): // Jika belum bayar
                        $adaBelumBayar = true;
                ?>
                    <li>
                        <a href="hasil.php?id=<?= $index ?>"> <!-- Link detail pendaftaran, berisi pemesanan yang belum di bayar -->
                            <?= htmlspecialchars($pendaftaran['id_penerbangan']) ?> - <?= htmlspecialchars($pendaftaran['maskapai']) ?>
                        </a>
                        <a href="clear.php?id=<?= $index ?>" class="btn-hapus">Hapus</a>
                    </li>
                <?php
                    endif;
                endforeach;
                if (!$adaBelumBayar) {
                    echo "<li>Tidak ada pendaftaran yang belum bayar.</li>"; // Tampilkan jika kosong
                }
                ?>
            </ul>

            <h3>Sudah Bayar</h3> <!-- judul kategori data pendaftaran bagi yang belum bayar -->
            <ul class="list-pendaftaran">
                <?php
                ksort($data); // Mengurutkan data berdasarkan key
                $adaSudahBayar = false;
                foreach ($data as $index => $pendaftaran):
                    if (isset($pendaftaran['payment']) && $pendaftaran['payment']): // Jika sudah bayar
                        $adaSudahBayar = true;
                ?>
                    <li>
                        <a href="hasil.php?id=<?= $index ?>"> <!-- Link detail pendaftaran, berisi pemesanan yang sudah di bayar -->
                            <?= htmlspecialchars($pendaftaran['id_penerbangan']) ?> - <?= htmlspecialchars($pendaftaran['maskapai']) ?>
                        </a>
                    </li>
                <?php
                    endif;
                endforeach;
                if (!$adaSudahBayar) {
                    echo "<li>Belum ada pendaftaran yang sudah bayar.</li>"; // Tampilkan jika kosong
                }
                ?>
            </ul>
        <?php else: ?> <!-- Jika ID sudah dipilih -->
            <?php if (isset($data[$id])): ?> <!-- Jika data sesuai ID ditemukan -->
                <?php $pendaftaran = $data[$id]; ?>
                <div class="card"> <!-- Kartu detail pendaftaran -->
                    <h2>Maskapai: <?= htmlspecialchars($pendaftaran['maskapai']) ?></h2>
                    <table class="tabel-pendaftaran">
                        <tr><th>ID Penerbangan</th><td><?= htmlspecialchars($pendaftaran['id_penerbangan']) ?></td></tr>
                        <tr><th>Tanggal Keberangkatan</th><td><?= htmlspecialchars($pendaftaran['tanggal']) ?></td></tr>
                        <tr><th>Nama Penumpang</th><td><?= htmlspecialchars($pendaftaran['penumpang']) ?></td></tr>
                        <tr><th>Bandara Asal</th><td><?= htmlspecialchars($pendaftaran['asal']) ?></td></tr>
                        <tr><th>Bandara Tujuan</th><td><?= htmlspecialchars($pendaftaran['tujuan']) ?></td></tr>
                        <tr><th>Kelas</th><td><?= htmlspecialchars($pendaftaran['kelas']) ?></td></tr>
                        <tr><th>Nomor Kursi</th><td><?= htmlspecialchars($pendaftaran['nomor_kursi']) ?></td></tr>
                        <tr><th>Harga Tiket</th><td>Rp. <?= number_format($pendaftaran['harga'], 0, ',', '.') ?></td></tr>
                        <tr><th>Pajak</th><td>Rp. <?= number_format($pendaftaran['pajak'], 0, ',', '.') ?></td></tr>
                        <tr><th>Total Harga Tiket</th><td>Rp. <?= number_format($pendaftaran['total'], 0, ',', '.') ?></td></tr>
                        <tr><th>Status Pembayaran</th><td id="statusBayar"><?= isset($pendaftaran['payment']) && $pendaftaran['payment'] ? 'Sudah Dibayar' : 'Belum Dibayar' ?></td></tr>
                    </table>

                    <h3>Detail Pajak</h3> <!-- menampilkan detail pajak -->
                    <table class="tabel-pajak">
                        <tr><th>Pajak Bandara Asal</th><td>Rp. <?= number_format(getPajakAsal($pendaftaran['asal']), 0, ',', '.') ?></td></tr>
                        <tr><th>Pajak Bandara Tujuan</th><td>Rp. <?= number_format(getPajakTujuan($pendaftaran['tujuan']), 0, ',', '.') ?></td></tr>
                        <tr><th>Total Pajak</th><td>Rp. <?= number_format($pendaftaran['pajak'], 0, ',', '.') ?></td></tr>
                    </table>

                    <?php if (isset($pendaftaran['created_at'])): ?> <!-- digunakan untuk menampilkan waktu saat input data -->
                    <div class="tgl-input">
                        Tanggal Input: <?= date('Y-m-d H:i:s', strtotime($pendaftaran['created_at'])) ?>
                    </div> 
                    <?php endif; ?>

                    <div class="buttons"> <!-- Tombol aksi -->
                        <a href="hasil.php" class="btn">Kembali ke Daftar</a> <!-- Kembali ke daftar -->

                        <?php if (!isset($pendaftaran['payment']) || !$pendaftaran['payment']): ?>
                            <button class="btn" id="btnBayar" onclick="openPopup()">Bayar Tiket</button> <!-- Tombol bayar -->
                            <a href="#" class="btn btn-disabled" id="btnCetak" style="display:none;">Cetak Tiket</a> <!-- Tombol cetak disembunyikan dulu sebelum bayar -->
                        <?php else: ?>
                            <a href="cetak.php?id=<?= $id ?>" class="btn" id="btnCetak">Cetak Tiket</a> <!-- Tampilkan jika sudah bayar -->
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <p>Data tidak ditemukan.</p> <!-- Jika ID tidak cocok -->
            <?php endif; ?>
        <?php endif; ?>
    <?php else: ?>
        <p>Belum ada data pendaftaran.</p> <!-- Jika tidak ada data sama sekali -->
    <?php endif; ?>

    <div class="buttons">
        <a href="index.php" class="btn-secondary">Kembali ke Form</a> <!-- Kembali ke form pendaftaran -->
    </div>


</div>

<!-- Popup Metode Pembayaran -->
<div class="popup" id="popupBayar">
    <div class="popup-content">
        <h2>Pilih Metode Pembayaran</h2>
        <select id="metodeBayar"> <!-- Dropdown pilihan metode -->
            <option value="">-- Pilih Metode --</option>
            <option value="QRIS">QRIS</option>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="Virtual Account">Virtual Account</option>
            <option value="E-Wallet">E-Wallet</option>
        </select> 
        <button class="btn" onclick="prosesBayar()">Bayar</button> <!-- Tombol bayar -->
        <button class="btn-secondary" onclick="closePopup()">Batal</button> <!-- Tombol batal -->
    </div>
</div>

<!-- Popup Pembayaran Berhasil -->
<div class="popup" id="popupPembayaranBerhasil">
    <div class="popup-content">
        <h2>Pembayaran Berhasil!</h2>
        <p>Terima kasih, pembayaran Anda telah berhasil. Tiket Anda sudah bisa dicetak.</p>
        <button class="btn" onclick="closeSuccessPopup()">Tutup</button> <!-- Tombol tutup popup -->
    </div>
</div>


<script>
// Fungsi untuk membuka popup pembayaran
function openPopup() {
    document.getElementById('popupBayar').style.display = 'flex';
}

// Fungsi untuk menutup popup pembayaran
function closePopup() {
    document.getElementById('popupBayar').style.display = 'none';
}

// Fungsi AJAX untuk proses pembayaran
function prosesBayar() {
    const metode = document.getElementById('metodeBayar').value;
    if (metode === '') {
        alert('Pilih metode pembayaran dulu!');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'hasil.php?id=<?= $id ?>', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (this.responseText.trim() === 'success') {
            document.getElementById('statusBayar').innerText = 'Sudah Dibayar'; // Update status
            document.getElementById('btnBayar').style.display = 'none'; // Sembunyikan tombol bayar
            document.getElementById('btnCetak').classList.remove('btn-disabled');
            document.getElementById('btnCetak').setAttribute('href', 'hasil.php?id=<?= $id ?>&cetak=1');
            document.getElementById('btnCetak').style.display = 'inline-block';

            showSuccessPopup(); // Tampilkan popup sukses
            closePopup(); // Tutup popup metode bayar
        } else {
            alert('Gagal bayar, coba lagi!');
        }
    };
    xhr.send('ajax=bayar'); // Kirim data
}

// Menampilkan popup sukses
function showSuccessPopup() {
    const successPopup = document.getElementById('popupPembayaranBerhasil');
    successPopup.style.display = 'flex';
}

// Menutup popup sukses
function closeSuccessPopup() {
    const successPopup = document.getElementById('popupPembayaranBerhasil');
    successPopup.style.display = 'none';
}


</script>

</body>
</html>