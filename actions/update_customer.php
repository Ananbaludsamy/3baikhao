<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['cus_id']) || empty($data['name']) || empty($data['tel']) || empty($data['address'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE customer_db SET Cus_name = ?, Cus_Tel = ?, Cus_Address = ? WHERE Cus_id = ?");
    $stmt->execute([$data['name'], $data['tel'], $data['address'], $data['cus_id']]);
    echo json_encode(['success' => true, 'message' => 'อัปเดตข้อมูลลูกค้าสำเร็จ']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}