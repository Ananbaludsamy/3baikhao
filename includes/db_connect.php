<?php
// การตั้งค่าฐานข้อมูล (สำหรับ XAMPP)
$host     = "localhost";
$username = "root";
$password = "";
$dbname   = "3baikhao";

// ตั้งค่าเขตเวลาให้ตรงกับไทย
date_default_timezone_set('Asia/Bangkok');

try {
    // สร้างการเชื่อมต่อด้วย PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    // ตัวแปรเชื่อมต่อสำเร็จ (สำหรับใช้งานในไฟล์อื่น)
    $conn = $pdo;

} catch (PDOException $e) {
    // หากเชื่อมต่อไม่ได้ (เช่น ยังไม่ได้สร้าง Database) จะยังไม่ให้ระบบพัง แต่เก็บ Error ไว้ตรวจสอบ
    // error_log("Database Connection Error: " . $e->getMessage());
    $conn = null;
}
?>