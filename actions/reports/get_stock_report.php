<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';
try {
    $stmt = $conn->query("
        SELECT m.Material_id, m.Material_name, m.Material_total, m.Material_unit,
               m.Material_costprice, mt.MaterialType_name
        FROM material_db m
        JOIN materialtype_db mt ON m.MaterialType_id = mt.MaterialType_id
        ORDER BY m.Material_total ASC
    ");
    $materials = $stmt->fetchAll();
    $low    = count(array_filter($materials, fn($m) => $m['Material_total'] <= 5));
    $medium = count(array_filter($materials, fn($m) => $m['Material_total'] > 5 && $m['Material_total'] <= 10));
    echo json_encode(['success' => true, 'materials' => $materials, 'low_count' => $low, 'medium_count' => $medium]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
