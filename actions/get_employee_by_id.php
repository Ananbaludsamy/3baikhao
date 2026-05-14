<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

$emp_id = $_GET['id'] ?? null;

if (!$emp_id) {
    echo json_encode(['success' => false, 'message' => 'ไม่ระบุรหัสพนักงาน']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT Emp_id, Emp_name, Emp_card, Emp_Address, Emp_Tel, Emp_gender, Username, Role FROM employee_db WHERE Emp_id = ?");
    $stmt->execute([$emp_id]);
    $employee = $stmt->fetch();

    if ($employee) {
        echo json_encode(['success' => true, 'employee' => $employee]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลพนักงาน']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}