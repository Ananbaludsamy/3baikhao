<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_login();
require_once '../includes/db_connect.php';

try {
    $today = date('Y-m-d');

    $pending = (int)$conn->query("SELECT COUNT(*) FROM saleh_db WHERE TRIM(Sale_status) IN ('ชำระเงินแล้ว','ຊໍາລະເງິນແລ້ວ')")->fetchColumn();

    $stmt = $conn->prepare("SELECT COUNT(*) FROM saleh_db WHERE TRIM(Sale_status) IN ('เสิร์ฟแล้ว','ເສີບແລ້ວ') AND DATE(Sale_date) = ?");
    $stmt->execute([$today]);
    $served_today = (int)$stmt->fetchColumn();

    $stmt2 = $conn->prepare("SELECT COUNT(*) FROM saleh_db WHERE DATE(Sale_date) = ?");
    $stmt2->execute([$today]);
    $total_today = (int)$stmt2->fetchColumn();

    $low_stock = (int)$conn->query("SELECT COUNT(*) FROM material_db WHERE Material_total < 10")->fetchColumn();

    echo json_encode([
        'success'     => true,
        'pending'     => $pending,
        'served_today'=> $served_today,
        'total_today' => $total_today,
        'low_stock'   => $low_stock,
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
