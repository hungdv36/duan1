<?php

class AdminSanPhamController
{

    public $modelSanPham;
    public $modelDanhMuc;
    public function __construct()
    {
        $this->modelSanPham = new AdminSanPham();
        $this->modelDanhMuc = new AdminDanhMuc();
    }
    public function danhSachSanPham()
    {

        $listSanPham = $this->modelSanPham->getAllSanPham();
        require_once './views/sanpham/listSanPham.php';
    }

    public function formAddSanPham(){
        // Hàm này dùng để hiển thị form nhập
        $listDanhMuc = $this->modelDanhMuc->getAllDanhMuc();
        require_once './views/sanpham/addSanPham.php';
    }

    public function postAddSanPham(){
        // Hàm này dùng để xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có được submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu

            $ten_san_pham = $_POST['ten_san_pham'];
            $gia_san_pham = $_POST['gia_san_pham'];
            $gia_khuyen_mai = $_POST['gia_khuyen_mai'];
            $so_luong = $_POST['so_luong'];
            $ngay_nhap = $_POST['ngay_nhap'];
            $danh_muc_id = $_POST['danh_muc_id'];
            $trang_thai = $_POST['trang_thai'];
            $mo_ta = $_POST['mo_ta'];

            $hinh_anh = $_FILES['hinh_anh'];
            
            // Lưu hình ảnh vào
            $file_thumb = uploadFile($hinh_anh, './uploads');

            $img_array = $_FILES['img_array'];

            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            if (empty($ten_san_pham)) {
                $errors['ten_san_pham'] = 'Tên sản phẩm không được để trống';
            }
            if (empty($gia_san_pham)) {
                $errors['gia_san_pham'] = 'Giá sản phẩm không được để trống';
            }
            if (empty($gia_khuyen_mai)) {
                $errors['gia_khuyen_mai'] = 'Giá khuyến mại không được để trống';
            }
            if (empty($so_luong)) {
                $errors['so_luong'] = 'Số lượng sản phẩm không được để trống';
            }
            if (empty($ngay_nhap)) {
                $errors['ngay_nhap'] = 'Ngày nhập sản phẩm không được để trống';
            }
            if (empty($danh_muc_id)) {
                $errors['danh_muc_id'] = 'Danh mục sản phẩm không được để trống';
            }
            if (empty($trang_thai)) {
                $errors['trang_thai'] = 'Trạng thái sản phẩm không được để trống';
            }

            // Nếu không có lỗi thì tiến hành thêm sản phẩm
            if (empty($errors)) {
                //  Nếu không có lỗi thì tiến hành thêm sản phẩm
                // var_dump("ok");

                $this->modelSanPham->insertSanPham($ten_san_pham, 
                                                    $gia_san_pham, 
                                                    $gia_khuyen_mai, 
                                                    $so_luong, 
                                                    $ngay_nhap, 
                                                    $danh_muc_id, 
                                                    $trang_thai, 
                                                    $mo_ta, 
                                                    $file_thumb);
                header("Location: " . BASE_URL_ADMIN . '?act=san-pham');
                exit();
            }else{
                // Trả về form  và lỗi
                require_once './views/sanpham/addSanPham.php';
            }
        }
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
