<?php
// Deklarasi kelas Book
class Book {
    // Properti privat: hanya bisa diakses dari dalam kelas
    private $code_book; // Menyimpan kode buku (format: 2 huruf kapital + 2 angka)
    private $name;      // Menyimpan nama buku
    private $qty;       // Menyimpan jumlah buku

    // Konstruktor: dipanggil saat objek dibuat
    public function __construct($code_book, $name, $qty) {
        $this->setCodeBook($code_book); // Set kode buku dengan validasi
        $this->name = $name;            // Set nama buku
        $this->setQty($qty);            // Set jumlah buku dengan validasi
    }

    // Setter untuk code_book dengan validasi format BB00 (2 huruf kapital, 2 angka)
    private function setCodeBook($code_book) {
        // Cek apakah format kode buku sesuai: 2 huruf kapital diikuti 2 angka
        if (preg_match('/^[A-Z]{2}[0-9]{2}$/', $code_book)) {
            $this->code_book = $code_book; // Jika valid, set nilai ke properti
        } else {
            // Jika tidak valid, tampilkan pesan error
            echo "\nError: Format code_book tidak valid. Harus 2 huruf kapital diikuti 2 angka (contoh: AB12).";
        }
    }

    // Setter untuk qty dengan validasi integer positif
    private function setQty($qty) {
        // Cek apakah qty adalah bilangan bulat positif
        if (is_int($qty) && $qty > 0) {
            $this->qty = $qty; // Jika valid, set nilai ke properti
        } else {
            // Jika tidak valid, tampilkan pesan error
            echo "\nError: qty harus berupa bilangan bulat positif.";
        }
    }

    // Getter untuk mendapatkan nilai code_book
    public function getCodeBook() {
        return $this->code_book; // Mengembalikan kode buku
    }

    // Getter untuk mendapatkan nilai qty
    public function getQty() {
        return $this->qty; // Mengembalikan jumlah buku
    }

    // Getter opsional untuk mendapatkan nama buku
    public function getName() {
        return $this->name; // Mengembalikan nama buku
    }
}

// Contoh penggunaan:
$book1 = new Book("AB12", "Pemrograman PHP", 10); // Membuat objek Book dengan data valid
echo "Buku 1";
echo "\nKode Buku: " . $book1->getCodeBook();       // Menampilkan kode buku
echo "\nNama Buku: " . $book1->getName();         // Menampilkan nama buku
echo "\nJumlah: " . $book1->getQty();             // Menampilkan jumlah buku

// Contoh error:
echo "\n\nBuku 2";
$book2 = new Book("ab12", "Salah Format", -5); // Kode dan jumlah tidak valid, akan muncul pesan error
