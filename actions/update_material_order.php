<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['order_id']) || empty($data['items'])) {
    echo json_encode(['success' => false, 'message' => 'ຂໍ້ມູນບໍ່ຄົບ']); exit;
}

try {
    $conn->beginTransaction();

    $order_id   = intval($data['order_id']);
    $order_date = $data['order_date'] ?? date('Y-m-d');
    $order_note = $data['note'] ?? '';
    $item_count = count($data['items']);

    $stmt_h = $conn->prepare("UPDATE orderh_db SET Order_date = ?, Order_sumtotal = ?, Order_note = ? WHERE Order_id = ?");
    $stmt_h->execute([$order_date, $item_count, $order_note, $order_id]);

    $conn->prepare("DELETE FROM orderd_db WHERE Order_id = ?")->execute([$order_id]);

    $stmt_d = $conn->prepare("INSERT INTO orderd_db (Order_id, Material_id, Order_total, Order_price, Order_sumprice) VALUES (?, ?, ?, 0, 0)");
    foreach ($data['items'] as $item) {
        $stmt_d->execute([$order_id, $item['mat_id'], $item['qty']]);
    }

    $conn->commit();

    $po_number = 'PO-' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
    echo json_encode(['success' => true, 'message' => 'ແກ້ໄຂໃບສັ່ງຊື້ສໍາເລັດ', 'order_no' => $po_number]);

} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
