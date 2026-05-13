<?php
error_reporting(0); // ปิดการแสดงผล Error เพื่อไม่ให้ JSON พัง
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
    $table_no = $data['table_no'] ?? '-';
    $status = 'ชำระเงินแล้ว';

    // 0. ตรวจสอบว่าโต๊ะนี้มีออเดอร์ที่ยังไม่ได้เสิร์ฟค้างอยู่หรือไม่ (ป้องกันเลขโต๊ะซ้ำ)
    // เราจะเช็คเฉพาะกรณีที่ระบุหมายเลขโต๊ะมา (ไม่ใช่ '-')
    if ($table_no !== '-') {
        $stmt_check = $conn->prepare("SELECT COUNT(*) FROM saleh_db WHERE Table_no = ? AND TRIM(Sale_status) = ?");
        $stmt_check->execute([$table_no, $status]);
        if ($stmt_check->fetchColumn() > 0) {
            echo json_encode(['success' => false, 'message' => "โต๊ะหมายเลข $table_no มีออเดอร์ค้างอยู่ในระบบ กรุณาเสิร์ฟรายการเดิมให้เรียบร้อยก่อน"]);
            exit;
        }
    }

    // เริ่มต้น Transaction เพื่อป้องกันข้อมูลบันทึกไม่ครบ
    $conn->beginTransaction();

    // 1. บันทึกข้อมูลลง saleh_db (ส่วนหัวบิล)
    $sale_date = date('Y-m-d H:i:s');
    $cus_id = $data['cus_id'] ?? 1;
    $emp_id = $_SESSION['Emp_id'] ?? 1; // ดึงรหัสพนักงานจาก Session
    $total = $data['total'];

    $stmt_h = $conn->prepare("INSERT INTO saleh_db (Sale_date, Cus_id, Emp_id, Sale_sumprice, Sale_status, Table_no) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_h->execute([$sale_date, $cus_id, $emp_id, $total, $status, $table_no]);
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