<?php 

class HomeController
{
    public $modelSanPham;
    public $modelDanhMuc;
    public $modelTaiKhoan;
    public $modelGioHang;
    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelDanhMuc = new DanhMuc();
        $this->modelTaiKhoan = new TaiKhoan();
        $this->modelGioHang = new GioHang();
    }

    public function home()
    {
        if (isset($_SESSION['user'])) {
            $gioHang = $this->modelGioHang->layGioHangTuNguoiDung($_SESSION['user']['id']);

            if (!$gioHang) {
                $gioHangId = $this->modelGioHang->themGioHang($_SESSION['user']['id']);
                $gioHang = ['id' => $gioHangId];
                $chiTietGioHang = [];
            } else {
                $chiTietGioHang = $this->modelGioHang->layChiTietGioHang($gioHang['id']);
            }
        }
        $danhSachSanPham = $this->modelSanPham->danhSachSanPham();
        $danhSachDanhMuc = $this->modelDanhMuc->danhSachDanhMuc();
        $danhSachSanPhamNoiBat = $this->modelSanPham->danhSachSanPhamNoiBat(); // limit 10 luot_xem desc
        require_once './views/home.php';
    }

    //hàm hiển thị giỏ hàng
    public function themGioHang() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION['user'])) {
                $gioHang = $this->modelGioHang->layGioHangTuNguoiDung($_SESSION['user']['id']);
                if (!$gioHang) {
                    $gioHangId = $this->modelGioHang->themGioHang($_SESSION['user']['id']);
                    $gioHang = ['id' => $gioHangId];
                    $chiTietGioHang = [];
                } else {
                    $chiTietGioHang[] = $this->modelGioHang->layChiTietGioHang($gioHang['id']);
                }
                $san_pham_id = $_POST['san_pham_id'];
                $so_luong = $_POST['so_luong'];

                $checkSanPham = false;
                foreach ($chiTietGioHang as $detail) {
                    if ($detail['san_pham_id'] == $san_pham_id) {
                        $newSoLuong = $detail['so_luong'] + $so_luong;
                        $this->modelGioHang->updateSoLuong($gioHang['id'], $san_pham_id, $newSoLuong);
                        $checkSanPham = true;
                        break;
                    }
                }

                if (!$checkSanPham) {
                    $this->modelGioHang->addDetailGioHang($gioHang['id'], $san_pham_id, $so_luong);
                }

                header('Location: ?act=gio-hang');
            } else {
                var_dump('Chưa đăng nhập'); die;
            }
        }
    }

    //hàm hiển thị giỏ hàng
    public function gioHang(){
        if (isset($_SESSION['user'])) {
            $gioHang = $this->modelGioHang->layGioHangTuNguoiDung($_SESSION['user']['id']);

            if (!$gioHang) {
                $gioHangId = $this->modelGioHang->themGioHang($_SESSION['user']['id']);
                $gioHang = ['id' => $gioHangId];
                $chiTietGioHang = [];
            } else {
                $chiTietGioHang = $this->modelGioHang->layChiTietGioHang($gioHang['id']);
            }

            require_once './views/cart.php';
        } else {
            var_dump('Chưa đăng nhập'); die;
        }
    }

    public function capNhatSoLuong() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user'])) {
            $gioHang = $this->modelGioHang->layGioHangTuNguoiDung($_SESSION['user']['id']);
            if ($gioHang && isset($_POST['so_luong'])) {
                foreach ($_POST['so_luong'] as $san_pham_id => $so_luong) {
                    if ($so_luong > 0) {
                        $this->modelGioHang->updateSoLuong($gioHang['id'], $san_pham_id, $so_luong);
                    } else {
                        // Nếu số lượng <= 0 thì xóa sản phẩm khỏi giỏ hàng
                        $this->modelGioHang->xoaSanPhamKhoiGioHang($gioHang['id'], $san_pham_id);
                    }
                }
            }
            header('Location: ?act=gio-hang');
        }
    }

    public function xoaSanPhamKhoiGioHang() {
        if (isset($_SESSION['user'])) {
            $gioHang = $this->modelGioHang->layGioHangTuNguoiDung($_SESSION['user']['id']);
            if ($gioHang) {
                $san_pham_id = isset($_POST['san_pham_id']) ? $_POST['san_pham_id'] : $_GET['san_pham_id'];
                
                if ($san_pham_id) {
                    $this->modelGioHang->xoaSanPhamKhoiGioHang($gioHang['id'], $san_pham_id);
                }
            }
            header('Location: ?act=gio-hang');
        }
    }
}