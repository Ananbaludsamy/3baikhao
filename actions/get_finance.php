<?php
header('Content-Type: application/json');
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

try {
    $month = $_GET['month'] ?? date('Y-m');
    if (!preg_match('/^\d{4}-\d{2}$/', $month)) $month = date('Y-m');
    $like = $month . '%';

    $stmt_sales = $conn->prepare("SELECT COALESCE(SUM(Sale_sumprice),0) FROM saleh_db WHERE Sale_date LIKE ?");
    $stmt_sales->execute([$like]);
    $sales_total = (float)$stmt_sales->fetchColumn();

    $stmt_other = $conn->prepare("SELECT COALESCE(SUM(Revenue_Price),0) FROM revenued_db WHERE Revenue_id = 1 AND Revenue_date LIKE ?");
    $stmt_other->execute([$like]);
    $other_revenue = (float)$stmt_other->fetchColumn();

    $stmt_expense = $conn->prepare("SELECT COALESCE(SUM(Revenue_Price),0) FROM revenued_db WHERE Revenue_id = 2 AND Revenue_date LIKE ?");
    $stmt_expense->execute([$like]);
    $expense_total = (float)$stmt_expense->fetchColumn();

    $stmt_history = $conn->prepare("SELECT * FROM revenued_db WHERE Revenue_date LIKE ? ORDER BY Revenue_no DESC LIMIT 100");
    $stmt_history->execute([$like]);
    $history = $stmt_history->fetchAll();

    echo json_encode([
        'success'       => true,
        'sales_total'   => $sales_total,
        'other_revenue' => $other_revenue,
        'total_revenue' => $sales_total + $other_revenue,
        'total_expense' => $expense_total,
        'net_profit'    => $sales_total + $other_revenue - $expense_total,
        'history'       => $history
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>