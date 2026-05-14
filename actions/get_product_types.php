<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

try {
    $stmt = $conn->query("SELECT * FROM producttype_db ORDER BY ProductType_id ASC");
    $categories = $stmt->fetchAll();

    echo json_encode(['success' => true, 'categories' => $categories]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}