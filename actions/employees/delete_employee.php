<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['emp_id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่ระบุรหัสพนักงาน']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    $emp_id = $data['emp_id'];
    $stmt = $conn->prepare("DELETE FROM employee_db WHERE Emp_id = ?");
    $stmt->execute([$emp_id]);

    echo json_encode(['success' => true, 'message' => 'ลบพนักงานสำเร็จ']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}