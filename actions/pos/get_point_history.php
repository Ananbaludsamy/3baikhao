<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';
$cus_id = intval($_GET['cus_id'] ?? 0);
if (!$cus_id) { echo json_encode(['success' => false, 'message' => 'ບໍ່ພົບລະຫັດລູກຄ້າ']); exit; }
try {
    $stmt = $conn->prepare("
        SELECT l.Log_id, l.Points, l.Log_type, l.Log_note, l.Log_date,
               l.Sale_id
        FROM point_log_db l
        WHERE l.Cus_id = ?
        ORDER BY l.Log_date DESC
        LIMIT 50
    ");
    $stmt->execute([$cus_id]);
    $logs = $stmt->fetchAll();
    echo json_encode(['success' => true, 'logs' => $logs]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
