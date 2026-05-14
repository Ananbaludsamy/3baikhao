<?php
header('Content-Type: application/json');
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

try {
    // 1. ดึงสถิติของวันนี้
    $today = date('Y-m-d');
    $stmt_stats = $conn->prepare("SELECT
                                    COALESCE(SUM(Sale_sumprice), 0) as total_sales,
                                    COUNT(Sale_id) as total_orders
                                  FROM saleh_db WHERE DATE(Sale_date) = ?");
    $stmt_stats->execute([$today]);
    $stats = $stmt_stats->fetch();

    // ดึงค่าใช้จ่ายวันนี้ (Revenue_id = 2)
    $stmt_exp = $conn->prepare("SELECT COALESCE(SUM(Revenue_Price), 0) FROM revenued_db WHERE Revenue_id = 2 AND Revenue_date = ?");
    $stmt_exp->execute([$today]);
    $expenses_today = (float)$stmt_exp->fetchColumn();

    $net_profit = (float)$stats['total_sales'] - $expenses_today;

    // 2. ดึงเมนูที่ขายดีที่สุด (นับจากจำนวนชิ้นทั้งหมด)
    $stmt_best = $conn->query("SELECT p.Product_name, SUM(d.Sale_total) as qty 
                               FROM saled_db d 
                               JOIN product_db p ON d.Product_id = p.Product_id 
                               GROUP BY d.Product_id 
                               ORDER BY qty DESC LIMIT 1");
    $best_seller = $stmt_best->fetch();

    // 3. สินค้าขายดี 5 อันดับแรก
    $stmt_top5 = $conn->query("SELECT p.Product_name, SUM(d.Sale_total) as qty 
                               FROM saled_db d 
                               JOIN product_db p ON d.Product_id = p.Product_id 
                               GROUP BY d.Product_id 
                               ORDER BY qty DESC LIMIT 5");
    $top5_products = $stmt_top5->fetchAll();

    // 4. แนวโน้มยอดขาย 7 วันล่าสุด (สำหรับกราฟเส้น)
    $stmt_trend = $conn->query("SELECT DATE(Sale_date) as Sale_date, SUM(Sale_sumprice) as total
                                FROM saleh_db
                                WHERE DATE(Sale_date) >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                                GROUP BY DATE(Sale_date) ORDER BY DATE(Sale_date) ASC");
    $weekly_trend = $stmt_trend->fetchAll();

    // 5. ยอดขายแยกตามหมวดหมู่ (สำหรับกราฟวงกลม)
    $stmt_cats = $conn->query("SELECT pt.ProductType_name, SUM(sd.Sale_sumprice) as total
                               FROM saled_db sd
                               JOIN product_db p ON sd.Product_id = p.Product_id
                               JOIN producttype_db pt ON p.ProductType_id = pt.ProductType_id
                               GROUP BY pt.ProductType_id");
    $category_sales = $stmt_cats->fetchAll();

    // 6. แจ้งเตือนสต็อกวัตถุดิบต่ำ (สมมติต่ำกว่า 10 หน่วย)
    $low_stock_count = $conn->query("SELECT COUNT(*) FROM material_db WHERE Material_total < 10")->fetchColumn();

    // 3. ดึงประวัติการขาย 50 รายการล่าสุด
    $stmt_history = $conn->query("SELECT 
                                    s.Sale_id, 
                                    s.Sale_date, 
                                    s.Sale_sumprice, 
                                    GROUP_CONCAT(CONCAT(p.Product_name, '(', d.Sale_total, ')') SEPARATOR ', ') as items 
                                  FROM saleh_db s 
                                  LEFT JOIN saled_db d ON s.Sale_id = d.Sale_id 
                                  LEFT JOIN product_db p ON d.Product_id = p.Product_id 
                                  GROUP BY s.Sale_id 
                                  ORDER BY s.Sale_id DESC LIMIT 50");
    $history = $stmt_history->fetchAll();

    echo json_encode([
        'success' => true,
        'stats' => [
            'total_sales' => (float)$stats['total_sales'],
            'total_orders' => (int)$stats['total_orders'],
            'best_seller' => $best_seller['Product_name'] ?? '-',
            'net_profit' => $net_profit,
            'low_stock' => (int)$low_stock_count
        ],
        'history' => $history,
        'top5' => $top5_products,
        'trend' => $weekly_trend,
        'category_dist' => $category_sales
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}