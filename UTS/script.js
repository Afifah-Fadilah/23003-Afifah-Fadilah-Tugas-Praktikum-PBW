// membuat animasi teks berjalan 
document.addEventListener("DOMContentLoaded", function () {
    // Array kalimat yang akan diketik secara bergantian
    const phrases = [
        "Homemade Happiness in Every Scoop!! ",
        "Fresh, Creamy & Delicious! ",
        "100% Homemade & Natural! "
    ];

    const typingElement = document.getElementById("typing-text"); // elemen teks yang akan diberi efek ketik
    let currentPhrase = 0; // index kalimat saat ini
    let currentChar = 0; // posisi karakter saat ini dalam kalimat
    let isDeleting = false; // status ketik (hapus atau tulis)

    function typeEffect() {
        const text = phrases[currentPhrase];
        if (isDeleting) {
            typingElement.textContent = text.substring(0, currentChar--); // hapus karakter satu per satu
        } else {
            typingElement.textContent = text.substring(0, currentChar++); // ketik karakter satu per satu
        }

        if (!isDeleting && currentChar === text.length) { // kondisi jika selesai mengetik kalimat, tunggu 1.5 detik lalu mulai hapus
            isDeleting = true;
            setTimeout(typeEffect, 1500); // tunggu sebelum hapus
        } else if (isDeleting && currentChar === 0) { // jika selesai menghapus, pindah ke kalimat berikutnya
            isDeleting = false;
            currentPhrase = (currentPhrase + 1) % phrases.length; // loop kalimat
            setTimeout(typeEffect, 400); // jeda antar frasa
        } else { // lanjut ketik atau hapus dengan kecepatan berbeda
            setTimeout(typeEffect, isDeleting ? 50 : 80);
        }
    }

    typeEffect(); // mulai efek ketik saat DOM siap
});

// Membuat kondisi tampilan untuk navigasi pada mobile
document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    // Saat tombol menu mobile diklik, tampilkan/sembunyikan menu
    mobileMenuButton.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
    
    // Smooth Scroll for Navigation Links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 50, // scroll ke posisi elemen dikurangi 50px
                    behavior: 'smooth' // animasi scroll halus
                });
                
                mobileMenu.classList.add('hidden'); // tutup menu mobile setelah klik link
            }
        });
    });
    
    // Highlight link navigasi aktif saat scroll halaman
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');
    
    window.addEventListener('scroll', function() {
        let current = '';
        // Cek section mana yang sedang di-view
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (pageYOffset >= sectionTop - 100) {
                current = section.getAttribute('id');
            }
        });
        // Update class active pada link yang sesuai dengan section aktif
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });
    
    // Back to Top Button
    const backToTopButton = document.getElementById('back-to-top');
    // Tampilkan tombol jika scroll lebih dari 300px
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.remove('hidden');
        } else {
            backToTopButton.classList.add('hidden');
        }
    });
    // Scroll ke atas saat tombol diklik
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Efek goyang pada tombol promo saat mouse hover
    const promoButtons = document.querySelectorAll('.promo-btn');
    
    promoButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.classList.add('shake'); // tambahkan class animasi shake
            setTimeout(() => {
                this.classList.remove('shake'); // hapus shake setelah 0.5 detik
            }, 500);
        });
    });
    
    
    // Ambil elemen form saran berdasarkan id 'suggestion-form'
    const suggestionForm = document.getElementById('suggestion-form');

    suggestionForm.addEventListener('submit', function(e) {
        e.preventDefault(); // cegah submit default

        // Ambil nilai input dan hapus spasi di awal/akhir dengan trim()
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const suggestion = document.getElementById('suggestion').value.trim();
        const rating = document.querySelector('input[name="rating"]:checked'); // Dapatkan rating yang dipilih

        // Cek apakah semua field sudah diisi
        if (!name || !email || !suggestion || !rating) {
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Harap lengkapi semua field sebelum mengirim!',
                confirmButtonText: 'Oke',
                confirmButtonColor: 'rgb(236, 72, 154)',
                allowOutsideClick: false,        // Tidak bisa klik luar popup untuk tutup
                allowEscapeKey: false,           // Tidak bisa tekan ESC untuk tutup popup
                scrollbarPadding: false          // FIX supaya halaman tidak bergeser saat popup muncul
            });
            return; // Stop eksekusi submit form kalau validasi gagal
        }

        // Jika valid, tampilkan pesan sukses
        Swal.fire({
            icon: 'success',
            title: 'Terima Kasih!',
            text: 'Kami sangat menghargai saran dan masukan Anda.',
            confirmButtonText: 'Sama-sama',
            confirmButtonColor: 'rgb(236, 72, 154)',
            allowOutsideClick: false,        // Tidak bisa klik luar popup untuk tutup
            allowEscapeKey: false,           // Tidak bisa tekan ESC untuk tutup popup
            scrollbarPadding: false          // FIX supaya halaman tidak bergeser saat popup muncul
        });

        // Reset form
        this.reset();
    });


