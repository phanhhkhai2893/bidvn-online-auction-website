<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Slider</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#FBC02D', // Màu vàng thương hiệu
                    }
                }
            }
        }
    </script>
    <style>
        /* Hiệu ứng fade nhẹ nhàng khi chuyển slide */
        .fade-transition {
            animation-name: fade;
            animation-duration: 1.0s;
        }
        @keyframes fade {
            from {opacity: .4} 
            to {opacity: 1}
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="relative max-w-[1200px] mx-auto mt-6 overflow-hidden md:rounded-2xl shadow-xl h-64 md:h-[600px] group bg-gray-200">

        <?php
            // 1. Lấy dữ liệu từ DB và lưu vào mảng để dùng lại 2 lần (cho ảnh và cho chấm tròn)
            $query = "SELECT * FROM slider ORDER BY hinhanh";
            $result_images = mysqli_query($kn->con, $query);
            $slidesData = [];
            while ($row = mysqli_fetch_assoc($result_images)) {
                $slidesData[] = $row;
            }
            $totalSlides = count($slidesData);
        ?>

        <div id="slider-track" class="h-full w-full relative">
            <?php foreach ($slidesData as $index => $row): ?>
                <div class="mySlides fade-transition hidden absolute inset-0 w-full h-full">
                    <img src="assets/imgs/slider/<?PHP echo $row['hinhanh'] ?>" 
                         alt="Banner <?php echo $index + 1; ?>" 
                         class="w-full h-full object-cover md:rounded-2xl">
                     </div>
            <?php endforeach; ?>
        </div>


        <button onclick="plusSlides(-1)" 
                class="absolute top-1/2 left-4 -translate-y-1/2 z-10 w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-black/30 text-white hover:bg-brand hover:text-black transition-all duration-300 backdrop-blur-sm hidden group-hover:flex">
            <i class="fas fa-chevron-left"></i>
        </button>

        <button onclick="plusSlides(1)" 
                class="absolute top-1/2 right-4 -translate-y-1/2 z-10 w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full bg-black/30 text-white hover:bg-brand hover:text-black transition-all duration-300 backdrop-blur-sm hidden group-hover:flex">
            <i class="fas fa-chevron-right"></i>
        </button>


        <div class="absolute bottom-4 left-1/2 z-10 flex -translate-x-1/2 space-x-2">
            <?php for ($i = 0; $i < $totalSlides; $i++): ?>
                <button onclick="currentSlide(<?php echo $i + 1; ?>)" 
                        class="dot-indicator w-3 h-3 rounded-full bg-white/50 hover:bg-brand transition-all duration-300"></button>
            <?php endfor; ?>
        </div>

    </div>


    <script>
        let slideIndex = 1;
        // Tự động chạy slider sau 5 giây
        let autoSlideInterval = setInterval(() => { plusSlides(1); }, 5000);

        showSlides(slideIndex);

        // Hàm cho nút Next/Prev
        function plusSlides(n) {
            clearInterval(autoSlideInterval); // Reset thời gian tự động khi người dùng bấm
            showSlides(slideIndex += n);
            autoSlideInterval = setInterval(() => { plusSlides(1); }, 5000); // Chạy lại tự động
        }

        // Hàm cho chấm tròn
        function currentSlide(n) {
            clearInterval(autoSlideInterval);
            showSlides(slideIndex = n);
            autoSlideInterval = setInterval(() => { plusSlides(1); }, 5000);
        }

        // Hàm hiển thị logic chính
        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot-indicator");

            // Logic vòng lặp (cuối về đầu, đầu về cuối)
            if (n > slides.length) {slideIndex = 1}    
            if (n < 1) {slideIndex = slides.length}

            // Ẩn tất cả slides
            for (i = 0; i < slides.length; i++) {
                slides[i].classList.add("hidden");  
            }

            // Reset style của tất cả chấm tròn
            for (i = 0; i < dots.length; i++) {
                dots[i].classList.remove("bg-brand", "w-6");
                dots[i].classList.add("bg-white/50");
            }

            // Hiện slide hiện tại
            slides[slideIndex-1].classList.remove("hidden");  
            
            // Highlight chấm tròn hiện tại (Thêm màu vàng và làm dài ra một chút)
            dots[slideIndex-1].classList.remove("bg-white/50");
            dots[slideIndex-1].classList.add("bg-brand", "w-6");
        }
    </script>

</body>
</html>