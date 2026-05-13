<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['cus_id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่ระบุรหัสลูกค้า']);
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM customer_db WHERE Cus_id = ?");
    $stmt->execute([$data['cus_id']]);
    echo json_encode(['success' => true, 'message' => 'ลบข้อมูลลูกค้าสำเร็จ']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบข้อมูลลูกค้าได้ (อาจมีประวัติการซื้อขายผูกไว้): ' . $e->getMessage()]);
}