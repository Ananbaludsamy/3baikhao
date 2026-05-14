<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_login();
require_once '../includes/db_connect.php';

try {
    $stmt = $conn->query("SELECT * FROM customer_db ORDER BY Cus_id DESC");
    $customers = $stmt->fetchAll();
    echo json_encode(['success' => true, 'customers' => $customers]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}