function showSimpleToast(message = "Hành động đã được thực hiện thành công!", title = "Thông báo") {
    const container = document.getElementById('toast-container');
    if (!container) return; 

    // 1. Tạo Toast
    const toast = document.createElement('div');
    toast.classList.add('toast-message');

    // Cấu trúc mới: Tiêu đề (Toast Title) và Nội dung (Toast Content)
    toast.innerHTML = `
        <div class="toast-title">${title}</div>
        <div class="toast-content">${message}</div>
    `;

    // 2. Thêm vào container và hiển thị
    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.add('show');
    }, 10);

    // 3. Tự động xóa sau 3 giây (3000ms)
    // (Giữ nguyên logic setTimeout và transitionend)
    setTimeout(() => {
        toast.classList.remove('show');
        toast.addEventListener('transitionend', () => {
            toast.remove();
        });
    }, 3000);

    // Click để xóa ngay
    toast.addEventListener('click', () => {
        // ... (Giữ nguyên logic click) ...
        toast.classList.remove('show');
        toast.addEventListener('transitionend', () => {
            toast.remove();
        });
    });
}