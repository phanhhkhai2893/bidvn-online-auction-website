# 🛒 BIDVN - Online Auction Platform (Website Đấu giá Trực tuyến)

![PHP](https://img.shields.io/badge/Backend-PHP-blue)
![MySQL](https://img.shields.io/badge/Database-MySQL-orange)
![Bootstrap](https://img.shields.io/badge/Frontend-Bootstrap-purple)

## 1. Giới thiệu (Introduction)
**BIDVN** là nền tảng đấu giá trực tuyến B2C, nơi kết nối giữa doanh nghiệp tổ chức đấu giá và người tiêu dùng. [cite_start]Dự án giải quyết bài toán định giá cho các sản phẩm đặc thù (đồ cổ, hàng hiếm) thông qua cơ chế đấu giá công khai và minh bạch[cite: 68, 72].

[cite_start]Dự án được xây dựng nhằm mục đích học tập, áp dụng kiến thức **PHP thuần** và **Cơ sở dữ liệu MySQL** để xử lý các nghiệp vụ thương mại điện tử phức tạp như: logic đặt giá thầu, quản lý thời gian thực và giỏ hàng[cite: 74].

## 2. Tính năng Chính (Key Features)

### 👤 Phân hệ Người dùng (Bidder)
* **Đấu giá thời gian thực:** Xem sản phẩm và đặt giá thầu (Bidding). [cite_start]Hệ thống tự động kiểm tra quy tắc: *Giá đặt > Giá hiện tại + Bước giá*[cite: 274, 442].
* [cite_start]**Countdown Timer:** Đồng hồ đếm ngược thời gian kết thúc phiên đấu giá[cite: 615].
* [cite_start]**Quản lý tài khoản:** Đăng ký, đăng nhập, xem lịch sử đấu giá cá nhân[cite: 122, 126].
* [cite_start]**Giỏ hàng & Thanh toán:** Tự động thêm sản phẩm thắng cuộc vào giỏ hàng và tạo hóa đơn[cite: 445, 447].

### 🛠 Phân hệ Quản trị (Admin Dashboard)
* [cite_start]**Thống kê (Analytics):** Báo cáo tổng quan doanh thu, số lượng người dùng mới, top sản phẩm có lượt đấu giá cao nhất[cite: 681, 703].
* [cite_start]**Quản lý Sản phẩm:** Thêm/Sửa/Xóa sản phẩm, thiết lập giá khởi điểm, bước giá và thời gian đấu giá[cite: 878, 888].
* [cite_start]**Quản lý Người dùng:** Theo dõi danh sách thành viên, khóa tài khoản vi phạm[cite: 802].

## 3. Công nghệ sử dụng (Tech Stack)
* **Backend:** PHP (Native).
* [cite_start]**Database:** MySQL (Thiết kế 8 bảng dữ liệu: Người dùng, Sản phẩm, Lịch sử đấu giá, Hóa đơn...)[cite: 321, 386].
* [cite_start]**Frontend:** HTML5, CSS3, JavaScript, Bootstrap (Responsive Design)[cite: 106].
* **Server Environment:** XAMPP (Apache).

## 4. Cài đặt & Chạy dự án (Installation)
Để chạy dự án này trên máy local, vui lòng làm theo các bước sau:

1.  **Clone repository:**
    ```bash
    git clone [https://github.com/username-cua-ban/bidvn-auction-php.git](https://github.com/username-cua-ban/bidvn-auction-php.git)
    ```
2.  **Cấu hình Database:**
    * Mở **XAMPP Control Panel**, khởi động Apache và MySQL.
    * Truy cập `http://localhost/phpmyadmin`.
    * Tạo database mới tên là `bidvn_db`.
    * Import file `database/bidvn_db.sql` vào database vừa tạo.
3.  **Cấu hình kết nối:**
    * Mở file `config/db_connect.php` (hoặc file kết nối tương ứng).
    * Kiểm tra thông tin: `$servername = "localhost"`, `$username = "root"`, `$password = ""`, `$dbname = "bidvn_db"`.
4.  **Chạy dự án:**
    * Copy thư mục dự án vào `C:/xampp/htdocs/`.
    * Mở trình duyệt và truy cập: `http://localhost/bidvn-auction-php/`.

## 5. Cơ sở dữ liệu (Database Design)
Dự án sử dụng mô hình quan hệ (ERD) với các bảng chính:
* [cite_start]**SAN_PHAM:** Lưu thông tin giá khởi điểm, giá hiện tại, thời gian bắt đầu/kết thúc[cite: 402].
* [cite_start]**LICH_SU_DAU_GIA:** Lưu vết các lượt đặt giá của người dùng để đảm bảo minh bạch[cite: 410].
* [cite_start]**HOA_DON & GIO_HANG:** Xử lý nghiệp vụ sau khi thắng đấu giá[cite: 412, 418].

## 6. Hướng phát triển (Future Improvements)
* [cite_start]Nâng cấp tính năng **Real-time** bằng công nghệ WebSocket để cập nhật giá tức thì mà không cần tải lại trang[cite: 936].
* [cite_start]Tích hợp cổng thanh toán trực tuyến (VNPay/MoMo)[cite: 937].

---
**Author:** Phan Hoàng Huy Khải
**Contact:** phhkhai2893@gmail.com