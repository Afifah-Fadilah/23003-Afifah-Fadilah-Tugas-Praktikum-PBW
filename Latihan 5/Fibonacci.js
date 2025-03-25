function fibonacci(n) { //Membuat fungsi fibonacci yang menerima parameter n, yaitu jumlah angka Fibonacci yang ingin dihasilkan.
    let fibSeries = [0, 1]; //Membuat array fibSeries dengan dua elemen pertama 0 dan 1, karena deret Fibonacci selalu dimulai dari angka ini.
    
    //Perulangan untuk Menghitung Fibonacci
    for (let i = 2; i < n; i++) { //Perulangan dimulai dari i = 2(karena 2 angka pertama sudah ada dlm array) berjalan sampai i < n
        fibSeries.push(fibSeries[i - 1] + fibSeries[i - 2]); //Menambahkan angka Fibonacci baru ke dalam fibSeries, diperoleh dari penjumlahan dua angka sebelumnya
    }
    return fibSeries.slice(0, n); //Mengembalikan deret Fibonacci sebanyak n angka.
}

// Contoh penggunaan
let jumlahAngka = 15; //misal disini kita isi n = 15, maka
console.log(fibonacci(jumlahAngka)); //akan mencetak 15 angka Fibonacci pertama.