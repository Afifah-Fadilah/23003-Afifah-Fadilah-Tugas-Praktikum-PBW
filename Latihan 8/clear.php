<?php
session_start(); // <<< WAJIB! Memulai sesi untuk mengakses data dalam $_SESSION

$data = isset($_SESSION['data']) ? $_SESSION['data'] : []; // Ambil data pendaftaran dari session jika ada, jika tidak, inisialisasi sebagai array kosong

$id = isset($_GET['id']) ? (int)$_GET['id'] : null; // Ambil parameter id dari URL, cast ke integer untuk keamanan, jika tidak ada maka null

if ($id !== null && isset($data[$id])) { // Cek apakah id tidak null dan data dengan id tersebut ada dalam array
    // Periksa jika pendaftaran belum bayar, maka data dapat dihapus
    if (!isset($data[$id]['payment']) || !$data[$id]['payment']) {
        // Hapus data yang belum bayar
        unset($data[$id]); // Hapus entri data tersebut dari array
        $_SESSION['data'] = $data; // Update session setelah hapus
        header('Location: hasil.php'); // Redirect ke halaman hasil
        exit; // Hentikan eksekusi skrip setelah redirect
    }
} 
?>
