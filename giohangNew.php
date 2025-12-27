<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - BIDVN (Mới)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Định nghĩa biến màu sắc */
        :root {
            --color-primary-light: #FFD54F; /* Vàng nhạt hơn */
            --color-primary-dark: #FBC02D;  /* Vàng đậm hơn cho button */
            --color-bg-light: #fdfdfd;      /* Nền trắng nhẹ */
            --color-text-dark: #333333;
            --color-text-muted: #777777;
            --color-border: #e0e0e0;        /* Border mềm mại */
            --shadow-soft: 0 8px 24px rgba(0, 0, 0, 0.1); /* Bóng mềm */
            --border-radius: 12px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: var(--color-bg-light);
            color: var(--color-text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
		
		.select-all-header {
			display: flex;
			align-items: center;
			margin-bottom: 15px;
			padding-bottom: 10px;
			border-bottom: 1px solid var(--color-border);
		}

        /* --- Header (Top Bar) --- */
        .top-bar {
            background-color: var(--color-primary-dark);
            color: white;
            padding: 15px 50px;
            text-align: center;
            font-size: 1.8em;
            font-weight: 700;
            letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* --- Main Content Layout --- */
        .cart-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr; /* Mặc định 1 cột */
            gap: 25px;
            flex-grow: 1; /* Cho phép giãn nở để footer xuống dưới */
        }
        
        /* Layout 2 cột khi màn hình lớn hơn */
        @media (min-width: 768px) {
            .cart-container {
                grid-template-columns: 2fr 1fr; /* Giỏ hàng chiếm 2/3, tổng tiền chiếm 1/3 */
            }
        }

        /* --- Left Section: Product List --- */
        .cart-items-section {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-soft);
            padding: 30px;
        }

		/* Cập nhật Product Card để chứa Checkbox */
		.product-card {
			display: flex;
			align-items: center;
			padding: 20px 0;
			border-bottom: 1px dashed var(--color-border);
			position: relative;
		}

		.product-checkbox {
			margin-right: 15px; /* Tạo khoảng trống giữa checkbox và ảnh */
			width: 20px;
			height: 20px;
			/* Custom Checkbox Style (tùy chọn) */
			accent-color: var(--color-primary-dark); /* Dùng màu vàng đậm cho checkbox */
			cursor: pointer;
		}

		.product-image {
			width: 100px;
			height: 100px;
			border-radius: 8px;
			object-fit: cover;
			margin-right: 20px;
			border: 1px solid var(--color-border);
		}

        .product-details {
            flex-grow: 1;
        }

        .product-details h3 {
            margin: 0 0 5px 0;
            font-size: 1.2em;
            color: var(--color-text-dark);
        }

        .product-details p {
            margin: 0;
            font-size: 0.9em;
            color: var(--color-text-muted);
        }

        .product-price-qty {
            display: flex;
            align-items: center;
            margin-top: 10px;
            font-size: 1em;
        }
        
        .product-price-qty span {
            font-weight: 600;
            color: var(--color-text-dark);
            margin-right: 20px;
            min-width: 120px; /* Đảm bảo đủ rộng cho giá */
        }

        .quantity-control {
            display: flex;
            align-items: center;
            border: 1px solid var(--color-border);
            border-radius: 5px;
            overflow: hidden;
        }

        .quantity-control button {
            background-color: var(--color-bg-light);
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 1em;
            color: var(--color-text-muted);
            transition: all 0.2s;
        }
        .quantity-control button:hover {
            background-color: var(--color-primary-light);
            color: white;
        }

        .quantity-control input {
            width: 30px;
            text-align: center;
            border: none;
            outline: none;
            font-size: 1em;
            color: var(--color-text-dark);
        }

        .total-item-price {
            font-weight: 700;
            color: var(--color-primary-dark);
            font-size: 1.2em;
            margin-left: 20px;
            min-width: 100px; /* Đảm bảo hiển thị đủ */
            text-align: right;
        }

        .remove-btn {
            position: absolute;
            top: 20px;
            right: 0;
            background: none;
            border: none;
            color: var(--color-text-muted);
            cursor: pointer;
            font-size: 1.2em;
            transition: color 0.2s;
        }
        .remove-btn:hover {
            color: #E53935; /* Màu đỏ khi hover */
        }

        /* --- Right Section: Cart Summary --- */
        .cart-summary-section {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-soft);
            padding: 30px;
            height: fit-content; /* Điều chỉnh chiều cao theo nội dung */
        }

        .cart-summary-section h2 {
            font-size: 1.6em;
            color: var(--color-text-dark);
            margin-bottom: 25px;
            border-bottom: 2px solid var(--color-primary-light);
            padding-bottom: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed var(--color-border);
        }
        .summary-item:last-of-type {
            border-bottom: none;
            margin-bottom: 20px;
        }

        .summary-item.total {
            font-size: 1.3em;
            font-weight: 700;
            color: var(--color-primary-dark);
            padding-top: 20px;
            border-top: 2px solid var(--color-primary-light);
        }

        .summary-item span:first-child {
            color: var(--color-text-dark);
        }
        .summary-item span:last-child {
            color: var(--color-text-muted);
        }
        .summary-item.total span:last-child {
            color: var(--color-primary-dark);
        }

        .checkout-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: var(--color-primary-dark);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        .checkout-btn:hover {
            background-color: #FDD835; /* Vàng sáng hơn */
            transform: translateY(-2px);
        }

        /* --- Footer --- */
        .footer {
            margin-top: auto; /* Đẩy footer xuống dưới */
            background-color: #eee;
            color: var(--color-text-muted);
            text-align: center;
            padding: 20px;
            font-size: 0.9em;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.05);
        }

        /* --- Responsive Adjustments --- */
        @media (max-width: 600px) {
            .top-bar {
                padding: 15px 20px;
                font-size: 1.5em;
            }
            .cart-items-section, .cart-summary-section {
                padding: 20px;
            }
            .product-card {
                flex-wrap: wrap; /* Cho phép các mục xuống dòng */
                text-align: center;
                justify-content: center;
            }
            .product-image {
                margin: 0 auto 15px auto;
            }
            .product-details {
                width: 100%;
                margin-bottom: 15px;
            }
            .product-price-qty {
                width: 100%;
                justify-content: space-between;
            }
            .remove-btn {
                position: static; /* Đặt nút xóa ở dưới cùng */
                margin-top: 15px;
            }
            .total-item-price {
                margin-left: 0;
                text-align: center;
                width: 100%;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="top-bar">Giỏ Hàng Của Bạn</div>

<div class="cart-container">
    <div class="cart-items-section">
        <h2>Sản Phẩm Trong Giỏ</h2>

        <div class="select-all-header">
            <input type="checkbox" id="select-all" class="product-checkbox">
            <label for="select-all" style="font-weight: 600; cursor: pointer;">Chọn Tất Cả Sản Phẩm</label>
        </div>

        <div class="product-card">
            <input type="checkbox" class="product-checkbox" checked> <img src="https://via.placeholder.com/100x100?text=Canon" alt="Canon EOS R5" class="product-image">
            <div class="product-details">
                <h3>Canon EOS R5</h3>
                <p>Phân Loại Hàng: Canon</p>
                <div class="product-price-qty">
                    <span>52.000.000₫</span>
                    <div class="quantity-control">
                        <button>-</button>
                        <input type="text" value="1">
                        <button>+</button>
                    </div>
                </div>
            </div>
            <div class="total-item-price">52.000.000₫</div>
            <button class="remove-btn"><i class="fas fa-times-circle"></i></button>
        </div>

        <div class="product-card">
            <input type="checkbox" class="product-checkbox" checked> <img src="https://via.placeholder.com/100x100?text=Fujifilm" alt="Fujifilm X-T5" class="product-image">
            <div class="product-details">
                <h3>Fujifilm X-T5</h3>
                <p>Phân Loại Hàng: Fujifilm</p>
                <div class="product-price-qty">
                    <span>32.000.000₫</span>
                    <div class="quantity-control">
                        <button>-</button>
                        <input type="text" value="1">
                        <button>+</button>
                    </div>
                </div>
            </div>
            <div class="total-item-price">32.000.000₫</div>
            <button class="remove-btn"><i class="fas fa-times-circle"></i></button>
        </div>

        <div class="product-card">
            <input type="checkbox" class="product-checkbox" checked> <img src="https://via.placeholder.com/100x100?text=Dell" alt="Dell XPS 13 Plus" class="product-image">
            <div class="product-details">
                <h3>Dell XPS 13 Plus</h3>
                <p>Phân Loại Hàng: Dell</p>
                <div class="product-price-qty">
                    <span>26.000.000₫</span>
                    <div class="quantity-control">
                        <button>-</button>
                        <input type="text" value="1">
                        <button>+</button>
                    </div>
                </div>
            </div>
            <div class="total-item-price">26.000.000₫</div>
            <button class="remove-btn"><i class="fas fa-times-circle"></i></button>
        </div>

    </div>

    <div class="cart-summary-section">
        <h2>Tóm Tắt Đơn Hàng</h2>
        <div class="summary-item">
            <span>Tổng sản phẩm (3)</span>
            <span>110.000.000₫</span>
        </div>
        <div class="summary-item">
            <span>Phí vận chuyển</span>
            <span>Miễn phí</span>
        </div>
        <div class="summary-item">
            <span>Mã giảm giá</span>
            <span>-0₫</span>
        </div>
        <div class="summary-item total">
            <span>Tổng cộng</span>
            <span>110.000.000₫</span>
        </div>
        <button class="checkout-btn">TIẾN HÀNH THANH TOÁN</button>
    </div>

</div>

<div class="footer">
    © 2024 BIDVN. Bảo mật & Điều khoản.
</div>

</body>
</html>