<?php

class AdminDonHangController
{

    public $modelDonHang;

    public function __construct()
    {
        $this->modelDonHang = new AdminDonHang();
    }
    public function danhSachDonHang()
    {

        $listDonHang = $this->modelDonHang->getAllDonHang();
        require_once './views/donhang/listDonhang.php';
    }

    
    // public function formEditDanhMuc(){
    //     // Hàm này dùng để hiển thị form nhập
    //     // Lấy ra thông tin của danh mục cần sửa
    //     $id = $_GET['id_danh_muc'];
    //     $danhMuc = $this->modelDanhMuc->getDetailDanhMuc($id);
    //     if ($danhMuc) {
    //         require_once './views/danhmuc/editDanhMuc.php';
    //     }else{
    //         header("Location: " . BASE_URL_ADMIN . '?act=danh-muc');
    //         exit();
    //     }
        
    // }

    // public function postEditDanhMuc(){
    //     // Hàm này dùng để xử lý thêm dữ liệu

    //     // Kiểm tra xem dữ liệu có được submit lên không
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         // Lấy ra dữ liệu

    //         $id = $_POST['id'];
    //         $ten_danh_muc = $_POST['ten_danh_muc'];
    //         $mo_ta = $_POST['mo_ta'];

    //         // Tạo 1 mảng trống để chứa dữ liệu
    //         $errors = [];
    //         if (empty($ten_danh_muc)) {
    //             $errors['ten_danh_muc'] = 'Tên danh mục không được để trống';
    //         }

    //         // Nếu không có lỗi thì tiến hành sửa danh mục
    //         if (empty($errors)) {
    //             //  Nếu không có lỗi thì tiến hành sủaq danh mục
    //             // var_dump("ok");

    //             $this->modelDanhMuc->updateDanhMuc($id, $ten_danh_muc, $mo_ta);
    //             header("Location: " . BASE_URL_ADMIN . '?act=danh-muc');
    //             exit();
    //         }else{
    //             // Trả về form  và lỗi
    //             $danhMuc = ['id' => $id, 'ten_danh_muc' => $ten_danh_muc, 'mo_ta' => $mo_ta];
    //             require_once './views/danhmuc/editDanhMuc.php';
    //         }
    //     }
    // }

    // public function deleteDanhMuc(){
    //     $id = $_GET['id_danh_muc'];
    //     $danhMuc = $this->modelDanhMuc->getDetailDanhMuc($id);

    //     if ($danhMuc) {
    //         $this->modelDanhMuc->destroyDanhMuc($id);
    //     }
    //     header("Location: " . BASE_URL_ADMIN . '?act=danh-muc');
    //        exit();
    // }
}
