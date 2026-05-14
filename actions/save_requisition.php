<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_login();
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['mat_id']) || empty($data['qty'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

try {
    $conn->beginTransaction();

    // 1. บันทึกประวัติการเบิก (Emp_id 1 เป็นค่าเริ่มต้น)
    $stmt_req = $conn->prepare("INSERT INTO requisition_db (Material_id, Req_qty, Req_date, Emp_id, Req_note) VALUES (?, ?, NOW(), 1, ?)");
    $stmt_req->execute([$data['mat_id'], $data['qty'], $data['note']]);

    // 2. ตรวจสอบสต็อกปัจจุบัน
    $stmt_check = $conn->prepare("SELECT Material_total FROM material_db WHERE Material_id = ?");
    $stmt_check->execute([$data['mat_id']]);
    $current_stock = $stmt_check->fetchColumn();

    if ($current_stock < $data['qty']) {
        throw new Exception("วัตถุดิบไม่เพียงพอสำหรับการเบิก (คงเหลือ: $current_stock)");
    }

    // 3. ปรับปรุงยอดสต็อก (ลดจำนวนลง)
    $stmt_update = $conn->prepare("UPDATE material_db SET Material_total = Material_total - ? WHERE Material_id = ?");
    $stmt_update->execute([$data['qty'], $data['mat_id']]);

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'เบิกวัตถุดิบสำเร็จ']);

} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}