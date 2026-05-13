<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

// รับข้อมูล JSON จาก Request Body
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data || empty($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลรายการสั่งซื้อ']);
    exit;
}

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้']);
    exit;
}

try {
    // เริ่มต้น Transaction
    $conn->beginTransaction();

    // 1. เตรียมข้อมูลสำหรับตาราง orderh_db (หัวบิล)
    $order_date = $data['order_date'] ?? date('Y-m-d');
    $emp_id = $data['emp_id'] ?: 1; // ถ้าไม่มีส่งมาให้ใช้ ID 1 เป็น Default
    $order_note = $data['note'] ?? '';
    
    // คำนวณยอดรวมจำนวนชิ้นและยอดเงินรวม
    $total_qty = 0;
    $grand_total = 0;
    foreach ($data['items'] as $item) {
        $total_qty += (int)$item['qty'];
        $grand_total += (int)$item['qty'] * (int)$item['price'];
    }

    // บันทึกหัวบิล
    $stmt_h = $conn->prepare("INSERT INTO orderh_db (Order_date, Emp_id, Order_sumtotal, Order_sumprice, Order_note) VALUES (?, ?, ?, ?, ?)");
    $stmt_h->execute([$order_date, $emp_id, $total_qty, $grand_total, $order_note]);
    $order_id = $conn->lastInsertId();

    // 2. บันทึกรายละเอียดลง orderd_db
    $stmt_d = $conn->prepare("INSERT INTO orderd_db (Order_id, Material_id, Order_total, Order_price, Order_sumprice) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($data['items'] as $item) {
        $line_total = (int)$item['qty'] * (int)$item['price'];
        $stmt_d->execute([
            $order_id,
            $item['mat_id'],
            $item['qty'],
            $item['price'],
            $line_total
        ]);
    }

    // ยืนยันการบันทึกทั้งหมด
    $conn->commit();
    
    $po_number = 'PO-' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
    echo json_encode(['success' => true, 'message' => 'บันทึกใบสั่งซื้อสำเร็จ', 'order_no' => $po_number]);

} catch (Exception $e) {
    if ($conn && $conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}