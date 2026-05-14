<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';
$data = json_decode(file_get_contents('php://input'), true);
$cus_id = intval($data['cus_id'] ?? 0);
$points = intval($data['points'] ?? 0);
$note   = trim($data['note'] ?? 'ປັບຄ່ານວນຄະແນນໂດຍ Admin');
if (!$cus_id || $points === 0) {
    echo json_encode(['success' => false, 'message' => 'ຂໍ້ມູນບໍ່ຄົບ']); exit;
}
try {
    $conn->beginTransaction();
    $stmt = $conn->prepare("UPDATE customer_db SET Cus_Points = GREATEST(0, Cus_Points + ?) WHERE Cus_id = ?");
    $stmt->execute([$points, $cus_id]);
    $stmt_log = $conn->prepare("INSERT INTO point_log_db (Cus_id, Sale_id, Points, Log_type, Log_note) VALUES (?, NULL, ?, 'adjust', ?)");
    $stmt_log->execute([$cus_id, $points, $note]);
    $conn->commit();
    // Return updated points
    $stmt2 = $conn->prepare("SELECT Cus_Points FROM customer_db WHERE Cus_id = ?");
    $stmt2->execute([$cus_id]);
    $row = $stmt2->fetch();
    echo json_encode(['success' => true, 'message' => 'ປັບຄະແນນສໍາເລັດ', 'new_points' => $row['Cus_Points']]);
} catch (Exception $e) {
    if ($conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
