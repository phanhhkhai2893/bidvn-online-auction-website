<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu BIDVN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#FBC02D',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-gray-50 font-sans text-gray-900">

    <div id="gioithieu" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative">
            
            <div class="absolute -top-10 -right-10 text-gray-50 opacity-50 transform rotate-12 pointer-events-none">
                <i class="fas fa-gavel text-[200px]"></i>
            </div>

            <div class="p-8 md:p-12 relative z-10">
                
                <div class="text-center mb-8">
                    <span class="text-brand font-bold tracking-widest text-xs uppercase mb-2 block">Về chúng tôi</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-4">
                        BIDVN - Đấu Giá Của Người Việt
                    </h2>
                    <h4 class="text-lg text-gray-500 font-medium max-w-2xl mx-auto">
                        Nền tảng đấu giá thế hệ mới, nơi bạn làm chủ mức giá!
                    </h4>
                </div>

                <div id="intro-content" class="relative overflow-hidden transition-all duration-500 max-h-[250px]">
                    
                    <div class="prose max-w-none text-gray-600 leading-relaxed space-y-6">
                        <p class="text-lg">
                            <span class="font-bold text-gray-800">BIDVN</span> tự hào là nền tảng đấu giá trực tuyến đột phá, được thiết kế để mang đến trải nghiệm mua sắm thông minh, tiết kiệm và đầy kịch tính cho người tiêu dùng Việt Nam. Chúng tôi không chỉ là một trang web đấu giá, mà còn là cánh cửa giúp bạn sở hữu các sản phẩm chất lượng với mức giá không tưởng.
                        </p>

                        <div class="grid md:grid-cols-2 gap-8 mt-6">
                            <div class="bg-yellow-50/50 p-6 rounded-xl border border-yellow-100">
                                <h5 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-bolt text-brand mr-2"></i> Tối ưu hóa thời gian
                                </h5>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2 text-sm"></i>
                                        <span><strong>Quy trình đơn giản:</strong> Chỉ vài cú nhấp chuột để tham gia đấu giá điện tử, thời trang, đồ sưu tầm.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2 text-sm"></i>
                                        <span><strong>Tiết kiệm tối đa:</strong> Cơ chế đấu giá ngược và đấu giá kín giúp mua hàng giá thấp hơn thị trường.</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100">
                                <h5 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-gamepad text-blue-500 mr-2"></i> Trải nghiệm cảm xúc
                                </h5>
                                <ul class="space-y-3">
                                    <li class="flex items-start">
                                        <i class="fas fa-trophy text-yellow-500 mt-1 mr-2 text-sm"></i>
                                        <span><strong>Cảm giác chiến thắng:</strong> Hồi hộp từng giây cuối và vỡ òa khi thắng cuộc.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-box-open text-yellow-500 mt-1 mr-2 text-sm"></i>
                                        <span><strong>Đa dạng sản phẩm:</strong> Bộ sưu tập độc đáo, cập nhật liên tục.</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-shield-alt text-yellow-500 mt-1 mr-2 text-sm"></i>
                                        <span><strong>Minh bạch tuyệt đối:</strong> Lịch sử đấu giá công khai, công bằng.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="fade-overlay" class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white via-white/80 to-transparent pointer-events-none"></div>
                </div>

                <div class="text-center mt-6">
                    <button id="toggle-btn" class="group inline-flex items-center px-6 py-2.5 border-2 border-gray-200 rounded-full text-sm font-bold text-gray-600 hover:border-brand hover:text-brand hover:bg-yellow-50 transition-all duration-300 focus:outline-none">
                        <span id="btn-text">Tìm hiểu thêm</span>
                        <i id="btn-icon" class="fas fa-chevron-down ml-2 transform transition-transform duration-300"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('toggle-btn');
        const content = document.getElementById('intro-content');
        const overlay = document.getElementById('fade-overlay');
        const btnText = document.getElementById('btn-text');
        const btnIcon = document.getElementById('btn-icon');

        let isExpanded = false;

        toggleBtn.addEventListener('click', () => {
            isExpanded = !isExpanded;

            if (isExpanded) {
                // Mở rộng
                content.style.maxHeight = content.scrollHeight + "px"; // Lấy chiều cao thực tế
                overlay.classList.add('opacity-0'); // Ẩn lớp mờ
                btnText.innerText = "Thu gọn nội dung";
                btnIcon.classList.add('rotate-180');
            } else {
                // Thu gọn
                content.style.maxHeight = "250px"; // Chiều cao mặc định
                overlay.classList.remove('opacity-0'); // Hiện lớp mờ
                btnText.innerText = "Tìm hiểu thêm";
                btnIcon.classList.remove('rotate-180');
                
                // Cuộn nhẹ lên lại đầu phần giới thiệu nếu đang ở quá sâu (Optional)
                // document.getElementById('gioithieu').scrollIntoView({behavior: 'smooth'});
            }
        });
    </script>

</body>
</html>