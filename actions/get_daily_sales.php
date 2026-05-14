<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';
$date = $_GET['date'] ?? date('Y-m-d');
try {
    $stmt = $conn->prepare("
        SELECT sh.Sale_id, sh.Sale_date, sh.Sale_sumprice, sh.Table_no, c.Cus_name,
               GROUP_CONCAT(CONCAT(p.Product_name, ' x', d.Sale_total) ORDER BY p.Product_name SEPARATOR ', ') AS items
        FROM saleh_db sh
        JOIN customer_db c ON sh.Cus_id = c.Cus_id
        LEFT JOIN saled_db d ON sh.Sale_id = d.Sale_id
        LEFT JOIN product_db p ON d.Product_id = p.Product_id
        WHERE DATE(sh.Sale_date) = ?
        GROUP BY sh.Sale_id, sh.Sale_date, sh.Sale_sumprice, sh.Table_no, c.Cus_name
        ORDER BY sh.Sale_id DESC
    ");
    $stmt->execute([$date]);
    $sales = $stmt->fetchAll();
    $total = array_sum(array_column($sales, 'Sale_sumprice'));
    echo json_encode(['success' => true, 'sales' => $sales, 'total' => $total, 'count' => count($sales)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
