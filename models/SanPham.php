<?php 
class SanPham {
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function danhSachSanPham(){
        try {
            $sql = 'SELECT san_phams.*, 
                           danh_mucs.ten_danh_muc, 
                           GROUP_CONCAT(hinh_anh_san_phams.link_hinh_anh) AS hinh_anh_san_pham
                    FROM san_phams
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
                    LEFT JOIN hinh_anh_san_phams ON san_phams.id = hinh_anh_san_phams.san_pham_id
                    GROUP BY san_phams.id';
            $stmt = $this->conn->prepare($sql);

            $stmt->execute();

            return  $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function danhSachSanPhamNoiBat()
    {
        try {
            $sql = 'SELECT san_phams.*, 
                           danh_mucs.ten_danh_muc, 
                           GROUP_CONCAT(hinh_anh_san_phams.link_hinh_anh) AS hinh_anh_san_pham
                    FROM san_phams
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
                    LEFT JOIN hinh_anh_san_phams ON san_phams.id = hinh_anh_san_phams.san_pham_id
                    GROUP BY san_phams.id
                    ORDER BY san_phams.luot_xem DESC 
                    LIMIT 10';
            $stmt = $this->conn->prepare($sql);

            $stmt->execute();

            return  $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function layChiTietSanPham($id){
        try {
            $sql = 'SELECT san_phams.*, 
                           danh_mucs.ten_danh_muc, 
                           GROUP_CONCAT(hinh_anh_san_phams.link_hinh_anh) AS hinh_anh_san_pham
                    FROM san_phams
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
                    LEFT JOIN hinh_anh_san_phams ON san_phams.id = hinh_anh_san_phams.san_pham_id
                    WHERE san_phams.id = :id
                    GROUP BY san_phams.id
                    ORDER BY san_phams.luot_xem DESC 
                    LIMIT 10';
            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);

            return  $stmt->fetch();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }

    public function laySanPhamTheoDanhMuc($id){
        try {
            $sql = 'SELECT san_phams.*, 
                           danh_mucs.ten_danh_muc, 
                           GROUP_CONCAT(hinh_anh_san_phams.link_hinh_anh) AS hinh_anh_san_pham
                    FROM san_phams
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
                    LEFT JOIN hinh_anh_san_phams ON san_phams.id = hinh_anh_san_phams.san_pham_id
                    WHERE san_phams.danh_muc_id = :danh_muc_id
                    GROUP BY san_phams.id
                    ORDER BY san_phams.luot_xem DESC
                    LIMIT 10';
            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':danh_muc_id' => $id]);

            return  $stmt->fetchAll();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
}