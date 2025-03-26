<?php 

class HomeController
{
    public $modelSanPham;
    public function __construct()
    {
        $this->modelSanPham = new SanPham();
    }

    public function home(){
       
    }

    public function trangchu(){
        echo 'Đây là trang chủ';
    }

    public function danhSachSanPham(){
        $listProduct = $this->modelSanPham->getAllProduct();
        // var_dump($listProduct);die();
        require_once './views/listProduct.php';
    }
}