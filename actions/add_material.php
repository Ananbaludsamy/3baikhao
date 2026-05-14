<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['name']) || empty($data['type_id']) || !isset($data['cost']) || empty($data['unit'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

try {
    // เริ่มต้นสต็อกที่ 0 เมื่อเพิ่มวัตถุดิบใหม่
    $stmt = $conn->prepare("INSERT INTO material_db (Material_name, MaterialType_id, Material_costprice, Material_unit, Material_total) VALUES (?, ?, ?, ?, 0)");
    $stmt->execute([$data['name'], $data['type_id'], $data['cost'], $data['unit']]);

    echo json_encode(['success' => true, 'message' => 'เพิ่มวัตถุดิบใหม่สำเร็จ']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>