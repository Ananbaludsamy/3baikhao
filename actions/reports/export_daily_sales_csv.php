<?php
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$date = $_GET['date'] ?? date('Y-m-d');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=daily_sales_' . $date . '.csv');
header('Pragma: no-cache');

echo "\xEF\xBB\xBF";
$output = fopen('php://output', 'w');

fputcsv($output, ['ລາຍງານຍອດຂາຍລາຍວັນ: ' . $date]);
fputcsv($output, []);
fputcsv($output, ['ເລກບິນ', 'ໂຕະ', 'ລູກຄ້າ', 'ລາຍການ', 'ຍອດ (₭)']);

try {
    $stmt = $conn->prepare("
        SELECT s.Sale_id, s.Table_no, c.Cus_name, s.Sale_sumprice,
               GROUP_CONCAT(p.Product_name ORDER BY p.Product_name SEPARATOR ', ') as items
        FROM saleh_db s
        JOIN customer_db c ON s.Cus_id = c.Cus_id
        JOIN saled_db sd ON s.Sale_id = sd.Sale_id
        JOIN product_db p ON sd.Product_id = p.Product_id
        WHERE DATE(s.Sale_date) = ?
        GROUP BY s.Sale_id
        ORDER BY s.Sale_date DESC
    ");
    $stmt->execute([$date]);

    $total = 0;
    $count = 0;
    while ($row = $stmt->fetch()) {
        fputcsv($output, [
            'ORD-' . str_pad($row['Sale_id'], 5, '0', STR_PAD_LEFT),
            $row['Table_no'] ?: '-',
            $row['Cus_name'],
            $row['items'],
            number_format($row['Sale_sumprice'], 0, '.', '')
        ]);
        $total += $row['Sale_sumprice'];
        $count++;
    }

    fputcsv($output, []);
    fputcsv($output, ['', '', '', 'ຈໍານວນບິນ', $count]);
    fputcsv($output, ['', '', '', 'ຍອດລວມ (₭)', number_format($total, 0, '.', '')]);

    fclose($output);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
