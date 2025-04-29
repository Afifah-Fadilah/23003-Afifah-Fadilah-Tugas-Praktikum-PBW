<?php

session_start(); //memulai sesi untuk menyimpan dan mengakses data 

// Array pajak bandara asal
$bandara_asal = [
    "Soekarno Hatta (CGK)" => 65000,
    "Husein Sastranegara (BDO)" => 50000,
    "Abdul Rachman Saleh (MLG)" => 40000,
    "Juanda (SUB)" => 30000
];

// Array pajak bandara tujuan
$bandara_tujuan = [
    "Ngurah Rai (DPS)" => 85000,
    "Hasanuddin (UPG)" => 70000,
    "Inanwatan (INX)" => 90000,
    "Sultan Iskandar Muda (BTJ)" => 60000   
];

// Daftar harga tiket antar bandara
$harga_tiket = [
    "Soekarno Hatta (CGK)" => [ //daftar harga tiket soekarno hatta (CGK) ke bandara tujuan
        "Ngurah Rai (DPS)" => 1500000,
        "Hasanuddin (UPG)" => 1400000,
        "Inanwatan (INX)" => 1700000,
        "Sultan Iskandar Muda (BTJ)" => 1600000,
    ],
    "Husein Sastranegara (BDO)" => [ //daftar harga tiket husein sastranegara (BDO) ke bandara tujuan
        "Ngurah Rai (DPS)" => 1300000, 
        "Hasanuddin (UPG)" => 1200000,
        "Inanwatan (INX)" => 1500000,
        "Sultan Iskandar Muda (BTJ)" => 1400000,
    ],
    "Abdul Rachman Saleh (MLG)" => [ //daftar harga tiket dari abdul rachman saleh (MLG) ke bandara tujuan
        "Ngurah Rai (DPS)" => 1200000,
        "Hasanuddin (UPG)" => 1100000,
        "Inanwatan (INX)" => 1400000,
        "Sultan Iskandar Muda (BTJ)" => 1300000,
    ],
    "Juanda (SUB)" => [ //daftar harga tiket dari juanda (SUB) ke bandara tujuan
        "Ngurah Rai (DPS)" => 1000000,
        "Hasanuddin (UPG)" => 900000,
        "Inanwatan (INX)" => 1200000,
        "Sultan Iskandar Muda (BTJ)" => 1100000,
    ],
];

// Sorting bandara
ksort($bandara_asal); // Mengurutkan array bandara asal berdasarkan nama (A-Z)
ksort($bandara_tujuan); // Mengurutkan array bandara tujuan berdasarkan nama (A-Z)
?>

<!DOCTYPE html> <!--Desain halaman web-->
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Tiket Pesawat</title>
    <link rel="stylesheet" href="style.css"> <!--memuat file style.css-->
</head>
<body>

<header>
    <h1>Selamat Datang di TiketKu</h1> <!--judul utama halaman untuk header-->
    <p>Pesan tiket pesawatmu dengan mudah dan cepat!</p> <!--deskripsi singkat web-->
</header>

<nav>
    <a href=" "></a> <!--dibuat kosong untuk membuat pembatas header dengan form-->
</nav>


<div class="container">
    <h2>Form Pendaftaran Rute Penerbangan</h2> <!--judul halaman form pendaftaran-->
    <form action="proses.php" method="POST"> <!-- Formulir dikirim ke proses.php -->
        
        <!-- Baris input maskapai dan penumpang -->
        <div class="form-row">
            <div class="form-group">
                <label>Nama Maskapai:</label>
                <input type="text" name="maskapai" required>
            </div>
            <div class="form-group">
                <label>Nama Penumpang:</label>
                <input type="text" name="penumpang" required>
            </div>
        </div>

        <!-- Baris input bandara asal dan bandara tujuan -->
        <div class="form-row">
            <div class="form-group">
                <label>Bandara Asal:</label>
                <select name="asal" required onchange="updateHarga()">
                    <option value="">--Pilih Bandara Asal--</option>
                    <?php foreach($bandara_asal as $bandara => $pajak): ?>
                        <option value="<?= $bandara ?>"><?= $bandara ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Bandara Tujuan:</label>
                <select name="tujuan" required onchange="updateHarga()">
                    <option value="">--Pilih Bandara Tujuan--</option>
                    <?php foreach($bandara_tujuan as $bandara => $pajak): ?>
                        <option value="<?= $bandara ?>"><?= $bandara ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Baris input tanggal dan kelas penerbangan -->
        <div class="form-row">
        <div class="form-group">
        <label>Tanggal Keberangkatan:</label>
        <!-- Input tanggal keberangkatan minimal besok -->
        <input type="date" name="tanggal" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
        </div>
        <div class="form-group">
        <label>Kelas Penerbangan:</label>
        <select name="kelas" required onchange="updateHarga()">
            <option value="">--Pilih Kelas--</option>
            <option value="Ekonomi">Ekonomi</option>
            <option value="Bisnis">Bisnis</option>
            <option value="First Class">First Class</option>
        </select>
        </div>
        </div>

        <!-- output harga tiket -->
        <label>Harga Tiket (Rp):</label>
        <input type="number" name="harga" readonly required>

        <button type="submit">Proses Pendaftaran</button> <!-- Tombol submit -->
        <a href="hasil.php" class="btn-secondary">Lihat Hasil Pendaftaran</a> <!-- Tautan ke hasil pendaftaran -->
    </form>

