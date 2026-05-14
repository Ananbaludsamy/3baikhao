<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่ระบุรหัสที่ต้องการลบ']);
    exit;
}

try {
    $id = $data['id'];
    // หมายเหตุ: หากมีสินค้าผูกกับประเภทนี้อยู่ จะลบไม่ได้เนื่องจาก Foreign Key
    $stmt = $conn->prepare("DELETE FROM producttype_db WHERE ProductType_id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'ลบประเภทสินค้าเรียบร้อยแล้ว']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบได้ (อาจมีสินค้าอยู่ในประเภทนี้): ' . $e->getMessage()]);
}