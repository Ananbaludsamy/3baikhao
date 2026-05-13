<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบรหัสลูกค้า']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM customer_db WHERE Cus_id = ?");
    $stmt->execute([$_GET['id']]);
    $customer = $stmt->fetch();
    echo json_encode(['success' => true, 'customer' => $customer]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}