</div>

<!--scpript.js untuk menghitung harga tiket berdasarkan kelas yang dipilih-->
<script>
// Data pajak bandara asal (dari PHP ke JS) 
const bandaraAsal = {
    <?php foreach($bandara_asal as $bandara => $pajak): ?> // Loop PHP: ambil semua item dari array bandara_asal
    "<?= $bandara ?>": <?= $pajak ?>, // Format jadi pasangan key-value dalam objek JS
    <?php endforeach; ?> // Akhiri loop
};

// Data pajak bandara tujuan (dari PHP ke JS) 
const bandaraTujuan = {
    <?php foreach($bandara_tujuan as $bandara => $pajak): ?>
    "<?= $bandara ?>": <?= $pajak ?>,
    <?php endforeach; ?>
};

// Data harga tiket berdasarkan rute
const hargaTiket = {
    <?php foreach($harga_tiket as $asal => $tujuans): ?> // Loop PHP: ambil asal bandara
    "<?= $asal ?>": { // Set key objek untuk asal bandara
        <?php foreach($tujuans as $tujuan => $harga): ?> // Loop dalam: ambil tujuan dan harga dari asal yang sama
        "<?= $tujuan ?>": <?= $harga ?>, // Format jadi key tujuan dan value harga dalam objek
        <?php endforeach; ?> // Akhiri loop dalam
    },
    <?php endforeach; ?> // Akhiri loop luar
};

//fungsi untuk membuat harga berdasarkan kelas yang dipilih
function updateHarga() {
    const asal = document.querySelector('select[name="asal"]').value; // Ambil nilai asal bandara yang dipilih oleh user
    const tujuan = document.querySelector('select[name="tujuan"]').value; // Ambil nilai tujuan bandara yang dipilih oleh user
    const kelas = document.querySelector('select[name="kelas"]').value; // Ambil kela penerbangan yang dipilih oleh user
    const hargaInput = document.querySelector('input[name="harga"]'); // Ambil input harga yang dipilih oleh user

    if (asal && tujuan && kelas) {  // Pastikan asal, tujuan, dan kelas sudah dipilih
        let hargaDasar = 0; //inisialisasi harga dasar sebelum di hitung yaitu 0
        
        //cek kombinasi asal dan tujuan bandara apakah tersedia
        if (hargaTiket[asal] && hargaTiket[asal][tujuan]) {
            hargaDasar = hargaTiket[asal][tujuan]; //jika tersedia, ambil harga dasar dari array
        }
        let pajakAsal = bandaraAsal[asal]; // Ambil nilai pajak dari bandara asal
        let pajakTujuan = bandaraTujuan[tujuan]; // Ambil nilai pajak dari bandara tujuan
        let total = hargaDasar + pajakAsal + pajakTujuan; // Hitung total harga keseluruhan harga dasar + pajak asal + pajak tujuan

        //dibuatkan faktorial untuk menentukan harga berdasarkan kelas yang di pilih
        let faktorKelas = 1; //default kelas faktorial kelas untuk ekonomi
        if (kelas === "Bisnis") { //jika kelas yang dipilih bisnis maka harga 1.5 kali harga dasar
            faktorKelas = 1.5;
        } else if (kelas === "First Class") { //jika kelas yang dipilih first class maka harga 2 kali harga dasar
            faktorKelas = 2;
        }
        let totalHarga = total * faktorKelas; // Hitung harga total setelah memperhitungkan faktor kelas
        hargaInput.value = Math.round(totalHarga); // Hitung harga total setelah memperhitungkan faktor kelas
    } else {
        hargaInput.value = ""; // Jika input belum lengkap, kosongkan nilai harga
    }
}
</script>

</body>
</html>