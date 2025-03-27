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
        require_once './views/donhang/listDonHang.php';
    }

    public function detailDonHang(){
        $don_hang_id = $_GET['id_don_hang'];
        
        // Lấy thông tin đơn hàng ở bảng don_hangs
        $donHang = $this->modelDonHang->getDetailDonHang($don_hang_id);

        // Lấy danh sách sản phẩm đã đặt ở đơn hàng chi_tiet_don_hangs
        $sanPhamDonHang = $this->modelDonHang->getListSpDonHang($don_hang_id);

        $listTrangThaiDonHang = $this->modelDonHang->getAllTrangThaiDonHang();
        
        require_once './views/donhang/detailDonHang.php';
    }

    public function formEditDonHang(){
        // Hàm này dùng để hiển thị form nhập
        // Lấy ra thông tin sản phẩm cần sửa
        $id = $_GET['id_don_hang'];
        $donHang = $this->modelDonHang->getDetailDonHang($id);
        $listTrangThaiDonHang = $this->modelDonHang->getAllTrangThaiDonHang();
        if ($donHang) {
            require_once './views/donhang/editDonHang.php';
            deleteSessionError();
        }else{
            header("Location: " . BASE_URL_ADMIN . '?act=don-hang');
            exit();
        }
        
    }

    public function postEditDonHang(){
        // Hàm này dùng để xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có được submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu
            // Lấy ra dữ liệu cũ của sản phẩm
            $don_hang_id = $_POST['don_hang_id'] ?? '';
          
            $ten_nguoi_nhan = $_POST['ten_nguoi_nhan'] ?? '';
            $sdt_nguoi_nhan = $_POST['sdt_nguoi_nhan'] ?? '';
            $email_nguoi_nhan = $_POST['email_nguoi_nhan'] ?? '';
            $dia_chi_nguoi_nhan = $_POST['dia_chi_nguoi_nhan'] ?? '';
            $ghi_chu = $_POST['ghi_chu'] ?? '';
            $trang_thai_id = $_POST['trang_thai_id'] ?? '';
            
            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            if (empty($ten_nguoi_nhan)) {
                $errors['ten_nguoi_nhan'] = 'Tên người nhận không được để trống';
            }
            if (empty($sdt_nguoi_nhan)) {
                $errors['sdt_nguoi_nhan'] = 'SĐT người nhận không được để trống';
            }
            if (empty($email_nguoi_nhan)) {
                $errors['email_nguoi_nhan'] = 'Email người nhận không được để trống';
            }
            if (empty($dia_chi_nguoi_nhan)) {
                $errors['dia_chi_nguoi_nhan'] = 'Địa chỉ người nhận không được để trống';
            }
            if (empty($trang_thai_id)) {
                $errors['trang_thai_id'] = 'Trạng thái đơn hàng';
            }
            
            
            $_SESSION['error'] = $errors;

            

            // Nếu không có lỗi thì tiến hành thêm sản phẩm
            if (empty($errors)) {
                //  Nếu không có lỗi thì tiến hành sửa

                $this->modelDonHang->updateDonHang( $don_hang_id,
                                                    $ten_nguoi_nhan, 
                                                    $sdt_nguoi_nhan, 
                                                    $email_nguoi_nhan, 
                                                    $dia_chi_nguoi_nhan, 
                                                    $ghi_chu, 
                                                    $trang_thai_id,);
                // Xử lý thêm album ảnh sản ohaamr img_array
                

                header("Location: " . BASE_URL_ADMIN . '?act=don-hang');
                exit();
            }else{
                // Trả về form  và lỗi
                // Đặt chỉ thị xóa session sao khi hiển thị form 
                $_SESSION['flash'] = true;
                header("Location: " . BASE_URL_ADMIN . '?act=form-sua-don-hang&id_don-hang=' . $don_hang_id);
                exit();
            }
        }
    }

    // public function postEditAnhSanPham(){
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $san_pham_id = $_POST['san_pham_id'] ?? '';

    //         // Lấy danh sách ảnh hiện tại của sản phẩm
    //         $listAnhSanPhamCurrent = $this->modelSanPham->getListAnhSanPham($san_pham_id);

    //         // Xử lý ảnh được gửi từ form 
    //         $img_array = $_FILES['img_array'];
    //         $img_delete = isset($_POST['img_delete']) ? explode(',', $_POST['img_delete']) : [];
    //         $current_img_ids = $_POST['current_img_ids'] ?? [];

    //         // Khai báo mẳng để lưu ảnh thêm mới hoặc thay thế ảnh cũ
    //         $upload_file = [];

    //         // Upload ảnh mới thay ảnh cũ
    //         foreach($img_array['name'] as $key=>$value){
    //             if($img_array['error'][$key] == UPLOAD_ERR_OK){
    //                 $new_file = uploadFileAlbum($img_array, './uploads/', $key);
    //                 if($new_file){
    //                     $upload_file[] = [
    //                         'id' => $current_img_ids[$key] ?? null,
    //                         'file' => $new_file
    //                     ];
    //                 }
    //             }
    //         }

    //         // Lưu ảnh mới vào db và xóa ảnh cũ nếu có
    //         foreach($upload_file as $file_info){
    //             if($file_info['id']){
    //                 $old_file = $this->modelSanPham->getDetailAnhSanPham($file_info['id'])['link_hinh_anh'];

    //                 // Cập nhật ảnh cũ
    //                 $this->modelSanPham->updateAnhSanPham($file_info['id'], $file_info['file']);

    //                 // Xóa ảnh cũ
    //                 deleteFile($old_file);
    //             }else {
    //                 // Thêm ảnh mới
    //                 $this->modelSanPham->insertAlbumAnhSanPham($san_pham_id, $file_info['file']);
    //             }
    //         }

    //         // Xử lý xóa ảnh
    //         foreach($listAnhSanPhamCurrent as $anhSP){
    //             $anh_id = $anhSP['id'];
    //             if(in_array($anh_id, $img_delete)){
    //                 // Xóa ảnh trong db
    //                 $this->modelSanPham->destroyAnhSanPham($anh_id);

    //                 // Xóa file
    //                 deleteFile($anhSP['link_hinh_anh']);
    //             }
    //         }
    //         header("Location: " . BASE_URL_ADMIN . '?act=form-sua-san-pham&id_san_pham=' . $san_pham_id);
    //         exit();
    //     }
    // }
    // public function deleteSanPham(){
    //     $id = $_GET['id_san_pham'];
    //     $sanPham = $this->modelSanPham->getDetailSanPham($id);

    //     $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);

    //     if ($sanPham) {
    //         deleteFile($sanPham['hinh_anh']);
    //         $this->modelSanPham->destroySanPham($id);
    //     }
    //     if($listAnhSanPham){
    //         foreach($listAnhSanPham as $key=>$anhSP){
    //             deleteFile($anhSP['link_hinh_anh']);
    //             $this->modelSanPham->destroyAnhSanPham($anhSP['id']);
    //         }
    //     }
    //     header("Location: " . BASE_URL_ADMIN . '?act=san-pham');
    //        exit();
    // }

    // public function detailSanPham(){
    //     $id = $_GET['id_san_pham'];
    //     $sanPham = $this->modelSanPham->getDetailSanPham($id);
    //     $listAnhSanPham = $this->modelSanPham->getListAnhSanPham($id);
    //     if ($sanPham) {
    //         require_once './views/sanpham/detailSanPham.php';
    //     }else{
    //         header("Location: " . BASE_URL_ADMIN . '?act=san-pham');
    //         exit();
    //     }
        
    // }
}
