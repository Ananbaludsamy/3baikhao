<?php
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

$month = $_GET['month'] ?? date('Y-m');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=finance_' . $month . '.csv');
header('Pragma: no-cache');

echo "\xEF\xBB\xBF";
$output = fopen('php://output', 'w');

fputcsv($output, ['ລາຍງານລາຍຮັບ-ລາຍຈ່າຍ: ' . $month]);
fputcsv($output, []);
fputcsv($output, ['ວັນທີ', 'ລາຍການ', 'ປະເພດ', 'ລາຍຮັບ (₭)', 'ລາຍຈ່າຍ (₭)']);

try {
    $stmt = $conn->prepare("
        SELECT r.Revenue_date, r.Revenue_name, r.Revenue_id, r.Revenue_Price
        FROM revenued_db r
        WHERE DATE_FORMAT(r.Revenue_date, '%Y-%m') = ?
        ORDER BY r.Revenue_date DESC
    ");
    $stmt->execute([$month]);

    $total_income  = 0;
    $total_expense = 0;

    while ($row = $stmt->fetch()) {
        $is_income = $row['Revenue_id'] == 1;
        fputcsv($output, [
            $row['Revenue_date'],
            $row['Revenue_name'],
            $is_income ? 'ລາຍຮັບ' : 'ລາຍຈ່າຍ',
            $is_income ? number_format($row['Revenue_Price'], 0, '.', '') : '',
            $is_income ? '' : number_format($row['Revenue_Price'], 0, '.', '')
        ]);
        if ($is_income) $total_income  += $row['Revenue_Price'];
        else            $total_expense += $row['Revenue_Price'];
    }

    fputcsv($output, []);
    fputcsv($output, ['', '', 'ລາຍຮັບລວມ',  number_format($total_income,  0, '.', ''), '']);
    fputcsv($output, ['', '', 'ລາຍຈ່າຍລວມ', '', number_format($total_expense, 0, '.', '')]);
    fputcsv($output, ['', '', 'ກໍາໄລສຸດທິ', number_format($total_income - $total_expense, 0, '.', ''), '']);

    fclose($output);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}