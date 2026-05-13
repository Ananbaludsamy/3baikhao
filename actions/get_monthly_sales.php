<?php
header('Content-Type: application/json');
require_once '../includes/db_connect.php';

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('m');
$month_year_filter = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);

try {
    // 1. สรุปยอดขายรวมสำหรับเดือนที่เลือก
    $stmt_total = $conn->prepare("SELECT 
                                    COALESCE(SUM(Sale_sumprice), 0) AS total_monthly_sales,
                                    COUNT(Sale_id) AS total_monthly_orders
                                  FROM saleh_db
                                  WHERE DATE_FORMAT(Sale_date, '%Y-%m') = ?");
    $stmt_total->execute([$month_year_filter]);
    $monthly_summary = $stmt_total->fetch();

    // 2. ยอดขายแยกตามประเภทสินค้าสำหรับเดือนที่เลือก
    $stmt_by_type = $conn->prepare("SELECT pt.ProductType_name, SUM(sd.Sale_sumprice) AS total_sales_by_type
                                    FROM saled_db sd
                                    JOIN product_db p ON sd.Product_id = p.Product_id
                                    JOIN producttype_db pt ON p.ProductType_id = pt.ProductType_id
                                    JOIN saleh_db sh ON sd.Sale_id = sh.Sale_id
                                    WHERE DATE_FORMAT(sh.Sale_date, '%Y-%m') = ?
                                    GROUP BY pt.ProductType_name
                                    ORDER BY total_sales_by_type DESC");
    $stmt_by_type->execute([$month_year_filter]);
    $sales_by_type = $stmt_by_type->fetchAll();

    // 3. รายการสินค้าขายดี 10 อันดับสำหรับเดือนที่เลือก
    $stmt_top_products = $conn->prepare("SELECT p.Product_name, SUM(sd.Sale_total) AS total_qty_sold, SUM(sd.Sale_sumprice) AS total_revenue
                                         FROM saled_db sd
                                         JOIN product_db p ON sd.Product_id = p.Product_id
                                         JOIN saleh_db sh ON sd.Sale_id = sh.Sale_id
                                         WHERE DATE_FORMAT(sh.Sale_date, '%Y-%m') = ?
                                         GROUP BY p.Product_name
                                         ORDER BY total_revenue DESC
                                         LIMIT 10");
    $stmt_top_products->execute([$month_year_filter]);
    $top_products = $stmt_top_products->fetchAll();

    echo json_encode([
        'success' => true,
        'summary' => $monthly_summary,
        'sales_by_type' => $sales_by_type,
        'top_products' => $top_products
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?>