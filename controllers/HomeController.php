<?php 

class HomeController
{
    public $modelSanPham;
    public function __construct()
    {
        $this->modelSanPham = new SanPham();
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
    public function thanhToan(){
        if (isset($_SESSION['user_client'])) {
            $user = $this->modelTaiKhoan->getTaiKhoanFormEmail($_SESSION['user_client']);
            // lấy dữ liệu giỏ hàng của người dùng

            $gioHang = $this->modelGioHang->getGioHangFormEmail($user['id']);
            if (!$gioHang) {
                $gioHangId = $this->modelGioHang->addGioHang($user['id']);
                $gioHang = ['id'=>$gioHangId];
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            } else {
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);
            }

            require_once '.views/thanhToan.php';
        }else {
            var_dump('Chưa đăng nhập');die;
        }
    }

    public function postThanhToan(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ten_nguoi_nhan = $_POST['ten_nguoi_nhan'];
            $email_nguoi_nhan = $_POST['email_nguoi_nhan'];
            $sdt_nguoi_nhan= $_POST['sdt_nguoi_nhan'];
            $dia_chi_nguoi_nhan = $_POST['dia_chi_nguoi_nhan'];
            $ghi_chu = $_POST['ghi_chu'];
            $tong_tien = $_POST['tong_tien'];
            $phuong_thuc_thanh_toan_id = $_POST['phuong_thuc_thanh_toan_id'];

            $ngay_dat = date('Y-m-d');
            $trang_thai_id = 1;

            $user = $this->modelTaiKhoan->getTaiKhoanFormEmail($_SESSION['user_client']);
            $tai_khoan_id = $user['id'];

            $ma_don_hang = 'DH-'. rand(1000,9999)

            // Thêm trông tin vào DB

            $this->modelDonHang->addDonHang($tai_khoan_id,
                                            $ten_nguoi_nhan,
                                            $email_nguoi_nhan,
                                            $sdt_nguoi_nhan,
                                            $dia_chi_nguoi_nhan,
                                            $ghi_chu,
                                            $tong_tien,
                                            $phuong_thuc_thanh_toan_id,
                                            $ngay_dat,
                                            $ma_don_hang,
                                            $trang_thai_id,
            );

        }
    }
}
// sửa giỏ hàng header("Location: ". BASE_URL . '?act=login');
