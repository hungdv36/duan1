<?php
class AdminTaiKhoanController
{
    public $modelTaiKhoan;

    public function __construct()
    {
        $this->modelTaiKhoan = new AdminTaiKhoan;
    }

    public function danhSachQuanTri()
    {
        $listQuanTri = $this->modelTaiKhoan->getAllTaiKhoan(1);

        require_once './views/taikhoan/quantri/listQuanTri.php';
    }

    public function formAddQuanTri()
    {
        require_once './views/taikhoan/quantri/addQuanTri.php';

        deleteSessionError();
    }

    public function postAddQuanTri(){
        // Hàm này dùng để xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có được submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu

            $ho_ten = $_POST['ho_ten'];
            $email = $_POST['email'];

            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            if (empty($ho_ten)) {
                $errors['ho_ten'] = 'Tên không được để trống';
            }
            if (empty($email)) {
                $errors['email'] = 'Email không được để trống';
            }

            $_SESSION['error'] = $errors;

            // Nếu không có lỗi thì tiến hành thêm tài khoản
            if (empty($errors)) {
                //  Nếu không có lỗi thì tiến hành thêm tài khoản
                // đặt password mặc định - 123@123ab
                $password = password_hash('123@123ab', PASSWORD_BCRYPT);

                // Khai báo chức vụ
                $chuc_vu_id = 1;
                // var_dump($password); die;
                $this->modelTaiKhoan->insertTaiKhoan($ho_ten, $email, $password, $chuc_vu_id);

                header("Location: " . BASE_URL_ADMIN . '?act=list-tai-khoan-quan-tri');
                exit();
            }else{
                // Trả về form  và lỗi
                $_SESSION['flash'] = true;

                header("Location: " . BASE_URL_ADMIN . '?act=form-them-quan-tri');
                exit();
            }
        }
    }

    public function formEditQuanTri(){
        $id_quan_tri = $_GET['id_quan_tri'];
        $quanTri = $this->modelTaiKhoan->getdetailTaiKhoan($id_quan_tri);
        // var_dump($quanTri);die;

        require_once './views/taikhoan/quantri/editQuanTri.php';
        deleteSessionError();
    }

    public function postEditQuanTri(){
        // Hàm này dùng để xử lý thêm dữ liệu

        // Kiểm tra xem dữ liệu có được submit lên không
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy ra dữ liệu
            // Lấy ra dữ liệu cũ của sản phẩm
            $quan_tri_id = $_POST['quan_tri_id'] ?? '';
          
            $ho_ten = $_POST['ho_ten'] ?? '';
            $email = $_POST['email'] ?? '';
            $so_dien_thoai = $_POST['so_dien_thoai'] ?? '';
            $trang_thai = $_POST['trang_thai'] ?? '';
            
            // Tạo 1 mảng trống để chứa dữ liệu
            $errors = [];
            if (empty($ho_ten)) {
                $errors['ho_ten'] = 'Tên người nhận không được để trống';
            }
            if (empty($email)) {
                $errors['email'] = 'Email người nhận không được để trống';
            }
            if (empty($trang_thai)) {
                $errors['trang_thai'] = 'Vui lòng nhập trạng thái';
            }
            
            $_SESSION['error'] = $errors;

            if (empty($errors)) {

                $this->modelTaiKhoan->updateTaiKhoan( $quan_tri_id,
                                                    $ho_ten, 
                                                    $email, 
                                                    $so_dien_thoai, 
                                                    $trang_thai,);

                header("Location: " . BASE_URL_ADMIN . '?act=list-tai-khoan-quan-tri');
                exit();
            }else{
                
                $_SESSION['flash'] = true;
                header("Location: " . BASE_URL_ADMIN . '?act=form-sua-quan-tri&id_quan_tri=' . $quan_tri_id);
                exit();
            }
        }
    }

    public function resetPasssword(){
        $tai_khoan_id = $_GET['id_quan_tri'];
        // đặt password mặc định - 123@123ab
        $password = password_hash('123@123ab', PASSWORD_BCRYPT);
        $status = $this->modelTaiKhoan->resetPassword($tai_khoan_id, $password);
        if($status){
            header("Location: " . BASE_URL_ADMIN . '?act=list-tai-khoan-quan-tri');
            exit();
        }else{
            var_dump('Lỗi khi reset tài khoản');die;
        }
    }
}