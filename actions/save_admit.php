<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['mat_id']) || empty($data['qty']) || !isset($data['price'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

try {
    $conn->beginTransaction();

    $qty = (int)$data['qty'];
    $price = (float)$data['price'];
    $mat_id = $data['mat_id'];
    $sum_price = $qty * $price;
    $emp_id = 1; // กำหนดพนักงานเริ่มต้นเป็น ID 1

    // 1. บันทึกหัวบิลรับเข้า (admith_db)
    $stmt_h = $conn->prepare("INSERT INTO admith_db (Admit_date, Emp_id, Admit_sumprice, Admit_Status) VALUES (NOW(), ?, ?, 1)");
    $stmt_h->execute([$emp_id, $sum_price]);
    $admit_id = $conn->lastInsertId();

    // 2. บันทึกรายละเอียดรับเข้า (admitd_db) - ใช้ Admit_sunprice ตามชื่อฟิลด์ในโครงสร้างตารางเดิม
    $stmt_d = $conn->prepare("INSERT INTO admitd_db (Admit_id, Material_id, Admit_price, Admit_total, Admit_sunprice) VALUES (?, ?, ?, ?, ?)");
    $stmt_d->execute([$admit_id, $mat_id, $price, $qty, $sum_price]);

    // 3. ปรับปรุงสต็อกปัจจุบันและอัปเดตราคาต้นทุนล่าสุด (material_db)
    $stmt_update = $conn->prepare("UPDATE material_db SET Material_total = Material_total + ?, Material_costprice = ? WHERE Material_id = ?");
    $stmt_update->execute([$qty, $price, $mat_id]);

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'รับเข้าวัตถุดิบและอัปเดตสต็อกเรียบร้อยแล้ว']);

} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}