// Init awal dengan animasi tertunda
document.querySelectorAll('.product-card').forEach((element, index) => {
    element.style.transitionDelay = `${index * 150}ms`; // Set jeda transisi bertingkat 150ms per elemen
});

    // Scroll Animation
    const animateOnScroll = function() {
        // Pilih semua elemen yang akan dianimasikan saat scroll
        const elements = document.querySelectorAll('.product-card, .promo-card, .testimonial-slide, form');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3; // Titik trigger animasi (sekitar 77% dari viewport bawah)
            
            if (elementPosition < screenPosition) {
                // Jika elemen sudah terlihat di viewport, ubah kelas untuk tampil dengan animasi
                element.classList.add('opacity-100', 'translate-y-0'); // Opacity penuh dan posisi normal
                element.classList.remove('opacity-0', 'translate-y-10'); // Hapus kelas awal yang tersembunyi dan bergeser
            }
        });
    };
    
    // Inisialisasi elemen dengan state awal tersembunyi dan bergeser ke bawah
    document.querySelectorAll('.product-card, .promo-card, .testimonial-slide, form').forEach(element => {
        element.classList.add('opacity-0', 'translate-y-10', 'transition-all', 'duration-500', 'ease-out');
        // Mulai dengan transparan, bergeser bawah 10px, dan atur animasi transisi selama 500ms dengan easing
    });
    
    window.addEventListener('scroll', animateOnScroll); // Jalankan fungsi animasi saat halaman discroll
    animateOnScroll(); // Jalankan sekali saat load halaman supaya elemen yang sudah terlihat langsung tampil
});


    document.addEventListener('DOMContentLoaded', function() { // Tunggu halaman selesai dimuat
        const slider = document.querySelector('.testimonial-slider');
        const slidesContainer = slider.querySelector('.testimonial-slides');
        const slides = slider.querySelectorAll('.testimonial-slide');
        const prevBtn = slider.querySelector('.testimonial-prev');
        const nextBtn = slider.querySelector('.testimonial-next');
        const dots = document.querySelectorAll('.testimonial-dot');
        
        const slideCount = slides.length - 2; // Jumlah slide asli (exclude clone untuk looping)
        let currentIndex = 1;  // Index slide aktif, mulai dari 1 karena ada clone
        let isTransitioning = false;  // Flag agar animasi tidak tumpang tindih
        let autoSlideInterval;  // Variabel untuk menyimpan interval auto slide
        
        // Set posisi awal slider ke slide pertama yang asli
        slidesContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        // Fungsi mulai auto slide setiap 5 detik
        function startAutoSlide() {
            autoSlideInterval = setInterval(() => {
                goToNextSlide();
            }, 5000); // Ganti slide tiap 5 detik
        }
        
        // Fungsi berhenti auto slide (saat hover)
        function stopAutoSlide() {
            clearInterval(autoSlideInterval);
        }

        // Fungsi untuk pindah ke slide berikutnya
        function goToNextSlide() {
            if (isTransitioning) return; // Jika animasi sedang berjalan, tidak lanjut
            
            isTransitioning = true;  // Set flag animasi berjalan
            currentIndex++;  // Naikkan index slide
            updateSlider();  // Update posisi slider
            
            // Jika sudah sampai slide clone terakhir (looping), langsung reset ke slide asli pertama
            if (currentIndex === slideCount + 1) {
                setTimeout(() => {
                    slidesContainer.style.transition = 'none';  // Matikan transisi
                    currentIndex = 1;  // Reset ke slide pertama
                    slidesContainer.style.transform = `translateX(-${currentIndex * 100}%)`;  // Geser posisi
                    setTimeout(() => {
                        slidesContainer.style.transition = 'transform 500ms ease-in-out';  // Hidupkan lagi transisi
                    }, 20);
                }, 500);
            }
            
            updateDots();  // Update indikator dot
        }
        
        // Fungsi untuk pindah ke slide sebelumnya
        function goToPrevSlide() {
            if (isTransitioning) return;
            
            isTransitioning = true;
            currentIndex--; // Turunkan index slide
            updateSlider();
            
            // Jika sudah sampai slide clone pertama (looping), langsung reset ke slide asli terakhir
            if (currentIndex === 0) {
                setTimeout(() => {
                    slidesContainer.style.transition = 'none';  // Matikan transisi
                    currentIndex = slideCount;  // Reset ke slide terakhir
                    slidesContainer.style.transform = `translateX(-${currentIndex * 100}%)`;  // Geser posisi
                    setTimeout(() => {
                        slidesContainer.style.transition = 'transform 500ms ease-in-out';  // Hidupkan lagi transisi
                    }, 20);
                }, 500);
            }
            updateDots();  // Update indikator dot
        }
        
        // Fungsi update posisi slider berdasarkan currentIndex
        function updateSlider() {
            slidesContainer.style.transform = `translateX(-${currentIndex * 100}%)`;
            
            // Reset flag isTransitioning saat animasi selesai
            slidesContainer.addEventListener('transitionend', () => {
                isTransitioning = false;
            }, { once: true });
        }
        
        // Fungsi update dot navigasi aktif
        function updateDots() {
            const dotIndex = (currentIndex - 1) % slideCount;  // Hitung dot yang aktif
            dots.forEach((dot, index) => {
                if (index === dotIndex) {
                    dot.classList.add('bg-pink-600');  // Beri warna aktif
                    dot.classList.remove('bg-pink-300');  // Hilangkan warna non-aktif
                } else {
                    dot.classList.remove('bg-pink-600');  // Hilangkan warna aktif
                    dot.classList.add('bg-pink-300');  // Beri warna non-aktif
                }
            });
        }
        
        // Event listener tombol next
        nextBtn.addEventListener('click', () => {
            stopAutoSlide();  // Hentikan auto slide saat manual klik
            goToNextSlide();  // Pindah ke slide berikutnya
            startAutoSlide();  // Mulai auto slide lagi
        });
        
        // Event listener tombol prev
        prevBtn.addEventListener('click', () => {
            stopAutoSlide();  // Hentikan auto slide saat manual klik
            goToPrevSlide();  
            startAutoSlide();  
        });
        
        // Event listener untuk klik dot navigasi
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                stopAutoSlide();  // Hentikan auto slide
                currentIndex = index + 1;  // Set index sesuai dot yang diklik
                updateSlider();  // Update slider ke posisi yang dipilih
                updateDots();  // Update warna dot
                startAutoSlide();  
            });
        });
        
        // Mulai auto sliding saat halaman load
        startAutoSlide();
        
        // Pause auto slide saat mouse hover slider
        slider.addEventListener('mouseenter', stopAutoSlide);
        slider.addEventListener('mouseleave', startAutoSlide);
    });


    
