/* jshint esversion: 6 */
document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // 1. Khai báo các biến (DOM Elements)
    const loginBtn = document.getElementById('login-btn');
    const registerBtn = document.getElementById('register-btn');
    const modal = document.getElementById('auth-modal');
    const closeModal = document.getElementById('close-modal');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    
    // Popup Banner elements
    const popupbanner = document.getElementById('popup-banner');
    const popupimg = document.getElementById('popup-banner-img');
    const closePopup = document.getElementById('close-popup');

    // 2. Hàm mở Modal và hiển thị Form tương ứng
    function openModal(formType) {
        if (!modal) return; // Kiểm tra nếu modal không tồn tại thì dừng
        
        modal.classList.remove('hidden');

        if (formType === 'login' && loginForm && registerForm) {
            // Hiển thị Form Đăng nhập
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
        } else if (formType === 'register' && loginForm && registerForm) {
            // Hiển thị Form Đăng ký
            registerForm.classList.remove('hidden');
            loginForm.classList.add('hidden');
        }
    }

    // 3. Hàm đóng Modal
    function closeAuthModal() {
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    // 4. Hàm đóng Popup Banner
    function closePopupBanner() {
        if (popupimg) popupimg.classList.add('hidden');
        if (popupbanner) popupbanner.classList.add('hidden');
    }

    // 5. Gắn trình lắng nghe sự kiện (Event Listeners) - CÓ KIỂM TRA TỒN TẠI

    // Khi click Đăng nhập
    if (loginBtn) {
        loginBtn.addEventListener('click', () => {
            openModal('login');
        });
    }

    // Khi click Đăng ký
    if (registerBtn) {
        registerBtn.addEventListener('click', () => {
            openModal('register');
        });
    }

    // Khi click nút đóng (x) Modal
    if (closeModal) {
        closeModal.addEventListener('click', closeAuthModal);
    }

    // Khi click ra ngoài Modal, đóng Modal
    if (modal) {
        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeAuthModal();
            }
        });
    }

    // Khi click nút đóng (x) Popup Banner
    if (closePopup) {
        closePopup.addEventListener('click', closePopupBanner);
    }
});