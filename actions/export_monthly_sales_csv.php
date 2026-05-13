<?php
require_once '../includes/db_connect.php';

$year = $_GET['year'] ?? date('Y');
$month = $_GET['month'] ?? date('m');
$month_year_filter = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT);

// ตั้งค่า Header เพื่อบอก Browser ว่านี่คือไฟล์ CSV และให้ดาวน์โหลด
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=monthly_sales_report_' . $month_year_filter . '.csv');

// ใส่ Byte Order Mark (BOM) เพื่อให้ Excel อ่านภาษาลาว/ไทย (UTF-8) ได้ถูกต้อง
echo "\xEF\xBB\xBF";

// สร้างตัวจัดการไฟล์ output
$output = fopen('php://output', 'w');

try {
    // 1. สรุปยอดขายรวมสำหรับเดือนที่เลือก
    $stmt_total = $conn->prepare("SELECT 
                                    COALESCE(SUM(Sale_sumprice), 0) AS total_monthly_sales,
                                    COUNT(Sale_id) AS total_monthly_orders
                                  FROM saleh_db
                                  WHERE DATE_FORMAT(Sale_date, '%Y-%m') = ?");
    $stmt_total->execute([$month_year_filter]);
    $monthly_summary = $stmt_total->fetch();

    fputcsv($output, ['รายงานยอดขายประจำเดือน: ' . $month_year_filter]);
    fputcsv($output, ['ยอดขายรวม (₭)', 'จำนวนบิล']);
    fputcsv($output, [$monthly_summary['total_monthly_sales'], $monthly_summary['total_monthly_orders']]);
    fputcsv($output, []); // บรรทัดว่าง

    // 2. ยอดขายแยกตามประเภทสินค้าสำหรับเดือนที่เลือก
    fputcsv($output, ['ยอดขายแยกตามประเภทสินค้า']);
    fputcsv($output, ['ประเภทสินค้า', 'ยอดขาย (₭)']);
    $stmt_by_type = $conn->prepare("SELECT pt.ProductType_name, SUM(sd.Sale_sumprice) AS total_sales_by_type
                                    FROM saled_db sd
                                    JOIN product_db p ON sd.Product_id = p.Product_id
                                    JOIN producttype_db pt ON p.ProductType_id = pt.ProductType_id
                                    JOIN saleh_db sh ON sd.Sale_id = sh.Sale_id
                                    WHERE DATE_FORMAT(sh.Sale_date, '%Y-%m') = ?
                                    GROUP BY pt.ProductType_name
                                    ORDER BY total_sales_by_type DESC");
    $stmt_by_type->execute([$month_year_filter]);
    while ($row = $stmt_by_type->fetch()) {
        fputcsv($output, [$row['ProductType_name'], $row['total_sales_by_type']]);
    }
    fputcsv($output, []); // บรรทัดว่าง

    // 3. รายการสินค้าขายดี 10 อันดับสำหรับเดือนที่เลือก
    fputcsv($output, ['10 อันดับเมนูขายดี']);
    fputcsv($output, ['ชื่อเมนู', 'จำนวนที่ขาย', 'รายได้ (₭)']);
    $stmt_top_products = $conn->prepare("SELECT p.Product_name, SUM(sd.Sale_total) AS total_qty_sold, SUM(sd.Sale_sumprice) AS total_revenue
                                         FROM saled_db sd
                                         JOIN product_db p ON sd.Product_id = p.Product_id
                                         JOIN saleh_db sh ON sd.Sale_id = sh.Sale_id
                                         WHERE DATE_FORMAT(sh.Sale_date, '%Y-%m') = ?
                                         GROUP BY p.Product_name
                                         ORDER BY total_revenue DESC
                                         LIMIT 10");
    $stmt_top_products->execute([$month_year_filter]);
    while ($row = $stmt_top_products->fetch()) {
        fputcsv($output, [$row['Product_name'], $row['total_qty_sold'], $row['total_revenue']]);
    }

    fclose($output);
    exit;

} catch (Exception $e) {
    die("Error exporting CSV: " . $e->getMessage());
}
?>