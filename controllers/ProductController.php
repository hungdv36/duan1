<?php

class ProductController
{
    public $modelSanPham;
    public $modelDanhMuc;
    public function __construct()
    {
        $this->modelSanPham = new SanPham();
        $this->modelDanhMuc = new DanhMuc();
    }

    public function chiTietSanPham()
    {
        try {
            if(!$id = $_GET["id"]){
                echo "Chưa có id để lấy chi tiết sản phẩm"; die();
            }

            $id_danhmuc = $_GET['id_danhmuc'];

            $chiTietSanPham = $this->modelSanPham->layChiTietSanPham($id);
            $dsSanPhamLienQuan = $this->modelSanPham->laySanPhamTheoDanhMuc($id_danhmuc);
            require_once './views/detail.php';
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}