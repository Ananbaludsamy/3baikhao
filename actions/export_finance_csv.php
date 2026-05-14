<?php
require_once '../includes/auth_check.php';
require_admin();
require_once '../includes/db_connect.php';

// ตั้งค่า Header เพื่อบอก Browser ว่านี่คือไฟล์ CSV และให้ดาวน์โหลด
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=finance_report_' . date('Y-m-d') . '.csv');

// ใส่ Byte Order Mark (BOM) เพื่อให้ Excel อ่านภาษาลาว/ไทย (UTF-8) ได้ถูกต้อง
echo "\xEF\xBB\xBF";

// สร้างตัวจัดการไฟล์ output
$output = fopen('php://output', 'w');

// กำหนดหัวตารางของไฟล์ Excel
fputcsv($output, ['วันที่', 'รายการ', 'รายรับ (₭)', 'รายจ่าย (₭)']);

try {
    // SQL Query รวมข้อมูลยอดขาย และ รายรับ-รายจ่ายอื่น ๆ
    $sql = "SELECT ReportDate, CategoryName, Income, Expense
            FROM (
                -- ยอดขายจาก POS
                SELECT Sale_date AS ReportDate, 'รายรับ (ยอดขายอาหาร)' AS CategoryName, Sale_sumprice AS Income, 0 AS Expense
                FROM saleh_db
                
                UNION ALL
                
                -- รายรับ-รายจ่ายอื่น ๆ
                SELECT d.Revenue_date AS ReportDate, d.Revenue_name AS CategoryName,
                       CASE WHEN d.Revenue_id = 1 THEN d.Revenue_Price ELSE 0 END AS Income,
                       CASE WHEN d.Revenue_id = 2 THEN d.Revenue_Price ELSE 0 END AS Expense
                FROM revenued_db d
            ) AS CombinedData
            ORDER BY ReportDate DESC";

    $stmt = $conn->query($sql);

    $total_income = 0;
    $total_expense = 0;

    while ($row = $stmt->fetch()) {
        fputcsv($output, [
            $row['ReportDate'],
            $row['CategoryName'],
            $row['Income'],
            $row['Expense']
        ]);
        $total_income += $row['Income'];
        $total_expense += $row['Expense'];
    }

    // เพิ่มบรรทัดสรุปยอดท้ายไฟล์
    fputcsv($output, []); // บรรทัดว่าง
    fputcsv($output, ['', 'ยอดรวมสุทธิ', $total_income, $total_expense]);
    fputcsv($output, ['', 'กำไร/ขาดทุนสุทธิ', $total_income - $total_expense, '']);

    fclose($output);
    exit;

} catch (Exception $e) {
    die("Error exporting CSV: " . $e->getMessage());
}