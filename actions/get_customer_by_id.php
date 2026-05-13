<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

$cus_id = $_GET['id'] ?? null;
if (!$cus_id) {
    echo json_encode(['success' => false, 'message' => 'ไม่ระบุรหัสลูกค้า']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT * FROM customer_db WHERE Cus_id = ?");
    $stmt->execute([$cus_id]);
    $customer = $stmt->fetch();
    if ($customer) {
        echo json_encode(['success' => true, 'customer' => $customer]);
    } else {
        echo json_encode(['success' => false, 'message' => 'ไม่พบข้อมูลลูกค้า']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}