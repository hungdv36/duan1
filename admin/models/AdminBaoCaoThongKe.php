<?php 
class AdminBaoCaoThongKe {
    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=duan1;charset=utf8mb4', 'root', '');
    }

    public function getTotalOrders() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM don_hangs");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalRevenue() {
        $stmt = $this->db->query("SELECT SUM(tong_tien) as revenue FROM don_hangs");
        return $stmt->fetch(PDO::FETCH_ASSOC)['revenue'];
    }

    public function getTotalProducts() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM san_phams");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getTotalUsers() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM tai_khoans");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getRevenueByDate() {
        $stmt = $this->db->query("
            SELECT DATE(ngay_dat) as date, SUM(tong_tien) as revenue
            FROM don_hangs
            GROUP BY DATE(ngay_dat)
            ORDER BY DATE(ngay_dat) ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}