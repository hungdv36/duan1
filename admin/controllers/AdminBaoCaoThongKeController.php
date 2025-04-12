<?php
require_once './models/AdminBaoCaoThongKe.php';

class AdminBaoCaoThongKeController {
    public function home() {
        $model = new AdminBaoCaoThongKe();

        $totalOrders = $model->getTotalOrders();
        $totalRevenue = $model->getTotalRevenue();
        $totalProducts = $model->getTotalProducts();
        $totalUsers = $model->getTotalUsers();
        $revenueByDate = $model->getRevenueByDate();

        require_once './views/home.php';
    }
}