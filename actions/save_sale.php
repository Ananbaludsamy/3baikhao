<?php
header('Content-Type: application/json');
session_start();
require_once '../includes/db_connect.php';
// รับข้อมูล JSON จาก Request Body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลรายการสินค้าในตะกร้า']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    // เริ่มต้น Transaction เพื่อป้องกันข้อมูลบันทึกไม่ครบ
    $conn->beginTransaction();

    // 1. บันทึกข้อมูลลง saleh_db (ส่วนหัวบิล)
    $sale_date = date('Y-m-d H:i:s');
    $cus_id = $data['cus_id'] ?? 1;
    $emp_id = $_SESSION['Emp_id'] ?? 1; // ดึงรหัสพนักงานจาก Session
    $total = $data['total'];
    $status = 'ชำระเงินแล้ว';

    $stmt_h = $conn->prepare("INSERT INTO saleh_db (Sale_date, Cus_id, Emp_id, Sale_sumprice, Sale_status) VALUES (?, ?, ?, ?, ?)");
    $stmt_h->execute([$sale_date, $cus_id, $emp_id, $total, $status]);
    $sale_id = $conn->lastInsertId();

    // 2. บันทึกข้อมูลลง saled_db (รายละเอียดสินค้าในบิล)
    $stmt_d = $conn->prepare("INSERT INTO saled_db (Sale_id, Product_id, Sale_total, Sale_price, Sale_sumprice) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($data['cart'] as $item) {
        $line_total = $item['price'] * $item['qty'];
        $stmt_d->execute([$sale_id, $item['id'], $item['qty'], $item['price'], $line_total]);
    }

    // ยืนยันการบันทึกทั้งหมด
    $conn->commit();
    
    $order_id = 'ORD-' . str_pad($sale_id, 5, '0', STR_PAD_LEFT);
    echo json_encode(['success' => true, 'sale_id' => $sale_id, 'order_id' => $order_id]);

} catch (Exception $e) {
    if ($conn && $conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}