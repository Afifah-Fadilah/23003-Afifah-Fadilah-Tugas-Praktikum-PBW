<?php
session_start(); // WAJIB: Memulai session PHP agar bisa menyimpan data antar halaman
date_default_timezone_set('Asia/Jakarta');


// Ambil data dari form POST yang dikirim dari form HTML
$maskapai   = $_POST['maskapai']; // Ambil nama maskapai dari form
$penumpang  = $_POST['penumpang']; // Ambil nama penumpang dari form
$asal       = $_POST['asal']; // Ambil bandara asal dari form
$tujuan     = $_POST['tujuan']; // Ambil bandara tujuan dari form
$tanggal    = $_POST['tanggal']; // Ambil tanggal penerbangan dari form
$kelas      = $_POST['kelas']; // Ambil kelas penerbangan dari form
$harga      = (int) $_POST['harga']; // Ambil harga tiket dan ubah ke integer

// Cek apakah sudah ada data di session sebelumnya
$data = isset($_SESSION['data']) ? $_SESSION['data'] : []; // Jika ada, ambil; jika belum, inisialisasi array kosong


// Fungsi untuk menghasilkan nomor kursi berdasarkan kelas dan data sebelumnya
function generateSeatNumber($kelas, $data) {
    $seat_prefix = [      // Awalan kursi berdasarkan kelas
        'Ekonomi' => 'E',
        'Bisnis' => 'B',
        'First Class' => 'F'
    ];

    $prefix = $seat_prefix[$kelas] ?? 'E'; // default Ekonomi

    $max_seat_number = 0; // Inisialisasi nilai awal nomor kursi tertinggi dengan 0
    foreach ($data as $entry) { // Looping setiap entri data penumpang yang sudah tersimpan
        if ($entry['kelas'] === $kelas && isset($entry['nomor_kursi'])) { // Cek apakah entri punya kelas yang sama & nomor kursi tersedia
            $seat = (int) substr($entry['nomor_kursi'], 1); // Ambil bagian angka dari nomor kursi (misal "E005" jadi 5)
            if ($seat > $max_seat_number) { // Jika nomor kursi sekarang lebih besar dari yang sebelumnya ditemukan
                $max_seat_number = $seat; // Update nomor kursi tertinggi dengan yang baru
            }
        }
    }

    $new_seat_number = $max_seat_number + 1; // Tambahkan 1 untuk kursi baru
    return $prefix . str_pad($new_seat_number, 3, '0', STR_PAD_LEFT); // Format kursi, contoh "E007"
}

// Hasilkan nomor kursi berdasarkan data di atas
$nomor_kursi = generateSeatNumber($kelas, $data);

// Fungsi untuk mengambil pajak berdasarkan bandara asal
function getPajakAsal($bandara) {
    $pajak_asal = [
        "Soekarno Hatta (CGK)" => 65000,
        "Husein Sastranegara (BDO)" => 50000,
        "Abdul Rachman Saleh (MLG)" => 40000,
        "Juanda (SUB)" => 30000
    ];
    return $pajak_asal[$bandara] ?? 0; // Jika tidak ditemukan, kembalikan 0
}

// Fungsi untuk mengambil pajak berdasarkan bandara tujuan
function getPajakTujuan($bandara) {
    $pajak_tujuan = [
        "Ngurah Rai (DPS)" => 85000,
        "Hasanuddin (UPG)" => 70000,
        "Inanwatan (INX)" => 90000,
        "Sultan Iskandar Muda (BTJ)" => 60000
    ];
    return $pajak_tujuan[$bandara] ?? 0; // Jika tidak ditemukan, kembalikan 0
}

// Hitung pajak berdasarkan asal dan tujuan yang akan ditampilkan di halaman hasil.php
$pajak_asal   = getPajakAsal($asal); // Pajak dari bandara asal
$pajak_tujuan = getPajakTujuan($tujuan); // Pajak dari bandara tujuan
$total_pajak  = $pajak_asal + $pajak_tujuan; //jumlahkan keduanya

// Total harga (sudah termasuk pajak dan kelas, jadi pakai nilai dari form)
$total_harga = $harga + $total_pajak;

// Hasilkan ID unik untuk penerbangan
$id_penerbangan = 'FLIGHT_' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

// Susun data pendaftaran yang akan disimpan
$pendaftaran = [
    'id_penerbangan' => $id_penerbangan,
    'maskapai'       => $maskapai,
    'penumpang'      => $penumpang,
    'asal'           => $asal,
    'tujuan'         => $tujuan,
    'tanggal'        => $tanggal,
    'kelas'          => $kelas,
    'harga'          => $harga,
    'pajak'          => $total_pajak,
    'total'          => $total_harga,
    'nomor_kursi'    => $nomor_kursi,
    'created_at' => date('Y-m-d H:i:s') // Catat waktu pendaftaran
];

// Tambahkan pendaftaran ke data session
$data[] = $pendaftaran;

// Simpan kembali ke session
$_SESSION['data'] = $data;

// Simpan juga pendaftaran terakhir untuk tampilkan langsung
$_SESSION['pendaftaran'] = $pendaftaran;

// Redirect user ke halaman hasil pendaftaran
header('Location: hasil.php');
exit;  // Hentikan eksekusi script setelah redirect
?>
