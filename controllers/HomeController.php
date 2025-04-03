<?php 

class HomeController
{
    public $modelSanPham;
    public $modelDanhMuc;
    public $modelTaiKhoan;
    public $modelGioHang;
    public $modelDonHang;

    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelDanhMuc = new DanhMuc();
        $this->modelTaiKhoan = new TaiKhoan();
        $this->modelGioHang = new GioHang();
        $this->modelDonHang = new DonHang();

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
            $user = $this->modelTaiKhoan->checkLogin($_SESSION['user_client']);
            // lấy dữ liệu giỏ hàng của người dùng

            $gioHang = $this->modelGioHang->getGioHangFormUser($user['id']);
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

            $user = $this->modelTaiKhoan->checkLogin($_SESSION['user_client']);
            $tai_khoan_id = $user['id'];

            $ma_don_hang = 'DH-'. rand(1000,9999);

            // Thêm trông tin vào DB

            $donHang = $this->modelDonHang->addDonHang($tai_khoan_id,
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
            // lấy thông tin giỏ hàng của người dùng 
            $gioHang = $this->modelGioHang->layGioHangTuNguoiDung($tai_khoan_id);

            // Lưu sản phẩm vào chi tiết đơn hàng
            if ($donHang) {
                // lấy ra toàn bộ sản phẩm trong giỏ hàng
                $chiTietGioHang = $this->modelGioHang->getDetailGioHang($gioHang['id']);

                //Thêm từng sản phẩm từ giỏ hàng vào bảng chi tiết đơn hàng
                foreach($chiTietGioHang as $item){
                    $donGia= $item['gia_khuyen_mai'] ?? $item['gia_san_pham']; // ưu tiên đơn giá lấy giá khuyễn mãi

                    $this->modelDonHang->addChiTietDonHang(
                        $donHang, // id đơn hàng vừa tạo
                        $item['san_pham_id'], // id sản phẩm
                        $donGia, // đơn giá lấy từ sản phẩm
                        $item['so_luong'], // số lượng
                        $donGia *  $item['so_luong'] // thành tiền
                    );
                } 
                // sau khi thêm xong phải tiến hành xóa sản phẩm trong giỏ hàng
                // Xóa toàn bộ sản phẩm trong chi tiết giỏ hàng 
                $this-> modelGioHang->clearDetailGioHang($gioHang['id']);
                // xóa thông tin giỏ hang người dùng
                $this-> modelGioHang->clearGioHang($tai_khoan_id);

                // chuyển hướng về trang lịch sử mua hàng 
                header("location: " . BASE_URL . '?act=lich-su-mua-hang');
                exit();
            }else {
                var_dump('Lỗi đặt hàng vui lòng thử lại sau');
                die();
            }
        }
    }

    public function lichSuMuaHang(){
        if (isset($_SESSION['user_client'])) {
            // lấy thông tin tài khoản đăng nhập
            $user = $this->modelTaiKhoan->checkLogin($_SESSION['user_client']);
            $tai_khoan_id = $user['id'];

            // lấy ra danh sách trạng thái đơn hàng

            $arrTrangThaiDonHang = $this->modelDonHang->getTrangThaiDonHang();
            $trangThaiDonHang = array_column($arrTrangThaiDonHang,'ten_trang_thai', 'id');

            // lấy ra dang sách phương thức thanh toán
            $arrPhuongThucThanhToan = $this->modelDonHang->getPhuongThucThanhToan();
            $phuongThucThanhToan = array_column($arrPhuongThucThanhToan,'ten_phuong_thuc', 'id');

            // Lấy ra danh sách tất cả đơn hàng của tài khoản
            $donHangs = $this->modelDonHang->getDonHangFromUser($tai_khoan_id);
            require_once "./view/lichSuMuaHang.php";
        } else {
            var_dump('Bạn chưa đăng nhập');
            die();
        }
    }
    public function chiTietMuaHang(){
        if (isset($_SESSION['user_client'])) {
            // lấy thông tin tài khoản đăng nhập
            $user = $this->modelTaiKhoan->checkLogin($_SESSION['user_client']);
            $tai_khoan_id = $user['id'];
 
            // lấy id đơn hàng truyền từ URL
            $donHangId = $_GET['id'];

             // lấy ra danh sách trạng thái đơn hàng
             $arrTrangThaiDonHang = $this->modelDonHang->getTrangThaiDonHang();
             $trangThaiDonHang = array_column($arrTrangThaiDonHang,'ten_trang_thai', 'id');
 
             // lấy ra dang sách phương thức thanh toán
             $arrPhuongThucThanhToan = $this->modelDonHang->getPhuongThucThanhToan();
             $phuongThucThanhToan = array_column($arrPhuongThucThanhToan,'ten_phuong_thuc', 'id');
 
            // lấy ra thông tin đơn hàng theo id
            $donHang = $this->modelDonHang->getDonHangById($donHangId);

            // lấy thông tin sản phẩm của đơn hàng trong bảng chi tiết đơn hàng 
            $chiTietDonHang = $this->modelDonHang->getChiTietDonHangByDonHangId($donHangId);

            if ($donHang['tai_khoan_id'] != $tai_khoan_id) {
                echo "Bạn không có quyền truy cập đơn hàng.";
                exit;
            }

            require_once "./view/chiTietMuaHang.php";
         } else {
            var_dump('Bạn chưa đăng nhập');
            die();
         }
    }
    public function huyDonHang(){
        if (isset($_SESSION['user_client'])) {
           // lấy thông tin tài khoản đăng nhập
           $user = $this->modelTaiKhoan->checkLogin($_SESSION['user_client']);
           $tai_khoan_id = $user['id'];

           // lấy id đơn hàng truyền từ URL
           $donHangId = $_GET['id'];

           // Kiểm tra đơn hàng 
           $donHang = $this->modelDonHang->getDonHangById($donHangId);

           if ($donHang['tai_khoan_id'] != $tai_khoan_id) {
                echo "Bạn không có quyền hủy đơn hàng này";
                exit;
           }

           if ($donHang['trang_thai_id'] != 1) {
            echo "Chỉ đơn hàng ở trạng thái 'Chưa xác nhận' mới có thể hủy";
            exit;
            }

            // hủy đơn hàng
            $this->modelDonHang->updateTrangThaiDonHang($donHangId, 11);
            header("location: " . BASE_URL . '?act=lich-su-mua-hang');
            exit();
        } else {
            var_dump('Bạn chưa đăng nhập');
            die();
        }
    }
}
// sửa giỏ hàng header("Location: ". BASE_URL . '?act=login');
