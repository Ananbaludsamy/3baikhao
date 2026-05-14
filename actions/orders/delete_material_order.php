<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$order_id = intval($data['order_id'] ?? 0);
if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'ບໍ່ພົບລະຫັດໃບສັ່ງຊື້']); exit;
}

try {
    $conn->beginTransaction();
    $conn->prepare("DELETE FROM orderd_db WHERE Order_id = ?")->execute([$order_id]);
    $conn->prepare("DELETE FROM orderh_db WHERE Order_id = ?")->execute([$order_id]);
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'ລຶບໃບສັ່ງຊື້ສໍາເລັດ']);
} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
