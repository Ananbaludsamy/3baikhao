<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

// รับข้อมูล JSON จากการส่งค่าด้วย fetch หรือ axios
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['sale_id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบรหัสบิลที่ต้องการอัปเดต']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    $sale_id = $data['sale_id'];
    $new_status = 'เสิร์ฟแล้ว';

    // อัปเดตสถานะในตาราง saleh_db
    $stmt = $conn->prepare("UPDATE saleh_db SET Sale_status = ? WHERE Sale_id = ?");
    $stmt->execute([$new_status, $sale_id]);

    // ส่งผลลัพธ์กลับไปยัง JavaScript
    echo json_encode(['success' => true, 'message' => 'อัปเดตสถานะเป็นเสิร์ฟแล้วเรียบร้อย']);

} catch (Exception $e) {
    // กรณีเกิดข้อผิดพลาด
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}