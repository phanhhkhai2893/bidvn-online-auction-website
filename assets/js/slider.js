

var slideIndex = 1;
var slideInterval;

// Hàm hiển thị slide (chức năng chính)
function showSlides(n) {
  "use strict";
    var i;
    var slides = document.getElementsByClassName("slide");
    var dots = document.getElementsByClassName("dot");
    var container = document.querySelector(".slides-container"); // Lấy container để dịch chuyển
    
    // Nếu chưa có slide, dừng
    if (slides.length === 0) {
        return;
    }

    // Xử lý vòng lặp (looping)
    if (n > slides.length) { 
        slideIndex = 1; 
    }
    if (n < 1) { 
        slideIndex = slides.length; 
    }
    
    // 1. TÍNH TOÁN VỊ TRÍ DỊCH CHUYỂN
    // Vị trí trượt (offset) = (Chỉ số hiện tại - 1) * Chiều rộng 1 slide (100% của wrapper)
    // Ví dụ: Slide 1 (index 1) -> 0 * 100% = 0%
    //         Slide 2 (index 2) -> 1 * 100% = -100% (trượt sang trái)
    //         Slide 3 (index 3) -> 2 * 100% = -200%
    var offset = (slideIndex - 1) * 100;
    
    // 2. ÁP DỤNG HIỆU ỨNG DỊCH CHUYỂN NGANG
    container.style.transform = "translateX(-" + offset + "%)";
    
    // 3. Cập nhật dot (vẫn như cũ)
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    
    if (dots.length > 0) {
        dots[slideIndex - 1].className += " active";
    }
}

// ... Giữ nguyên các hàm changeSlide, currentSlide, autoSlides, startAutoSlide, pauseAutoSlide ...

function changeSlide(n) {
  "use strict";
    pauseAutoSlide(); 
    slideIndex = slideIndex + n;
    showSlides(slideIndex);
    startAutoSlide(); 
}

function currentSlide(n) {
  "use strict";
    pauseAutoSlide();
    slideIndex = n;
    showSlides(slideIndex);
    startAutoSlide();
}

function autoSlides() {
  "use strict";
    slideIndex++;
    showSlides(slideIndex);
}

function startAutoSlide() {
  "use strict";
    clearInterval(slideInterval); 
    slideInterval = setInterval(autoSlides, 5000); 
}

function pauseAutoSlide() {
  "use strict";
    clearInterval(slideInterval);
}


// Khởi tạo slider
window.onload = function() {
  "use strict";
    showSlides(slideIndex);
    startAutoSlide(); 
};