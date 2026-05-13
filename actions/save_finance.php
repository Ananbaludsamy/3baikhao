<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['name']) || empty($data['amount']) || empty($data['type_id'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

try {
    $name = $data['name'];
    $amount = (float)$data['amount'];
    $type_id = (int)$data['type_id']; // 1 = รายรับ, 2 = รายจ่าย
    $date = date('Y-m-d');

    // ตรวจสอบว่ามีรหัสประเภทใน revenueh_db หรือยัง (ถ้าไม่มีให้ข้ามหรือสร้างใหม่)
    // ในที่นี้เราจะบันทึกลง revenued_db โดยตรง
    $stmt = $conn->prepare("INSERT INTO revenued_db (Revenue_id, Revenue_name, Revenue_date, Revenue_Price) VALUES (?, ?, ?, ?)");
    $stmt->execute([$type_id, $name, $date, $amount]);

    echo json_encode([
        'success' => true, 
        'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว',
        'type_id' => $type_id
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>