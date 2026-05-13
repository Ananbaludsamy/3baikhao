<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    // ดึงข้อมูลพนักงานโดยไม่เอารหัสผ่านออกมา
    $stmt = $conn->query("SELECT Emp_id, Emp_name, Emp_card, Emp_Address, Emp_Tel, Emp_gender, Username, Role FROM employee_db ORDER BY Emp_id DESC");
    $employees = $stmt->fetchAll();
    echo json_encode(['success' => true, 'employees' => $employees]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>