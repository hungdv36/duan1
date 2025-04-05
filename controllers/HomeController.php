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

    public function home(){
        $listSanPham = $this->modelSanPham->getAllSanPham();
        require_once './views/home.php';
    }

    public function trangchu(){
        echo 'Đây là trang chủ';
    }

    public function danhSachSanPham(){
        $listProduct = $this->modelSanPham->getAllProduct();
        // var_dump($listProduct);die();
        require_once './views/listProduct.php';
    }
    public function chiTietSanPham(){
        $id = $_GET['id_san_pham'];
        $sanPham = $this->modelSanPham->getDetailSanPham($id);
        $listSanPhamDanhMuc = $this->modelSanPham->listSanPhamDanhMuc($sanPham['danh_muc_id']); 
        $listBinhLuan = $this-> modelSanPham->getBinhLuanFromSanPham($id);
        // var_dump($listSanPhamDanhMuc);
        // die;
        if (count($sanPham)> 0 ) {
          require_once './views/detailSanPham.php';
        }else{
          header("Location: " . BASE_URL);
          exit();
        }
        
    }

    public function formLogin(){
      require_once './views/auth/formLogin.php';
      deleteSessionError();
      exit();
  }

  public function postLogin(){
      if ($_SERVER['REQUEST_METHOD'] == 'POST') {
          // Lấy email và pass gửi lên từ form
          $email = $_POST['email'];
          $password = $_POST['password'];

          // var_dump($email); die;

          // Xử lý kiểm tra thông tin đăng nhập
          $user = $this->modelTaiKhoan->checklogin($email, $password);

          if ($user == $email) {
              // Lưu thông tin vao session
              $_SESSION['user_client'] = $user;
              header("Location: " . BASE_URL);
              exit();
          }else{
              // Lỗi thì lưu lỗi vào session
              $_SESSION['error'] = $user;
              // var_dump($_SESSION['error']); die;

              $_SESSION['flash'] = true;

              header("Location: " . BASE_URL . '?act=login');
              exit();
          }
      }
  }
  public function thanhToan(){
    if (isset($_SESSION['user_client'])) {
        $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
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

        $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
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
        $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
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
        $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
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
       $user = $this->modelTaiKhoan->getTaiKhoanFromEmail($_SESSION['user_client']);
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
