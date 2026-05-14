<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_login();
require_once '../includes/db_connect.php';

try {
    $stmt = $conn->query("SELECT m.*, t.MaterialType_name 
                          FROM material_db m 
                          JOIN materialtype_db t ON m.MaterialType_id = t.MaterialType_id 
                          ORDER BY m.Material_name ASC");
    $materials = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'materials' => $materials
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}