<?php

// Kết nối CSDL qua PDO
function connectDB() {
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
        return $conn;
    } catch (PDOException $e) {
        echo ("Connection failed: " . $e->getMessage());
    }
}

// Thêm file
function uploadFile($file, $folderUpload){
    $pathStrorage = $folderUpload . time() . $file['name'];

    $from = $file['tmp_name'];
    $to = PATH_ROOT . $pathStrorage;

    if(move_uploaded_file($from, $to)){
        return $pathStrorage;
    }
    return null;
}

// Xóa file
function deleteFile($file){
    $pathDelete = PATH_ROOT . $file;
    if (file_exists($pathDelete)) {
        unlink($pathDelete);
    }
}

// Xóa session sau khi load trang
function deleteSessionError(){
    if (isset($_SESSION['flash'])) {
        // Hủy session sau khi tải trang
        unset($_SESSION['flash']);
        session_unset();
        session_destroy();
    }
}

// Upload - update album ảnh
function uploadFileAlbum($file, $folderUpload, $key){
    $pathStrorage = $folderUpload . time() . $file['name'][$key];

    $from = $file['tmp_name'][$key];
    $to = PATH_ROOT . $pathStrorage;

    if(move_uploaded_file($from, $to)){
        return $pathStrorage;
    }
    return null;
}

// format date
function formatDate($date){
    return date("d-m-Y", strtotime($date));
}