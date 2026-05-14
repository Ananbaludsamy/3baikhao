<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';
try {
    $stmt = $conn->query("
        SELECT oh.Order_id, oh.Order_date, oh.Order_sumprice, oh.Order_sumtotal, oh.Order_note, e.Emp_name
        FROM orderh_db oh
        JOIN employee_db e ON oh.Emp_id = e.Emp_id
        ORDER BY oh.Order_id DESC LIMIT 300
    ");
    $orders = $stmt->fetchAll();
    echo json_encode(['success' => true, 'orders' => $orders]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
