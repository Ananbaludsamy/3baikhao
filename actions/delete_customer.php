<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['cus_id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบรหัสลูกค้า']);
    exit;
}

try {
    // ตรวจสอบก่อนลบ ไม่ให้ลบ "ลูกค้าทั่วไป" (ID 1)
    if ($data['cus_id'] == 1) throw new Exception("ไม่สามารถลบข้อมูลลูกค้าทั่วไปได้");

    $stmt = $conn->prepare("DELETE FROM customer_db WHERE Cus_id = ?");
    $stmt->execute([$data['cus_id']]);
    echo json_encode(['success' => true, 'message' => 'ลบข้อมูลลูกค้าสำเร็จ']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}