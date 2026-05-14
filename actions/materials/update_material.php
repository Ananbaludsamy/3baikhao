<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['id']) || empty($data['name']) || empty($data['type_id']) || !isset($data['cost']) || empty($data['unit'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE material_db SET Material_name = ?, MaterialType_id = ?, Material_costprice = ?, Material_unit = ? WHERE Material_id = ?");
    $stmt->execute([$data['name'], $data['type_id'], $data['cost'], $data['unit'], $data['id']]);

    echo json_encode(['success' => true, 'message' => 'อัปเดตข้อมูลวัตถุดิบสำเร็จ']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>