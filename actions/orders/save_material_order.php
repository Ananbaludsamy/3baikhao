<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'ບໍ່ພົບຂໍ້ມູນລາຍການສັ່ງຊື້']);
    exit;
}

try {
    $conn->beginTransaction();

    $order_date = $data['order_date'] ?? date('Y-m-d');
    $emp_id     = $data['emp_id'] ?: ($_SESSION['Emp_id'] ?? 1);
    $order_note = $data['note'] ?? '';

    // Order_sumtotal = จำนวน items, Order_sumprice = 0 (ไม่มีราคาในขั้นตอนสั่ง)
    $item_count = count($data['items']);

    $stmt_h = $conn->prepare("INSERT INTO orderh_db (Order_date, Emp_id, Order_sumtotal, Order_sumprice, Order_note) VALUES (?, ?, ?, 0, ?)");
    $stmt_h->execute([$order_date, $emp_id, $item_count, $order_note]);
    $order_id = $conn->lastInsertId();

    $stmt_d = $conn->prepare("INSERT INTO orderd_db (Order_id, Material_id, Order_total, Order_price, Order_sumprice) VALUES (?, ?, ?, 0, 0)");
    foreach ($data['items'] as $item) {
        $stmt_d->execute([$order_id, $item['mat_id'], $item['qty']]);
    }

    $conn->commit();

    $po_number = 'PO-' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
    echo json_encode(['success' => true, 'message' => 'ບັນທຶກໃບສັ່ງຊື້ສໍາເລັດ', 'order_no' => $po_number]);

} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
