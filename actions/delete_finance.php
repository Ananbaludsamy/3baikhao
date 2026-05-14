<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ບໍ່ພົບລະຫັດລາຍການ']); exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM revenued_db WHERE Revenue_no = ?");
    $stmt->execute([$id]);
    if ($stmt->rowCount() === 0) {
        echo json_encode(['success' => false, 'message' => 'ບໍ່ພົບລາຍການນີ້']); exit;
    }
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
