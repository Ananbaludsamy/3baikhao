<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่ระบุรหัสสินค้า']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    $id = $data['id'];
    $stmt = $conn->prepare("DELETE FROM product_db WHERE Product_id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบได้ (สินค้าอาจถูกใช้งานในประวัติการขายแล้ว): ' . $e->getMessage()]);
}