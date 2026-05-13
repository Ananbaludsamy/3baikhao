<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

try {
    $stmt = $conn->query("SELECT * FROM materialtype_db ORDER BY MaterialType_id ASC");
    $material_types = $stmt->fetchAll();

    echo json_encode(['success' => true, 'material_types' => $material_types]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>