document.addEventListener('DOMContentLoaded', function() {
    // Ambil semua elemen label bintang rating
    const stars = document.querySelectorAll('.rating-stars .star-label');
    // Ambil semua input radio di dalam rating-stars (untuk nilai rating yang dipilih)
    const radioInputs = document.querySelectorAll('.rating-stars input[type="radio"]');
    
    // Ketika mouse hover pada setiap bintang
    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            // Ambil nilai rating dari atribut data-rating pada ikon i
            const rating = this.querySelector('i').getAttribute('data-rating');
            
            // Reset warna semua bintang jadi abu-abu (warna default)
            document.querySelectorAll('.rating-stars i').forEach(icon => {
                icon.style.color = '#d1d5db'; // abu-abu muda
            });
            
            // Warnai bintang dari 1 sampai rating yang di-hover dengan warna kuning (#fbbf24)
            for (let i = 1; i <= rating; i++) {
                document.querySelector(`.rating-stars i[data-rating="${i}"]`).style.color = '#fbbf24';
            }
        });
        
        // Ketika klik pada bintang
        star.addEventListener('click', function() {
            // Ambil nilai rating dari data-rating pada ikon
            const rating = this.querySelector('i').getAttribute('data-rating');
            // Set input radio dengan id sesuai rating jadi terpilih (checked)
            document.querySelector(`#star${rating}`).checked = true;
        });
    });
    
    // Ketika mouse keluar dari area bintang rating
    document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
        // Cek apakah ada rating yang sudah dipilih (input radio yang checked)
        const selectedRating = document.querySelector('.rating-stars input[type="radio"]:checked');
        
        if (!selectedRating) {
            // Jika belum ada rating yang dipilih, reset semua warna bintang jadi abu-abu
            document.querySelectorAll('.rating-stars i').forEach(icon => {
                icon.style.color = '#d1d5db';
            });
        } else {
            // Jika sudah ada rating yang dipilih, warnai bintang sampai rating tersebut
            const rating = selectedRating.value;
            document.querySelectorAll('.rating-stars i').forEach(icon => {
                const iconRating = icon.getAttribute('data-rating');
                // Jika nilai rating ikon <= rating yang dipilih, beri warna kuning, kalau tidak abu-abu
                icon.style.color = iconRating <= rating ? '#fbbf24' : '#d1d5db';
            });
        }
    });
});

// efek animasi saat elemen .product-card muncul di layar (viewport)
document.addEventListener("DOMContentLoaded", () => {
  const productCards = document.querySelectorAll('.product-card'); // Ambil semua elemen dengan kelas product-card

  // Buat IntersectionObserver untuk memantau saat elemen muncul di viewport
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) { // Jika elemen terlihat di layar (viewport)
        const card = entry.target; // Elemen yang terlihat
        const index = Array.from(productCards).indexOf(card); // Dapatkan index elemen dalam daftar productCards

        // Atur delay animasi berdasarkan index (buat efek animasi bertahap)
        card.style.setProperty('--delay', `${index * 150}ms`);
        card.classList.add('appeared'); // Tambahkan kelas untuk memicu animasi CSS

        // Hentikan observasi pada elemen ini supaya animasi hanya terjadi sekali
        observer.unobserve(card);
      }
    });
  }, {
    threshold: 0.3, // Animasi baru dimulai saat 30% bagian elemen terlihat di viewport
  });

  // Mulai observasi untuk setiap product card
  productCards.forEach((card) => {
    observer.observe(card);
  });
});
