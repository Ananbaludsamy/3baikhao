<?php
require_once '../../includes/auth_check.php';
require_admin();
require_once '../../includes/db_connect.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=stock_report_' . date('Y-m-d') . '.csv');
header('Pragma: no-cache');

echo "\xEF\xBB\xBF";
$output = fopen('php://output', 'w');

fputcsv($output, ['ລາຍງານສະຕ໋ອກວັດຖຸດິບ']);
fputcsv($output, ['ວັນທີ', date('Y-m-d H:i:s')]);
fputcsv($output, []);
fputcsv($output, ['ຊື່ວັດຖຸດິບ', 'ປະເພດ', 'ຄົງເຫຼືອ', 'ຫົວໜ່ວຍ', 'ລາຄາ/ໜ່ວຍ (₭)', 'ສະຖານະ']);

try {
    $stmt = $conn->query("
        SELECT m.Material_name, mt.MaterialType_name, m.Material_total, m.Material_unit, m.Material_costprice
        FROM material_db m
        JOIN materialtype_db mt ON m.MaterialType_id = mt.MaterialType_id
        ORDER BY m.Material_total ASC
    ");

    while ($row = $stmt->fetch()) {
        $qty    = floatval($row['Material_total']);
        $status = $qty <= 5 ? 'ສ່ຽງໝົດ' : ($qty <= 10 ? 'ໃກ້ໝົດ' : 'ປົກກະຕິ');
        fputcsv($output, [
            $row['Material_name'],
            $row['MaterialType_name'],
            $qty,
            $row['Material_unit'],
            number_format($row['Material_costprice'], 0, '.', ''),
            $status
        ]);
    }

    fclose($output);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
