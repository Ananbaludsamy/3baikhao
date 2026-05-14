<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$order_id = intval($_GET['id'] ?? 0);
if (!$order_id) {
    echo json_encode(['success' => false, 'message' => 'ບໍ່ພົບລະຫັດໃບສັ່ງຊື້']); exit;
}

try {
    $stmt_h = $conn->prepare("
        SELECT oh.Order_id, oh.Order_date, oh.Order_sumprice, oh.Order_sumtotal, oh.Order_note, e.Emp_name
        FROM orderh_db oh
        JOIN employee_db e ON oh.Emp_id = e.Emp_id
        WHERE oh.Order_id = ?
    ");
    $stmt_h->execute([$order_id]);
    $header = $stmt_h->fetch();

    if (!$header) {
        echo json_encode(['success' => false, 'message' => 'ບໍ່ພົບໃບສັ່ງຊື້']); exit;
    }

    $stmt_d = $conn->prepare("
        SELECT od.Order_total, od.Order_price, od.Order_sumprice, od.Material_id, m.Material_name, m.Material_unit
        FROM orderd_db od
        JOIN material_db m ON od.Material_id = m.Material_id
        WHERE od.Order_id = ?
        ORDER BY od.Order_id ASC
    ");
    $stmt_d->execute([$order_id]);
    $items = $stmt_d->fetchAll();

    echo json_encode(['success' => true, 'header' => $header, 'items' => $items]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
