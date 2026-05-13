<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่ระบุรหัสที่ต้องการลบ']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

try {
    $id = $data['id'];
    $stmt = $conn->prepare("DELETE FROM materialtype_db WHERE MaterialType_id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true, 'message' => 'ลบประเภทวัตถุดิบเรียบร้อยแล้ว']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบได้ (อาจมีวัตถุดิบอยู่ในประเภทนี้): ' . $e->getMessage()]);
}
?>