<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['name'])) {
    echo json_encode(['success' => false, 'message' => 'กรุณาระบุชื่อประเภทสินค้า']);
    exit;
}

try {
    $name = $data['name'];
    $stmt = $conn->prepare("INSERT INTO producttype_db (ProductType_name) VALUES (?)");
    $stmt->execute([$name]);

    echo json_encode(['success' => true, 'message' => 'เพิ่มประเภทสินค้าเรียบร้อยแล้ว']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}