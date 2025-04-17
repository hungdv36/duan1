<?php
class DanhMuc {
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }
    //lay danh sach danh muc san pham

    public function danhSachDanhMuc(){
        try {
            $sql = 'SELECT * FROM danh_mucs';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return  $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
}
//danh muc san pham update 