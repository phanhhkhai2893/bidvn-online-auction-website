-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th12 01, 2025 lúc 07:38 PM
-- Phiên bản máy phục vụ: 10.4.28-MariaDB
-- Phiên bản PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `bidvn_database`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chi_tiet_hoa_don`
--

CREATE TABLE `chi_tiet_hoa_don` (
  `soluong` int(11) NOT NULL,
  `thanhtien` float NOT NULL,
  `SoHD` varchar(10) NOT NULL,
  `MaSP` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chi_tiet_hoa_don`
--

INSERT INTO `chi_tiet_hoa_don` (`soluong`, `thanhtien`, `SoHD`, `MaSP`) VALUES
(1, 500000000, 'HD1', 'SP11'),
(1, 3800000, 'HD2', 'SP12'),
(1, 95000000, 'HD3', 'SP13'),
(1, 19500000, 'HD4', 'SP14'),
(1, 16000000, 'HD5', 'SP15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc`
--

CREATE TABLE `danh_muc` (
  `MaDM` varchar(10) NOT NULL,
  `tendm` varchar(30) NOT NULL,
  `mota` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc`
--

INSERT INTO `danh_muc` (`MaDM`, `tendm`, `mota`) VALUES
('DM1', 'Điện thoại', 'Các loại điện thoại thông minh'),
('DM10', 'Khác', 'Danh mục sản phẩm khác'),
('DM2', 'Máy tính', 'Laptop và máy tính để bàn'),
('DM3', 'Đồng hồ', 'Đồng hồ đeo tay và đồng hồ treo tường'),
('DM4', 'Máy ảnh', 'Máy ảnh và thiết bị nhiếp ảnh'),
('DM5', 'Điện tử', 'Thiết bị điện tử gia dụng'),
('DM6', 'Xe cộ', 'Xe máy, xe đạp, phụ tùng'),
('DM7', 'Thời trang', 'Quần áo, giày dép, phụ kiện'),
('DM8', 'Đồ cổ', 'Đồ sưu tầm, đồ cổ quý hiếm'),
('DM9', 'Nghệ thuật', 'Tranh ảnh, tác phẩm nghệ thuật');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danh_muc_con`
--

CREATE TABLE `danh_muc_con` (
  `MaDMCon` varchar(10) NOT NULL,
  `tendm` varchar(30) NOT NULL,
  `mota` varchar(50) NOT NULL,
  `MaDM` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danh_muc_con`
--

INSERT INTO `danh_muc_con` (`MaDMCon`, `tendm`, `mota`, `MaDM`) VALUES
('DMC1', 'Apple', 'Điện thoại iPhone các đời', 'DM1'),
('DMC10', 'Canon', 'Máy ảnh DSLR và Mirrorless Canon', 'DM4'),
('DMC11', 'Sony', 'Máy ảnh Mirrorless Sony Alpha', 'DM4'),
('DMC12', 'Máy ảnh khác', 'Nikon, Flycam, ống kính, thiết bị studio', 'DM4'),
('DMC13', 'Tivi & Loa', 'Tivi thông minh, loa và âm ly', 'DM5'),
('DMC14', 'Thiết bị nhà bếp', 'Nồi chiên, máy hút bụi, lò vi sóng', 'DM5'),
('DMC15', 'Điện tử khác', 'Thiết bị điện tử gia dụng khác', 'DM5'),
('DMC16', 'Ô tô', 'Các dòng xe ô tô đã qua sử dụng', 'DM6'),
('DMC17', 'Xe máy', 'Xe máy phổ thông và xe tay ga', 'DM6'),
('DMC18', 'Xe cộ khác', 'Xe đạp, xe điện và phụ tùng', 'DM6'),
('DMC19', 'Quần áo & Váy đầm', 'Các loại quần áo và trang phục', 'DM7'),
('DMC2', 'Samsung', 'Điện thoại Samsung Galaxy các dòng', 'DM1'),
('DMC20', 'Giày dép', 'Giày thể thao, giày da, sandal', 'DM7'),
('DMC21', 'Thời trang khác', 'Túi xách, thắt lưng, kính mắt, mũ nón', 'DM7'),
('DMC22', 'Gốm sứ cổ', 'Đồ gốm sứ và đồ sứ sưu tầm', 'DM8'),
('DMC23', 'Tiền xu & Giấy tờ', 'Tiền cổ, tem, giấy tờ quý hiếm', 'DM8'),
('DMC24', 'Đồ cổ khác', 'Đồ gỗ, đồ đồng sưu tầm, đồ vật lịch sử', 'DM8'),
('DMC25', 'Tranh ảnh', 'Tranh sơn dầu, màu nước, ký họa', 'DM9'),
('DMC26', 'Điêu khắc', 'Các tác phẩm tượng, điêu khắc', 'DM9'),
('DMC27', 'Nghệ thuật khác', 'Thư pháp, thủ công mỹ nghệ, trang trí', 'DM9'),
('DMC28', 'Sách báo & Truyện', 'Sách báo, tài liệu hiếm, truyện tranh', 'DM10'),
('DMC29', 'Nhạc cụ', 'Đàn, kèn, trống và phụ kiện âm nhạc', 'DM10'),
('DMC3', 'Điện thoại khác', 'Các hãng khác và điện thoại cổ', 'DM1'),
('DMC30', 'Sản phẩm khác', 'Các mặt hàng không thuộc danh mục trên', 'DM10'),
('DMC4', 'Laptop Dell', 'Các dòng máy tính xách tay Dell', 'DM2'),
('DMC5', 'Macbook & iMac', 'Máy tính Apple', 'DM2'),
('DMC6', 'Máy tính khác', 'PC, HP, Asus, linh kiện', 'DM2'),
('DMC7', 'Rolex & Omega', 'Đồng hồ Thụy Sĩ cao cấp', 'DM3'),
('DMC8', 'Casio & Seiko', 'Đồng hồ điện tử và cơ giá bình dân', 'DM3'),
('DMC9', 'Đồng hồ khác', 'Đồng hồ thông minh (Smartwatch) và treo tường', 'DM3');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `gio_hang`
--

CREATE TABLE `gio_hang` (
  `thanhtien` float NOT NULL,
  `MaND` varchar(10) NOT NULL,
  `MaSP` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `gio_hang`
--

INSERT INTO `gio_hang` (`thanhtien`, `MaND`, `MaSP`) VALUES
(310000000, 'ND3', 'SP3'),
(120000000, 'ND6', 'SP7'),
(2400000, 'ND2', 'SP16'),
(39000000, 'ND4', 'SP17'),
(52000000, 'ND5', 'SP18'),
(12500000, 'ND3', 'SP19'),
(12500000, 'ND7', 'SP20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hoa_don`
--

CREATE TABLE `hoa_don` (
  `SoHD` varchar(10) NOT NULL,
  `noidung` varchar(50) NOT NULL,
  `ngaylap` datetime NOT NULL,
  `phuongthuctt` varchar(20) NOT NULL,
  `MaND` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hoa_don`
--

INSERT INTO `hoa_don` (`SoHD`, `noidung`, `ngaylap`, `phuongthuctt`, `MaND`) VALUES
('HD1', 'Thanh toán cho sản phẩm Xe BMW 7 Series Cũ', '2025-11-20 12:30:00', 'Tiền mặt', 'ND3'),
('HD2', 'Thanh toán cho sản phẩm Đồng hồ Apple Watch S5', '2025-11-21 15:00:00', 'Chuyển khoản', 'ND5'),
('HD3', 'Thanh toán cho sản phẩm Túi Da Cá Sấu Hermès', '2025-11-23 09:00:00', 'Momo', 'ND4'),
('HD4', 'Thanh toán cho sản phẩm Laptop Dell XPS 13 i7', '2025-11-24 10:00:00', 'Visa', 'ND3'),
('HD5', 'Thanh toán cho sản phẩm Amply Sansui AU-9900', '2025-11-25 08:30:00', 'Tiền mặt', 'ND7');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lich_su_dau_gia`
--

CREATE TABLE `lich_su_dau_gia` (
  `MaLS` int(11) NOT NULL,
  `gia_hientai` float NOT NULL,
  `thoigian_dau` datetime NOT NULL,
  `MaND` varchar(10) NOT NULL,
  `MaSP` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `lich_su_dau_gia`
--

INSERT INTO `lich_su_dau_gia` (`MaLS`, `gia_hientai`, `thoigian_dau`, `MaND`, `MaSP`) VALUES
(1, 260000000, '2025-01-12 09:00:00', 'ND8', 'SP3'),
(2, 182000000, '2025-10-29 08:00:00', 'ND10', 'SP5'),
(3, 50000000, '2025-10-21 08:00:00', 'ND6', 'SP6'),
(4, 55000000, '2025-10-22 12:00:00', 'ND8', 'SP6'),
(5, 65000000, '2025-10-25 16:00:00', 'ND3', 'SP6'),
(6, 80000000, '2025-10-28 09:30:00', 'ND4', 'SP2'),
(7, 280000000, '2025-01-13 14:30:00', 'ND4', 'SP3'),
(8, 300000000, '2025-01-14 20:00:00', 'ND8', 'SP3'),
(9, 310000000, '2025-01-14 23:55:00', 'ND3', 'SP3'),
(10, 120000000, '2025-02-02 10:00:00', 'ND6', 'SP7'),
(11, 150000000, '2025-10-26 08:30:00', 'ND4', 'SP1'),
(12, 155000000, '2025-10-27 09:00:00', 'ND8', 'SP1'),
(13, 180000000, '2025-10-27 10:00:00', 'ND10', 'SP5'),
(14, 181000000, '2025-10-28 15:00:00', 'ND4', 'SP5'),
(17, 156000000, '2025-12-01 06:05:45', 'ND3', 'SP1'),
(18, 157000000, '2025-12-01 06:11:15', 'ND3', 'SP1'),
(19, 158000000, '2025-12-01 06:12:24', 'ND3', 'SP1'),
(20, 159000000, '2025-12-01 06:13:45', 'ND3', 'SP1'),
(21, 160000000, '2025-12-01 06:18:25', 'ND3', 'SP1'),
(22, 161000000, '2025-12-01 06:46:24', 'ND3', 'SP1'),
(23, 162000000, '2025-12-01 06:51:54', 'ND3', 'SP1'),
(24, 80500000, '2025-12-01 06:53:07', 'ND3', 'SP2'),
(25, 81000000, '2025-12-01 06:53:48', 'ND3', 'SP2'),
(26, 163000000, '2025-12-01 06:54:28', 'ND3', 'SP1'),
(27, 164000000, '2025-12-01 06:56:10', 'ND3', 'SP1'),
(28, 165000000, '2025-12-01 07:09:13', 'ND4', 'SP1'),
(29, 166000000, '2025-12-01 07:10:36', 'ND4', 'SP1'),
(30, 450000000, '2025-11-20 10:00:00', 'ND2', 'SP11'),
(31, 470000000, '2025-11-20 10:05:00', 'ND4', 'SP11'),
(32, 500000000, '2025-11-20 10:10:00', 'ND3', 'SP11'),
(33, 3000000, '2025-11-21 09:00:00', 'ND7', 'SP12'),
(34, 3500000, '2025-11-21 09:05:00', 'ND4', 'SP12'),
(35, 3800000, '2025-11-21 09:10:00', 'ND5', 'SP12'),
(36, 80000000, '2025-11-22 14:00:00', 'ND2', 'SP13'),
(37, 85000000, '2025-11-22 14:05:00', 'ND7', 'SP13'),
(38, 95000000, '2025-11-22 14:10:00', 'ND4', 'SP13'),
(39, 18000000, '2025-11-23 11:00:00', 'ND5', 'SP14'),
(40, 19000000, '2025-11-23 11:05:00', 'ND2', 'SP14'),
(41, 19500000, '2025-11-23 11:10:00', 'ND3', 'SP14'),
(42, 15000000, '2025-11-24 08:00:00', 'ND4', 'SP15'),
(43, 15500000, '2025-11-24 08:05:00', 'ND5', 'SP15'),
(44, 16000000, '2025-11-24 08:10:00', 'ND7', 'SP15'),
(45, 2000000, '2025-11-25 14:00:00', 'ND3', 'SP16'),
(46, 2200000, '2025-11-25 14:05:00', 'ND7', 'SP16'),
(47, 2400000, '2025-11-25 14:10:00', 'ND2', 'SP16'),
(48, 35000000, '2025-11-26 16:00:00', 'ND5', 'SP17'),
(49, 37000000, '2025-11-26 16:05:00', 'ND3', 'SP17'),
(50, 39000000, '2025-11-26 16:10:00', 'ND4', 'SP17'),
(51, 45000000, '2025-11-27 10:00:00', 'ND3', 'SP18'),
(52, 48000000, '2025-11-27 10:05:00', 'ND4', 'SP18'),
(53, 52000000, '2025-11-27 10:10:00', 'ND5', 'SP18'),
(54, 11000000, '2025-11-28 09:00:00', 'ND7', 'SP19'),
(55, 12000000, '2025-11-28 09:05:00', 'ND5', 'SP19'),
(56, 12500000, '2025-11-28 09:10:00', 'ND3', 'SP19'),
(57, 10000000, '2025-11-29 15:00:00', 'ND4', 'SP20'),
(58, 11500000, '2025-11-29 15:05:00', 'ND2', 'SP20'),
(59, 12500000, '2025-11-29 15:10:00', 'ND7', 'SP20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `MaND` varchar(10) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(8) NOT NULL,
  `hoten` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `sdt` varchar(10) NOT NULL,
  `diachi` varchar(100) NOT NULL,
  `avarta` varchar(30) NOT NULL,
  `phanquyen` varchar(10) NOT NULL,
  `ngaydangky` datetime NOT NULL,
  `trangthai` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`MaND`, `username`, `password`, `hoten`, `email`, `sdt`, `diachi`, `avarta`, `phanquyen`, `ngaydangky`, `trangthai`) VALUES
('ND1', 'admin', '123456', 'Phan Hoàng Huy Khải', 'admin@bidvn.com', '0907636172', 'Mỹ Thới, An Giang', 'avatar_ND1_1764590680', 'Admin', '2025-08-04 19:58:51', 'Hoạt động'),
('ND10', 'haivan', '123456', 'Vân Thị Hải', 'hai@gmail.com', '0912345687', 'Khánh Hòa', 'default_user', 'Người bán', '2025-08-14 06:01:58', 'Hoạt động'),
('ND11', 'test', '123', 'Test Phan', 'test@gmail.com', '123456', 'AG, LX', 'avatar_ND11_1764607756', 'Người mua', '2025-11-24 18:13:18', 'Hoạt động'),
('ND2', 'thanhngoc', '123456', 'Huỳnh Thị Thanh Ngọc', 'tngoc@gmail.com', '0912345679', 'Quận 3, TP. Hồ Chí Minh', 'default_user', 'Người bán', '2025-06-20 01:00:39', 'Hoạt động'),
('ND3', 'tuanle', '123456', 'Lê Anh Tuấn', 'tuan@gmail.com', '0912345680', 'Thanh Xuân, Hà Nội', 'avatar_ND3_1764599433', 'Người mua', '2025-12-24 22:23:19', 'Hoạt động'),
('ND4', 'lanpham', '123456', 'Phạm Thị Lan', 'lan@gmail.com', '0912345661', 'Dĩ An, Bình Dương', 'default_user', 'Người bán', '2025-09-30 12:54:43', 'Hoạt động'),
('ND5', 'hungvo', '123456', 'Võ Mạnh Hùng', 'hungvo@gmail.com', '0912345682', 'Đồng Nai', 'default_user', 'Người mua', '2025-12-18 21:23:39', 'Hoạt động'),
('ND6', 'minhnguyen', '123456', 'Nguyễn Thị Minh', 'minhnguyen@gmail.com', '0912345683', 'Hải Phòng', 'default_user', 'Người bán', '2025-12-29 20:14:08', 'Hoạt động'),
('ND7', 'hoangtran', '123456', 'Trần Văn Hoàng', 'hoangtran@gmail.com', '0912345684', 'Đà Nẵng', 'default_user', 'Người bán', '2025-06-29 12:59:48', 'Hoạt động'),
('ND8', 'thuyLe', '123456', 'Lê Thị Thùy', 'thuyLe@gmail.com', '0912345685', 'Ninh Kiều, Cần Thơ', 'default_user', 'Người bán', '2025-10-28 04:16:34', 'Hoạt động'),
('ND9', 'dungpham', '123456', 'Phạm Văn Dũng', 'dungpham@gmail.com', '0912345686', 'Thừa Thiên Huế', 'default_user', 'Người mua', '2025-06-19 06:23:29', 'Hoạt động');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `san_pham`
--

CREATE TABLE `san_pham` (
  `MaSP` varchar(10) NOT NULL,
  `tensp` varchar(30) NOT NULL,
  `mota` varchar(1000) NOT NULL,
  `gia_khoidiem` float NOT NULL,
  `gia_hientai` float NOT NULL,
  `buocgia` float NOT NULL,
  `thoigian_batdau` datetime NOT NULL,
  `thoigian_ketthuc` datetime NOT NULL,
  `trangthai` varchar(15) NOT NULL,
  `hinhanh` varchar(30) NOT NULL,
  `MaND` varchar(10) NOT NULL,
  `MaDMCon` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `san_pham`
--

INSERT INTO `san_pham` (`MaSP`, `tensp`, `mota`, `gia_khoidiem`, `gia_hientai`, `buocgia`, `thoigian_batdau`, `thoigian_ketthuc`, `trangthai`, `hinhanh`, `MaND`, `MaDMCon`) VALUES
('SP1', 'Vertu Signature S Cloud', 'Máy nguyên bản, da cá sấu, full box 99%', 150000000, 166000000, 1000000, '2025-12-01 10:33:15', '2025-12-06 11:33:15', 'Đang đấu giá', 'img_SP1', 'ND2', 'DMC3'),
('SP10', 'Leica M3 + Lens Summicron', 'Máy ảnh film huyền thoại, hoạt động hoàn hảo', 60000000, 60000000, 500000, '2025-12-10 09:00:00', '2025-12-20 18:00:00', 'Sắp diễn ra', 'img_SP10', 'ND2', 'DMC12'),
('SP11', 'Xe BMW 7 Series Cũ', 'Đã qua sử dụng, đời 2018, bảo dưỡng định kỳ.', 450000000, 500000000, 5000000, '2025-11-01 08:00:00', '2025-11-20 08:00:00', 'Kết thúc', 'default_product', 'ND6', 'DMC16'),
('SP12', 'Đồng hồ Apple Watch S5', 'Hàng đã qua sử dụng, Pin 90%, dây thép không gỉ.', 3000000, 3800000, 100000, '2025-11-05 10:00:00', '2025-11-22 15:00:00', 'Kết thúc', 'default_product', 'ND7', 'DMC9'),
('SP13', 'Túi Da Cá Sấu Hermès', 'Phiên bản giới hạn, da thật, fullbox, màu nâu.', 80000000, 95000000, 2000000, '2025-11-08 14:00:00', '2025-11-25 18:00:00', 'Kết thúc', 'default_product', 'ND8', 'DMC21'),
('SP14', 'Laptop Dell XPS 13 i7', 'Máy tính xách tay, cấu hình cao, màn 4K.', 18000000, 19500000, 500000, '2025-11-10 09:30:00', '2025-11-28 17:00:00', 'Kết thúc', 'default_product', 'ND9', 'DMC4'),
('SP15', 'Amply Sansui AU-9900', 'Hàng bãi mới về, nguyên bản 100%, chất âm ấm áp.', 15000000, 16000000, 500000, '2025-11-02 11:00:00', '2025-11-15 11:00:00', 'Kết thúc', 'default_product', 'ND10', 'DMC13'),
('SP16', 'Bộ Sưu Tập Tiền Xu Đông Dương', '50 đồng xu cổ, bảo quản tốt.', 2000000, 2400000, 100000, '2025-11-09 15:30:00', '2025-11-29 15:30:00', 'Kết thúc', 'default_product', 'ND6', 'DMC23'),
('SP17', 'Máy ảnh Sony Alpha A7R III', 'Hàng used, chụp 10k shot, tặng kèm 2 pin.', 35000000, 39000000, 1000000, '2025-11-01 08:00:00', '2025-11-18 08:00:00', 'Kết thúc', 'default_product', 'ND7', 'DMC11'),
('SP18', 'Bàn Ăn Gỗ Gụ 12 Ghế', 'Gỗ gụ mật cao cấp, chạm khắc tinh xảo, nặng 1 tấn.', 45000000, 52000000, 1000000, '2025-11-05 13:00:00', '2025-11-20 13:00:00', 'Kết thúc', 'default_product', 'ND8', 'DMC22'),
('SP19', 'iPhone 12 Pro Max', 'Máy quốc tế, pin 85%, nguyên zin, chưa bung.', 11000000, 12500000, 500000, '2025-11-10 16:00:00', '2025-11-29 16:00:00', 'Kết thúc', 'default_product', 'ND9', 'DMC1'),
('SP2', 'Sim Tứ Quý 9 Viettel', 'Sim trả trước, sang tên chính chủ 1 nốt nhạc', 80000000, 81000000, 500000, '2025-12-01 10:33:15', '2025-12-06 11:33:15', 'Đang đấu giá', 'img_SP2', 'ND5', 'DMC3'),
('SP20', 'Tranh Thủy Mặc Độc Bản', 'Tác phẩm của nghệ nhân Nguyễn Văn B., chất liệu giấy dó.', 10000000, 12500000, 500000, '2025-11-05 10:00:00', '2025-11-15 20:00:00', 'Kết thúc', 'default_product', 'ND10', 'DMC25'),
('SP3', 'Hermes Birkin 25 Togo', 'Màu Gold, khóa vàng, bill Pháp 2024', 250000000, 310000000, 2000000, '2025-12-01 10:33:15', '2025-12-06 11:33:15', 'Đang đấu giá', 'img_SP3', 'ND7', 'DMC21'),
('SP4', 'Tờ 100đ Đông Dương 1950', 'Tiền giấy bảo quản kỹ, seri đẹp, hiếm', 2000000, 2000000, 50000, '2025-12-01 10:33:15', '2025-12-06 11:33:15', 'Đang đấu giá', 'img_SP4', 'ND2', 'DMC23'),
('SP5', 'Rolex Submariner Date', 'Đồng hồ lặn, niềng Ceramic, no box', 180000000, 182000000, 1000000, '2025-12-01 10:33:15', '2025-12-06 11:33:15', 'Đang đấu giá', 'img_SP5', 'ND5', 'DMC7'),
('SP6', 'Tượng Di Lặc Gỗ Sưa Đỏ', 'Gỗ sưa đỏ quý hiếm, đục tay nguyên khối', 50000000, 65000000, 500000, '2025-12-01 10:33:15', '2025-12-06 11:33:15', 'Đang đấu giá', 'img_SP6', 'ND7', 'DMC26'),
('SP7', 'Air Jordan 1 Dior Low', 'Size 42, bản giới hạn toàn cầu', 120000000, 120000000, 1000000, '2025-12-01 10:33:15', '2025-12-06 11:33:15', 'Đang đấu giá', 'img_SP7', 'ND2', 'DMC20'),
('SP8', 'Vespa ACMA 1958', 'Xe cổ Pháp, máy êm, giấy tờ hợp lệ', 45000000, 45000000, 500000, '2025-12-01 10:33:15', '2025-12-06 11:33:15', 'Đang đấu giá', 'img_SP8', 'ND5', 'DMC17'),
('SP9', 'Zippo Mỹ La Mã 1998', 'Hàng mới chưa lên lửa, nguyên tem', 1500000, 1800000, 50000, '2025-12-01 10:33:15', '2025-12-06 11:33:15', 'Đang đấu giá', 'img_SP9', 'ND7', 'DMC24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `slider`
--

CREATE TABLE `slider` (
  `hinhanh` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `slider`
--

INSERT INTO `slider` (`hinhanh`) VALUES
('slider 1.jpeg'),
('slider 2.jpeg'),
('slider 3.jpeg'),
('slider 4.jpeg'),
('slider 5.jpeg');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  ADD KEY `SoHD` (`SoHD`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Chỉ mục cho bảng `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`MaDM`);

--
-- Chỉ mục cho bảng `danh_muc_con`
--
ALTER TABLE `danh_muc_con`
  ADD PRIMARY KEY (`MaDMCon`),
  ADD KEY `MaDM` (`MaDM`);

--
-- Chỉ mục cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD KEY `MaND` (`MaND`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Chỉ mục cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD PRIMARY KEY (`SoHD`),
  ADD KEY `MaND` (`MaND`);

--
-- Chỉ mục cho bảng `lich_su_dau_gia`
--
ALTER TABLE `lich_su_dau_gia`
  ADD PRIMARY KEY (`MaLS`),
  ADD KEY `MaND` (`MaND`),
  ADD KEY `MaSP` (`MaSP`);

--
-- Chỉ mục cho bảng `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`MaND`);

--
-- Chỉ mục cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`MaSP`),
  ADD KEY `MaND` (`MaND`),
  ADD KEY `MaDMCon` (`MaDMCon`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `lich_su_dau_gia`
--
ALTER TABLE `lich_su_dau_gia`
  MODIFY `MaLS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chi_tiet_hoa_don`
--
ALTER TABLE `chi_tiet_hoa_don`
  ADD CONSTRAINT `chi_tiet_hoa_don_ibfk_1` FOREIGN KEY (`MaSP`) REFERENCES `san_pham` (`MaSP`) ON UPDATE CASCADE,
  ADD CONSTRAINT `chi_tiet_hoa_don_ibfk_2` FOREIGN KEY (`SoHD`) REFERENCES `hoa_don` (`SoHD`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `danh_muc_con`
--
ALTER TABLE `danh_muc_con`
  ADD CONSTRAINT `danh_muc_con_ibfk_1` FOREIGN KEY (`MaDM`) REFERENCES `danh_muc` (`MaDM`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `gio_hang`
--
ALTER TABLE `gio_hang`
  ADD CONSTRAINT `gio_hang_ibfk_1` FOREIGN KEY (`MaSP`) REFERENCES `san_pham` (`MaSP`) ON UPDATE CASCADE,
  ADD CONSTRAINT `gio_hang_ibfk_2` FOREIGN KEY (`MaND`) REFERENCES `nguoi_dung` (`MaND`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `hoa_don`
--
ALTER TABLE `hoa_don`
  ADD CONSTRAINT `hoa_don_ibfk_1` FOREIGN KEY (`MaND`) REFERENCES `nguoi_dung` (`MaND`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `lich_su_dau_gia`
--
ALTER TABLE `lich_su_dau_gia`
  ADD CONSTRAINT `lich_su_dau_gia_ibfk_1` FOREIGN KEY (`MaSP`) REFERENCES `san_pham` (`MaSP`) ON UPDATE CASCADE,
  ADD CONSTRAINT `lich_su_dau_gia_ibfk_2` FOREIGN KEY (`MaND`) REFERENCES `nguoi_dung` (`MaND`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`MaDMCon`) REFERENCES `danh_muc_con` (`MaDMCon`) ON UPDATE CASCADE,
  ADD CONSTRAINT `san_pham_ibfk_2` FOREIGN KEY (`MaND`) REFERENCES `nguoi_dung` (`MaND`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
