<?php 
session_start();
// session_destroy();
// print_r($_SESSION); die();

// Require file Common
require_once './commons/env.php'; // Khai báo biến môi trường
require_once './commons/function.php'; // Hàm hỗ trợ

// Require toàn bộ file Controllers
require_once './controllers/HomeController.php';
require_once './controllers/ProductController.php';
require_once './controllers/LoginController.php';
require_once './controllers/GioHangController.php';

// Require toàn bộ file Models
require_once './models/Student.php';
require_once './models/SanPham.php';
require_once './models/DanhMuc.php';
require_once './models/TaiKhoan.php';
require_once './models/GioHang.php';

require_once './models/DonHang.php';
// Route
$act = $_GET['act'] ?? '/';

// Để bảo bảo tính chất chỉ gọi 1 hàm Controller để xử lý request thì mình sử dụng match

match ($act) {

    '/' => (new HomeController())->home(),

    'trang-chu' => (new HomeController())->home(),

    'chi-tiet-san-pham' => (new ProductController())->chiTietSanPham(),

    'them-gio-hang' => (new HomeController())->themGioHang(),
    'gio-hang' => (new HomeController())->gioHang(),
    'cap-nhat-so-luong' => (new HomeController())->capNhatSoLuong(),
    'xoa-san-pham-khoi-gio-hang' => (new HomeController())->xoaSanPhamKhoiGioHang(),
    
    'dang-nhap' => (new LoginController())->dangNhap(),
    'dang-xuat' => (new LoginController())->dangXuat(),
    'xu-ly-dang-nhap' => (new LoginController())->xuLyDangNhap(),
    
    'thanh-toan' => (new HomeController())->thanhToan(),
    'xu-ly-thanh-toan' => (new HomeController())->postThanhToan(),
};