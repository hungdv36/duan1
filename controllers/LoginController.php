<?php

class LoginController
{
    public $modelLogin;
    public function __construct()
    {
        $this->modelLogin = new TaiKhoan();
    }

    public function dangNhap()
    {
        try {
            require_once './views/login.php';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function xuLyDangNhap()
    {
        try {
            if(isset($_POST['btnLogin']))
            {
                $email = $_POST['email'];
                $password = $_POST['password'];

                $user = $this->modelLogin->checkLogin($email, $password);

                if($user){
                    //session user
                    $_SESSION['user'] = $user;
                    header('location: ?act=trang-chu');
                    exit();
                }
                header('location: ?act=dang-nhap');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function dangXuat()
    {
        unset($_SESSION['user']);
        header('location: ?act=trang-chu');
        exit();
    }
}