<?php
session_start();
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $conn->prepare("SELECT * FROM employee_db WHERE Username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            // ตรวจสอบรหัสผ่าน (รองรับทั้งแบบ Hash และ Plain text เผื่อกรณีข้อมูลเก่า)
            $is_valid = password_verify($password, $user['Password']) || ($password === $user['Password']);

            if ($is_valid) {
                $_SESSION['Emp_id'] = $user['Emp_id'];
                $_SESSION['Emp_name'] = $user['Emp_name'];
                $_SESSION['Role'] = $user['Role']; // 'admin' หรือ 'staff'
                
                header("Location: ../index.php");
                exit;
            }
        }
        
        $_SESSION['login_error'] = "Username หรือ Password ไม่ถูกต้อง";
        header("Location: ../login.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['login_error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
        header("Location: ../login.php");
        exit;
    }
}
header("Location: ../login.php");