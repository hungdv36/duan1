<?php
class TaiKhoan {
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    //kiem tra dang nhap
    public function checkLogin($email, $password)
    {
        try {
            $sql = "SELECT * FROM tai_khoans WHERE email = :email AND trang_thai = 1 LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            if (password_verify($password, $user['mat_khau'])) {
                return $user;
            }
            return false;
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }
}