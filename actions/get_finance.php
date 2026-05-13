<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

try {
    $month = date('Y-m');
    
    // 1. สรุปรายรับเดือนนี้ (Revenue_id = 1) และรายจ่าย (Revenue_id = 2)
    // รวมรายรับจากการขายอาหารด้วย (saleh_db)
    $stmt_sales = $conn->prepare("SELECT SUM(Sale_sumprice) FROM saleh_db WHERE Sale_date LIKE ?");
    $stmt_sales->execute([$month . '%']);
    $sales_total = (float)$stmt_sales->fetchColumn();

    $stmt_revenue = $conn->prepare("SELECT SUM(Revenue_Price) FROM revenued_db WHERE Revenue_id = 1 AND Revenue_date LIKE ?");
    $stmt_revenue->execute([$month . '%']);
    $other_revenue = (float)$stmt_revenue->fetchColumn();

    $stmt_expense = $conn->prepare("SELECT SUM(Revenue_Price) FROM revenued_db WHERE Revenue_id = 2 AND Revenue_date LIKE ?");
    $stmt_expense->execute([$month . '%']);
    $expense_total = (float)$stmt_expense->fetchColumn();

    // 2. ดึงประวัติ 20 รายการล่าสุด
    $stmt_history = $conn->query("SELECT * FROM revenued_db ORDER BY Revenue_no DESC LIMIT 20");
    $history = $stmt_history->fetchAll();

    echo json_encode([
        'success' => true,
        'total_revenue' => $sales_total + $other_revenue,
        'total_expense' => $expense_total,
        'history' => $history
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>