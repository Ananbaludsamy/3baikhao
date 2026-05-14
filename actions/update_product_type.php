<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['id']) || empty($data['name'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

try {
    $id = $data['id'];
    $name = $data['name'];
    
    $stmt = $conn->prepare("UPDATE producttype_db SET ProductType_name = ? WHERE ProductType_id = ?");
    $stmt->execute([$name, $id]);

    echo json_encode(['success' => true, 'message' => 'อัปเดตประเภทสินค้าเรียบร้อยแล้ว']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}