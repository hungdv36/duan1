<?php

class GioHangController
{
    public $modelGioHang;
    public function __construct()
    {
        $this->modelGioHang = new GioHang();
    }
    
    //hàm hiển thị giỏ hàng
    public function themGioHang()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION['user'])) {
                $gioHang = $this->modelGioHang->layGioHangTuNguoiDung($_SESSION['user']['id']);
                if (!$gioHang) {
                    $gioHangId = $this->modelGioHang->themGioHang($_SESSION['user']['id']);
                    $gioHang = ['id' => $gioHangId];
                    $chiTietGioHang = [];
                } else {
                    $chiTietGioHang = $this->modelGioHang->layChiTietGioHang($gioHang['id']);
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

                header('Location: ?act=trang-chu');
            } else {  
                var_dump('Chưa đăng nhập');
                die;
            }
        }
    }
}
