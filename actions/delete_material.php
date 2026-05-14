<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่ระบุรหัสวัตถุดิบ']);
    exit;
}

try {
    // ตรวจสอบว่าสามารถลบได้หรือไม่ (กรณีมี Foreign Key อ้างอิง)
    $stmt = $conn->prepare("DELETE FROM material_db WHERE Material_id = ?");
    $stmt->execute([$data['id']]);

    echo json_encode(['success' => true, 'message' => 'ลบวัตถุดิบเรียบร้อยแล้ว']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบได้ (อาจมีการใช้ในประวัติการเบิกหรือรับเข้า): ' . $e->getMessage()]);
}
?>