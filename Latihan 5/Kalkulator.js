//membuat Arrow function bernama calculate yang menerima dua parameter:
const calculate = (operator, ...numbers) => { //yaitu operator(+,-,*,/,%) dan number (spread operator, menangkap semua angka yang diberikan)
    const expression = numbers.join(` ${operator} `); //membiuat string bernama expression dari angka dan operator, digunakan untuk menggabungkan number dan operator
    let result; //variabel result untuk menyimpan hasil penggabungan angka dan operator.

    switch (operator) { //digunakan untuk mengecek jenis operator yang digunakan
        case '+': result = numbers.reduce((a, b) => a + b); break; //jika operasi nya +, maka menjumlahkan hasil pertambahan semua angka dalam numbers(reduce).
        case '-': result = numbers.reduce((a, b) => a - b); break; //jika operasi nya -, maka menjumlahkan hasil perkurangan semua angka dalam numbers(reduce).
        case '*': result = numbers.reduce((a, b) => a * b); break; //jika operasi nya *, maka menjumlahkan hasil perkalian semua angka dalam numbers(reduce). 
        case '/': result = numbers.reduce((a, b) => a / b); break; //jika operasi nya -, maka menjumlahkan hasil pembagian semua angka dalam numbers(reduce).
        case '%': result = numbers.reduce((a, b) => a % b); break; //jika operasi nya -, maka menjumlahkan hasil modulus semua angka dalam numbers(reduce).
        default: result = 'Operator tidak valid'; //jika tidak ada operasi selain case diatas, maka akan menampilkan ini
    }
    console.log(`${expression} = ${result}`); //Menampilkan ekspresi dan hasil perhitungan.
};
console.log ('=========================');
console.log ('  Kalkulator sederhana'); //membuat judul 
console.log ('=========================');

console.log ('Penjumlahan');
calculate('+', 10, 5, 8, 9); //memanggil fungsi calculate untuk menghitung hasil pertambahan
console.log ('-------------------------');
console.log ('Perkurangan');
calculate('-', 20, 5, 3); //memanggil fungsi calculate untuk menghitung hasil perkurangan
console.log ('-------------------------');
console.log('Perkalian');
calculate('*', 2, 3, 4); //memanggil fungsi calculate untuk menghitung hasil perkalian
console.log ('-------------------------');
console.log('Pembagian');
calculate('/', 100, 2, 5); //memanggil fungsi calculate untuk menghitung hasil pembagian
console.log ('-------------------------');
console.log('Modulus');
calculate('%', 12, 7 ); //memanggil fungsi calculate untuk menghitung hasil modulus
console.log ('=========================');
