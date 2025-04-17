<?php
class SanPham
{
    public $conn; // khai báo phương thức

    public function __construct()
    {
        $this->conn = connectDB();
    }

    //Viết hàm lấy toàn bộ danh sách sản phẩm
    public function getAllSanPham($filters = [])
    {
        try {
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc 
                    FROM san_phams 
                    INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id 
                    WHERE san_phams.trang_thai = 1';
            $params = [];

            // Lọc theo danh mục
            if (!empty($filters['danh_muc_id']) && is_numeric($filters['danh_muc_id'])) {
                $sql .= ' AND san_phams.danh_muc_id = :danh_muc_id';
                $params[':danh_muc_id'] = (int)$filters['danh_muc_id'];
            }

            // Lọc theo giá
            if (isset($filters['gia_min']) && is_numeric($filters['gia_min'])) {
                $sql .= ' AND COALESCE(san_phams.gia_khuyen_mai, san_phams.gia_san_pham) >= :gia_min';
                $params[':gia_min'] = (float)$filters['gia_min'];
            }
            if (isset($filters['gia_max']) && is_numeric($filters['gia_max'])) {
                $sql .= ' AND COALESCE(san_phams.gia_khuyen_mai, san_phams.gia_san_pham) <= :gia_max';
                $params[':gia_max'] = (float)$filters['gia_max'];
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (\Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }
    public function getDetailSanPham($id)
    {
        try {
            $sql = 'SELECT san_phams.*,danh_mucs.ten_danh_muc 
            FROM san_phams 
            INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id
            WHERE san_phams.id = :id';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([':id' => $id]);

            return $stmt->fetch();
        } catch (\Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

    public function getListAnhSanPham($id)
    {
        try {
            $sql = 'SELECT *FROM hinh_anh_san_phams WHERE san_pham_id =:id';
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

    public function getBinhLuanFromSanPham($id)
    {
        try {
            $sql = 'SELECT binh_luans.*,tai_khoans.ho_ten,tai_khoans.anh_dai_dien
             FROM binh_luans
             INNER JOIN tai_khoans ON binh_luans.tai_khoan_id = tai_khoans.id
             WHERE binh_luans.san_pham_id = :id';

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo "lỗi" . $e->getMessage();
        }
    }

    public function listSanPhamDanhMuc($danh_muc_id)
    {
        try {
            $sql = 'SELECT san_phams.*, danh_mucs.ten_danh_muc 
            FROM san_phams 
            INNER JOIN danh_mucs ON san_phams.danh_muc_id = danh_mucs.id 
            WHERE  san_phams.danh_muc_id ='.$danh_muc_id;
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\Exception $e) {
            echo "lỗi" . $e->getMessage();
        }   
    }
    
}
