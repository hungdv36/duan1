<?php

class TaiKhoan
{
    public $conn;

    public function __construct()
    {
        $this->conn = connectDB();
    }

    public function checklogin($email, $mat_khau){
        try {
            $sql = "SELECT * FROM tai_khoans WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email'=>$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($mat_khau, $user['mat_khau'])) {
                if ($user['chuc_vu_id'] == 2) {
                    if($user['trang_thai'] == 1){
                        return $user['email'];
                    }else{
                        return "Tài khoản bị cấm";
                    }
                }else {
                    return "Tài khoản không có quyền đăng nhập";
                }
            }else{
                return "Bạn nhập sai thông tin mật khẩu hoặc tài khoản";
            }
        } catch (\Exception $e) {
            echo "Lỗi: " . $e->getMessage();
            return false;
        }
    }
    public function getTaiKhoanFromEmail($email){
        try {
            $sql = 'SELECT * FROM tai_khoans WHERE email = :email';

            $stmt = $this->conn->prepare($sql);

            $stmt->execute([
                ':email' => $email,
            ]);

            return  $stmt->fetch();
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage();
        }
    }
    public function register($ho_ten, $email, $mat_khau)
    {
        try {
            // Kiểm tra xem email đã tồn tại chưa
            $sql = "SELECT * FROM tai_khoans WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email' => $email]);
            if ($stmt->fetch()) {
                return "Email đã tồn tại";
            }

            // Mã hóa mật khẩu
            $hashed_password = password_hash($mat_khau, PASSWORD_DEFAULT);

            // Thêm người dùng mới
            $sql = "INSERT INTO tai_khoans (ho_ten, email, mat_khau, chuc_vu_id, trang_thai) VALUES (:ho_ten, :email, :mat_khau, :chuc_vu_id, :trang_thai)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':ho_ten' => $ho_ten,
                ':email' => $email,
                ':mat_khau' => $hashed_password,
                ':chuc_vu_id' => 2,
                ':trang_thai' => 1
            ]);

            return true;
        } catch (\Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            return "Đã xảy ra lỗi khi đăng ký: " . $e->getMessage();
        }
    }
}